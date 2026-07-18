<?php
/**
 * Admin-only login. Separate from the storefront customer login so
 * admin credentials and sessions never mix with the shopper flow.
 */
require_once __DIR__ . '/../Includes/functions.php';

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    redirect(BASE_URL . 'Admin/index.php');
}

$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = db()->prepare('SELECT id, full_name, email, password_hash, role FROM users WHERE email = ? AND role = "admin" AND is_active = 1 LIMIT 1');
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $admin['id'];
        $_SESSION['user_name'] = $admin['full_name'];
        $_SESSION['user_role'] = $admin['role'];
        redirect(BASE_URL . 'Admin/index.php');
    }

    $errorMessage = 'Invalid admin credentials.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Spotlight Fashion Store</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            background: #111;
            display: flex;
            align-items: center;
            font-family: "Inter", sans-serif;
        }
        .admin-login-card {
            background: #1b1b1b;
            border: 1px solid #2b2b2b;
            border-radius: 1rem;
            color: #f7f4ef;
        }
        .admin-login-card .form-control {
            background: #111;
            border-color: #333;
            color: #f7f4ef;
        }
        .admin-login-card .form-control:focus {
            background: #111;
            color: #f7f4ef;
            border-color: #be6d2c;
            box-shadow: 0 0 0 0.2rem rgba(190, 109, 44, 0.25);
        }
        .admin-login-card ::placeholder { color: #8a8a8a; }
        .btn-accent { background: #be6d2c; border: none; color: white; }
        .btn-accent:hover { background: #8b4613; color: white; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-8 col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock text-white-50" style="font-size: 2rem;"></i>
                <h4 class="fw-bold text-white mt-2 mb-0">Spotlight Admin</h4>
                <p class="text-white-50 small">Restricted access — administrators only</p>
            </div>
            <div class="admin-login-card p-4 p-lg-5">
                <?php if ($errorMessage !== ''): ?>
                    <div class="alert alert-danger py-2 small"><?php echo e($errorMessage); ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label small text-white-50">Admin email</label>
                        <input class="form-control" type="email" name="email" placeholder="admin@yourstore.test" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small text-white-50">Password</label>
                        <input class="form-control" type="password" name="password" placeholder="••••••••" required>
                    </div>
                    <button class="btn btn-accent w-100" type="submit">Sign in to Dashboard</button>
                </form>
            </div>
            <div class="text-center mt-4">
                <a href="<?php echo BASE_URL; ?>" class="text-white-50 small"><i class="bi bi-arrow-left me-1"></i>Back to storefront</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
