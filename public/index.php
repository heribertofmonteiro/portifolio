<?php
session_start();

function detect_language() {
    $accept_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'pt';
    $langs = explode(',', $accept_lang);
    foreach ($langs as $lang) {
        $lang = trim(explode(';', $lang)[0]);
        if (strpos($lang, 'pt') === 0) return 'pt';
        if (strpos($lang, 'en') === 0) return 'en';
        if (strpos($lang, 'es') === 0) return 'es';
        if (strpos($lang, 'jp') === 0) return 'jp';
    }
    return 'pt'; // default
}
$lang = $_GET['lang'] ?? detect_language();

// Handle logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin']);
    header('Location: index.php');
    exit;
}

include '../src/data/translations.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" x-data="{ darkMode: false, mobileMenu: false }" x-init="darkMode = localStorage.getItem('darkMode') === 'true'; $watch('darkMode', value => localStorage.setItem('darkMode', value))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations[$lang]['meta_title_home']; ?></title>
    <meta name="description" content="<?php echo $translations[$lang]['meta_desc_home']; ?>">
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $translations[$lang]['meta_title_home']; ?>">
    <meta property="og:description" content="<?php echo $translations[$lang]['meta_desc_home']; ?>">
    <meta property="og:image" content="assets/img/og-image.jpg">
    <meta property="og:url" content="https://heriberto.dev">
    <meta property="og:type" content="website">
    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $translations[$lang]['meta_title_home']; ?>">
    <meta name="twitter:description" content="<?php echo $translations[$lang]['meta_desc_home']; ?>">
    <meta name="twitter:image" content="assets/img/og-image.jpg">
    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "Heriberto da Fonseca Monteiro",
        "jobTitle": "<?php echo $translations[$lang]['home_subtitle']; ?>",
        "url": "https://heriberto.dev",
        "sameAs": [
            "https://github.com/heribertofmonteiro",
            "https://www.linkedin.com/in/heribertomonteirohfm"
        ]
    }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
    <?php include '../src/partials/header.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="relative py-20 overflow-hidden" :class="darkMode ? 'bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900' : 'bg-gradient-to-br from-blue-50 via-indigo-100 to-purple-100'">
            <div class="absolute inset-0" :class="darkMode ? 'bg-black opacity-20' : 'bg-white opacity-10'"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/20 to-purple-500/20 animate-pulse"></div>
            <div class="relative container mx-auto px-4 text-center z-10">
                <div class="mb-8">
                    <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-gradient-to-r from-cyan-400 to-purple-400 p-1 shadow-2xl">
                        <div class="w-full h-full rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                            <span class="text-4xl">👤</span>
                        </div>
                    </div>
                    <p class="text-cyan-300 text-sm uppercase tracking-wider">Desenvolvedor Full-Stack</p>
                </div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold mb-4 animate-fade-in" :class="darkMode ? 'bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent' : 'text-gray-900'"><?php echo $translations[$lang]['home_title']; ?></h1>
                <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl mb-6 font-light relative">
                    <span :class="darkMode ? 'text-white drop-shadow-lg' : 'text-gray-800'" class="animate-pulse"><?php echo $translations[$lang]['home_subtitle']; ?></span>
                    <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-0.5 bg-gradient-to-r from-cyan-400 to-purple-400 rounded-full"></div>
                </h2>
                <p class="text-sm sm:text-base md:text-lg lg:text-xl max-w-3xl mx-auto mb-8 leading-relaxed" :class="darkMode ? 'text-gray-300' : 'text-gray-700'"><?php echo $translations[$lang]['home_bio']; ?></p>
                <div class="flex flex-wrap justify-center gap-4 mb-8">
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-cyan-300">
                        <span class="font-semibold">1+</span> <?php echo $lang === 'pt' ? 'Ano de Experiência' : 'Year Experience'; ?>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-cyan-300">
                        <span class="font-semibold">3+</span> <?php echo $lang === 'pt' ? 'Projetos Completados' : 'Projects Completed'; ?>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-cyan-300">
                        <span class="font-semibold">Laravel</span> <?php echo $lang === 'pt' ? 'Especialista' : 'Expert'; ?>
                    </div>
                </div>
                <a href="contato.php?lang=<?php echo $lang; ?>" class="inline-block bg-gradient-to-r from-cyan-500 to-purple-500 hover:from-cyan-600 hover:to-purple-600 text-white px-8 py-4 rounded-full text-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"><?php echo $translations[$lang]['cta_button']; ?></a>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-20 bg-gradient-to-t from-white dark:from-gray-900 to-transparent"></div>
        </section>

        <!-- Stack Section -->
        <section class="py-16 bg-gray-50 dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-6 sm:mb-8 md:mb-12 bg-gradient-to-r from-cyan-500 to-purple-500 bg-clip-text text-transparent"><?php echo $translations[$lang]['stack_title']; ?></h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 sm:p-6 rounded-xl shadow-lg border border-white/20 hover:border-cyan-400/50 transition-all duration-300 hover:transform hover:scale-105">
                        <h4 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-cyan-400"><?php echo $translations[$lang]['stack_languages']; ?></h4>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300"><?php echo $translations[$lang]['stack_languages_list']; ?></p>
                    </div>
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 sm:p-6 rounded-xl shadow-lg border border-white/20 hover:border-cyan-400/50 transition-all duration-300 hover:transform hover:scale-105">
                        <h4 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-cyan-400"><?php echo $translations[$lang]['stack_backend']; ?></h4>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300"><?php echo $translations[$lang]['stack_backend_list']; ?></p>
                    </div>
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 sm:p-6 rounded-xl shadow-lg border border-white/20 hover:border-cyan-400/50 transition-all duration-300 hover:transform hover:scale-105">
                        <h4 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-cyan-400"><?php echo $translations[$lang]['stack_frontend']; ?></h4>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300"><?php echo $translations[$lang]['stack_frontend_list']; ?></p>
                    </div>
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 sm:p-6 rounded-xl shadow-lg border border-white/20 hover:border-cyan-400/50 transition-all duration-300 hover:transform hover:scale-105">
                        <h4 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-cyan-400"><?php echo $translations[$lang]['stack_mobile']; ?></h4>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300"><?php echo $translations[$lang]['stack_mobile_list']; ?></p>
                    </div>
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 sm:p-6 rounded-xl shadow-lg border border-white/20 hover:border-cyan-400/50 transition-all duration-300 hover:transform hover:scale-105">
                        <h4 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-cyan-400"><?php echo $translations[$lang]['stack_databases']; ?></h4>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300"><?php echo $translations[$lang]['stack_databases_list']; ?></p>
                    </div>
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 sm:p-6 rounded-xl shadow-lg border border-white/20 hover:border-cyan-400/50 transition-all duration-300 hover:transform hover:scale-105">
                        <h4 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-cyan-400"><?php echo $translations[$lang]['stack_devops']; ?></h4>
                        <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300"><?php echo $translations[$lang]['stack_devops_list']; ?></p>
                    </div>
                </div>
                <div class="mt-12 text-center">
                    <h4 class="text-2xl font-semibold mb-4 bg-gradient-to-r from-cyan-500 to-purple-500 bg-clip-text text-transparent"><?php echo $translations[$lang]['stack_tools']; ?></h4>
                    <p class="text-gray-700 dark:text-gray-300 max-w-4xl mx-auto"><?php echo $translations[$lang]['stack_tools_list']; ?></p>
                </div>
                <div class="mt-8 text-center">
                    <h4 class="text-2xl font-semibold mb-4 bg-gradient-to-r from-cyan-500 to-purple-500 bg-clip-text text-transparent"><?php echo $translations[$lang]['stack_arch']; ?></h4>
                    <p class="text-gray-700 dark:text-gray-300 max-w-4xl mx-auto"><?php echo $translations[$lang]['stack_arch_list']; ?></p>
                </div>
                <div class="mt-8 text-center">
                    <h4 class="text-2xl font-semibold mb-4 bg-gradient-to-r from-cyan-500 to-purple-500 bg-clip-text text-transparent"><?php echo $translations[$lang]['stack_projects']; ?></h4>
                    <p class="text-gray-700 dark:text-gray-300 max-w-4xl mx-auto"><?php echo $translations[$lang]['stack_projects_list']; ?></p>
                </div>
            </div>
        </section>

        <!-- Skills Section -->
        <section class="py-16 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-6 sm:mb-8 md:mb-12 bg-gradient-to-r from-cyan-500 to-purple-500 bg-clip-text text-transparent"><?php echo $lang === 'pt' ? 'Habilidades Técnicas' : 'Technical Skills'; ?></h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 md:gap-8 max-w-4xl mx-auto">
                    <div>
                        <h4 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-cyan-400">Backend</h4>
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm sm:text-base">PHP/Laravel</span>
                                    <span class="text-sm sm:text-base">85%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-cyan-500 to-purple-500 h-2 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm sm:text-base">MySQL/PostgreSQL</span>
                                    <span class="text-sm sm:text-base">80%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-cyan-500 to-purple-500 h-2 rounded-full" style="width: 80%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm sm:text-base">APIs REST</span>
                                    <span class="text-sm sm:text-base">75%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-cyan-500 to-purple-500 h-2 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-cyan-400">Frontend & Mobile</h4>
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm sm:text-base">Vue.js/React</span>
                                    <span class="text-sm sm:text-base">70%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-cyan-500 to-purple-500 h-2 rounded-full" style="width: 70%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm sm:text-base">Flutter/Dart</span>
                                    <span class="text-sm sm:text-base">75%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-cyan-500 to-purple-500 h-2 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm sm:text-base">Tailwind CSS</span>
                                    <span class="text-sm sm:text-base">90%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-cyan-500 to-purple-500 h-2 rounded-full" style="width: 90%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../src/partials/footer.php'; ?>
</body>
</html>