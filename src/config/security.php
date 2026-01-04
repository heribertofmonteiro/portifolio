<?php
// Funções de segurança e compatibilidade para hospedagem compartilhada

class SecurityHelper {
    private static $config;
    
    public static function init() {
        self::$config = include '../src/config/config.php';
        
        // Configurações PHP para hospedagem compartilhada
        self::setupPHP();
        
        // Segurança de sessão
        self::setupSession();
        
        // Headers de segurança
        self::setupHeaders();
    }
    
    private static function setupPHP() {
        // Ajustes para compatibilidade
        if (ini_get('max_execution_time') > 60) {
            set_time_limit(30);
        }
        
        // Configurações de erro
        if (!self::$config['debug']['enabled']) {
            ini_set('display_errors', 0);
            ini_set('log_errors', 1);
            if (self::$config['debug']['error_log_path']) {
                ini_set('error_log', self::$config['debug']['error_log_path']);
            }
        }
        
        // Limites de memória
        if (ini_get('memory_limit') > '128M') {
            ini_set('memory_limit', '128M');
        }
    }
    
    private static function setupSession() {
        // Configurações de sessão para hospedagem compartilhada
        if (session_status() === PHP_SESSION_NONE) {
            // Usar caminho temporário do sistema
            $sessionPath = session_save_path();
            if (!is_dir($sessionPath) || !is_writable($sessionPath)) {
                session_save_path(sys_get_temp_dir());
            }
            
            // Configurações de segurança
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            
            // HTTPS apenas se disponível
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', 1);
            }
            
            session_start();
        }
    }
    
    private static function setupHeaders() {
        // Headers de segurança compatíveis
        if (!headers_sent()) {
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: DENY');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Remover versão do PHP
            header_remove('X-Powered-By');
        }
    }
    
    public static function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizeInput'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function generateToken() {
        return bin2hex(random_bytes(32));
    }
    
    public static function checkRateLimit($identifier, $limit = 5, $window = 900) {
        $cacheKey = 'rate_limit_' . md5($identifier);
        $cacheFile = sys_get_temp_dir() . '/' . $cacheKey;
        
        if (file_exists($cacheFile)) {
            $data = json_decode(file_get_contents($cacheFile), true);
            if ($data['count'] >= $limit && (time() - $data['start']) < $window) {
                return false;
            }
        }
        
        // Atualizar contador
        $newData = [
            'count' => isset($data['count']) ? $data['count'] + 1 : 1,
            'start' => isset($data['start']) ? $data['start'] : time()
        ];
        
        file_put_contents($cacheFile, json_encode($newData));
        
        // Limpar arquivo antigo
        if ((time() - $newData['start']) > $window) {
            unlink($cacheFile);
        }
        
        return true;
    }
    
    public static function isLoginAllowed($username) {
        return self::checkRateLimit('login_' . $username, 5, 900);
    }
    
    public static function getConfig() {
        return self::$config;
    }
}

// Inicializar segurança
SecurityHelper::init();
?>
