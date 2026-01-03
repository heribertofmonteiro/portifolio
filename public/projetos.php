<?php
function detect_language() {
    $accept_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'pt';
    $langs = explode(',', $accept_lang);
    foreach ($langs as $lang) {
        $lang = trim(explode(';', $lang)[0]);
        if (strpos($lang, 'pt') === 0) return 'pt';
        if (strpos($lang, 'en') === 0) return 'en';
        if (strpos($lang, 'es') === 0) return 'es';
        if (strpos($lang, 'ja') === 0) return 'ja';
    }
    return 'pt'; // default
}
$lang = $_GET['lang'] ?? detect_language();
include '../src/data/translations.php';
include '../src/data/projetos.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" x-data="{ darkMode: false, mobileMenu: false }" x-init="darkMode = localStorage.getItem('darkMode') === 'true'; $watch('darkMode', value => localStorage.setItem('darkMode', value))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations[$lang]['meta_title_projects']; ?></title>
    <meta name="description" content="<?php echo $translations[$lang]['meta_desc_projects']; ?>">
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $translations[$lang]['meta_title_projects']; ?>">
    <meta property="og:description" content="<?php echo $translations[$lang]['meta_desc_projects']; ?>">
    <meta property="og:image" content="assets/img/og-image.jpg">
    <meta property="og:url" content="https://heriberto.dev/projetos">
    <meta property="og:type" content="website">
    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $translations[$lang]['meta_title_projects']; ?>">
    <meta name="twitter:description" content="<?php echo $translations[$lang]['meta_desc_projects']; ?>">
    <meta name="twitter:image" content="assets/img/og-image.jpg">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
    <?php include '../src/partials/header.php'; ?>

    <main class="py-8 sm:py-12 md:py-16">
        <div class="container mx-auto px-3 sm:px-4">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-6 sm:mb-8 md:mb-12"><?php echo $translations[$lang]['projects_title']; ?></h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                <?php foreach ($projetos as $projeto): ?>
                <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-3 sm:p-4 md:p-6 rounded-xl shadow-lg border border-white/20 hover:border-cyan-400/50 transition-all duration-300 hover:transform hover:scale-105 hover:shadow-2xl">
                    <h3 class="text-lg sm:text-xl md:text-2xl font-semibold mb-2 sm:mb-3 md:mb-4 bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent"><?php echo $projeto['titulo']; ?></h3>
                    <p class="mb-2 sm:mb-3 md:mb-4 text-xs sm:text-sm md:text-base text-gray-700 dark:text-gray-300"><?php echo $projeto['descricao']; ?></p>
                    <div class="mb-2 sm:mb-3 md:mb-4">
                        <h4 class="font-semibold text-cyan-400 text-xs sm:text-sm md:text-base"><?php echo $translations[$lang]['project_tech']; ?>:</h4>
                        <div class="flex flex-wrap gap-1 sm:gap-2 mt-1 sm:mt-2">
                            <?php foreach ($projeto['tecnologias'] as $tech): ?>
                            <span class="bg-cyan-500/20 text-cyan-300 px-1.5 sm:px-2 md:px-3 py-0.5 sm:py-1 rounded-full text-xs sm:text-sm border border-cyan-400/30"><?php echo $tech; ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <a href="<?php echo $projeto['link']; ?>" class="inline-block bg-gradient-to-r from-cyan-500 to-purple-500 hover:from-cyan-600 hover:to-purple-600 text-white px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 rounded-full text-xs sm:text-sm md:text-base transition-all duration-300 transform hover:scale-105 shadow-lg"><?php echo $translations[$lang]['view_project']; ?></a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include '../src/partials/footer.php'; ?>
</body>
</html>