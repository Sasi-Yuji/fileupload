<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration | University ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/style.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.3.4/build/css/intlTelInput.css">
    
    <style>
        /* Signature Pad Styling */
        .signature-wrapper {
            position: relative;
            width: 100%;
            height: 200px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            touch-action: none;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        .signature-pad {
            position: absolute;
            left: 0;
            top: 0;
            width: 100% !important;
            height: 100% !important;
            cursor: crosshair;
        }
        .signature-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }
        .btn-clear-sig {
            font-size: 0.75rem;
            padding: 4px 10px;
            color: #ef4444;
            background: none;
            border: 1px solid #ef4444;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-clear-sig:hover {
            background: #fef2f2;
        }
        .signature-preview-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.8125rem;
            color: var(--text-muted);
        }
        .current-signature-preview {
            max-width: 150px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 4px;
            margin-bottom: 0.5rem;
        }
    </style>
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

        <div class="container" style="margin-top: 2rem;">
            <div class="card" style="max-width: 1000px; margin: 0 auto;">
                <h2 style="text-align: left; margin-bottom: 2rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;"><?= isset($student) ? 'Edit Student Details' : 'Add New Student'; ?></h2>
                <form id="registrationForm" action="<?= isset($student) ? '/students/update/'.$student['id'] : '/students/save'; ?>" method="post" enctype="multipart/form-data" novalidate style="width: 100%;">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" id="name" placeholder="Enter student name" value="<?= isset($student) ? esc($student['name']) : ''; ?>">
                            <span id="nameError" class="error-msg"></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" placeholder="example@domain.com" value="<?= isset($student) ? esc($student['email']) : ''; ?>">
                            <span id="emailError" class="error-msg"></span>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" name="phone" id="phone" value="<?= isset($student) ? esc($student['phone']) : ''; ?>">
                            <span id="phoneError" class="error-msg"></span>
                        </div>

                        <div class="form-group">
                            <label for="department">Department</label>
                            <select name="department" id="department" required>
                                <option value="" <?= !isset($student) ? 'selected' : ''; ?>>Select Department</option>
                                <?php 
                                    $depts = ["Computer Science and Engineering", "Electronics and Communication Engineering", "Electrical and Electronics Engineering", "Mechanical Engineering", "Civil Engineering", "Artificial Intelligence and Machine Learning", "Artificial Intelligence and Data Science", "Information Technology", "Chemical Engineering", "Aeronautical Engineering"];
                                    foreach($depts as $dept):
                                        $selected = (isset($student['department']) && $student['department'] == $dept) ? 'selected' : '';
                                        echo "<option value=\"$dept\" $selected>$dept</option>";
                                    endforeach;
                                ?>
                            </select>
                            <span id="deptError" class="error-msg"></span>
                        </div>

                        <div class="form-group">
                            <label>Profile Photo <?= isset($student) ? '(Optional)' : ''; ?></label>
                            <div class="custom-file-container">
                                <input type="file" name="profile_photo" id="profile_photo" class="custom-file-input" accept="image/*" <?= isset($student) ? '' : 'required'; ?> data-original="<?= isset($student) ? $student['profile_photo'] : '' ?>">
                                <div class="custom-file-trigger">
                                    <span class="file-name"><?= (isset($student) && $student['profile_photo']) ? esc($student['profile_photo']) : 'No file chosen' ?></span>
                                    <span class="btn-browse">Browse</span>
                                </div>
                            </div>
                            <span id="profileError" class="error-msg"></span>
                            <span class="file-label">JPG, PNG or GIF. Max 2MB.</span>
                        </div>

                        <div class="form-group">
                            <label>Resume (PDF) <?= isset($student) ? '(Optional)' : ''; ?></label>
                            <div class="custom-file-container">
                                <input type="file" name="resume" id="resume" class="custom-file-input" accept=".pdf" <?= isset($student) ? '' : 'required'; ?> data-original="<?= isset($student) ? $student['resume'] : '' ?>">
                                <div class="custom-file-trigger">
                                    <span class="file-name"><?= (isset($student) && $student['resume']) ? esc($student['resume']) : 'No file chosen' ?></span>
                                    <span class="btn-browse">Browse</span>
                                </div>
                            </div>
                            <span id="resumeError" class="error-msg"></span>
                            <span class="file-label">Max 2MB. Only PDF allowed.</span>
                        </div>

                        <div class="form-group">
                            <label for="id_proof">ID Proof (Image)</label>
                            <div class="custom-file-container">
                                <input type="file" name="id_proof" id="id_proof" class="custom-file-input" accept="image/*" data-original="<?= isset($student) ? esc($student['id_proof']) : '' ?>">
                                <div class="custom-file-trigger">
                                    <span class="file-name"><?= isset($student['id_proof']) ? esc($student['id_proof']) : 'No file chosen' ?></span>
                                    <span class="btn-browse">Browse</span>
                                </div>
                            </div>
                            <span class="file-label">Max 2MB. Valid image formats only.</span>
                            <span id="idProofError" class="error-msg"></span>
                        </div>

                        <!-- 🔹 Signature Field -->
                        <div class="form-group">
                            <label for="signature">Digital Signature</label>
                            
                            <?php if (isset($student['signature']) && !empty($student['signature'])): ?>
                                <div class="signature-preview-label">Current Signature:</div>
                                <img src="/uploads/signature/<?= $student['signature'] ?>" class="current-signature-preview" alt="Current Signature">
                            <?php endif; ?>

                            <div class="signature-wrapper">
                                <canvas id="signature-pad" class="signature-pad"></canvas>
                            </div>
                            <div class="signature-actions">
                                <button type="button" class="btn-clear-sig" id="clearPad">Clear Signature</button>
                            </div>
                            <input type="hidden" name="signature_data" id="signature_data">
                            
                            <span class="file-label">Use your mouse or touch screen to sign above.</span>
                            <span id="signatureError" class="error-msg"></span>
                        </div>

                        <div class="form-group">
                            <label>Certificates (Optional)</label>
                            <div class="custom-file-container">
                                <input type="file" name="certificates[]" id="certificates" class="custom-file-input" multiple>
                                <div class="custom-file-trigger">
                                    <span class="file-name">
                                        <?php 
                                            if (!empty($certificates)) {
                                                $count = count($certificates);
                                                echo $count . " files currently attached";
                                            } else {
                                                echo "No file chosen";
                                            }
                                        ?>
                                    </span>
                                    <span class="btn-browse">Browse</span>
                                </div>
                            </div>
                            <span id="certsError" class="error-msg"></span>
                            <span class="file-label">Max 5MB total. Select multiple files.</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="reset" class="btn-secondary">Reset Form</button>
                        <button type="submit" class="btn-primary"><?= isset($student) ? 'Update Student Record' : 'Save Student Record'; ?></button>
                    </div>

                    <div style="text-align: center; margin-top: 1.5rem;">
                        <a href="/students" style="color: var(--primary-color); text-decoration: none; font-size: 0.875rem; font-weight: 500;">&larr; Back to Student List</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@24.3.4/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@5.0.4/dist/signature_pad.umd.min.js"></script>
    <script src="<?= base_url('js/validator.js'); ?>"></script>

    <script>
        $(document).ready(function() {
            const validator = new FormValidator();
            const isEdit = <?= isset($student) ? 'true' : 'false'; ?>;
            
            
            // Initialize intlTelInput
            const phoneInput = document.querySelector("#phone");
            const iti = window.intlTelInput(phoneInput, {
                initialCountry: "in",
                separateDialCode: true,
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@24.3.4/build/js/utils.js",
            });

            // ✍️ Signature Pad Initialization
            const canvas = document.querySelector("#signature-pad");
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            // Resize signature pad canvas
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                signaturePad.clear(); // otherwise clear() will be called internally
            }

            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            $('#clearPad').on('click', function() {
                signaturePad.clear();
            });

            // 🎯 Active Input Blocking & Character Limits
            $('#name').on('input', function() {
                // Allow only letters and spaces + Enforce 50 char limit
                let val = $(this).val().replace(/[^a-zA-Z\s]/g, '');
                if (val.length > 50) val = val.substring(0, 50);
                $(this).val(val);
                validator.validateName(val, '#nameError', '#name');
            });

            // Handle Department Select changes
            $('#department').on('change', function() {
                const val = $(this).val();
                validator.validateDepartment(val, '#deptError', '#department');
            });

            $('#email').on('input', function() {
                let val = $(this).val();
                // Enforce 100 char limit
                if (val.length > 100) val = val.substring(0, 100);
                
                if (val.includes('@')) {
                    const parts = val.split('@');
                    // Block numbers after @
                    parts[1] = parts[1].replace(/\d/g, '');
                    val = parts[0] + '@' + (parts[1] || '');
                }
                $(this).val(val);
                validator.validateEmail(val);
            });

            $('#phone').on('input', function() {
                let val = $(this).val().replace(/\D/g, ''); // Numbers only for limit check
                const countryData = iti.getSelectedCountryData();
                const iso = countryData && countryData.iso2 ? countryData.iso2.toLowerCase() : '';
                const limit = (iso === 'in' || countryData.dialCode === '91') ? 10 : 15;
                
                if (val.length > limit) val = val.substring(0, limit);
                $(this).val(val);
                validator.validateMobile(iti, val);
            });

            // 🚀 Real-time File Size Validation & Name Update
            $('.custom-file-input').on('change', function() {
                const input = $(this);
                const fileNameSpan = input.siblings('.custom-file-trigger').find('.file-name');
                const files = input[0].files;
                
                if (files.length > 0) {
                    if (files.length === 1) {
                        fileNameSpan.text(files[0].name);
                    } else {
                        fileNameSpan.text(files.length + ' files selected');
                    }
                    fileNameSpan.css('color', 'var(--text-main)');
                } else {
                    const original = input.data('original');
                    fileNameSpan.text(original ? original : 'No file chosen');
                    fileNameSpan.css('color', 'var(--text-muted)');
                }

                // Run validation
                const id = '#' + input.attr('id');
                if (id === '#profile_photo') validator.validateFile(id, '#profileError', 'Profile Photo', 2, !isEdit);
                else if (id === '#resume') validator.validateFile(id, '#resumeError', 'Resume', 2, !isEdit);
                else if (id === '#id_proof') validator.validateFile(id, '#idProofError', 'ID Proof', 2, !isEdit);
                else if (id === '#certificates') validator.validateFile(id, '#certsError', 'Certificates', 5, false);
            });



            $('#registrationForm').on('submit', function(e) {
                const isNameValid = validator.validateName($('#name').val());
                const isEmailValid = validator.validateEmail($('#email').val());
                const isPhoneValid = validator.validateMobile(iti, $('#phone').val());
                const isDeptValid = validator.validateDepartment($('#department').val());
                
                // File validations
                // 🛑 Profile Photo (Required for New, Size-Check if provided for Edit)
                let isProfileValid = validator.validateFile('#profile_photo', '#profileError', 'Profile Photo', 2, !isEdit);
                
                // 🛑 Resume (Required for New, Size-Check if provided for Edit)
                let isResumeValid = validator.validateFile('#resume', '#resumeError', 'Resume', 2, !isEdit);
                
                // 🛑 ID Proof (Required for New, Size-Check if provided for Edit)
                let isIdProofValid = validator.validateFile('#id_proof', '#idProofError', 'ID Proof', 2, !isEdit);

                // 🛑 Certificates (Always Optional, but size check if provided)
                const certsInput = $('#certificates')[0];
                let isCertsValid = true;
                if (certsInput && certsInput.files.length > 0) {
                    isCertsValid = validator.validateFile('#certificates', '#certsError', 'Certificates', 5);
                }

                // ✍️ Capture Signature
                let isSignatureValid = true;
                if (signaturePad.isEmpty()) {
                    if (!isEdit) {
                        isSignatureValid = false;
                        validator.showError('#signatureError', null, 'Digital signature is required.');
                    }
                } else {
                    $('#signature_data').val(signaturePad.toDataURL());
                    validator.hideError('#signatureError', null);
                }

                if (!isNameValid || !isEmailValid || !isPhoneValid || !isDeptValid || !isProfileValid || !isResumeValid || !isIdProofValid || !isCertsValid || !isSignatureValid) {
                    e.preventDefault();
                    // Scroll to first error
                    const firstError = $('.error-msg.visible').first();
                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                    }
                }
            });



            // Clear form validation errors
            $('button[type="reset"]').on('click', function() {
                $('.error-msg').removeClass('visible').text('');
                $('input').removeClass('error');
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
</body>
</html>
