<?php
include 'session.php';
include 'db_connect.php';
checkLogin(); // Allows User OR Admin

$isAdmin = isset($_SESSION['admin_logged_in']);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ($isAdmin ? 'Pentadbir' : 'Tetamu');

// Fetch Categories
$cats = $conn->query("SELECT * FROM kategori");
$categories = [];
while($c = $cats->fetch_assoc()) $categories[] = $c;

// Fetch Songs
$songsResult = $conn->query("SELECT * FROM lagu");
$songs = [];
while($s = $songsResult->fetch_assoc()) $songs[] = $s;

// Determine active category tab (default to first)
$active_cat = isset($_GET['cat']) ? $_GET['cat'] : ($categories[0]['id_kategori'] ?? '');

// Handling Feedback Modals (Status)
$status = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Undi | SMJK Chung Ling</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: { "primary": "#0d59f2", "background-light": "#f5f6f8", "success": "#10b981", "warning": "#f59e0b" },
                fontFamily: { "display": ["Inter", "sans-serif"], "neon": ["Orbitron", "sans-serif"] },
            },
        },
    }
</script>
<style>
    body { font-family: 'Inter', sans-serif; }
    h1, h2, h3, .neon-text { font-family: 'Orbitron', sans-serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 1; }
    .hover-card:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(13, 89, 242, 0.2); transition: all 0.3s ease; }
    .btn-neon:hover { box-shadow: 0 0 15px #0d59f2; transform: translateY(-2px); transition: all 0.3s ease; }
</style>
</head>
<body class="bg-background-light font-display text-[#111318] min-h-screen relative">

<!-- FEEDBACK MODALS OVERLAY -->
<?php if ($status == 'success'): ?>
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="w-full max-w-[480px] bg-white rounded-xl shadow-2xl p-8 flex flex-col items-center text-center hover-card">
        <div class="size-20 bg-success/20 rounded-full flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-success text-5xl">check_circle</span>
        </div>
        <h1 class="text-2xl font-bold mb-2 neon-text">Undian Dihantar!</h1>
        <p class="text-sm text-gray-600 mb-8 leading-relaxed px-4">
            Terima kasih! Undian anda telah direkodkan dengan selamat.
        </p>
        <a href="vote.php" class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-primary/90 transition-colors block btn-neon">
            Teruskan Mengundi
        </a>
    </div>
</div>
<?php elseif ($status == 'duplicate'): ?>
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="w-full max-w-[480px] bg-white rounded-xl shadow-2xl p-8 flex flex-col items-center text-center hover-card">
        <div class="size-20 bg-warning/20 rounded-full flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-warning text-5xl">warning</span>
        </div>
        <h1 class="text-2xl font-bold mb-2 neon-text">Sudah Mengundi</h1>
        <p class="text-sm text-gray-600 mb-8 leading-relaxed px-4">
            Anda telah mengundi untuk kategori ini.
        </p>
        <a href="vote.php" class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-primary/90 transition-colors block btn-neon">
            Kembali ke Papan Pemuka
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Top Navigation Bar -->
<header class="sticky top-0 z-50 w-full bg-white border-b border-solid border-[#f0f1f5] px-4 md:px-10 py-3 flex items-center justify-between shadow-sm">
    <div class="flex items-center gap-3">
        <a href="index.php" class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-3xl">school</span>
            <h1 class="text-lg md:text-xl font-bold leading-tight tracking-tight text-primary neon-text">SMJK Chung Ling Voting</h1>
        </a>
    </div>
    <div class="flex items-center gap-4">
        <div class="hidden md:flex flex-col items-end">
            <span class="text-xs text-[#606e8a]">Log masuk sebagai</span>
            <span class="text-sm font-bold"><?php echo htmlspecialchars($user_name); ?></span>
        </div>
        <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white font-bold shadow-md">
            <?php echo substr($user_name, 0, 1); ?>
        </div>
        <a href="logout.php" class="bg-[#f0f1f5] text-[#111318] px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-500 hover:text-white transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">logout</span>
            <span class="hidden sm:inline">Log Keluar</span>
        </a>
    </div>
</header>

<main class="max-w-[1200px] mx-auto px-4 py-8">
    <!-- Page Heading -->
    <div class="mb-8">
        <h2 class="text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em] mb-2 neon-text">Lakukan Undian Anda</h2>
        <p class="text-[#606e8a] text-base max-w-2xl">
            Pilih kategori di bawah untuk melihat persembahan.
            <?php if($isAdmin): ?> <span class="text-red-500 font-bold">(Mod Admin: Undian Dilumpuhkan)</span> 
            <?php else: ?> Anda berhak untuk <b>satu undian bagi setiap kategori</b>. <?php endif; ?>
        </p>
        <?php if($isAdmin): ?>
            <a href="admin.php" class="inline-flex items-center gap-2 mt-4 text-primary font-bold hover:underline">
                <span class="material-symbols-outlined">arrow_back</span> Kembali ke Panel Admin
            </a>
        <?php endif; ?>
    </div>

    <!-- Category Tabs -->
    <div class="mb-8 border-b border-[#dbdfe6] flex overflow-x-auto no-scrollbar gap-2">
        <?php foreach ($categories as $cat): 
            $isActive = $cat['id_kategori'] == $active_cat;
            $class = $isActive 
                ? "border-b-[3px] border-primary text-primary" 
                : "border-b-[3px] border-transparent text-[#606e8a] hover:text-primary";
        ?>
        <a href="vote.php?cat=<?php echo $cat['id_kategori']; ?>" class="flex flex-col items-center justify-center pb-3 pt-2 px-6 whitespace-nowrap transition-colors <?php echo $class; ?>">
            <p class="text-sm font-bold leading-normal tracking-wide neon-text"><?php echo htmlspecialchars($cat['kategori']); ?></p>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Check if User Voted for Active Category (Only for Voters) -->
    <?php
    $hasVoted = false;
    if (!$isAdmin) {
        $checkVote = $conn->query("SELECT * FROM undian WHERE nokp='$user_id' AND id_kategori='$active_cat'");
        $hasVoted = $checkVote->num_rows > 0;
    }
    ?>
    
    <?php if($hasVoted && !$isAdmin): ?>
        <div class="bg-gray-100 p-6 rounded-xl text-center mb-8 border border-gray-200 hover-card">
            <span class="material-symbols-outlined text-green-600 text-4xl mb-2">check_circle</span>
            <h3 class="text-xl font-bold text-gray-800 neon-text">Anda telah mengundi untuk kategori ini.</h3>
            <p class="text-gray-500">Sila pilih kategori lain untuk mengundi.</p>
        </div>
    <?php else: ?>
        <!-- Competition Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($songs as $song): ?>
            <!-- Song Card -->
            <div class="bg-white rounded-xl overflow-hidden border border-[#f0f1f5] shadow-sm flex flex-col hover-card">
                <div class="relative aspect-video bg-black group">
                    <video controls class="w-full h-full object-cover">
                        <source src="<?php echo htmlspecialchars($song['video']); ?>" type="video/mp4">
                    </video>
                </div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="mb-4">
                        <h3 class="text-[#111318] text-lg font-bold mb-1 neon-text"><?php echo htmlspecialchars($song['nama_lagu']); ?></h3>
                        <p class="text-sm text-[#606e8a]">ID: <?php echo $song['id_lagu']; ?></p>
                    </div>
                    <div class="mt-auto pt-4 border-t border-gray-100 flex gap-3">
                        <form action="submit_vote.php" method="POST" class="w-full">
                            <input type="hidden" name="id_kategori" value="<?php echo $active_cat; ?>">
                            <input type="hidden" name="id_lagu" value="<?php echo $song['id_lagu']; ?>">
                            <?php if ($isAdmin): ?>
                                <button type="button" disabled class="w-full bg-gray-400 cursor-not-allowed text-white py-2.5 rounded-lg font-bold text-sm flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-lg">lock</span>
                                    Paparan Admin Sahaja
                                </button>
                            <?php else: ?>
                                <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg font-bold text-sm hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 btn-neon">
                                    <span class="material-symbols-outlined text-lg">how_to_vote</span>
                                    Undi Sekarang
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<footer class="mt-12 py-8 border-t border-gray-200 text-center">
    <p class="text-[#606e8a] text-sm">
        © 2024 SMJK Chung Ling. Electronic Voting System.<br/>
        Perlukan bantuan? Hubungi Kelab AV atau Jabatan IT.
    </p>
</footer>

</body>
</html>
