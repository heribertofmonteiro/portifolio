<?php
// Incluir sistema de segurança e email
require_once '../src/config/security.php';
require_once '../src/config/email.php';

$config = SecurityHelper::getConfig();
$emailHelper = new EmailHelper($config);

function detect_language() {
    global $config;
    $accept_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'pt';
    $langs = explode(',', $accept_lang);
    foreach ($langs as $lang) {
        $lang = trim(explode(';', $lang)[0]);
        if (strpos($lang, 'pt') === 0) return 'pt';
        if (strpos($lang, 'en') === 0) return 'en';
        if (strpos($lang, 'es') === 0) return 'es';
        if (strpos($lang, 'ja') === 0) return 'ja';
    }
    return $config['seo']['default_language'];
}
$lang = $_GET['lang'] ?? detect_language();
include '../src/data/translations.php';

// Process form
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = SecurityHelper::sanitizeInput($_POST['name'] ?? '');
    $email = SecurityHelper::sanitizeInput($_POST['email'] ?? '');
    $msg = SecurityHelper::sanitizeInput($_POST['message'] ?? '');
    $honeypot = $_POST['website'] ?? '';

    if ($honeypot !== '') {
        // Spam
        $message = $translations[$lang]['form_error'];
    } elseif (empty($name) || empty($email) || empty($msg)) {
        $message = $translations[$lang]['form_error'];
    } elseif (!SecurityHelper::validateEmail($email)) {
        $message = $translations[$lang]['form_error'];
    } else {
        // Usar novo sistema de email
        $result = $emailHelper->sendContactEmail($name, $email, $msg, $lang);
        $message = $result['message'];
        
        // Log para debug (apenas em desenvolvimento)
        if ($config['debug']['enabled'] && !$result['success']) {
            error_log('Email send failed: ' . $result['message']);
            error_log('Email test results: ' . print_r($emailHelper->testEmailConfiguration(), true));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" x-data="{ darkMode: false, mobileMenu: false }" x-init="darkMode = localStorage.getItem('darkMode') === 'true'; $watch('darkMode', value => localStorage.setItem('darkMode', value))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations[$lang]['meta_title_contact']; ?></title>
    <meta name="description" content="<?php echo $translations[$lang]['meta_desc_contact']; ?>">
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $translations[$lang]['meta_title_contact']; ?>">
    <meta property="og:description" content="<?php echo $translations[$lang]['meta_desc_contact']; ?>">
    <meta property="og:image" content="assets/img/og-image.jpg">
    <meta property="og:url" content="https://heriberto.dev/contato">
    <meta property="og:type" content="website">
    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $translations[$lang]['meta_title_contact']; ?>">
    <meta name="twitter:description" content="<?php echo $translations[$lang]['meta_desc_contact']; ?>">
    <meta name="twitter:image" content="assets/img/og-image.jpg">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
    <?php include '../src/partials/header.php'; ?>

    <main class="py-8 sm:py-12 md:py-16">
        <div class="container mx-auto px-3 sm:px-4 max-w-2xl">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-4 sm:mb-6 md:mb-8"><?php echo $translations[$lang]['contact_title']; ?></h1>
            <p class="text-center mb-6 sm:mb-8 md:mb-12 text-xs sm:text-sm md:text-base"><?php echo $translations[$lang]['contact_subtitle']; ?></p>

            <?php if ($message): ?>
            <div class="mb-6 md:mb-8 p-4 rounded <?php echo strpos($message, 'sucesso') !== false ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'; ?>">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <form method="post" class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 sm:p-6 md:p-8 rounded-xl shadow-2xl border border-white/20">
                <div class="mb-3 sm:mb-4 md:mb-6">
                    <label for="name" class="block text-xs sm:text-sm font-medium mb-1 sm:mb-2 text-cyan-400"><?php echo $translations[$lang]['form_name']; ?></label>
                    <input type="text" id="name" name="name" required class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all text-sm md:text-base">
                </div>
                <div class="mb-3 sm:mb-4 md:mb-6">
                    <label for="email" class="block text-xs sm:text-sm font-medium mb-1 sm:mb-2 text-cyan-400"><?php echo $translations[$lang]['form_email']; ?></label>
                    <input type="email" id="email" name="email" required class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all text-sm md:text-base">
                </div>
                <div class="mb-3 sm:mb-4 md:mb-6">
                    <label for="message" class="block text-xs sm:text-sm font-medium mb-1 sm:mb-2 text-cyan-400"><?php echo $translations[$lang]['form_message']; ?></label>
                    <textarea id="message" name="message" rows="4" required class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all resize-none text-sm md:text-base"></textarea>
                </div>
                <!-- Honeypot -->
                <input type="text" name="website" style="display:none;">
                <button type="submit" class="w-full bg-gradient-to-r from-cyan-500 to-purple-500 hover:from-cyan-600 hover:to-purple-600 text-white py-2 sm:py-3 md:py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl text-sm md:text-base"><?php echo $translations[$lang]['form_submit']; ?></button>
            </form>
        </div>
    </main>

    <?php include '../src/partials/footer.php'; ?>
</body>
</html>