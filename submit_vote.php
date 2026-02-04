<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nokp = $_SESSION['user_id'];
    $id_kategori = $_POST['id_kategori'];
    $id_lagu = $_POST['id_lagu'];

    // Check double voting again
    $check = $conn->query("SELECT * FROM undian WHERE nokp='$nokp' AND id_kategori='$id_kategori'");
    if ($check->num_rows > 0) {
        // Already voted -> Redirect with duplicate status
        header("Location: vote.php?cat=$id_kategori&status=duplicate");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO undian (nokp, id_kategori, id_lagu) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nokp, $id_kategori, $id_lagu);

    if ($stmt->execute()) {
         // Success -> Redirect with success status
         header("Location: vote.php?cat=$id_kategori&status=success");
         exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
