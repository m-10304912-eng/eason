<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Daftar Akaun - SMJK Chung Ling</title>
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
            <h1 class="text-3xl font-bold text-gray-900 neon-text">Daftar Akaun</h1>
            <p class="text-gray-500 mt-2">Sila masukkan maklumat anda</p>
        </div>

        <?php
        if (isset($_POST['register'])) {
            $nokp = $_POST['nokp'];
            $nama = $_POST['nama'];
            $password = $_POST['password'];

            // Validation: Numeric Only for IC
            if (!ctype_digit($nokp)) {
                 echo "<script>Swal.fire('Ralat!', 'No. Kad Pengenalan mesti mengandungi nombor sahaja.', 'error');</script>";
            } else {
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
                        echo "<script>Swal.fire('Ralat!', 'Ralat Pangkalan Data: " . $conn->error . "', 'error');</script>";
                    }
                }
            }
        }
        ?>

        <form method="post" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Kad Pengenalan</label>
                <input type="text" name="nokp" required pattern="[0-9]+" title="Sila masukkan nombor sahaja" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 transition-shadow" placeholder="Contoh: 091023070189">
                <p class="text-xs text-gray-400 mt-1">Nombor sahaja, tanpa sengkang (-)</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penuh</label>
                <input type="text" name="nama" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 transition-shadow" placeholder="Nama Penuh">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Laluan</label>
                <input type="password" name="password" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 transition-shadow" placeholder="Kata Laluan">
            </div>

            <button type="submit" name="register" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg btn-neon transition-all duration-300">
                Daftar
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Sudah ada akaun? <a href="login.php" class="font-bold text-blue-600 hover:text-blue-800 transition-colors">Log Masuk</a>
            </p>
        </div>
    </div>
</body>
</html>
