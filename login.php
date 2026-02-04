<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Log Masuk - SMJK Chung Ling</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#f0f2f5] flex items-center justify-center min-h-screen p-4">
    
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Log Masuk</h1>
            <p class="text-gray-500 mt-2">Sila log masuk untuk mengundi</p>
        </div>

        <?php
        if (isset($_POST['login'])) {
            $nokp = $_POST['nokp'];
            $password = $_POST['password'];

            // Admin Logic
            // User requested: "add lines for admin and password 12345"
            // We check the admins table we created
            $adminCheck = $conn->query("SELECT * FROM admins WHERE username='admin' AND password='$password'");
            if ($nokp === 'admin' && $adminCheck->num_rows > 0) {
                 $_SESSION['admin_logged_in'] = true;
                 echo "<script>window.location.href = 'admin.php';</script>";
                 exit();
            }

            // Voter Logic
            $sql = "SELECT * FROM pengundi WHERE nokp='$nokp' AND password='$password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $_SESSION['user_id'] = $row['nokp'];
                $_SESSION['user_name'] = $row['nama'];
                
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berjaya!',
                        text: 'Selamat datang " . htmlspecialchars($row['nama']) . "',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => { window.location.href = 'index.php'; });
                </script>";
            } else {
                echo "<script>Swal.fire('Gagal!', 'No. Kad Pengenalan atau Kata Laluan salah.', 'error');</script>";
            }
        }
        ?>

        <form method="post" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Kad Pengenalan / Username</label>
                <input type="text" name="nokp" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4" placeholder="Contoh: 091023070189 atau admin">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Laluan</label>
                <input type="password" name="password" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4" placeholder="Kata Laluan">
            </div>

            <button type="submit" name="login" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
                Log Masuk
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Belum daftar? <a href="register.php" class="font-bold text-blue-600 hover:underline">Daftar Sekarang</a>
            </p>
        </div>
    </div>
</body>
</html>
