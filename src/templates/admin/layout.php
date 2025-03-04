<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="bg-indigo-800 text-white w-64 px-6 py-8 hidden md:block">
            <div class="flex items-center mb-8">
                <span class="text-2xl font-bold">Portfolio Admin</span>
            </div>
            
            <nav class="space-y-2">
                <a href="/admin/dashboard" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700 <?= strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false ? 'bg-indigo-700' : '' ?>">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="/admin/blog" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700 <?= strpos($_SERVER['REQUEST_URI'], '/admin/blog') !== false ? 'bg-indigo-700' : '' ?>">
                    <i class="fas fa-blog mr-2"></i> Blog Posts
                </a>
                <a href="/admin/cv" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700 <?= strpos($_SERVER['REQUEST_URI'], '/admin/cv') !== false ? 'bg-indigo-700' : '' ?>">
                    <i class="fas fa-file-alt mr-2"></i> CV
                </a>
            </nav>
            
            <div class="absolute bottom-0 left-0 w-64 px-6 py-6">
                <a href="/admin/logout" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </aside>

        <!-- Mobile menu -->
        <div class="md:hidden bg-indigo-800 text-white w-full" x-data="{ open: false }">
            <div class="flex items-center justify-between px-6 py-4">
                <span class="text-xl font-bold">Portfolio Admin</span>
                <button @click="open = !open" class="text-white">
                    <i x-show="!open" class="fas fa-bars text-xl"></i>
                    <i x-show="open" class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav x-show="open" class="px-6 py-4 space-y-2">
                <a href="/admin/dashboard" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700 <?= strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false ? 'bg-indigo-700' : '' ?>">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="/admin/blog" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700 <?= strpos($_SERVER['REQUEST_URI'], '/admin/blog') !== false ? 'bg-indigo-700' : '' ?>">
                    <i class="fas fa-blog mr-2"></i> Blog Posts
                </a>
                <a href="/admin/cv" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700 <?= strpos($_SERVER['REQUEST_URI'], '/admin/cv') !== false ? 'bg-indigo-700' : '' ?>">
                    <i class="fas fa-file-alt mr-2"></i> CV
                </a>
                <a href="/admin/logout" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm">
                <div class="py-4 px-6">
                    <h1 class="text-2xl font-bold text-gray-800"><?= $title ?? 'Admin Dashboard' ?></h1>
                </div>
            </header>
            
            <main class="flex-1 p-6">
                <?= $content ?>
            </main>
        </div>
    </div>
</body>
</html>