<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List | University ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/style.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.min.css">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* DataTables Custom Polish */
        .dt-container {
            padding: 0 !important; /* Remove excess padding */
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            width: 100% !important;
        }
        .dt-layout-row {
            width: 100% !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 0.75rem !important;
        }
        .dt-layout-cell {
            padding: 0 !important;
        }
        .dt-layout-table {
            width: 100% !important;
        }
        table.dataTable {
            width: auto !important;
            margin: 0 auto !important;
        }
        .dt-search input {
            padding: 0.5rem 1rem !important;
            border-radius: 8px !important;
            border: 1px solid var(--border-color) !important;
            margin-bottom: 0 !important; /* Remove bottom margin to close gap */
        }
        .dt-length select {
            border-radius: 8px !important;
            border: 1px solid var(--border-color) !important;
        }
        .dt-container .dt-layout-row {
            margin-bottom: 0 !important;
            margin-top: 0 !important;
        }
        
        /* Column Search Styles */
        table.dataTable thead tr:nth-child(2) th {
            padding: 4px 10px !important; /* Reduce padding on the row itself */
            border-bottom: 2px solid var(--border-color) !important;
        }

        .column-search-input {
            width: 100% !important;
            height: 28px !important; /* Force explicit slim height */
            padding: 0 8px !important; /* Force small padding */
            font-size: 11px !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 4px !important;
            outline: none !important;
            background: #fff !important;
            display: block !important;
            box-sizing: border-box !important;
            font-weight: 400 !important;
            margin: 0 !important;
            color: var(--text-main) !important;
        }
        .column-search-input::placeholder {
            color: var(--text-muted) !important;
        }
        .column-search-input:focus {
            border-color: var(--primary-color) !important;
            box-shadow: inset 0 0 0 1px var(--primary-color) !important;
        }


        .action-cell {
            padding: 8px 0 !important;
            width: 1px !important;
            text-align: center !important;
            white-space: nowrap !important;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px !important; 
            height: 26px !important; 
            padding: 0 !important; /* Override global button padding */
            border-radius: 4px;
            color: white;
            text-decoration: none;
            transition: transform 0.2s, background-color 0.2s;
            border: none;
            cursor: pointer;
            font-size: 11px;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            color: white;
            opacity: 0.9;
        }
        .btn-view { background: var(--secondary-color); }
        .btn-edit { background: #10b981; }
        .btn-delete { background: #ef4444; }
        tr.editing-row {
            background-color: #f8fafc !important;
        }

        .copy-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 2px 5px;
            font-size: 0.75rem;
            transition: color 0.2s;
        }
        .copy-btn:hover {
            color: var(--primary-color);
        }
    </style>


</head>
<body>

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

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-university"></i>
            ERP Admin
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item"><i class="fas fa-th-large"></i> Dashboard Overview</a>
            <a href="/students" class="nav-item active"><i class="fas fa-user-graduate"></i> Students Directory</a>
            <a href="#" class="nav-item"><i class="fas fa-building"></i> Departments</a>
            <a href="#" class="nav-item"><i class="fas fa-file-invoice"></i> Reports & Analytics</a>
            <a href="#" class="nav-item"><i class="fas fa-cog"></i> System Settings</a>
        </nav>
        <div style="margin-top: auto; padding-top: 2rem;">
            <a href="#" class="nav-item" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="top-bar">
            <div style="display: flex; align-items: center;">
                <button class="menu-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <button style="background: none; border: none; font-size: 1.25rem; color: var(--text-muted); cursor: pointer;"><i class="fas fa-bell"></i></button>
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.75rem;">AD</div>
                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--text-main);">Admin User</span>
                </div>
            </div>
        </header>

        <div class="container">
            <div class="header-row">
                <div>
                    <h1>Students Directory</h1>
                    <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 0.25rem;">Manage and monitor all student academic records in one place.</p>
                </div>
                <a href="/students/create" class="btn-link"><i class="fas fa-plus"></i> Add New Student</a>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon bg-blue-light"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <span class="stat-value"><?= count($students ?? []) ?></span>
                        <span class="stat-label">Total Students</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-green-light"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <span class="stat-value"><?= count($students ?? []) ?></span>
                        <span class="stat-label">Active Records</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-purple-light"><i class="fas fa-id-card"></i></div>
                    <div class="stat-info">
                        <?php 
                            $depts = [];
                            foreach($students as $s) if(!empty($s['department'])) $depts[] = $s['department'];
                            $uniqueDepts = count(array_unique($depts));
                        ?>
                        <span class="stat-value"><?= $uniqueDepts ?></span>
                        <span class="stat-label">Departments</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-yellow-light"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <span class="stat-value"><?= date('M j') ?></span>
                        <span class="stat-label">Current Session</span>
                    </div>
                </div>
            </div>

            <div class="table-container-dt">
            <table id="studentsTable" class="display" style="width:100% !important;">
                <thead>
                    <tr>
                        <th class="hidden-col">Last Updated</th>
                        <th style="width: 50px !important;">Photo</th>
                        <th style="width: 180px !important;">Student Details</th>
                        <th style="width: 140px !important;">Department</th>
                        <th style="width: 140px !important; text-align: center;">Contact</th>
                        <th style="width: 80px !important;">Actions</th>
                    </tr>
                    <tr>
                        <th class="hidden-col"></th>
                        <th></th>
                        <th><input type="text" class="column-search-input" placeholder="Search Details..."></th>
                        <th><input type="text" class="column-search-input" placeholder="Search..."></th>
                        <th><input type="text" class="column-search-input" placeholder="Search..."></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($students)): ?>
                        <?php foreach($students as $s): ?>
                        <tr id="row-<?= $s['id'] ?>" data-id="<?= $s['id'] ?>">
                            <td class="sort-timestamp hidden-col"><?= strtotime($s['updated_at'] ?? 'now') ?></td>
                            <td>
                                <img src="/uploads/profile/<?= $s['profile_photo'] ?>" class="student-img" style="width: 35px; height: 35px;" alt="<?= esc($s['name']) ?>">
                            </td>
                            <td>
                                <div class="name-text" style="font-weight: 600; color: var(--text-main); font-size: 0.9375rem;"><?= esc($s['name']) ?></div>
                                <div class="email-text" style="font-size: 0.8125rem; color: var(--text-muted);"><?= esc($s['email']) ?></div>
                            </td>
                            <td style="white-space: nowrap;">
                                <span class="dept-text" style="font-size: 0.9375rem; color: var(--text-main);"><?= esc($s['department'] ?? 'N/A') ?></span>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <span class="phone-text" style="font-size: 0.875rem; color: var(--text-main); font-weight: 500;"><?= esc($s['phone'] ?? 'N/A') ?></span>
                                    <button class="copy-btn copy-trigger" data-value="<?= esc($s['phone']) ?>" title="Copy Number"><i class="far fa-copy"></i></button>
                                </div>
                            </td>
                            <td class="action-cell">
                                <div style="display: flex; gap: 1px; justify-content: center;">
                                    <a href="/students/view/<?= $s['id'] ?>" class="action-btn btn-view" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="/students/edit/<?= $s['id'] ?>" class="action-btn btn-edit" title="Edit"><i class="fas fa-pen"></i></a>
                                    <button type="button" class="action-btn btn-delete delete-trigger" data-href="/students/delete/<?= $s['id'] ?>" title="Delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>
    <script src="<?= base_url('js/validator.js'); ?>"></script>
    <script src="<?= base_url('js/ui_helper.js'); ?>"></script>
    <script>
        $(document).ready(function() {
            // 🚀 Check for Flash Messages (Add Success)
            <?php if (session()->getFlashdata('success')): ?>
                UI.showToast('Success!', '<?= session()->getFlashdata('success') ?>', 'success', 4000);
            <?php endif; ?>

            const validator = new FormValidator();
            const table = $('#studentsTable').DataTable({
                pageLength: 10,
                responsive: true,
                orderCellsTop: true,
                autoWidth: false,
                order: [[0, 'desc']],
                columnDefs: [
                    { targets: [0], visible: false, searchable: false }
                ],
                layout: {
                    topStart: 'pageLength',
                    topEnd: 'search',
                    bottomStart: 'info',
                    bottomEnd: 'paging'
                },
                language: { 
                    search: "Global Search:",
                    searchPlaceholder: "Type to filter..."
                }
            });

            // Column Search
            $('#studentsTable thead tr:eq(1) th').each(function(i) {
                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
            });

            // Modern Delete Confirmation
            $(document).on('click', '.delete-trigger', function() {
                const url = $(this).data('href');
                UI.showModal({
                    title: 'Delete Student',
                    message: 'Are you sure you want to delete this record? This action cannot be undone.',
                    type: 'error',
                    confirmText: 'Delete Now',
                    cancelText: 'Keep Record',
                    onConfirm: function() {
                        window.location.href = url;
                    }
                });
            });

            // Copy to Clipboard
            $(document).on('click', '.copy-trigger', function() {
                const val = $(this).data('value');
                const btn = $(this);
                const originalIcon = btn.find('i').attr('class');
                
                if (val && val !== 'N/A') {
                    navigator.clipboard.writeText(val).then(() => {
                        btn.find('i').attr('class', 'fas fa-check').css('color', '#10b981');
                        setTimeout(() => {
                            btn.find('i').attr('class', originalIcon).css('color', '');
                        }, 2000);
                        // Optional toast
                        // UI.showToast('Copied!', 'Number copied to clipboard.', 'success', 2000);
                    });
                }
            });
            // Sidebar Toggle for Mobile
            $('#sidebarToggle').on('click', function() {
                $('.sidebar').toggleClass('active');
            });

            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() <= 992) {
                    if (!$('.sidebar').is(e.target) && $('.sidebar').has(e.target).length === 0 && !$('#sidebarToggle').is(e.target) && $('#sidebarToggle').has(e.target).length === 0) {
                        $('.sidebar').removeClass('active');
                    }
                }
            });
        });
    </script>
        </div>
    </div>
</body>
</html>




