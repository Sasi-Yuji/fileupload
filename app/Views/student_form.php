<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?><?= isset($student) ? 'Modify Record' : 'Register New Student'; ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
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
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="header-row">
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 800; color: #1e293b;"><?= isset($student) ? 'Modify Record' : 'Register New Student'; ?></h1>
            <p style="color: #64748b; margin-top: 0.25rem;">Complete the form below to <?= isset($student) ? 'update the' : 'enroll a new'; ?> student record.</p>
        </div>
        <a href="/students" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Directory
        </a>
    </div>

    <div class="card" style="margin: 0 auto;">
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

                <div class="form-group">
                    <label>Certificates (Optional)</label>
                    <div class="custom-file-container">
                        <input type="file" name="certificates[]" id="certificates" class="custom-file-input" multiple>
                        <div class="custom-file-trigger">
                            <span class="file-name">
                                <?php 
                                    if (!empty($certificates)) {
                                        echo count($certificates) . " files currently attached";
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

                <div class="form-group" style="grid-column: span 2;">
                    <label for="signature">Digital Signature</label>
                    <?php if (isset($student['signature']) && !empty($student['signature'])): ?>
                        <div class="signature-preview-label">Current Signature:</div>
                        <img src="/uploads/signature/<?= $student['signature'] ?>" class="current-signature-preview" alt="Signature">
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
            </div>

            <div class="form-actions">
                <button type="reset" class="btn-secondary">Reset Form</button>
                <button type="submit" class="btn-primary"><?= isset($student) ? 'Update Student Record' : 'Save Student Record'; ?></button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@24.3.4/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@5.0.4/dist/signature_pad.umd.min.js"></script>
    <script src="<?= base_url('js/validator.js'); ?>"></script>
    <script>
        $(document).ready(function() {
            const validator = new FormValidator();
            const isEdit = <?= isset($student) ? 'true' : 'false'; ?>;
            
            const phoneInput = document.querySelector("#phone");
            const iti = window.intlTelInput(phoneInput, {
                initialCountry: "in",
                separateDialCode: true,
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@24.3.4/build/js/utils.js",
            });

            const canvas = document.querySelector("#signature-pad");
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                signaturePad.clear();
            }
            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            $('#clearPad').on('click', function() { signaturePad.clear(); });

            $('#name').on('input', function() {
                let val = $(this).val().replace(/[^a-zA-Z\s]/g, '');
                if (val.length > 50) val = val.substring(0, 50);
                $(this).val(val);
                validator.validateName(val, '#nameError', '#name');
            });

            $('#email').on('input', function() {
                let val = $(this).val();
                if (val.length > 100) val = val.substring(0, 100);
                $(this).val(val);
                validator.validateEmail(val);
            });

            $('#phone').on('input', function() {
                let val = $(this).val().replace(/\D/g, '');
                const countryData = iti.getSelectedCountryData();
                const limit = (countryData.dialCode === '91') ? 10 : 15;
                if (val.length > limit) val = val.substring(0, limit);
                $(this).val(val);
                validator.validateMobile(iti, val);
            });

            $('.custom-file-input').on('change', function() {
                const input = $(this);
                const fileNameSpan = input.siblings('.custom-file-trigger').find('.file-name');
                const files = input[0].files;
                if (files.length > 0) {
                    fileNameSpan.text(files.length === 1 ? files[0].name : files.length + ' files selected');
                } else {
                    fileNameSpan.text(input.data('original') || 'No file chosen');
                }
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
                let isProfileValid = validator.validateFile('#profile_photo', '#profileError', 'Profile Photo', 2, !isEdit);
                let isResumeValid = validator.validateFile('#resume', '#resumeError', 'Resume', 2, !isEdit);
                let isIdProofValid = validator.validateFile('#id_proof', '#idProofError', 'ID Proof', 2, !isEdit);
                
                let isSignatureValid = true;
                if (signaturePad.isEmpty()) {
                    if (!isEdit) {
                        isSignatureValid = false;
                        validator.showError('#signatureError', null, 'Digital signature is required.');
                    }
                } else {
                    $('#signature_data').val(signaturePad.toDataURL());
                }

                if (!isNameValid || !isEmailValid || !isPhoneValid || !isDeptValid || !isProfileValid || !isResumeValid || !isIdProofValid || !isSignatureValid) {
                    e.preventDefault();
                    const firstError = $('.error-msg.visible').first();
                    if (firstError.length) {
                        $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500);
                    }
                }
            });
        });
    </script>
<?= $this->endSection() ?>
