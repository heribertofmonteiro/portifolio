<?php
// Sistema de email compatível com hospedagem compartilhada

class EmailHelper {
    private $config;
    
    public function __construct($config) {
        $this->config = $config['email'];
    }
    
    public function sendContactEmail($name, $email, $message, $lang = 'pt') {
        // Sanitizar inputs
        $name = SecurityHelper::sanitizeInput($name);
        $email = SecurityHelper::sanitizeInput($email);
        $message = SecurityHelper::sanitizeInput($message);
        
        // Validar email
        if (!SecurityHelper::validateEmail($email)) {
            return ['success' => false, 'message' => 'Email inválido'];
        }
        
        // Rate limiting
        if (!SecurityHelper::checkRateLimit('email_' . $email, 3, 3600)) {
            return ['success' => false, 'message' => 'Muitas tentativas. Tente novamente em 1 hora.'];
        }
        
        // Preparar email
        $to = $this->config['to_address'];
        $subject = 'Contato do portfólio: ' . $name;
        $body = $this->buildEmailBody($name, $email, $message);
        $headers = $this->buildHeaders($email);
        
        // Tentar envio
        if ($this->config['use_smtp'] && $this->isSMTPAvailable()) {
            return $this->sendSMTP($to, $subject, $body, $headers);
        } else {
            return $this->sendMail($to, $subject, $body, $headers);
        }
    }
    
    private function buildEmailBody($name, $email, $message) {
        return "Nome: $name\nEmail: $email\n\nMensagem:\n$message\n\n---\nEnviado em: " . date('d/m/Y H:i:s');
    }
    
    private function buildHeaders($fromEmail) {
        $headers = [];
        $headers[] = "From: {$this->config['from_name']} <$fromEmail>";
        $headers[] = "Reply-To: $fromEmail";
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/plain; charset=UTF-8";
        $headers[] = "X-Mailer: PHP/" . phpversion();
        
        return implode("\r\n", $headers);
    }
    
    private function sendMail($to, $subject, $body, $headers) {
        // Tentar com mail() nativo
        if (function_exists('mail') && @mail($to, $subject, $body, $headers)) {
            return ['success' => true, 'message' => 'Email enviado com sucesso!'];
        }
        
        // Fallback: salvar em arquivo log
        return $this->saveEmailLog($to, $subject, $body, $headers);
    }
    
    private function sendSMTP($to, $subject, $body, $headers) {
        // Implementação SMTP básica (se disponível)
        try {
            // Usar PHPMailer se disponível, ou implementação nativa
            if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
                return $this->sendWithPHPMailer($to, $subject, $body);
            }
        } catch (Exception $e) {
            error_log('SMTP Error: ' . $e->getMessage());
        }
        
        // Fallback para mail()
        return $this->sendMail($to, $subject, $body, $headers);
    }
    
    private function sendWithPHPMailer($to, $subject, $body) {
        // Implementação se PHPMailer estiver disponível
        return ['success' => false, 'message' => 'PHPMailer não disponível'];
    }
    
    private function saveEmailLog($to, $subject, $body, $headers) {
        $logDir = sys_get_temp_dir();
        $logFile = $logDir . '/portfolio_emails.log';
        
        $logEntry = sprintf(
            "[%s] TO: %s | SUBJECT: %s | BODY: %s\n",
            date('Y-m-d H:i:s'),
            $to,
            $subject,
            $body
        );
        
        if (file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX)) {
            return ['success' => true, 'message' => 'Email salvo no log (servidor de email indisponível)'];
        }
        
        return ['success' => false, 'message' => 'Erro ao enviar email'];
    }
    
    private function isSMTPAvailable() {
        // Verificar se extensões necessárias estão disponíveis
        return extension_loaded('sockets') && extension_loaded('openssl');
    }
    
    public function testEmailConfiguration() {
        $results = [];
        
        // Testar função mail()
        $results['mail_function'] = function_exists('mail');
        
        // Testar SMTP
        $results['smtp_available'] = $this->isSMTPAvailable();
        
        // Testar escrita em log
        $tempDir = sys_get_temp_dir();
        $results['temp_writable'] = is_writable($tempDir);
        
        // Testar configuração atual
        $results['current_config'] = [
            'use_smtp' => $this->config['use_smtp'],
            'smtp_configured' => !empty($this->config['smtp_host']),
            'to_address' => !empty($this->config['to_address'])
        ];
        
        return $results;
    }
}
?>
