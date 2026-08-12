<?php
session_start();
require_once 'shared/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $escaped = mysqli_real_escape_string($conn, $username);
        $query = "SELECT * FROM users WHERE username = '$escaped'";
        $res = mysqli_query($conn, $query);

        if ($res && $row = mysqli_fetch_assoc($res)) {
            if ($password === $row['password'] || (function_exists('password_verify') && password_verify($password, $row['password']))) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                header("Location: ./dashboard/home.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "Account not found!";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Inventory - Login</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/main.css">

    <style>
        body {
            background: linear-gradient(135deg, #0b1329 0%, #101c3d 50%, #15254e 100%);
            min-height: 100vh;
            color: #fff;
            font-family: system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 16px;
            color: #1e293b;
            padding: 32px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 440px;
        }

        .app-icon-box {
            width: 54px;
            height: 54px;
            background-color: #2563eb;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4);
        }

        .form-control {
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .btn-primary-custom {
            background-color: #2563eb;
            border: none;
            padding: 11px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .btn-primary-custom:hover {
            background-color: #1d4ed8;
        }

        .demo-box {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.85rem;
            color: #64748b;
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <div class="container py-5 d-flex flex-column align-items-center justify-content-center">
        
        <div class="text-center mb-4">
            <div class="app-icon-box mx-auto mb-3">
                <i class="bi bi-laptop"></i>
            </div>
            <h2 class="fw-bold text-white mb-1">Laptop Inventory</h2>
            <p class="text-white-50 small m-0">Warehouse Management System</p>
        </div>

        <div class="login-card">
            <div class="mb-4">
                <h4 class="fw-bold text-dark mb-1">Sign in to your account</h4>
                <p class="text-secondary small m-0">Enter your credentials to access the dashboard</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3" role="alert">
                    <i class="bi bi-exclamation-circle me-1"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" placeholder="admin" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Password <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="password" id="passwordInput" name="password" class="form-control" placeholder="Enter your password" required>
                        <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary-custom text-white w-100 mb-4 mt-3">Sign In</button>
            </form>

            <div class="demo-box">
                <div class="fw-semibold text-dark mb-1">Demo credentials:</div>
                <div>Username: <span class="fw-medium text-dark">admin</span></div>
                <div>Password: <span class="fw-medium text-dark">admin123</span></div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#passwordInput');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>
