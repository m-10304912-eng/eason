<?php
session_start();

function checkLogin() {
    // Return true if either User OR Admin is logged in.
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_logged_in'])) {
        header("Location: login.php");
        exit();
    }
}
?>
