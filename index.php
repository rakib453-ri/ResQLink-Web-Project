<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] == 'seeker') {
    header("Location: seeker/dashboard.php");
} elseif ($_SESSION['role'] == 'volunteer') {
    header("Location: volunteer/dashboard.php");
} else {
    header("Location: admin/dashboard.php");
}
exit();
?>
