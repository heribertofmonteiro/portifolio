<header class="bg-white/10 dark:bg-gray-900/80 backdrop-blur-lg shadow-lg sticky top-0 z-50 border-b border-white/20">
    <nav class="container mx-auto px-4 sm:px-6 md:px-8 py-4 sm:py-5 md:py-6">
        <div class="flex justify-between items-center">
            <a href="index.php?lang=<?php echo $lang; ?>" class="text-base sm:text-lg md:text-xl lg:text-2xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent hover:scale-105 transition-transform">Heriberto Monteiro</a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-6 lg:space-x-8">
                <?php if (basename($_SERVER['PHP_SELF']) === 'admin.php'): ?>
                <a href="index.php?lang=<?php echo $lang; ?>" class="text-white hover:text-cyan-400 transition-colors relative group text-sm lg:text-base">
                    <?php echo $translations[$lang]['nav_home']; ?>
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-cyan-400 transition-all group-hover:w-full"></span>
                </a>
                <a href="projetos.php?lang=<?php echo $lang; ?>" class="text-white hover:text-cyan-400 transition-colors relative group text-sm lg:text-base">
                    <?php echo $translations[$lang]['nav_projects']; ?>
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-cyan-400 transition-all group-hover:w-full"></span>
                </a>
                <a href="contato.php?lang=<?php echo $lang; ?>" class="text-white hover:text-cyan-400 transition-colors relative group text-sm lg:text-base">
                    <?php echo $translations[$lang]['nav_contact']; ?>
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-cyan-400 transition-all group-hover:w-full"></span>
                </a>
                <?php else: ?>
                <a href="index.php?lang=<?php echo $lang; ?>" class="hover:text-cyan-400 transition-colors relative group text-base lg:text-lg px-3 py-2 rounded-lg hover:bg-white/10 dark:hover:bg-gray-800/30">
                    <?php echo $translations[$lang]['nav_home']; ?>
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-cyan-400 transition-all group-hover:w-full"></span>
                </a>
                <a href="projetos.php?lang=<?php echo $lang; ?>" class="hover:text-cyan-400 transition-colors relative group text-base lg:text-lg px-3 py-2 rounded-lg hover:bg-white/10 dark:hover:bg-gray-800/30">
                    <?php echo $translations[$lang]['nav_projects']; ?>
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-cyan-400 transition-all group-hover:w-full"></span>
                </a>
                <a href="contato.php?lang=<?php echo $lang; ?>" class="hover:text-cyan-400 transition-colors relative group text-base lg:text-lg px-3 py-2 rounded-lg hover:bg-white/10 dark:hover:bg-gray-800/30">
                    <?php echo $translations[$lang]['nav_contact']; ?>
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-cyan-400 transition-all group-hover:w-full"></span>
                </a>
                <?php if (isset($_SESSION['admin'])): ?>
                <a href="admin.php" class="hover:text-cyan-400 transition-colors relative group text-base lg:text-lg px-3 py-2 rounded-lg hover:bg-white/10 dark:hover:bg-gray-800/30 bg-gradient-to-r from-cyan-500/20 to-purple-500/20 border border-cyan-400/30">
                    Admin
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-cyan-400 transition-all group-hover:w-full"></span>
                </a>
                <?php endif; ?>
                <?php endif; ?>
                <div class="hidden lg:flex space-x-1">
                    <a href="?lang=pt" class="px-2 py-1 rounded-full text-xs font-medium transition-all <?php echo $lang === 'pt' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/20 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 hover:bg-cyan-500/20'; ?>"><?php echo $translations[$lang]['lang_pt']; ?></a>
                    <a href="?lang=en" class="px-2 py-1 rounded-full text-xs font-medium transition-all <?php echo $lang === 'en' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/20 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 hover:bg-cyan-500/20'; ?>"><?php echo $translations[$lang]['lang_en']; ?></a>
                    <a href="?lang=es" class="px-2 py-1 rounded-full text-xs font-medium transition-all <?php echo $lang === 'es' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/20 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 hover:bg-cyan-500/20'; ?>"><?php echo $translations[$lang]['lang_es']; ?></a>
                    <a href="?lang=ja" class="px-2 py-1 rounded-full text-xs font-medium transition-all <?php echo $lang === 'ja' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/20 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 hover:bg-cyan-500/20'; ?>"><?php echo $translations[$lang]['lang_ja']; ?></a>
                </div>
                <?php if (isset($_SESSION['admin'])): ?>
                <a href="admin.php?logout=1" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition-colors text-sm lg:text-base">
                    Logout
                </a>
                <?php endif; ?>
                <button @click="darkMode = !darkMode" class="p-2 rounded-full bg-white/20 dark:bg-gray-700/50 hover:bg-cyan-500/20 transition-all">
                    <span x-show="!darkMode" class="text-yellow-400 text-sm lg:text-base">🌙</span>
                    <span x-show="darkMode" class="text-cyan-400 text-sm lg:text-base">☀️</span>
                </button>
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex items-center space-x-2">
                <!-- Tablet Language Selector -->
                <div class="hidden md:flex lg:hidden space-x-1">
                    <a href="?lang=pt" class="px-2 py-1 rounded-full text-xs font-medium transition-all <?php echo $lang === 'pt' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/20 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 hover:bg-cyan-500/20'; ?>"><?php echo $translations[$lang]['lang_pt']; ?></a>
                    <a href="?lang=en" class="px-2 py-1 rounded-full text-xs font-medium transition-all <?php echo $lang === 'en' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/20 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 hover:bg-cyan-500/20'; ?>"><?php echo $translations[$lang]['lang_en']; ?></a>
                </div>
                <button @click="darkMode = !darkMode" class="md:hidden p-2 rounded-full bg-white/20 dark:bg-gray-700/50 hover:bg-cyan-500/20 transition-all">
                    <span x-show="!darkMode" class="text-yellow-400 text-sm">🌙</span>
                    <span x-show="darkMode" class="text-cyan-400 text-sm">☀️</span>
                </button>
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-1.5 sm:p-2 rounded-full bg-white/20 dark:bg-gray-700/50 hover:bg-cyan-500/20 transition-all">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenu" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenu" class="lg:hidden mt-3 sm:mt-4 pb-3 sm:pb-4 border-t border-white/20 pt-3 sm:pt-4" x-cloak>
            <div class="flex flex-col space-y-2 sm:space-y-3 md:space-y-4">
                <?php if (basename($_SERVER['PHP_SELF']) === 'admin.php'): ?>
                <a href="index.php?lang=<?php echo $lang; ?>" class="text-white hover:text-cyan-400 transition-colors py-2 text-sm md:text-base" @click="mobileMenu = false">
                    <?php echo $translations[$lang]['nav_home']; ?>
                </a>
                <a href="projetos.php?lang=<?php echo $lang; ?>" class="text-white hover:text-cyan-400 transition-colors py-2 text-sm md:text-base" @click="mobileMenu = false">
                    <?php echo $translations[$lang]['nav_projects']; ?>
                </a>
                <a href="contato.php?lang=<?php echo $lang; ?>" class="text-white hover:text-cyan-400 transition-colors py-2 text-sm md:text-base" @click="mobileMenu = false">
                    <?php echo $translations[$lang]['nav_contact']; ?>
                </a>
                <?php else: ?>
                <a href="index.php?lang=<?php echo $lang; ?>" class="hover:text-cyan-400 transition-colors py-2 text-sm md:text-base" @click="mobileMenu = false">
                    <?php echo $translations[$lang]['nav_home']; ?>
                </a>
                <a href="projetos.php?lang=<?php echo $lang; ?>" class="hover:text-cyan-400 transition-colors py-2 text-sm md:text-base" @click="mobileMenu = false">
                    <?php echo $translations[$lang]['nav_projects']; ?>
                </a>
                <a href="contato.php?lang=<?php echo $lang; ?>" class="hover:text-cyan-400 transition-colors py-2 text-sm md:text-base" @click="mobileMenu = false">
                    <?php echo $translations[$lang]['nav_contact']; ?>
                </a>
                <?php if (isset($_SESSION['admin'])): ?>
                <a href="admin.php" class="hover:text-cyan-400 transition-colors py-2 text-sm md:text-base" @click="mobileMenu = false">
                    Admin
                </a>
                <?php endif; ?>
                <?php endif; ?>

                <!-- Mobile Language Selector -->
                <div class="flex flex-wrap gap-2 py-2">
                    <a href="?lang=pt" class="px-3 py-1 rounded-full text-sm font-medium transition-all <?php echo $lang === 'pt' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/20 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 hover:bg-cyan-500/20'; ?>" @click="mobileMenu = false"><?php echo $translations[$lang]['lang_pt']; ?></a>
                    <a href="?lang=en" class="px-3 py-1 rounded-full text-sm font-medium transition-all <?php echo $lang === 'en' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/20 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 hover:bg-cyan-500/20'; ?>" @click="mobileMenu = false"><?php echo $translations[$lang]['lang_en']; ?></a>
                    <a href="?lang=es" class="px-3 py-1 rounded-full text-sm font-medium transition-all <?php echo $lang === 'es' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/20 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 hover:bg-cyan-500/20'; ?>" @click="mobileMenu = false"><?php echo $translations[$lang]['lang_es']; ?></a>
                    <a href="?lang=ja" class="px-3 py-1 rounded-full text-sm font-medium transition-all <?php echo $lang === 'ja' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/20 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 hover:bg-cyan-500/20'; ?>" @click="mobileMenu = false"><?php echo $translations[$lang]['lang_ja']; ?></a>
                </div>

                <!-- Mobile Dark Mode Toggle -->
                <button @click="darkMode = !darkMode; mobileMenu = false" class="flex items-center space-x-2 p-2 rounded-lg bg-white/20 dark:bg-gray-700/50 hover:bg-cyan-500/20 transition-all w-fit">
                    <span x-show="!darkMode" class="text-yellow-400 text-sm">🌙</span>
                    <span x-show="darkMode" class="text-cyan-400 text-sm">☀️</span>
                    <span class="text-sm">Dark Mode</span>
                </button>
            </div>
        </div>
    </nav>
</header>