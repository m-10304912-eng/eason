<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Daftar Akaun - SMJK Chung Ling</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#f0f2f5] flex items-center justify-center min-h-screen p-4">
    
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Daftar Akaun</h1>
            <p class="text-gray-500 mt-2">Sila masukkan maklumat anda</p>
        </div>

        <?php
        if (isset($_POST['register'])) {
            $nokp = $_POST['nokp'];
            $nama = $_POST['nama'];
            $password = $_POST['password']; // Plain text as requested/implied simpler flow, or simple hash. 
            // Let's use plain text to strictly match "password 12345" request style for admin, 
            // but for users, I'll allow them to set it. 

            // Check duplicate
            $check = $conn->query("SELECT * FROM pengundi WHERE nokp='$nokp'");
            if ($check->num_rows > 0) {
                 echo "<script>Swal.fire('Ralat!', 'No. Kad Pengenalan sudah wujud.', 'error');</script>";
            } else {
                // Insert
                $stmt = $conn->prepare("INSERT INTO pengundi (nokp, nama, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $nokp, $nama, $password);
                
                if ($stmt->execute()) {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berjaya!',
                            text: 'Pendaftaran berjaya. Sila log masuk.',
                            confirmButtonColor: '#3085d6'
                        }).then(() => { window.location.href = 'login.php'; });
                    </script>";
                } else {
                    echo "<script>Swal.fire('Ralat!', 'DB Error: " . $conn->error . "', 'error');</script>";
                }
            }
        }
        ?>

        <form method="post" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Kad Pengenalan</label>
                <input type="text" name="nokp" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4" placeholder="Contoh: 091023070189">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penuh</label>
                <input type="text" name="nama" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4" placeholder="Nama Penuh">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Laluan</label>
                <input type="password" name="password" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4" placeholder="Kata Laluan">
            </div>

            <button type="submit" name="register" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
                Daftar
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Sudah ada akaun? <a href="login.php" class="font-bold text-blue-600 hover:underline">Log Masuk</a>
            </p>
        </div>
    </div>
</body>
</html>
