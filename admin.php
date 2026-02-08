<?php
session_start();
include 'db_connect.php';

// Handle Admin Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    header("Location: login.php");
    exit();
}

// Check Admin Session - Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// --- ADMIN LOGIC ---

// Handle Reset Votes
if(isset($_POST['reset_votes'])) {
    $conn->query("TRUNCATE TABLE undian");
}

// Handle Delete Song
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM undian WHERE id_lagu='$id'");
    $conn->query("DELETE FROM lagu WHERE id_lagu='$id'");
    header("Location: admin.php");
    exit();
}

// Stats Queries
$total_voters = $conn->query("SELECT COUNT(*) as c FROM pengundi")->fetch_assoc()['c'];
$total_votes = $conn->query("SELECT COUNT(*) as c FROM undian")->fetch_assoc()['c'];
$unique_voters = $conn->query("SELECT COUNT(DISTINCT nokp) as c FROM undian")->fetch_assoc()['c'];
$participation_rate = $total_voters > 0 ? round(($unique_voters / $total_voters) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Dashboard | SMJK Chung Ling</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#0d59f2",
                        "background-light": "#f5f6f8",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"],
                        "neon": ["Orbitron", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .neon-text { font-family: 'Orbitron', sans-serif; }
        .hover-card:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(13, 89, 242, 0.2); transition: all 0.3s ease; }
    </style>
</head>
<body class="bg-background-light text-[#111318] antialiased">
<div class="flex min-h-screen">
    <!-- Side Navigation -->
    <aside class="w-64 border-r border-[#dbdfe6] bg-white flex flex-col shrink-0 shadow-sm">
        <div class="p-6">
            <div class="flex flex-col mb-8">
                <h1 class="text-primary text-xl font-bold leading-tight neon-text">SMJK Chung Ling</h1>
                <p class="text-[#606e8a] text-xs font-medium uppercase tracking-wider">Sistem Admin</p>
            </div>
            <nav class="flex flex-col gap-2">
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary hover-card" href="#">
                    <span class="material-symbols-outlined text-[24px]">dashboard</span>
                    <span class="text-sm font-semibold">Papan Pemuka</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#606e8a] hover:bg-gray-100 transition-colors hover-card" href="index.php">
                    <span class="material-symbols-outlined text-[24px]">public</span>
                    <span class="text-sm font-medium">Lihat Laman</span>
                </a>
            </nav>
        </div>
        <div class="mt-auto p-6">
            <a href="admin.php?logout=true" class="flex w-full items-center justify-center gap-2 rounded-lg h-11 bg-red-600 text-white text-sm font-bold shadow-lg hover:bg-red-700 transition-all hover-card">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                <span>Log Keluar</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="flex items-center justify-between border-b border-[#dbdfe6] bg-white px-8 py-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary/10 rounded-lg text-primary">
                    <span class="material-symbols-outlined">how_to_vote</span>
                </div>
                <h2 class="text-lg font-bold tracking-tight neon-text">Papan Pemuka Admin</h2>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 pl-4 border-l border-gray-200">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold text-xs shadow-md">AD</div>
                    <span class="text-sm font-semibold">Admin</span>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto p-8">
            <!-- System Statistics -->
            <div class="mb-8">
                <h3 class="text-sm font-bold uppercase tracking-widest text-[#606e8a] mb-4">Statistik Sistem</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl p-6 border border-[#dbdfe6] shadow-sm hover-card">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-[#606e8a] text-sm font-medium leading-normal">Jumlah Pengundi Berdaftar</p>
                            <span class="material-symbols-outlined text-primary">person</span>
                        </div>
                        <p class="text-3xl font-bold leading-tight neon-text text-primary"><?php echo $total_voters; ?></p>
                        <div class="mt-2 text-xs text-gray-500">Pelajar & Staf</div>
                    </div>
                    <div class="bg-white rounded-xl p-6 border border-[#dbdfe6] shadow-sm hover-card">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-[#606e8a] text-sm font-medium leading-normal">Jumlah Undian Terkumpul</p>
                            <span class="material-symbols-outlined text-primary">ballot</span>
                        </div>
                        <p class="text-3xl font-bold leading-tight neon-text text-primary"><?php echo $total_votes; ?></p>
                        <div class="mt-2 text-xs text-gray-500">Semua Kategori</div>
                    </div>
                    <div class="bg-white rounded-xl p-6 border border-[#dbdfe6] shadow-sm hover-card">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-[#606e8a] text-sm font-medium leading-normal">Kadar Penyertaan</p>
                            <span class="material-symbols-outlined text-primary">pie_chart</span>
                        </div>
                        <p class="text-3xl font-bold leading-tight neon-text text-primary"><?php echo $participation_rate; ?>%</p>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full shadow-[0_0_10px_#0d59f2]" style="width: <?php echo $participation_rate; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Song Section -->
            <div class="mb-8 bg-white rounded-xl border border-[#dbdfe6] p-6 shadow-sm hover-card">
                 <h3 class="text-lg font-bold mb-4 neon-text">Tambah Lagu Baharu</h3>
                 <?php
                 if(isset($_POST['add_song'])) {
                    $id = $_POST['id_lagu'];
                    $name = $_POST['nama_lagu'];
                    $video = $_POST['video'];
                    
                    $stmt = $conn->prepare("INSERT INTO lagu (id_lagu, nama_lagu, video) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $id, $name, $video);
                    if($stmt->execute()) {
                        echo "<p class='text-green-600 mb-2 font-bold'>Lagu berjaya ditambah!</p>";
                    } else {
                        echo "<p class='text-red-600 mb-2 font-bold'>Ralat: " . $conn->error . "</p>";
                    }
                    $stmt->close();
                }
                ?>
                <form method="post" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="id_lagu" placeholder="ID (Contoh: L1)" class="border-[#dbdfe6] rounded-lg text-sm focus:ring-primary focus:border-primary" required>
                    <input type="text" name="nama_lagu" placeholder="Nama Lagu" class="border-[#dbdfe6] rounded-lg text-sm focus:ring-primary focus:border-primary" required>
                    <input type="text" name="video" placeholder="Video (Contoh: file.mp4)" class="border-[#dbdfe6] rounded-lg text-sm focus:ring-primary focus:border-primary" required>
                    <button type="submit" name="add_song" class="bg-primary text-white font-bold py-2 rounded-lg hover:bg-blue-700 shadow-md transition-all hover:scale-105">Tambah Lagu</button>
                </form>
            </div>

            <!-- Real-time Voting Results -->
            <div class="bg-white rounded-xl border border-[#dbdfe6] shadow-sm overflow-hidden hover-card">
                <div class="px-6 py-5 border-b border-[#dbdfe6] flex items-center justify-between">
                    <h2 class="text-lg font-bold neon-text">Keputusan Undian Terkini</h2>
                    <a href="admin.php" class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-primary hover:bg-primary/5 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-[18px]">refresh</span>
                        <span>Muat Semula</span>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#f8fafc]">
                                <th class="px-6 py-4 text-xs font-bold uppercase text-[#606e8a] tracking-wider">ID</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-[#606e8a] tracking-wider">Nama Lagu</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-[#606e8a] tracking-wider">Video</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-[#606e8a] tracking-wider">Graf Visual</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-[#606e8a] tracking-wider text-right">Jumlah Undi</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-[#606e8a] tracking-wider text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#dbdfe6]">
                            <?php
                            $query = "
                                SELECT l.*, COUNT(u.id_undi) as vote_count 
                                FROM lagu l 
                                LEFT JOIN undian u ON l.id_lagu = u.id_lagu 
                                GROUP BY l.id_lagu 
                                ORDER BY vote_count DESC
                            ";
                            $songs = $conn->query($query);
                            $max_votes = 1;
                            $all_songs = [];
                            while($s = $songs->fetch_assoc()) {
                                $all_songs[] = $s;
                                if ($s['vote_count'] > $max_votes) $max_votes = $s['vote_count'];
                            }

                            if (count($all_songs) > 0):
                                foreach($all_songs as $song):
                                    $percent = ($song['vote_count'] / $max_votes) * 100;
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4"><span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded"><?php echo $song['id_lagu']; ?></span></td>
                                <td class="px-6 py-4 font-semibold"><?php echo $song['nama_lagu']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?php echo $song['video']; ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center w-32 bg-gray-100 rounded-full h-2">
                                        <div class="bg-primary h-2 rounded-full shadow-[0_0_5px_#0d59f2]" style="width: <?php echo $percent; ?>%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-primary neon-text"><?php echo $song['vote_count']; ?></td>
                                <td class="px-6 py-4 text-right">
                                    <a href="edit_song.php?id=<?php echo $song['id_lagu']; ?>" class="text-blue-600 hover:text-blue-800 mr-2 font-medium">Edit</a>
                                    <a href="admin.php?delete=<?php echo $song['id_lagu']; ?>" onclick="return confirm('Padam lagu ini?')" class="text-red-600 hover:text-red-800 font-medium">Padam</a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Tiada lagu dijumpai.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Dangerous Action Section -->
            <div class="mt-12 mb-8 bg-red-50 border border-red-200 rounded-xl p-8 hover-card">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex gap-4">
                        <div class="bg-red-100 p-3 rounded-lg text-red-600 self-start">
                             <span class="material-symbols-outlined text-[32px]">warning</span>
                        </div>
                        <div>
                            <h4 class="text-red-800 font-bold text-lg">Zon Bahaya</h4>
                            <p class="text-red-700 text-sm max-w-xl">
                                Menetapkan semula semua undian akan memadamkan semua rekod dalam jadual UNDIAN secara kekal. Tindakan ini tidak boleh dikembalikan.
                            </p>
                        </div>
                    </div>
                    <form method="post" onsubmit="return confirm('Adakah anda pasti mahu memadam SEMUA undian? TINDAKAN INI TIDAK BOLEH DIKEMBALIKAN.');">
                        <button type="submit" name="reset_votes" class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3 rounded-lg shadow-lg hover:shadow-red-500/30 transition-all flex items-center gap-2 whitespace-nowrap">
                            <span class="material-symbols-outlined">delete_forever</span>
                            Tetapkan Semula Undian
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>
</div>
</body>
</html>
