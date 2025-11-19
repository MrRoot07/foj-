<?php
session_start();
// Include i18n bootstrap
require_once __DIR__ . '/../bootstrap/i18n.php';
include '../server/inc/connection.php';

$companyName = "FOJ Express";
$current_lang = get_current_lang();
$is_rtl = is_rtl();
$error_message = '';

// Process login if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (!empty($email) && !empty($password)) {
        // Check admin login
        $loginAdmin = "SELECT * FROM employee WHERE email = '$email' AND password = '$password' AND is_deleted = '0'";
        $result = mysqli_query($con, $loginAdmin);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['admin'] = $row['email'];
            header("Location: index.php");
            exit();
        } else {
            // Check customer login
            $loginCustomer = "SELECT * FROM customer WHERE email = '$email' AND password = '$password' AND is_deleted = '0'";
            $result = mysqli_query($con, $loginCustomer);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION['customer'] = $row['customer_id'];
                header("Location: ../index.php");
                exit();
            } else {
                $error_message = __t('error_invalid_credentials');
            }
        }
    } else {
        $error_message = __t('error_invalid_credentials');
    }
}

// Redirect if already logged in
if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo $is_rtl ? 'rtl' : 'ltr'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - FOJ Express</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <style>
        body {
            font-family: sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

  .card {
            width: 100%;
            max-width: 450px;
    border-radius: 10px;
            background-color: #ffffff;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  }

  .title {
    text-align: center;
    font-weight: bold;
            margin: 0 0 2rem 0;
            font-size: 2rem;
            color: #333;
        }

  .email-login {
    display: flex;
    flex-direction: column;
  }

  .email-login label {
            color: #666;
            font-weight: 500;
            margin-bottom: 8px;
  }

  input[type="text"],
  input[type="password"] {
    padding: 15px 20px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
    border-radius: 8px;
    box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
  }

  .cta-btn {
    background-color: rgb(69, 69, 185);
    color: white;
    padding: 18px 20px;
    margin-top: 10px;
    margin-bottom: 20px;
    width: 100%;
    border-radius: 10px;
    border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .cta-btn:hover {
            background-color: rgb(50, 50, 150);
        }

        .error-message {
            background-color: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fcc;
            text-align: center;
        }

        .links {
    text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 10px;
        }

        .links a:hover {
            text-decoration: underline;
  }
</style>
<?php if ($is_rtl): ?>
<link rel="stylesheet" href="../css/rtl.css">
<?php endif; ?>
</head>

<body>
    <div class="card">
        <form method="POST" action="">
            <h2 class="title"><?php __e('admin_login'); ?></h2>

            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <div class="email-login">
                <label for="email"><b><?php __e('admin_email'); ?></b></label>
                <input type="text" placeholder="<?php __e('admin_enter_email'); ?>" name="email" id="email" required autofocus>

                <label for="password"><b><?php __e('register_password'); ?></b></label>
                <input type="password" name="password" id="password" placeholder="<?php __e('admin_enter_password'); ?>" required>
            </div>

            <button type="submit" class="cta-btn"><?php __e('admin_login'); ?></button>

            <div class="links">
                <?php __e('admin_no_account'); ?> <a href="register.php"><?php __e('admin_sign_up'); ?></a>
                <br>
                <?php __e('admin_or_go_back'); ?> <a href="../index.php"><?php __e('home'); ?></a>
            </div>
        </form>
    </div>
</body>

</html>
