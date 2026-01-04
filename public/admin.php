<?php
// Incluir sistema de segurança
require_once '../src/config/security.php';
$config = SecurityHelper::getConfig();

// Rate limiting para login
$clientIP = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (!SecurityHelper::isLoginAllowed($clientIP)) {
    $login_error = 'Muitas tentativas de login. Tente novamente em 15 minutos.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = SecurityHelper::sanitizeInput($_POST['username']);
    $password = SecurityHelper::sanitizeInput($_POST['password']);
    
    if ($username !== $config['security']['admin_username'] || $password !== $config['security']['admin_password']) {
        $login_error = 'Usuário ou senha incorretos!';
    } else {
        $_SESSION['admin'] = true;
        $_SESSION['login_time'] = time();
    }
}
if (!isset($_SESSION['admin']) || 
    (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $config['security']['session_timeout'])) {
    // Destruir sessão se expirou
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $config['security']['session_timeout']) {
        session_destroy();
        $login_error = 'Sessão expirada. Faça login novamente.';
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt" x-data="{ darkMode: false }" x-init="darkMode = localStorage.getItem('darkMode') === 'true'; $watch('darkMode', value => localStorage.setItem('darkMode', value))" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - Portfolio</title>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="assets/css/app.css">
        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            .animate-float {
                animation: float 3s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 min-h-screen flex items-center justify-center p-4 overflow-hidden relative" :class="darkMode ? 'bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900' : 'bg-gradient-to-br from-blue-50 via-indigo-100 to-purple-100'">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative z-10 bg-white/10 dark:bg-gray-800/50 backdrop-blur-xl p-10 rounded-2xl shadow-2xl border border-white/20 max-w-lg w-full">
            <div class="text-center mb-8">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-r from-cyan-400 to-purple-400 rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-4xl font-bold bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400 bg-clip-text text-transparent mb-2">Admin Portal</h2>
                <p class="text-white text-lg">Entre com suas credenciais para acessar</p>
                <?php if ($login_error): ?>
                <div class="bg-red-500/20 border border-red-400 text-red-300 px-4 py-3 rounded-lg">
                    <?php echo $login_error; ?>
                </div>
                <?php endif; ?>
            </div>

            <form method="post" class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-cyan-400 mb-3 uppercase tracking-wide">Usuário</label>
                    <input type="text" name="username" required
                           class="w-full px-5 py-4 border border-white/30 rounded-xl bg-white/20 text-white placeholder-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all text-lg"
                           placeholder="Digite seu usuário">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-cyan-400 mb-3 uppercase tracking-wide">Senha</label>
                    <input type="password" name="password" required
                           class="w-full px-5 py-4 border border-white/30 rounded-xl bg-white/20 text-white placeholder-gray-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all text-lg"
                           placeholder="Digite sua senha">
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500 hover:from-cyan-600 hover:via-blue-600 hover:to-purple-600 text-white py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl relative overflow-hidden">
                    <span class="relative z-10">Acessar Sistema</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-cyan-300 text-sm">Portfólio de Heriberto da Fonseca Monteiro</p>
                <p class="text-cyan-400 text-xs mt-1">Desenvolvedor Full-Stack</p>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}
$_SESSION['admin'] = true;

// Language detection
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

// Include data
include '../src/data/translations.php';
include '../src/data/projetos.php';

// Handle updates
$message = '';
$messageType = 'success';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_project'])) {
        // Update project
        $index = $_POST['project_index'];
        $projetos[$index]['titulo'] = $_POST['titulo'];
        $projetos[$index]['descricao'] = $_POST['descricao'];
        $projetos[$index]['tecnologias'] = array_map('trim', explode(',', $_POST['tecnologias']));
        $projetos[$index]['link'] = $_POST['link'];
        // Save to file
        $content = "<?php\n\$projetos = " . var_export($projetos, true) . ";\n?>";
        file_put_contents('../src/data/projetos.php', $content);
        $message = 'Projeto atualizado com sucesso!';
    }
    if (isset($_POST['update_translation'])) {
        // Update translation
        $lang = $_POST['lang'];
        $key = $_POST['key'];
        $translations[$lang][$key] = $_POST['value'];
        // Save to file
        $content = "<?php\n\$translations = " . var_export($translations, true) . ";\n?>";
        file_put_contents('../src/data/translations.php', $content);
        $message = 'Tradução atualizada com sucesso!';
    }
    if (isset($_POST['add_project'])) {
        // Add new project
        $newProject = [
            'titulo' => $_POST['new_titulo'],
            'descricao' => $_POST['new_descricao'],
            'tecnologias' => array_map('trim', explode(',', $_POST['new_tecnologias'])),
            'link' => $_POST['new_link']
        ];
        $projetos[] = $newProject;
        $content = "<?php\n\$projetos = " . var_export($projetos, true) . ";\n?>";
        file_put_contents('../src/data/projetos.php', $content);
        $message = 'Novo projeto adicionado!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt" x-data="{ darkMode: false, mobileMenu: false, activeTab: 'projects' }" x-init="darkMode = localStorage.getItem('darkMode') === 'true'; $watch('darkMode', value => localStorage.setItem('darkMode', value))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Portfolio</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 min-h-screen">
    <div class="min-h-screen">
        <?php include '../src/partials/header.php'; ?>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-6 md:py-8">
            <?php if ($message): ?>
            <div class="mb-6 md:mb-8 p-4 rounded-xl shadow-lg <?php echo $messageType === 'success' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 border border-red-200 dark:border-red-800'; ?>">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <!-- Tabs -->
            <div class="mb-6 md:mb-8">
                <div class="flex space-x-1 bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-1 rounded-xl shadow-lg overflow-x-auto">
                    <button @click="activeTab = 'projects'" :class="activeTab === 'projects' ? 'bg-cyan-500 text-white' : 'text-gray-600 dark:text-gray-300 hover:text-cyan-400'" class="py-2 md:py-3 px-2 md:px-4 rounded-lg font-medium transition-all whitespace-nowrap text-xs md:text-sm lg:text-base min-w-fit">Projetos</button>
                    <button @click="activeTab = 'translations'" :class="activeTab === 'translations' ? 'bg-cyan-500 text-white' : 'text-gray-600 dark:text-gray-300 hover:text-cyan-400'" class="py-2 md:py-3 px-2 md:px-4 rounded-lg font-medium transition-all whitespace-nowrap text-xs md:text-sm lg:text-base min-w-fit">Traduções</button>
                    <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-cyan-500 text-white' : 'text-gray-600 dark:text-gray-300 hover:text-cyan-400'" class="py-2 md:py-3 px-2 md:px-4 rounded-lg font-medium transition-all whitespace-nowrap text-xs md:text-sm lg:text-base min-w-fit">SEO & Marketing</button>
                    <button @click="activeTab = 'analytics'" :class="activeTab === 'analytics' ? 'bg-cyan-500 text-white' : 'text-gray-600 dark:text-gray-300 hover:text-cyan-400'" class="py-2 md:py-3 px-2 md:px-4 rounded-lg font-medium transition-all whitespace-nowrap text-xs md:text-sm lg:text-base min-w-fit">Analytics</button>
                </div>
            </div>

            <!-- Projects Tab -->
            <div x-show="activeTab === 'projects'" class="space-y-4 md:space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h2 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">Gerenciar Projetos</h2>
                    <button @click="showAddProject = !showAddProject" class="bg-gradient-to-r from-cyan-500 to-purple-500 hover:from-cyan-600 hover:to-purple-600 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">+ Novo Projeto</button>
                </div>

                <!-- Add New Project -->
                <div x-show="showAddProject" x-data="{ showAddProject: false }" class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 md:p-6 rounded-xl shadow-lg border border-white/20">
                    <h3 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-cyan-400">Adicionar Novo Projeto</h3>
                    <form method="post" class="space-y-3 md:space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-cyan-400 mb-2">Título</label>
                            <input type="text" name="new_titulo" required class="w-full px-4 py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all text-sm md:text-base">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-cyan-400 mb-2">Descrição</label>
                            <textarea name="new_descricao" rows="3" required class="w-full px-4 py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all resize-none text-sm md:text-base"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-cyan-400 mb-2">Tecnologias (separadas por vírgula)</label>
                            <input type="text" name="new_tecnologias" required class="w-full px-4 py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all text-sm md:text-base">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-cyan-400 mb-2">Link</label>
                            <input type="url" name="new_link" class="w-full px-4 py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all text-sm md:text-base">
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <button type="submit" name="add_project" class="bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">Adicionar</button>
                            <button type="button" @click="showAddProject = false" class="bg-gray-500 hover:bg-gray-600 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg font-semibold transition-all text-sm md:text-base">Cancelar</button>
                        </div>
                    </form>
                </div>

                <!-- Existing Projects -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <?php foreach ($projetos as $index => $projeto): ?>
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 md:p-6 rounded-xl shadow-lg border border-white/20 hover:border-cyan-400/50 transition-all duration-300">
                        <h3 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-cyan-400"><?php echo $projeto['titulo']; ?></h3>
                        <form method="post" class="space-y-3 md:space-y-4">
                            <input type="hidden" name="project_index" value="<?php echo $index; ?>">
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-1">Título</label>
                                <input type="text" name="titulo" value="<?php echo htmlspecialchars($projeto['titulo']); ?>" required class="w-full px-3 py-2 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 transition-all text-sm md:text-base">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-1">Descrição</label>
                                <textarea name="descricao" rows="3" required class="w-full px-3 py-2 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 transition-all resize-none text-sm md:text-base"><?php echo htmlspecialchars($projeto['descricao']); ?></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-1">Tecnologias</label>
                                <input type="text" name="tecnologias" value="<?php echo htmlspecialchars(implode(', ', $projeto['tecnologias'])); ?>" required class="w-full px-3 py-2 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 transition-all text-sm md:text-base">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-1">Link</label>
                                <input type="url" name="link" value="<?php echo htmlspecialchars($projeto['link']); ?>" class="w-full px-3 py-2 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 transition-all text-sm md:text-base">
                            </div>
                            <button type="submit" name="update_project" class="w-full bg-gradient-to-r from-cyan-500 to-purple-500 hover:from-cyan-600 hover:to-purple-600 text-white py-2 md:py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">Atualizar Projeto</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Translations Tab -->
            <div x-show="activeTab === 'translations'" class="space-y-6">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">Gerenciar Traduções</h2>
                <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-white/20">
                    <form method="post" class="space-y-4">
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-2">Idioma</label>
                                <select name="lang" class="w-full px-4 py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all">
                                    <option value="pt">Português</option>
                                    <option value="en">Inglês</option>
                                    <option value="es">Espanhol</option>
                                    <option value="ja">Japonês</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-2">Chave</label>
                                <input type="text" name="key" placeholder="ex: home_title" required class="w-full px-4 py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-2">Valor</label>
                                <input type="text" name="value" placeholder="Texto da tradução" required class="w-full px-4 py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all">
                            </div>
                        </div>
                        <button type="submit" name="update_translation" class="bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">Atualizar Tradução</button>
                    </form>
                </div>
            </div>

            <!-- SEO & Marketing Tab -->
            <div x-show="activeTab === 'seo'" class="space-y-6">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">SEO & Marketing</h2>

                <!-- Google Analytics -->
                <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-white/20">
                    <h3 class="text-xl font-semibold mb-4 text-cyan-400">Google Analytics</h3>
                    <form method="post" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-cyan-400 mb-2">Tracking ID (GA4)</label>
                            <input type="text" name="ga_id" placeholder="G-XXXXXXXXXX" class="w-full px-4 py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all">
                        </div>
                        <button type="submit" name="update_ga" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">Atualizar GA</button>
                    </form>
                </div>

                <!-- Ad Platforms -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 md:p-6 rounded-xl shadow-lg border border-white/20">
                        <h3 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-cyan-400">Google Ads</h3>
                        <form method="post" class="space-y-3 md:space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-2">Conversion ID</label>
                                <input type="text" name="google_ads_id" placeholder="AW-XXXXXXXXX" class="w-full px-4 py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all text-sm md:text-base">
                            </div>
                            <button type="submit" name="update_google_ads" class="bg-red-500 hover:bg-red-600 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">Atualizar Google Ads</button>
                        </form>
                    </div>

                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 md:p-6 rounded-xl shadow-lg border border-white/20">
                        <h3 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-cyan-400">Meta (Facebook) Ads</h3>
                        <form method="post" class="space-y-3 md:space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-2">Pixel ID</label>
                                <input type="text" name="meta_pixel_id" placeholder="123456789012345" class="w-full px-4 py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all text-sm md:text-base">
                            </div>
                            <button type="submit" name="update_meta_ads" class="bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">Atualizar Meta Ads</button>
                        </form>
                    </div>

                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 md:p-6 rounded-xl shadow-lg border border-white/20">
                        <h3 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-cyan-400">TikTok Ads</h3>
                        <form method="post" class="space-y-3 md:space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-2">Pixel ID</label>
                                <input type="text" name="tiktok_pixel_id" placeholder="ABC123DEF456" class="w-full px-4 py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all text-sm md:text-base">
                            </div>
                            <button type="submit" name="update_tiktok_ads" class="bg-black hover:bg-gray-800 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">Atualizar TikTok Ads</button>
                        </form>
                    </div>

                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-4 md:p-6 rounded-xl shadow-lg border border-white/20">
                        <h3 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-cyan-400">LinkedIn Ads</h3>
                        <form method="post" class="space-y-3 md:space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-2">Partner ID</label>
                                <input type="text" name="linkedin_partner_id" placeholder="123456" class="w-full px-4 py-3 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition-all text-sm md:text-base">
                            </div>
                            <button type="submit" name="update_linkedin_ads" class="bg-blue-700 hover:bg-blue-800 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">Atualizar LinkedIn Ads</button>
                        </form>
                    </div>
                </div>

                <!-- SEO Tools -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-white/20">
                        <h3 class="text-xl font-semibold mb-4 text-cyan-400">Ferramentas Técnicas SEO</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-2">Robots.txt</label>
                                <textarea rows="3" class="w-full px-3 py-2 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white text-xs" readonly><?php echo htmlspecialchars(file_get_contents('robots.txt')); ?></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-cyan-400 mb-2">Sitemap.xml</label>
                                <textarea rows="3" class="w-full px-3 py-2 border border-white/30 rounded-lg bg-white/20 dark:bg-gray-700/50 text-gray-900 dark:text-white text-xs" readonly><?php echo htmlspecialchars(substr(file_get_contents('sitemap.xml'), 0, 200)) . '...'; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-white/20">
                        <h3 class="text-xl font-semibold mb-4 text-cyan-400">Análise On-Page</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span>Meta Descriptions:</span>
                                <span class="font-semibold text-green-400">✓ Otimizadas</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Headings (H1-H2):</span>
                                <span class="font-semibold text-green-400">✓ Estruturados</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Schema Markup:</span>
                                <span class="font-semibold text-green-400">✓ Implementado</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Velocidade Mobile:</span>
                                <span class="font-semibold text-green-400">✓ Otimizada</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Keyword Research -->
                <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-white/20">
                    <h3 class="text-xl font-semibold mb-4 text-cyan-400">Otimização para Vagas</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-lg font-medium mb-3 text-cyan-300">Palavras-Chave Alvo</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>"Laravel Developer"</span>
                                    <span class="text-green-400">Alta</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>"Full-Stack PHP"</span>
                                    <span class="text-green-400">Alta</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>"Flutter Mobile Dev"</span>
                                    <span class="text-yellow-400">Média</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>"Vue.js Expert"</span>
                                    <span class="text-yellow-400">Média</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-medium mb-3 text-cyan-300">Plataformas de Busca</h4>
                            <div class="space-y-2">
                                <div>• LinkedIn Jobs</div>
                                <div>• Indeed Brasil</div>
                                <div>• Glassdoor</div>
                                <div>• GitHub Jobs</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Optimization -->
                <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-white/20">
                    <h3 class="text-xl font-semibold mb-4 text-cyan-400">Otimização de Conteúdo</h3>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-400 mb-1">95%</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Densidade Ideal</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-400">H1 ✓</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Heading Principal</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-400">4.2s</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Tempo de Carregamento</div>
                        </div>
                    </div>
                </div>

                <!-- Backlinks & Links Internos -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-white/20">
                        <h3 class="text-xl font-semibold mb-4 text-cyan-400">Links Internos</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Home → Projetos:</span>
                                <span class="font-semibold text-green-400">✓</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Projetos → Contato:</span>
                                <span class="font-semibold text-green-400">✓</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Links por página:</span>
                                <span class="font-semibold text-blue-400">3-5</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-white/20">
                        <h3 class="text-xl font-semibold mb-4 text-cyan-400">Backlinks Sugeridos</h3>
                        <div class="space-y-2 text-sm">
                            <div>• GitHub Profile</div>
                            <div>• LinkedIn Profile</div>
                            <div>• Stack Overflow</div>
                            <div>• Medium Articles</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Tab -->
            <div x-show="activeTab === 'analytics'" class="space-y-4 md:space-y-6">
                <h2 class="text-xl md:text-2xl lg:text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">Analytics & Estatísticas</h2>

                <!-- Portfolio Engagement Chart -->
                <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-3 md:p-4 lg:p-6 rounded-xl shadow-lg border border-white/20">
                    <h3 class="text-base md:text-lg lg:text-xl font-semibold mb-2 md:mb-4 text-cyan-400">Engajamento do Portfólio (Últimos 7 dias)</h3>
                    <div class="h-32 md:h-48 lg:h-64 flex items-end justify-between space-x-1 md:space-x-2">
                        <div class="flex-1 bg-gradient-to-t from-cyan-500 to-cyan-300 rounded-t" style="height: 40%"></div>
                        <div class="flex-1 bg-gradient-to-t from-cyan-500 to-cyan-300 rounded-t" style="height: 60%"></div>
                        <div class="flex-1 bg-gradient-to-t from-cyan-500 to-cyan-300 rounded-t" style="height: 80%"></div>
                        <div class="flex-1 bg-gradient-to-t from-cyan-500 to-cyan-300 rounded-t" style="height: 70%"></div>
                        <div class="flex-1 bg-gradient-to-t from-cyan-500 to-cyan-300 rounded-t" style="height: 90%"></div>
                        <div class="flex-1 bg-gradient-to-t from-cyan-500 to-cyan-300 rounded-t" style="height: 75%"></div>
                        <div class="flex-1 bg-gradient-to-t from-cyan-500 to-cyan-300 rounded-t" style="height: 85%"></div>
                    </div>
                    <div class="flex justify-between mt-1 md:mt-2 text-xs md:text-sm text-gray-600 dark:text-gray-300">
                        <span>Seg</span><span>Ter</span><span>Qua</span><span>Qui</span><span>Sex</span><span>Sáb</span><span>Dom</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 md:gap-4 lg:gap-6">
                    <!-- Portfolio Interest -->
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-3 md:p-4 lg:p-6 rounded-xl shadow-lg border border-white/20">
                        <h3 class="text-base md:text-lg lg:text-xl font-semibold mb-2 md:mb-3 lg:mb-4 text-cyan-400">Interesse por Habilidades</h3>
                        <div class="space-y-2 md:space-y-3">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs md:text-sm lg:text-base">Laravel/PHP</span>
                                    <span class="text-xs md:text-sm lg:text-base">65%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 md:h-2">
                                    <div class="bg-gradient-to-r from-cyan-500 to-purple-500 h-1.5 md:h-2 rounded-full" style="width: 65%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs md:text-sm lg:text-base">Vue.js/React</span>
                                    <span class="text-xs md:text-sm lg:text-base">55%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 md:h-2">
                                    <div class="bg-gradient-to-r from-cyan-500 to-purple-500 h-1.5 md:h-2 rounded-full" style="width: 55%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs md:text-sm lg:text-base">Flutter/Dart</span>
                                    <span class="text-xs md:text-sm lg:text-base">45%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 md:h-2">
                                    <div class="bg-gradient-to-r from-cyan-500 to-purple-500 h-1.5 md:h-2 rounded-full" style="width: 45%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Performance -->
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-3 md:p-4 lg:p-6 rounded-xl shadow-lg border border-white/20">
                        <h3 class="text-base md:text-lg lg:text-xl font-semibold mb-2 md:mb-3 lg:mb-4 text-cyan-400">Performance SEO</h3>
                        <div class="space-y-2 md:space-y-3 lg:space-y-4">
                            <div class="text-center">
                                <div class="text-xl md:text-2xl lg:text-3xl font-bold text-green-400 mb-1">92</div>
                                <div class="text-xs md:text-sm text-gray-600 dark:text-gray-300">Pontuação SEO</div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 md:gap-3 lg:gap-4 text-center">
                                <div>
                                    <div class="text-base md:text-lg lg:text-xl font-bold text-blue-400">4.2s</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-300">Loading Speed</div>
                                </div>
                                <div>
                                    <div class="text-base md:text-lg lg:text-xl font-bold text-purple-400">95%</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-300">Mobile Score</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Application Funnel -->
                <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-3 md:p-4 lg:p-6 rounded-xl shadow-lg border border-white/20">
                    <h3 class="text-base md:text-lg lg:text-xl font-semibold mb-2 md:mb-4 text-cyan-400">Funil de Candidatura</h3>
                    <div class="flex items-center justify-between h-20 md:h-24 lg:h-32 overflow-x-auto">
                        <div class="text-center flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 bg-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-xs md:text-sm lg:text-lg mb-1 md:mb-2">100%</div>
                            <div class="text-xs">Visualizações</div>
                        </div>
                        <div class="w-4 md:w-6 lg:w-8 h-0.5 bg-cyan-400 flex-shrink-0"></div>
                        <div class="text-center flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xs md:text-sm lg:text-lg mb-1 md:mb-2">75%</div>
                            <div class="text-xs">Interesse</div>
                        </div>
                        <div class="w-4 md:w-6 lg:w-8 h-0.5 bg-blue-400 flex-shrink-0"></div>
                        <div class="text-center flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xs md:text-sm lg:text-lg mb-1 md:mb-2">25%</div>
                            <div class="text-xs">Contatos</div>
                        </div>
                        <div class="w-4 md:w-6 lg:w-8 h-0.5 bg-purple-400 flex-shrink-0"></div>
                        <div class="text-center flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-xs md:text-sm lg:text-lg mb-1 md:mb-2">10%</div>
                            <div class="text-xs">Entrevistas</div>
                        </div>
                    </div>
                </div>

                <!-- Portfolio Stats -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 md:gap-3 lg:gap-4">
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-2 md:p-3 lg:p-4 rounded-xl shadow-lg border border-white/20 text-center">
                        <div class="text-lg md:text-xl lg:text-2xl font-bold text-cyan-400">2,847</div>
                        <div class="text-xs md:text-sm text-gray-600 dark:text-gray-300">Visualizações</div>
                    </div>
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-2 md:p-3 lg:p-4 rounded-xl shadow-lg border border-white/20 text-center">
                        <div class="text-lg md:text-xl lg:text-2xl font-bold text-green-400">156</div>
                        <div class="text-xs md:text-sm text-gray-600 dark:text-gray-300">Contatos Profissionais</div>
                    </div>
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-2 md:p-3 lg:p-4 rounded-xl shadow-lg border border-white/20 text-center">
                        <div class="text-lg md:text-xl lg:text-2xl font-bold text-purple-400">23</div>
                        <div class="text-xs md:text-sm text-gray-600 dark:text-gray-300">Entrevistas</div>
                    </div>
                    <div class="bg-white/10 dark:bg-gray-800/50 backdrop-blur-lg p-2 md:p-3 lg:p-4 rounded-xl shadow-lg border border-white/20 text-center">
                        <div class="text-lg md:text-xl lg:text-2xl font-bold text-yellow-400">3</div>
                        <div class="text-xs md:text-sm text-gray-600 dark:text-gray-300">Ofertas Recebidas</div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?php
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
}
?>