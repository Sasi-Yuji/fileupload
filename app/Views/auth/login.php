<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Student Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/style.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-deep: #0a0e17;
            --accent-cyan: #00f2fe;
            --accent-teal: #4facfe;
            --text-light: #e0e7ff;
            --glass-bg: rgba(13, 18, 29, 0.9);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(224, 242, 254, 0.85)), 
                        url('<?= base_url('images/campus-bg.png'); ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            position: relative;
        }

        /* Ambient animated shapes */
        .shape {
            position: absolute;
            background: linear-gradient(45deg, var(--accent-cyan), var(--accent-teal));
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            z-index: 0;
            animation: float 20s infinite alternate;
        }

        .shape-1 { width: 400px; height: 400px; top: -100px; left: -100px; }
        .shape-2 { width: 300px; height: 300px; bottom: -50px; right: -50px; animation-delay: -5s; }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(50px, 50px) rotate(15deg); }
        }

        .login-wrapper {
            position: relative;
            width: 1100px;
            height: 650px;
            display: flex;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--glass-border);
            z-index: 10;
        }

        .form-side {
            flex: 1.1;
            padding: 4.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            z-index: 2;
        }

        .visual-side {
            flex: 0.9;
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.95) 0%, rgba(0, 242, 254, 0.95) 100%);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            color: #0a0e17;
            padding: 4rem;
            position: relative;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
        }

        .visual-side::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://www.transparenttextures.com/patterns/cubes.png');
            opacity: 0.1;
            pointer-events: none;
        }

        .visual-side h2 {
            font-size: 3.8rem;
            font-weight: 900;
            margin: 0;
            line-height: 1;
            text-transform: uppercase;
            letter-spacing: -2px;
            margin-bottom: 2rem;
        }

        .system-features {
            margin-top: 1.5rem;
            text-align: left;
            width: 100%;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.2rem;
            font-weight: 500;
            font-size: 1rem;
            gap: 1rem;
        }

        .feature-item i {
            width: 32px;
            height: 32px;
            background: rgba(10, 14, 23, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .login-header h1 {
            color: white;
            font-size: 2.8rem;
            margin-bottom: 0.5rem;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 3rem;
            font-size: 1rem;
        }

        #loginForm {
            display: flex;
            flex-direction: column;
        }

        .input-group {
            margin-bottom: 2.2rem;
            position: relative;
        }

        .input-group label {
            display: block;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            margin-bottom: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-group input {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem 1.2rem;
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
            outline: none;
            box-sizing: border-box;
        }

        .input-group input:focus {
            background: rgba(255, 255, 255, 0.07);
            border-color: var(--accent-cyan);
            box-shadow: 0 0 15px rgba(0, 242, 254, 0.2);
        }

        .input-group i {
            position: absolute;
            right: 1.2rem;
            bottom: 1rem;
            color: rgba(255, 255, 255, 0.3);
            font-size: 1.1rem;
            transition: all 0.3s;
            pointer-events: none;
        }

        .toggle-password {
            cursor: pointer;
            pointer-events: auto !important;
        }

        .input-group i#passIcon {
            right: 3.2rem;
        }

        .btn-modern {
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            padding: 1.1rem 3.5rem;
            border-radius: 14px;
            color: #0a0e17;
            font-weight: 800;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 1rem;
            box-shadow: 0 10px 25px rgba(0, 242, 254, 0.3);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            width: 100%;
            display: block;
        }

        .btn-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 242, 254, 0.5);
            filter: brightness(1.1);
        }

        .footer-links {
            margin-top: 2.5rem;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.5);
            text-align: center;
        }

        .footer-links a {
            color: var(--accent-cyan);
            text-decoration: none;
            font-weight: 700;
            margin-left: 0.5rem;
            position: relative;
        }

        .footer-links a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-cyan);
            transition: width 0.3s;
        }

        .footer-links a:hover::after {
            width: 100%;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border-left: 4px solid #f87171;
            color: #fca5a5;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .field-error {
            color: #f87171;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            display: none;
            font-weight: 500;
        }
        
        .field-error.visible {
            display: block;
        }

        .input-group input.input-invalid {
            border-color: #f87171;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.2);
        }

        .input-group input.input-valid {
            border-color: #10b981;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.2);
        }

        .field-icon-check { color: #10b981 !important; }
        .field-icon-error { color: #f87171 !important; }

        @media (max-width: 1150px) {
            .login-wrapper { width: 95%; height: auto; flex-direction: column; }
            .visual-side { padding: 3rem; order: -1; }
            .form-side { padding: 3rem; }
        }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="login-wrapper">
        <div class="form-side">
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Sign in to your account with your credentials</p>
            </div>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="/login" method="POST" id="loginForm" novalidate>
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" id="loginUsername" placeholder="e.g. jdoe" autocomplete="username" autofocus>
                    <i class="fas fa-user" id="userIcon"></i>
                    <div class="field-error" id="usernameError"></div>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" id="loginPassword" placeholder="••••••••" autocomplete="current-password">
                    <i class="fas fa-lock" id="passIcon"></i>
                    <i class="fas fa-eye toggle-password" id="togglePasswordBtn"></i>
                    <div class="field-error" id="passwordError"></div>
                </div>
                
                <button type="submit" class="btn-modern" id="loginBtn">Sign In</button>
            </form>
            
            <div class="footer-links">
                Don't have an account? <a href="/">Register Here</a>
            </div>
        </div>

        <div class="visual-side">
            <h2>Modern <br> Education <br> Portal</h2>
            
            <div class="system-features">
                <div class="feature-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Streamlined Student Management</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Secure Document Digitalization</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Real-time Academic Analytics</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Enterprise Grade Security</span>
                </div>
            </div>

            <div style="margin-top: 4rem; opacity: 0.9; font-size: 0.9rem; font-weight: 700; color: #0a0e17; background: rgba(255,255,255,0.2); padding: 0.8rem 1.5rem; border-radius: 50px;">
                <i class="fas fa-university"></i> University Management System v3.1
            </div>
        </div>
    </div>

<script>
    (function () {
        const usernameInput = document.getElementById('loginUsername');
        const passwordInput = document.getElementById('loginPassword');
        const userIcon = document.getElementById('userIcon');
        const passIcon = document.getElementById('passIcon');
        const usernameError = document.getElementById('usernameError');
        const passwordError = document.getElementById('passwordError');
        const form = document.getElementById('loginForm');
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');

        togglePasswordBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // ─── Helpers ───────────────────────────────────────────────
        function showError(input, icon, errorEl, msg) {
            input.classList.add('input-invalid');
            input.classList.remove('input-valid');
            icon.className = 'fas fa-exclamation-circle field-icon-error';
            errorEl.textContent = msg;
            errorEl.classList.add('visible');
        }

        function showValid(input, icon, errorEl) {
            input.classList.remove('input-invalid');
            input.classList.add('input-valid');
            icon.className = 'fas fa-check-circle field-icon-check';
            errorEl.textContent = '';
            errorEl.classList.remove('visible');
        }

        function clearState(input, icon, errorEl) {
            input.classList.remove('input-invalid', 'input-valid');
            icon.className = icon.dataset.default;
            errorEl.textContent = '';
            errorEl.classList.remove('visible');
        }

        // Store default icon classes
        userIcon.dataset.default = 'fas fa-user';
        passIcon.dataset.default = 'fas fa-lock';

        // ─── Block numbers & special chars LIVE on keypress ────────
        usernameInput.addEventListener('keypress', function (e) {
            const char = String.fromCharCode(e.which);
            // Allow only letters (a-z, A-Z) and spaces
            if (!/^[a-zA-Z ]$/.test(char)) {
                e.preventDefault();
                showError(usernameInput, userIcon, usernameError,
                    'Username must contain only letters. Numbers & special characters are not allowed.');
                // Auto-hide the tip after 2.5s
                setTimeout(() => {
                    if (usernameInput.value.trim() !== '') {
                        validateUsername();
                    } else {
                        clearState(usernameInput, userIcon, usernameError);
                    }
                }, 2500);
            }
        });

        // ─── Full validation functions ─────────────────────────────
        function validateUsername() {
            const val = usernameInput.value.trim();
            if (val === '') {
                showError(usernameInput, userIcon, usernameError, 'Username is required.');
                return false;
            }
            if (!/^[a-zA-Z ]+$/.test(val)) {
                showError(usernameInput, userIcon, usernameError,
                    'Username must contain only letters. No numbers or special characters allowed.');
                return false;
            }
            if (val.length < 3) {
                showError(usernameInput, userIcon, usernameError, 'Username must be at least 3 characters.');
                return false;
            }
            showValid(usernameInput, userIcon, usernameError);
            return true;
        }

        function validatePassword() {
            const val = passwordInput.value;
            if (val === '') {
                showError(passwordInput, passIcon, passwordError, 'Password is required.');
                return false;
            }
            if (val.length < 4) {
                showError(passwordInput, passIcon, passwordError, 'Password must be at least 4 characters.');
                return false;
            }
            showValid(passwordInput, passIcon, passwordError);
            return true;
        }

        // ─── Validate on blur (when user leaves field) ─────────────
        usernameInput.addEventListener('blur', function () {
            if (this.value.trim() !== '') validateUsername();
        });
        passwordInput.addEventListener('blur', function () {
            if (this.value !== '') validatePassword();
        });

        // ─── Live feedback while typing ────────────────────────────
        usernameInput.addEventListener('input', function () {
            if (this.value.trim() === '') {
                clearState(usernameInput, userIcon, usernameError);
            } else {
                validateUsername();
            }
        });
        passwordInput.addEventListener('input', function () {
            if (this.value === '') {
                clearState(passwordInput, passIcon, passwordError);
            } else {
                validatePassword();
            }
        });

        // ─── Block submit if invalid ───────────────────────────────
        form.addEventListener('submit', function (e) {
            const uOk = validateUsername();
            const pOk = validatePassword();
            if (!uOk || !pOk) {
                e.preventDefault();
            }
        });
    })();
</script>
</body>
</html>
