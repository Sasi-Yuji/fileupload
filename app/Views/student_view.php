<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Profile | ERP Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/style.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
</head>
<body>

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
                    <h1>Student Details</h1>
                    <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 0.25rem;">Detailed academic and personal profile of <?= esc($student['name']) ?>.</p>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <a href="/students/edit/<?= $student['id'] ?>" class="btn-link" style="background: var(--primary-color); border-color: var(--primary-color);"><i class="fas fa-edit"></i> Edit Profile</a>
                    <a href="/students" class="btn-link" style="background: #fff; color: var(--text-main); border: 1px solid var(--border-color);"><i class="fas fa-arrow-left"></i> Back to Directory</a>
                </div>
            </div>

            <div class="card" style="padding: 0; overflow: hidden; border: 1px solid var(--border-color);">
                <div class="profile-grid">
                    <!-- Profile Sidebar Section -->
                    <div class="profile-sidebar">
                        <div style="position: relative; display: inline-block; margin-bottom: 2rem;">
                            <img src="/uploads/profile/<?= $student['profile_photo'] ?>" style="width: 180px; height: 180px; border-radius: 20px; object-fit: cover; box-shadow: var(--shadow); border: 5px solid #fff;" alt="Profile">
                            <span style="position: absolute; bottom: 10px; right: 10px; width: 24px; height: 24px; background: #10b981; border: 3px solid #fff; border-radius: 50%;"></span>
                        </div>
                        <h2 style="margin-bottom: 0.5rem; color: #1e293b;"><?= esc($student['name']) ?></h2>
                        <span style="background: rgba(79, 70, 229, 0.1); color: var(--primary-color); padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;"><?= esc($student['department']) ?> Department</span>
                        
                        <div style="margin-top: 2.5rem; text-align: left; padding: 0 1rem;">
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Email Address</label>
                                <span style="font-size: 0.9375rem; color: var(--text-main); font-weight: 500; display: block; overflow: hidden; text-overflow: ellipsis;"><?= esc($student['email']) ?></span>
                            </div>
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Contact Number</label>
                                <span style="font-size: 0.9375rem; color: var(--text-main); font-weight: 500;"><?= esc($student['phone']) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Main Info/Docs Section -->
                    <div style="padding: 3rem;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;">
                            <i class="far fa-folder-open" style="color: var(--primary-color);"></i> Primary Documents
                        </h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 3rem;">
                            <a href="/uploads/resume/<?= $student['resume'] ?>" target="_blank" style="text-decoration: none; padding: 1.25rem; background: #fff; border: 1px solid var(--border-color); border-radius: 12px; display: flex; align-items: center; gap: 1rem; transition: all 0.2s; box-shadow: var(--shadow-sm);" onmouseover="this.style.borderColor='var(--primary-color)'; this.style.background='#fcfdfe'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.background='#fff'">
                                <div style="width: 48px; height: 48px; min-width: 48px; background: #eff6ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 1.25rem;">
                                    <i class="far fa-file-pdf"></i>
                                </div>
                                <div>
                                    <span style="display: block; font-size: 0.9375rem; font-weight: 600; color: var(--text-main);">Resume (CV)</span>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">Application Metadata</span>
                                </div>
                            </a>
                            <a href="/uploads/id_proof/<?= $student['id_proof'] ?>" target="_blank" style="text-decoration: none; padding: 1.25rem; background: #fff; border: 1px solid var(--border-color); border-radius: 12px; display: flex; align-items: center; gap: 1rem; transition: all 0.2s; box-shadow: var(--shadow-sm);" onmouseover="this.style.borderColor='var(--primary-color)'; this.style.background='#fcfdfe'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.background='#fff'">
                                <div style="width: 48px; height: 48px; min-width: 48px; background: #fdf2f8; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #db2777; font-size: 1.25rem;">
                                    <i class="far fa-id-card"></i>
                                </div>
                                <div>
                                    <span style="display: block; font-size: 0.9375rem; font-weight: 600; color: var(--text-main);">Identification</span>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">Verified ID Proof</span>
                                </div>
                            </a>
                        </div>

                        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-award" style="color: #eab308;"></i> Certificates (<?= count($certificates) ?>)
                        </h3>
                        <?php if(!empty($certificates)): ?>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 3rem;">
                                <?php foreach($certificates as $cert): ?>
                                    <a href="/uploads/certificates/<?= $cert['file_name'] ?>" target="_blank" style="text-decoration: none; padding: 1rem; background: #fff; border: 1px solid var(--border-color); border-radius: 10px; display: flex; align-items: center; gap: 10px; transition: all 0.2s; box-shadow: var(--shadow-sm);" onmouseover="this.style.borderColor='#eab308'; this.style.background='#fffbeb'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.background='#fff'">
                                        <i class="fas fa-certificate" style="color: #eab308;"></i>
                                        <span style="font-size: 0.8125rem; font-weight: 600; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= esc($cert['file_name']) ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="padding: 2rem; border: 2px dashed var(--border-color); border-radius: 12px; text-align: center; color: var(--text-muted); margin-bottom: 3rem;">
                                <i class="fas fa-info-circle" style="margin-bottom: 8px; display: block; font-size: 1.5rem;"></i>
                                <p style="font-size: 0.875rem; font-style: italic;">No certificates have been uploaded for this student.</p>
                            </div>
                        <?php endif; ?>

                        <!-- 🔹 Digital Signature Section -->
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-file-signature" style="color: var(--primary-color);"></i> Verified Signature
                        </h3>
                        <div style="padding: 1.5rem; background: #fff; border: 1px solid var(--border-color); border-radius: 12px; display: inline-block;">
                            <?php if ($student['signature']): ?>
                                <img src="/uploads/signature/<?= $student['signature'] ?>" style="max-width: 250px; height: auto; display: block; border-radius: 8px;" alt="Signature">
                            <?php else: ?>
                                <p style="color: var(--text-muted); font-size: 0.875rem;">Signature not yet uploaded.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
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
</body>
</html>

