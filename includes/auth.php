<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /ResQLink/login.php");
        exit();
    }
}

function require_role($role) {
    require_login();
    if ($_SESSION['role'] != $role) {
        header("Location: /ResQLink/index.php");
        exit();
    }
}

function clean($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

function set_message($message) {
    $_SESSION['message'] = $message;
}

function show_message() {
    if (isset($_SESSION['message'])) {
        echo '<div class="alert success">' . clean($_SESSION['message']) . '</div>';
        unset($_SESSION['message']);
    }
}
?>
