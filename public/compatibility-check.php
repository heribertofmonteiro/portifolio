<?php
// Script de verificação de compatibilidade para hospedagem compartilhada

require_once '../src/config/security.php';
require_once '../src/config/email.php';

$config = SecurityHelper::getConfig();
$emailHelper = new EmailHelper($config);

class CompatibilityChecker {
    private $results = [];
    
    public function runAllTests() {
        $this->checkPHPVersion();
        $this->checkRequiredExtensions();
        $this->checkFilePermissions();
        $this->checkServerConfiguration();
        $this->checkEmailConfiguration();
        $this->checkSecuritySettings();
        $this->checkPerformanceSettings();
        
        return $this->generateReport();
    }
    
    private function checkPHPVersion() {
        $phpVersion = PHP_VERSION;
        $this->results['php_version'] = [
            'status' => version_compare($phpVersion, '7.4.0', '>=') ? 'PASS' : 'FAIL',
            'current' => $phpVersion,
            'required' => '>= 7.4.0',
            'message' => version_compare($phpVersion, '7.4.0', '>=') 
                ? 'Versão PHP compatível' 
                : 'Versão PHP muito antiga. Atualize para PHP 7.4+'
        ];
    }
    
    private function checkRequiredExtensions() {
        $required = ['session', 'json', 'mbstring', 'fileinfo'];
        $optional = ['curl', 'openssl', 'sockets', 'gd'];
        
        foreach ($required as $ext) {
            $this->results['extension_' . $ext] = [
                'status' => extension_loaded($ext) ? 'PASS' : 'FAIL',
                'type' => 'required',
                'message' => extension_loaded($ext) 
                    ? "Extensão $ext carregada" 
                    : "Extensão obrigatória $ext não encontrada"
            ];
        }
        
        foreach ($optional as $ext) {
            $this->results['extension_' . $ext] = [
                'status' => extension_loaded($ext) ? 'PASS' : 'WARN',
                'type' => 'optional',
                'message' => extension_loaded($ext) 
                    ? "Extensão $ext disponível" 
                    : "Extensão opcional $ext não encontrada"
            ];
        }
    }
    
    private function checkFilePermissions() {
        $paths = [
            '../src/data' => 'writable',
            sys_get_temp_dir() => 'writable',
            '../public/assets/img/uploads' => 'writable'
        ];
        
        foreach ($paths as $path => $permission) {
            $exists = file_exists($path);
            $writable = $exists && is_writable($path);
            
            $this->results['path_' . md5($path)] = [
                'status' => $writable ? 'PASS' : ($exists ? 'FAIL' : 'WARN'),
                'path' => $path,
                'exists' => $exists,
                'writable' => $writable,
                'message' => $writable 
                    ? "Caminho $path acessível" 
                    : ($exists ? "Caminho $path sem permissão de escrita" : "Caminho $path não existe")
            ];
        }
    }
    
    private function checkServerConfiguration() {
        // Verificar Apache/Nginx
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
        $this->results['server_software'] = [
            'status' => 'INFO',
            'software' => $serverSoftware,
            'message' => "Servidor: $serverSoftware"
        ];
        
        // Verificar mod_rewrite
        $this->results['mod_rewrite'] = [
            'status' => $this->hasModRewrite() ? 'PASS' : 'WARN',
            'message' => $this->hasModRewrite() 
                ? 'mod_rewrite disponível' 
                : 'mod_rewrite não detectado (URLs amigáveis podem não funcionar)'
        ];
        
        // Verificar HTTPS
        $this->results['https'] = [
            'status' => $this->isHTTPS() ? 'PASS' : 'WARN',
            'message' => $this->isHTTPS() 
                ? 'HTTPS ativo' 
                : 'HTTPS não detectado'
        ];
    }
    
    private function checkEmailConfiguration() {
        global $emailHelper;
        $emailTests = $emailHelper->testEmailConfiguration();
        
        foreach ($emailTests as $test => $result) {
            $this->results['email_' . $test] = [
                'status' => is_bool($result) ? ($result ? 'PASS' : 'FAIL') : 'INFO',
                'value' => $result,
                'message' => $this->formatEmailTestMessage($test, $result)
            ];
        }
    }
    
    private function checkSecuritySettings() {
        $settings = [
            'display_errors' => ['expected' => '0', 'good' => '0'],
            'log_errors' => ['expected' => '1', 'good' => '1'],
            'allow_url_fopen' => ['expected' => '1', 'good' => '1'],
            'file_uploads' => ['expected' => '1', 'good' => '1'],
            'session.cookie_httponly' => ['expected' => '1', 'good' => '1']
        ];
        
        foreach ($settings as $setting => $expected) {
            $current = ini_get($setting);
            $this->results['security_' . $setting] = [
                'status' => $current === $expected['good'] ? 'PASS' : 'WARN',
                'current' => $current,
                'expected' => $expected['good'],
                'message' => $current === $expected['good'] 
                    ? "Configuração $setting segura" 
                    : "Configuração $setting pode ser melhorada"
            ];
        }
    }
    
    private function checkPerformanceSettings() {
        $settings = [
            'memory_limit' => ['min' => '128M'],
            'max_execution_time' => ['min' => 30],
            'post_max_size' => ['min' => '8M'],
            'upload_max_filesize' => ['min' => '5M']
        ];
        
        foreach ($settings as $setting => $requirements) {
            $current = ini_get($setting);
            $currentBytes = $this->parseShorthandBytes($current);
            $minBytes = $this->parseShorthandBytes($requirements['min']);
            
            $this->results['performance_' . $setting] = [
                'status' => $currentBytes >= $minBytes ? 'PASS' : 'WARN',
                'current' => $current,
                'minimum' => $requirements['min'],
                'message' => $currentBytes >= $minBytes 
                    ? "Configuração $setting adequada" 
                    : "Configuração $setting pode ser limitante"
            ];
        }
    }
    
    private function hasModRewrite() {
        return function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules());
    }
    
    private function isHTTPS() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
               $_SERVER['SERVER_PORT'] == 443 || 
               (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    }
    
    private function formatEmailTestMessage($test, $result) {
        $messages = [
            'mail_function' => $result ? 'Função mail() disponível' : 'Função mail() indisponível',
            'smtp_available' => $result ? 'SMTP disponível' : 'SMTP não disponível',
            'temp_writable' => $result ? 'Diretório temp gravável' : 'Diretório temp sem permissão',
            'current_config' => 'Configuração atual: ' . json_encode($result)
        ];
        
        return $messages[$test] ?? "Teste $test: " . json_encode($result);
    }
    
    private function parseShorthandBytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;
        
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        
        return $val;
    }
    
    private function generateReport() {
        $summary = [
            'pass' => 0,
            'warn' => 0,
            'fail' => 0,
            'info' => 0
        ];
        
        foreach ($this->results as $result) {
            $status = $result['status'];
            $summary[strtolower($status)]++;
        }
        
        return [
            'summary' => $summary,
            'details' => $this->results,
            'overall' => $summary['fail'] === 0 ? 'COMPATÍVEL' : 'NEEDS ATTENTION',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}

// Executar testes se acessado diretamente
if (basename($_SERVER['PHP_SELF']) === 'compatibility-check.php') {
    header('Content-Type: application/json');
    
    $checker = new CompatibilityChecker();
    $report = $checker->runAllTests();
    
    echo json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>
