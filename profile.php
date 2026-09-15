<?php
include "includes/auth.php";
require_login();
include "config/database.php";

$user_id = (int)$_SESSION['user_id'];
$error = "";

if (isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));

    if ($name == "") {
        $error = "Name is required.";
    } else {
        mysqli_query($conn, "UPDATE users SET name='$name', phone='$phone' WHERE id=$user_id");
        $_SESSION['name'] = $name;
        set_message("Profile updated.");
        header("Location: profile.php");
        exit();
    }
}

$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id");
$user = mysqli_fetch_assoc($result);

$pageTitle = "Profile";
include "includes/header.php";
?>
<div class="layout">
<?php include "includes/sidebar.php"; ?>
<section class="content">
<h2>My Profile</h2>
<?php show_message(); ?>
<?php if ($error != "") { ?><div class="alert error"><?php echo clean($error); ?></div><?php } ?>
<div class="section">
<form method="post" class="form-grid">
  <div class="form-group"><label>Name</label><input name="name" value="<?php echo clean($user['name']); ?>" required></div>
  <div class="form-group"><label>Email</label><input value="<?php echo clean($user['email']); ?>" disabled></div>
  <div class="form-group"><label>Phone</label><input name="phone" value="<?php echo clean($user['phone']); ?>"></div>
  <div class="form-group"><label>Role</label><input value="<?php echo clean(ucfirst($user['role'])); ?>" disabled></div>
  <div class="form-group full"><button class="btn" name="update_profile">Update Profile</button></div>
</form>
</div>
</section>
</div>
<?php include "includes/footer.php"; ?>
