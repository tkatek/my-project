<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <title><?php echo e(config('app.name', 'Laravel')); ?></title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
    <body class="font-sans antialiased">
        <div class="app-wrapper">

            <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>

            <aside class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <a href="<?php echo e(route('dashboard')); ?>" class="sidebar-brand">
                        <div class="brand-icon">
                            <i class="fa-solid fa-cube"></i>
                        </div>
                        <span>Admin</span>
                    </a>
                </div>

                <nav class="sidebar-nav">
                    <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-house"></i>
                        Dashboard
                    </a>
                    <a href="<?php echo e(route('admin.packages')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.packages') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-box"></i>
                        Packages
                    </a>
                    <a href="<?php echo e(route('admin.orders')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.orders') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-clipboard-list"></i>
                        Orders
                    </a>
                    <a href="<?php echo e(route('admin.messages')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.messages*') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-envelope"></i>
                        Messages
                        <?php ($unreadMessages = \App\Models\Contact::whereNull('read_at')->count()); ?>
                        <?php if($unreadMessages > 0): ?>
                            <span style="margin-left:auto;background:#f97316;color:#fff;font-size:11px;font-weight:700;padding:2px 7px;border-radius:9999px;"><?php echo e($unreadMessages); ?></span>
                        <?php endif; ?>
                    </a>
                </nav>

                <div class="sidebar-footer">
                    <div class="user-info">
                        <div class="user-avatar"><?php echo e(substr(Auth::user()->name, 0, 1)); ?></div>
                        <div class="user-details">
                            <p class="user-name"><?php echo e(Auth::user()->name); ?></p>
                            <p class="user-email"><?php echo e(Auth::user()->email); ?></p>
                        </div>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="logout-btn">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Log Out
                        </button>
                    </form>
                </div>
            </aside>

            <div class="main-content">
                <header class="top-header">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h1 class="page-title"><?php echo e($header ?? 'Dashboard'); ?></h1>
                </header>
                <main class="content-area">
                    <?php echo e($slot); ?>

                </main>
            </div>
        </div>

        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                const isOpen = sidebar.classList.contains('open');
                if (isOpen) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('visible');
                } else {
                    sidebar.classList.add('open');
                    overlay.classList.add('visible');
                }
            }
        </script>

        <style>
            * { box-sizing: border-box; margin: 0; padding: 0; }
            body { overflow-x: hidden; background: #faf8f3; }
            .app-wrapper { display: flex; min-height: 100vh; position: relative; }

            .sidebar {
                width: 260px;
                background: linear-gradient(180deg, #fef9f0 0%, #faf5eb 100%);
                border-right: 1px solid #f0e9dd;
                display: flex;
                flex-direction: column;
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 50;
                transition: transform 0.3s ease;
            }
            .sidebar-header { padding: 24px 20px; border-bottom: 1px solid #f0e9dd; }
            .sidebar-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
            .brand-icon { width: 36px; height: 36px; background: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
            .sidebar-brand span { font-size: 18px; font-weight: 700; color: #111827; }

            .sidebar-nav { flex: 1; padding: 16px 12px; display: flex; flex-direction: column; gap: 4px; }
            .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; text-decoration: none; font-size: 14px; font-weight: 500; color: #6b7280; transition: all 0.2s; }
            .nav-link:hover { background: #f5f0e8; }
            .nav-link.active { background: #fff3e6; color: #f97316; }

            .sidebar-footer { padding: 16px 12px; border-top: 1px solid #f0e9dd; }
            .user-info { display: flex; align-items: center; gap: 12px; padding: 12px 16px; margin-bottom: 8px; }
            .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: #fff3e6; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600; color: #f97316; flex-shrink: 0; }
            .user-details { min-width: 0; }
            .user-name { font-size: 14px; font-weight: 500; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .user-email { font-size: 12px; color: #9ca3af; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .logout-btn { width: 100%; display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; font-size: 14px; font-weight: 500; color: #6b7280; background: transparent; border: none; cursor: pointer; text-align: left; }
            .logout-btn:hover { background: #fef2f2; color: #dc2626; }

            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 40; }
            .sidebar-overlay.visible { display: block; }

            .main-content { flex: 1; margin-left: 260px; display: flex; flex-direction: column; min-height: 100vh; min-width: 0; }
            .top-header { background: #fff; height: 64px; display: flex; align-items: center; padding: 0 32px; border-bottom: 1px solid #f0e9dd; position: sticky; top: 0; z-index: 20; }
            .menu-toggle { display: none; background: none; border: none; cursor: pointer; padding: 8px; margin-right: 16px; }
            .page-title { font-size: 18px; font-weight: 600; color: #111827; }
            .content-area { flex: 1; padding: 32px; }

            @media (max-width: 768px) {
                .sidebar { transform: translateX(-100%); }
                .sidebar.open { transform: translateX(0); }
                .main-content { margin-left: 0; }
                .menu-toggle { display: block; }
                .content-area { padding: 16px; }
                .top-header { padding: 0 16px; }
            }
        </style>
    </body>
</html>
<?php /**PATH C:\Users\HP\my-project\resources\views/components/sidebar-layout.blade.php ENDPATH**/ ?>