<?php
session_start();
include 'db_connect.php';

// Enforce Login - Allow User OR Admin
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$displayName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : (isset($_SESSION['admin_logged_in']) ? 'Admin' : 'User');
?>
<!DOCTYPE html>
<html class="light" lang="ms">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>SMJK Chung Ling | Pertandingan Lagu</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { "primary": "#135bec", "background-light": "#f6f6f8" },
                    fontFamily: { "display": ["Inter", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light font-display text-[#111318]">
<div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
    <!-- Navigation -->
    <header class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-[#f0f2f4] bg-white px-10 py-3">
        <div class="flex items-center gap-8">
            <div class="flex items-center gap-3 text-primary">
                <span class="material-symbols-outlined text-4xl">school</span>
                <h2 class="text-xl font-bold leading-tight tracking-[-0.015em]">SMJK Chung Ling</h2>
            </div>
            <div class="hidden md:flex items-center gap-9">
                <a class="text-sm font-medium text-primary" href="index.php">Laman Utama</a>
                <a class="text-sm font-medium hover:text-primary transition-colors" href="vote.php">Undi</a>
                <?php if(isset($_SESSION['admin_logged_in'])): ?>
                <a class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors" href="admin.php">Admin Panel</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="flex gap-4">
            <span class="flex items-center text-sm font-medium mr-4">Hai, <?php echo htmlspecialchars($displayName); ?></span>
            <a href="logout.php" class="flex items-center justify-center rounded-lg h-10 px-4 bg-gray-200 text-gray-700 text-sm font-bold hover:bg-gray-300 transition-all">
                Keluar
            </a>
        </div>
    </header>

    <main class="flex-1 max-w-[1200px] mx-auto w-full px-4 md:px-10 py-8">
        <!-- Hero Section -->
        <section class="mb-12">
            <div class="flex min-h-[300px] flex-col gap-6 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl items-center justify-center p-8 text-center shadow-lg relative overflow-hidden">
                <div class="flex flex-col gap-4 max-w-2xl relative z-10">
                    <h1 class="text-white text-4xl font-black leading-tight tracking-[-0.033em] md:text-5xl">
                        Pertandingan Lagu Kelas
                    </h1>
                    <p class="text-white/90 text-sm font-normal leading-relaxed md:text-lg">
                        Saksikan kreativiti dan bakat pelajar kita. Tonton persembahan di bawah dan undi lagu kegemaran anda!
                    </p>
                </div>
                <div class="flex gap-4 relative z-10">
                    <a href="vote.php" class="flex min-w-[140px] items-center justify-center rounded-lg h-12 px-6 bg-white text-primary text-base font-bold shadow-xl hover:scale-105 transition-transform">
                        Mula Mengundi
                    </a>
                </div>
            </div>
        </section>

        <!-- Dynamic Song Display (Preview) -->
        <div class="pb-6">
            <h2 class="text-2xl font-bold leading-tight tracking-[-0.015em] mb-6 border-b pb-2">Senarai Penyertaan</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $sql = "SELECT * FROM lagu";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                ?>
                <div class="group flex flex-col gap-3 pb-4 bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <!-- Video/Image Area -->
                    <div class="relative w-full aspect-video bg-gray-200">
                        <video controls class="w-full h-full object-cover">
                            <source src="<?php echo htmlspecialchars($row['video']); ?>" type="video/mp4">
                            Browser anda tidak menyokong tag video.
                        </video>
                    </div>
                    <div class="px-4 pb-2">
                        <div class="flex justify-between items-start">
                            <div class="w-full">
                                <p class="text-[#111318] text-lg font-bold leading-tight"><?php echo htmlspecialchars($row['nama_lagu']); ?></p>
                                <p class="text-[#616f89] text-sm font-medium leading-normal">
                                    ID: <?php echo htmlspecialchars($row['id_lagu']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo "<p class='col-span-3 text-center text-gray-500'>Tiada penyertaan dijumpai.</p>";
                }
                ?>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-[#f0f2f4] py-12 px-10">
        <div class="max-w-[1200px] mx-auto text-center">
            <p class="text-sm text-[#616f89]">Kecemerlangan dalam pendidikan dan kokurikulum.</p>
            <div class="mt-8 pt-8 border-t border-[#f0f2f4] text-xs text-[#616f89]">
                © 2024 SMJK Chung Ling. Hak Cipta Terpelihara.
            </div>
        </div>
    </footer>
</div>
</body>
</html>
