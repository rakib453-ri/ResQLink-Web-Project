<?php
session_start();
include "config/database.php";
include "includes/auth.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    if ($email == "" || $password == "" || $role == "") {
        $error = "Please complete all fields.";
    } else {
        $sql = "SELECT * FROM users WHERE email='$email' AND role='$role' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            if ($user['status'] != 'active') {
                $error = "Your account is not active yet.";
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email, password or role.";
        }
    }
}

$pageTitle = "Login";
$authPage = true;
include "includes/header.php";
?>
<div class="auth-page">
  <section class="auth-brand">
    <div class="logo">ResQLink</div>
    <div class="tag">Emergency Shelter<br>Coordination System</div>
  </section>
  <section class="auth-main">
    <div class="auth-card">
      <h2>Welcome Back</h2>
      <p class="auth-sub">Login to continue</p>
      <?php if ($error != "") { ?><div class="alert error"><?php echo clean($error); ?></div><?php } ?>
      <?php show_message(); ?>
      <form method="post">
        <div class="form-group"><label>Email</label><input type="email" name="email" autocomplete="email" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" autocomplete="current-password" required></div>
        <div class="form-group"><label>Login As</label>
          <select name="role" required>
            <option value="">Select a role</option>
            <option value="seeker">Shelter Seeker</option>
            <option value="volunteer">Volunteer</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <button class="btn" type="submit" name="login">Login</button>
      </form>
      <div class="auth-register">New user? <a href="register.php">Register here</a></div>
    </div>
  </section>
</div>
<?php include "includes/footer.php"; ?>
