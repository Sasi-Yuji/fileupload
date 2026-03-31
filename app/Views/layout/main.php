<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('title') ?> | University ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/style.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- DataTables & Other Plugin CSS -->
    <?= $this->renderSection('styles') ?>

    <script>
        // Immediately apply sidebar state to prevent jump
        (function() {
            const sidebarState = localStorage.getItem('sidebar-collapsed');
            if (sidebarState === 'true') {
                document.documentElement.classList.add('sidebar-is-collapsed');
            }
        })();
    </script>
    <style>
        /* Sidebar Persistence Classes */
        .sidebar-is-collapsed .sidebar {
            width: 80px;
            padding: 2rem 0.75rem;
        }
        .sidebar-is-collapsed .sidebar-logo {
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .sidebar-is-collapsed .sidebar-logo span,
        .sidebar-is-collapsed .nav-item span {
            display: none;
        }
        .sidebar-is-collapsed .nav-item {
            justify-content: center;
            padding: 1rem;
        }
        .sidebar-is-collapsed .main-wrapper {
            margin-left: 80px;
            width: calc(100% - 80px);
        }
        
        /* Smooth transitions for shell elements */
        .sidebar, .main-wrapper, .top-bar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Active State Fix */
        .sidebar-nav .nav-item.active {
            background: #eff6ff !important;
            color: var(--primary-color) !important;
        }
    </style>
</head>
<?php $uriSegments = service('request')->getUri()->getSegments(); ?>
<body class="<?= (isset($uriSegments[1])) ? 'page-'.$uriSegments[1] : 'page-home' ?>">

    <!-- Premium Modal Overlay -->
    <div id="modal-overlay" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-icon warning"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="modal-title">Attention</div>
            <div class="modal-body text-muted">Are you sure you want to proceed?</div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary btn-cancel-modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-confirm">OK</button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <?php 
        $currentUri = uri_string();
        $isDashboard = ($currentUri == 'students/dashboard') ? 'active' : '';
        $isCreate = ($currentUri == 'students/create') ? 'active' : '';
        $isList = ($currentUri == 'students' || strpos($currentUri, 'students/view') !== false || strpos($currentUri, 'students/edit') !== false) ? 'active' : '';
    ?>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-university"></i> <span>ERP Admin</span>
        </div>
        <nav class="sidebar-nav">
            <a href="/students/dashboard" class="nav-item <?= $isDashboard ?>"><i class="fas fa-chart-line"></i> <span>System Dashboard</span></a>
            <a href="/students" class="nav-item <?= $isList ?>"><i class="fas fa-user-graduate"></i> <span>Students Directory</span></a>
            <a href="/students/create" class="nav-item <?= $isCreate ?>"><i class="fas fa-plus"></i> <span>Add New Student</span></a>
            <a href="#" class="nav-item"><i class="fas fa-building"></i> <span>Departments</span></a>
            <a href="#" class="nav-item"><i class="fas fa-file-invoice"></i> <span>Reports & Analytics</span></a>
            <a href="#" class="nav-item"><i class="fas fa-cog"></i> <span>System Settings</span></a>
        </nav>
        <div style="margin-top: auto;">
            <a href="/logout" class="nav-item" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="top-bar">
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button class="menu-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">Student Information System</h2>
                </div>
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <button style="background: none; border: none; font-size: 1.1rem; color: var(--text-muted); cursor: pointer;"><i class="fas fa-bell"></i></button>
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.75rem;">
                        <?= strtoupper(substr(session()->get('username') ?? 'A', 0, 2)) ?>
                    </div>
                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--text-main);"><?= esc(session()->get('username') ?? 'Admin User') ?></span>
                </div>
            </div>
        </header>

        <div id="content-area">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?= base_url('js/ui_helper.js'); ?>"></script>
    
    <script>
        $(document).ready(function() {
            // Sidebar Persistence & Toggle
            const body = $('body');
            const html = $('html');
            
            // Sync with class applied by inline script
            if (html.hasClass('sidebar-is-collapsed')) {
                $('.sidebar').addClass('collapsed');
            }

            $('#sidebarToggle').on('click', function() {
                $('.sidebar').toggleClass('collapsed');
                const isCollapsed = $('.sidebar').hasClass('collapsed');
                
                if(isCollapsed) {
                    html.addClass('sidebar-is-collapsed');
                    localStorage.setItem('sidebar-collapsed', 'true');
                } else {
                    html.removeClass('sidebar-is-collapsed');
                    localStorage.setItem('sidebar-collapsed', 'false');
                }
            });

            // Prevent link jump flicker (Visual Fix)
            $('a.nav-item').on('click', function() {
                const href = $(this).attr('href');
                if (href !== '#' && !href.startsWith('javascript:')) {
                    // Pre-active link to show immediate feedback
                    $('.nav-item').removeClass('active');
                    $(this).addClass('active');
                }
            });
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
