<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['user_id']) || isset($_SESSION['admin_logged_in'])) {
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
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .neon-text { font-family: 'Orbitron', sans-serif; }
        .hover-card:hover { transform: scale(1.02); box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5); }
        .btn-neon:hover { box-shadow: 0 0 15px #2563eb; transform: translateY(-2px); }
    </style>
</head>
<body class="bg-white flex items-center justify-center min-h-screen p-4">
    
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100 hover-card transition-all duration-300">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 neon-text">Log Masuk</h1>
            <p class="text-gray-500 mt-2">Sila log masuk untuk mengundi</p>
        </div>

        <?php
        if (isset($_POST['login'])) {
            $nokp = $_POST['nokp'];
            $password = $_POST['password'];

            // Admin Logic
            $adminCheck = $conn->query("SELECT * FROM admins WHERE username='admin' AND password='$password'");
            if ($nokp === 'admin' && $adminCheck->num_rows > 0) {
                 $_SESSION['admin_logged_in'] = true;
                 // Redirect to Normal User Site first as requested
                 echo "<script>window.location.href = 'index.php';</script>";
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
                        text: 'Selamat datang " . htmlspecialchars($row['nama'], ENT_QUOTES) . "',
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
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Kad Pengenalan / Admin ID</label>
                <input type="text" name="nokp" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 transition-shadow" placeholder="Contoh: 091023070189 atau admin">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Laluan</label>
                <input type="password" name="password" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 transition-shadow" placeholder="Kata Laluan">
            </div>

            <button type="submit" name="login" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg btn-neon transition-all duration-300">
                Log Masuk
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Belum daftar? <a href="register.php" class="font-bold text-blue-600 hover:text-blue-800 transition-colors">Daftar Sekarang</a>
            </p>
        </div>
    </div>
</body>
</html>
