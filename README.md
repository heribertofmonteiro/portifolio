# Portfolio - Heriberto da Fonseca Monteiro

## Deploy para Hospedagem Compartilhada

### 📋 Pré-requisitos
- PHP 7.4+ (8.0+ recomendado)
- Apache com mod_rewrite
- Extensões: session, json, mbstring, fileinfo
- 50MB de espaço em disco

### 🚀 Passos de Deploy

1. **Upload dos arquivos**
   ```bash
   # Fazer upload do conteúdo da pasta /public para o document root
   # Manter estrutura de pastas /src e /config
   ```

2. **Configurar permissões**
   ```bash
   chmod 755 /public
   chmod 644 /public/*.php
   chmod 755 /public/assets
   chmod 644 /public/assets/*
   chmod 755 /src
   chmod 644 /src/data/*.php
   ```

3. **Configurar domínio**
   - Apontar document root para `/public`
   - Configurar SSL (Let's Encrypt)

4. **Testar compatibilidade**
   - Acessar: `seu-dominio.com/compatibility-check.php`
   - Verificar se todos os testes passam

### ⚙️ Configurações

#### Email (contato.php)
Editar `/src/config/config.php`:
```php
'email' => [
    'to_address' => 'seu-email@dominio.com',
    'use_smtp' => false,  // Mudar para true se tiver SMTP
    // ... configurações SMTP
]
```

#### SEO e Analytics
Editar `/src/config/config.php`:
```php
'seo' => [
    'site_url' => 'https://seu-dominio.com',
    'google_analytics_id' => 'G-XXXXXXXXXX',
    // ... outras configurações
]
```

### 🔧 URLs Amigáveis

O `.htaccess` já está configurado para:
- `/` → index.php
- `/projetos` → projetos.php
- `/contato` → contato.php
- `/admin` → admin.php

### 📧 Email

Se a função `mail()` não funcionar:
1. Ative SMTP no config
2. Configure credenciais SMTP
3. Emails serão salvos em `/tmp/portfolio_emails.log`

### 🔒 Segurança

- Rate limiting no login (5 tentativas/15min)
- Rate limiting no email (3 tentativas/hora)
- Sessão expira em 1 hora
- Inputs sanitizados
- Headers de segurança

### 🐛 Debug

Para habilitar debug:
```php
// Em /src/config/config.php
'debug' => [
    'enabled' => true,
    // ...
]
```

### 📱 Teste Final

1. Acessar site principal
2. Testar navegação entre páginas
3. Testar formulário de contato
4. Acessar painel admin (heriberto / Romario@!#$1994&&)
5. Testar gerenciamento de projetos
6. Verificar compatibilidade: `/compatibility-check.php`

### 🆘 Suporte

- Verificar logs de erro: `/tmp/error.log`
- Logs de email: `/tmp/portfolio_emails.log`
- Teste de compatibilidade mostra problemas específicos

### 📊 Performance

- Cache configurado para 1 mês
- Compressão gzip ativa
- Headers de cache otimizados
- Imagens otimizadas (WebP recomendado)

---

**Status**: ✅ Pronto para hospedagem compartilhada
