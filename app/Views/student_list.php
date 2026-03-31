<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Students Directory<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.min.css">
    <style>
        /* DataTables Custom Polish */
        .dt-container {
            padding: 0 !important;
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
            margin-bottom: 0 !important;
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
            padding: 4px 10px !important;
            border-bottom: 2px solid var(--border-color) !important;
        }

        .column-search-input {
            width: 100% !important;
            height: 28px !important;
            padding: 0 8px !important;
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
            padding: 0 !important;
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
        
        .badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-pending { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .badge-approved { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-rejected { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        
        .btn-approve { background: #10b981; }
        .btn-reject { background: #f59e0b; }

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

        /* ─── Storage Metrics Panel ─────────────────────────── */
        .storage-panel {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.25rem;
        }
        .storage-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .storage-panel-header h3 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .storage-total-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            background: #eff6ff;
            color: var(--primary-color);
            border: 1px solid #bfdbfe;
        }
        .storage-total-badge.warning { background: #fffbeb; color: #92400e; border-color: #fde68a; }
        .storage-total-badge.critical { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
        .storage-bars {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.75rem;
        }
        .storage-bar-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .storage-bar-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .storage-bar-track {
            height: 8px;
            background: #f1f5f9;
            border-radius: 99px;
            overflow: hidden;
        }
        .storage-bar-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.8s ease;
        }
        .storage-bar-val {
            font-size: 0.72rem;
            color: var(--text-main);
            font-weight: 600;
        }
        .storage-bar-count {
            font-size: 0.68rem;
            color: var(--text-muted);
        }
        .quota-bar-wrap {
            margin-top: 0.85rem;
            border-top: 1px solid var(--border-color);
            padding-top: 0.85rem;
        }
        .quota-bar-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 5px;
        }
        .quota-bar-track {
            height: 10px;
            background: #f1f5f9;
            border-radius: 99px;
            overflow: hidden;
        }
        .quota-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #3b82f6, #6366f1);
            transition: width 1s ease;
        }
        .quota-bar-fill.warning { background: linear-gradient(90deg, #f59e0b, #ef4444); }
        .quota-bar-fill.critical { background: #ef4444; }

        /* ─── Export Modal ──────────────────────────────────── */
        .export-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 9000;
            align-items: center;
            justify-content: center;
        }
        .export-modal-backdrop.open { display: flex; }
        .export-modal {
            background: #fff;
            border-radius: 14px;
            width: 480px;
            max-width: 95vw;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: slideUp 0.25s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }
        .export-modal h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .export-field { margin-bottom: 1rem; }
        .export-field label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .export-field select,
        .export-field input[type=date] {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.875rem;
            color: var(--text-main);
            outline: none;
        }
        .export-field select:focus,
        .export-field input[type=date]:focus {
            border-color: var(--primary-color);
        }
        .export-date-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .export-modal-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }
        .export-note {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.75rem;
            padding: 0.6rem 0.75rem;
            background: #f8fafc;
            border-radius: 6px;
            border-left: 3px solid var(--primary-color);
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="header-row">
        <div>
            <h1>Students Directory</h1>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 0.25rem;">Manage and monitor all student academic records in one place.</p>
        </div>
        <div style="display:flex; gap: 0.6rem; align-items: center;">
            <button id="openExportModal" class="btn-link" style="background: #10b981;"><i class="fas fa-file-archive"></i> Bulk Export ZIP</button>
            <a href="/students/create" class="btn-link"><i class="fas fa-plus"></i> Add New Student</a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon bg-blue-light"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <span class="stat-value"><?= $total_students ?? 0 ?></span>
                <span class="stat-label">Total Student</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-orange-light" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <span class="stat-value"><?= $pending_count ?? 0 ?></span>
                <span class="stat-label">Pending Approval</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-green-light" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <span class="stat-value"><?= $approved_count ?? 0 ?></span>
                <span class="stat-label">Approved Registration</span>
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

    <!-- Storage Metrics Panel -->
    <div class="storage-panel" id="storagePanel">
        <div class="storage-panel-header">
            <h3><i class="fas fa-hdd" style="color: var(--primary-color);"></i> Storage Metrics</h3>
            <span class="storage-total-badge" id="storageTotalBadge"><i class="fas fa-spinner fa-spin"></i> Loading...</span>
        </div>
        <div class="storage-bars" id="storageBarsGrid">
            <!-- Injected by JS -->
            <div style="grid-column: 1/-1; text-align:center; color: var(--text-muted); font-size:0.8rem; padding: 0.5rem;">Calculating disk usage...</div>
        </div>
        <div class="quota-bar-wrap">
            <div class="quota-bar-header">
                <span>Overall Usage</span>
                <span id="quotaLabel">—</span>
            </div>
            <div class="quota-bar-track">
                <div class="quota-bar-fill" id="quotaBarFill" style="width:0%"></div>
            </div>
        </div>
    </div>

    <!-- Bulk Export Filter Modal -->
    <div class="export-modal-backdrop" id="exportModalBackdrop">
        <div class="export-modal">
            <h3><i class="fas fa-file-archive" style="color:#10b981;"></i> Bulk Export — Filter Documents</h3>

            <div class="export-field">
                <label>Department</label>
                <select id="exportDept">
                    <option value="">All Departments</option>
                    <?php
                        $exportDepts = array_unique(array_column($students, 'department'));
                        sort($exportDepts);
                        foreach ($exportDepts as $dept):
                            if (!empty($dept)):
                    ?>
                    <option value="<?= esc($dept) ?>"><?= esc($dept) ?></option>
                    <?php endif; endforeach; ?>
                </select>
            </div>

            <div class="export-field">
                <label>Status</label>
                <select id="exportStatus">
                    <option value="">All Statuses</option>
                    <option value="approved">Approved Only</option>
                    <option value="pending">Pending Only</option>
                    <option value="rejected">Rejected Only</option>
                </select>
            </div>

            <div class="export-field">
                <label>Date Range (Registration)</label>
                <div class="export-date-row">
                    <input type="date" id="exportFrom" placeholder="From">
                    <input type="date" id="exportTo" placeholder="To">
                </div>
            </div>

            <p class="export-note">
                <i class="fas fa-info-circle"></i>
                Export is capped at <strong>200 students</strong> per batch to prevent timeouts. Files are organized in categorized sub-folders.
            </p>

            <div class="export-modal-actions">
                <button class="btn btn-secondary" id="closeExportModal" style="padding: 0.5rem 1.25rem; border-radius:8px; border:1px solid var(--border-color); background:#fff; cursor:pointer; font-weight:600;">Cancel</button>
                <a href="#" id="exportDownloadBtn" class="btn-link" style="background:#10b981;">
                    <i class="fas fa-download"></i> Download ZIP
                </a>
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
                    <th style="width: 100px !important;">Status</th>
                    <th style="width: 150px !important;">Actions</th>
                </tr>
                <tr>
                    <th class="hidden-col"></th>
                    <th></th>
                    <th><input type="text" class="column-search-input" placeholder="Search Details..."></th>
                    <th><input type="text" class="column-search-input" placeholder="Search..."></th>
                    <th><input type="text" class="column-search-input" placeholder="Search..."></th>
                    <th>
                        <select class="column-search-input" style="height: 28px !important; padding: 0 4px !important;">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </th>
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
                        <td>
                            <span class="badge badge-<?= $s['status'] ?> status-text"><?= ucfirst($s['status']) ?></span>
                        </td>
                        <td class="action-cell">
                            <div style="display: flex; gap: 2px; justify-content: center;">
                                <a href="/students/view/<?= $s['id'] ?>" class="action-btn btn-view" title="View Detail"><i class="fas fa-eye"></i></a>
                                <?php if ($s['status'] === 'pending'): ?>
                                    <button type="button" class="action-btn btn-approve status-trigger" data-id="<?= $s['id'] ?>" data-status="approved" title="Approve"><i class="fas fa-check"></i></button>
                                    <button type="button" class="action-btn btn-reject status-trigger" data-id="<?= $s['id'] ?>" data-status="rejected" title="Reject"><i class="fas fa-times"></i></button>
                                <?php endif; ?>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
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
                $('select', this).on('change', function() {
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
                    message: 'Are you sure you want to delete this record?',
                    type: 'error',
                    confirmText: 'Delete Now',
                    cancelText: 'Keep Record',
                    onConfirm: function() {
                        window.location.href = url;
                    }
                });
            });

            // ⚡ Status Update (AJAX)
            $(document).on('click', '.status-trigger', function() {
                const id = $(this).data('id');
                const status = $(this).data('status');
                const row = $(this).closest('tr');
                
                UI.showModal({
                    title: (status === 'approved' ? 'Approve' : 'Reject') + ' Registration',
                    message: `Are you sure you want to mark this registration as ${status}?`,
                    type: status === 'approved' ? 'success' : 'warning',
                    confirmText: 'Yes, Proceed',
                    onConfirm: function() {
                        $.post(`/students/status/${id}`, { status: status }, function(response) {
                            if (response.status === 'success') {
                                const badge = row.find('.status-text');
                                badge.removeClass('badge-pending badge-approved badge-rejected').addClass(`badge-${status}`).text(status.charAt(0).toUpperCase() + status.slice(1));
                                row.find('.status-trigger').remove();
                                UI.showToast('Success', response.message, 'success');
                            } else {
                                UI.showToast('Error', response.message, 'error');
                            }
                        }, 'json');
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
                    });
                }
            });

            // \u2500\u2500\u2500\u2500\u2500 Storage Stats Panel \u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500
            const barColors = {
                'Photos':       '#3b82f6',
                'Resumes':      '#8b5cf6',
                'ID Proofs':    '#f59e0b',
                'Signatures':   '#10b981',
                'Certificates': '#ef4444',
            };

            function loadStorageStats() {
                $.getJSON('/students/storage-stats', function(data) {
                    const badge = $('#storageTotalBadge');
                    badge.removeClass('warning critical').text(data.total_human + ' / ' + data.quota_human);
                    if (data.status === 'warning')  badge.addClass('warning');
                    if (data.status === 'critical') badge.addClass('critical');

                    let html = '';
                    const maxBytes = Math.max(...Object.values(data.breakdown).map(d => d.bytes)) || 1;

                    $.each(data.breakdown, function(label, info) {
                        const pct  = info.bytes > 0 ? Math.round((info.bytes / maxBytes) * 100) : 0;
                        const color = barColors[label] || '#6366f1';
                        html += `
                            <div class="storage-bar-item">
                                <span class="storage-bar-label">${label}</span>
                                <div class="storage-bar-track">
                                    <div class="storage-bar-fill" style="width:0%; background:${color};" data-target="${pct}%"></div>
                                </div>
                                <span class="storage-bar-val">${info.human}</span>
                                <span class="storage-bar-count">${info.file_count} file${info.file_count !== 1 ? 's' : ''}</span>
                            </div>`;
                    });

                    $('#storageBarsGrid').html(html);

                    // Animate bars after render
                    setTimeout(() => {
                        $('#storageBarsGrid .storage-bar-fill').each(function() {
                            $(this).css('width', $(this).data('target'));
                        });
                    }, 80);

                    // Quota overall bar
                    const qPct = Math.min(data.used_percent, 100);
                    const qFill = $('#quotaBarFill').css('width', qPct + '%');
                    if (data.status === 'warning')  qFill.addClass('warning');
                    if (data.status === 'critical') qFill.addClass('critical');
                    $('#quotaLabel').text(data.used_percent + '% used of ' + data.quota_human + ' quota');
                }).fail(function() {
                    $('#storageBarsGrid').html('<div style="grid-column:1/-1;color:#ef4444;font-size:0.8rem;">Could not load storage data.</div>');
                });
            }

            loadStorageStats();

            // \u2500\u2500\u2500\u2500\u2500 Export Filter Modal \u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500
            $('#openExportModal').on('click', function() {
                $('#exportModalBackdrop').addClass('open');
                updateExportUrl();
            });

            $('#closeExportModal').on('click', function() {
                $('#exportModalBackdrop').removeClass('open');
            });

            // Close on backdrop click
            $('#exportModalBackdrop').on('click', function(e) {
                if ($(e.target).is('#exportModalBackdrop')) {
                    $(this).removeClass('open');
                }
            });

            function updateExportUrl() {
                const dept   = $('#exportDept').val();
                const status = $('#exportStatus').val();
                const from   = $('#exportFrom').val();
                const to     = $('#exportTo').val();

                const params = new URLSearchParams();
                if (dept)   params.set('department', dept);
                if (status) params.set('status', status);
                if (from)   params.set('from', from);
                if (to)     params.set('to', to);

                const qs = params.toString();
                $('#exportDownloadBtn').attr('href', '/students/export-zip' + (qs ? '?' + qs : ''));
            }

            // Rebuild download URL whenever filters change
            $('#exportDept, #exportStatus, #exportFrom, #exportTo').on('change', updateExportUrl);
        });
    </script>
<?= $this->endSection() ?>
