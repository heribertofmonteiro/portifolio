<?php
// Configurações de ambiente para hospedagem compartilhada
return [
    // Configurações de segurança
    'security' => [
        'admin_username' => 'heriberto',
        'admin_password' => 'Romario@!#$1994&&',
        'session_timeout' => 3600, // 1 hora
        'max_login_attempts' => 5,
        'lockout_time' => 900, // 15 minutos
    ],
    
    // Configurações de email
    'email' => [
        'to_address' => 'hfmk2015@gmail.com',
        'from_name' => 'Portfolio Heriberto',
        'use_smtp' => false, // Mudar para true se SMTP estiver disponível
        'smtp_host' => '',
        'smtp_port' => 587,
        'smtp_username' => '',
        'smtp_password' => '',
        'smtp_encryption' => 'tls',
    ],
    
    // Configurações de upload
    'upload' => [
        'max_file_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'upload_path' => '/assets/img/uploads/',
        'max_width' => 1920,
        'max_height' => 1080,
        'quality' => 85,
    ],
    
    // Configurações de SEO
    'seo' => [
        'site_url' => 'https://seu-dominio.com',
        'site_name' => 'Heriberto da Fonseca Monteiro',
        'default_language' => 'pt',
        'google_analytics_id' => '',
        'google_ads_id' => '',
        'meta_pixel_id' => '',
        'tiktok_pixel_id' => '',
        'linkedin_partner_id' => '',
    ],
    
    // Configurações de cache
    'cache' => [
        'enabled' => true,
        'duration' => 3600, // 1 hora
        'path' => '/tmp/cache/',
    ],
    
    // Configurações de debug
    'debug' => [
        'enabled' => false,
        'log_errors' => true,
        'error_log_path' => '/tmp/error.log',
        'display_errors' => false,
    ],
];
?>
