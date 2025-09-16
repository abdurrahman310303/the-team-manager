<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Team Manager' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {}
            }
        }
    </script>
    <style>
        .btn {
            @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200;
        }
        .btn-primary {
            @apply text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500;
        }
        .btn-secondary {
            @apply text-gray-700 bg-white hover:bg-gray-50 border-gray-300 focus:ring-blue-500;
        }
        .btn-danger {
            @apply text-white bg-red-600 hover:bg-red-700 focus:ring-red-500;
        }
        .btn-sm {
            @apply px-3 py-2 text-sm;
        }
        .btn-success {
            @apply text-white bg-green-600 hover:bg-green-700 focus:ring-green-500;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="/dashboard" class="text-xl font-bold text-gray-900">Team Manager</a>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="/dashboard" class="<?= ($currentPage ?? '') === 'dashboard' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Dashboard
                            </a>
                            <?php if (isset($_SESSION['user']) && ($_SESSION['user']['role_name'] === 'bd' || $_SESSION['user']['role_name'] === 'admin')): ?>
                            <a href="/leads" class="<?= ($currentPage ?? '') === 'leads' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Leads
                            </a>
                            <?php endif; ?>
                            <a href="/expenses" class="<?= ($currentPage ?? '') === 'expenses' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Expenses
                            </a>
                            <?php if (isset($_SESSION['user']) && ($_SESSION['user']['role_name'] === 'developer' || $_SESSION['user']['role_name'] === 'admin')): ?>
                            <a href="/projects" class="<?= ($currentPage ?? '') === 'projects' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Projects
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <div class="ml-3 relative">
                            <div class="flex items-center space-x-4">
                                <?php if (isset($_SESSION['user'])): ?>
                                <span class="text-gray-700">Hello, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                                <a href="/logout" class="text-gray-500 hover:text-gray-700">Logout</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Flash Messages -->
                <?php if (isset($_SESSION['flash'])): ?>
                    <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                        <div class="mb-4 rounded-md <?= $type === 'error' ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200' ?> p-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm <?= $type === 'error' ? 'text-red-800' : 'text-green-800' ?>">
                                        <?= htmlspecialchars($message) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </main>
    </div>
</body>
</html>
