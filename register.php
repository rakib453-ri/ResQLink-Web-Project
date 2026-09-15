<?php
session_start();
include "config/database.php";
include "includes/auth.php";

$error = "";

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $password = $_POST['password'];
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    if ($name == "" || $email == "" || $password == "" || $role == "") {
        $error = "Please complete all required fields.";
    } elseif ($role != 'seeker' && $role != 'volunteer') {
        $error = "Please select a valid role.";
    } else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

        if (mysqli_num_rows($check) > 0) {
            $error = "Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $status = ($role == 'volunteer') ? 'pending' : 'active';

            $sql = "INSERT INTO users (name,email,phone,password,role,status)
                    VALUES ('$name','$email','$phone','$hashed_password','$role','$status')";

            if (mysqli_query($conn, $sql)) {
                $user_id = mysqli_insert_id($conn);

                if ($role == 'seeker') {
                    mysqli_query($conn, "INSERT INTO shelter_seekers (user_id) VALUES ($user_id)");
                } else {
                    mysqli_query($conn, "INSERT INTO volunteers (user_id,approved) VALUES ($user_id,0)");
                }

                set_message("Registration successful. Please login.");
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed.";
            }
        }
    }
}

$pageTitle = "Register";
$authPage = true;
include "includes/header.php";
?>
<div class="auth-page">
  <section class="auth-brand"><div class="logo">ResQLink</div><div class="tag">Emergency Shelter<br>Coordination System</div></section>
  <section class="auth-main">
    <div class="auth-card">
      <h2>Create Account</h2><p class="auth-sub">Shelter Seeker or Volunteer</p>
      <?php if ($error != "") { ?><div class="alert error"><?php echo clean($error); ?></div><?php } ?>
      <form method="post" autocomplete="off">
        <div class="form-group"><label>Name</label><input name="name" autocomplete="name" required></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" autocomplete="email" required></div>
        <div class="form-group"><label>Phone</label><input name="phone" autocomplete="tel"></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" autocomplete="new-password" required></div>
        <div class="form-group"><label>Register As</label>
          <select name="role" required>
            <option value="">Select a role</option>
            <option value="seeker">Shelter Seeker</option>
            <option value="volunteer">Volunteer</option>
          </select>
        </div>
        <button class="btn" type="submit" name="register">Register</button>
      </form>
      <div class="auth-register">Already registered? <a href="login.php">Back to login</a></div>
    </div>
  </section>
</div>
<?php include "includes/footer.php"; ?>
