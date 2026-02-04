<?php 
include 'session.php';
include 'db_connect.php';
checkLogin();
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Live Results - SMJK Chung Ling</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <meta http-equiv="refresh" content="30"> <!-- Auto refresh every 30s -->
</head>
<body class="bg-[#f6f6f8] dark:bg-[#101622] font-display text-[#111318] dark:text-white">

<header class="flex items-center justify-between whitespace-nowrap border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-[#101622] px-10 py-3 sticky top-0 z-50">
    <div class="flex items-center gap-4">
        <div class="text-primary size-8 flex items-center justify-center">
            <span class="material-symbols-outlined text-3xl">school</span>
        </div>
        <h2 class="text-lg font-bold leading-tight">SMJK Chung Ling</h2>
    </div>
    <nav class="flex items-center gap-9">
        <a class="text-sm font-medium hover:text-primary transition-colors" href="index.php">Home</a>
        <a class="text-sm font-medium hover:text-primary transition-colors" href="vote.php">Vote</a>
        <a class="text-sm font-medium text-primary" href="results.php">Results</a>
        <?php if(isset($_SESSION['admin_logged_in'])): ?>
            <a class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors" href="admin.php">Admin Panel</a>
        <?php endif; ?>
    </nav>
</header>

<main class="flex flex-1 justify-center py-8">
    <div class="flex flex-col max-w-[1200px] flex-1 px-4 sm:px-10">
        <div class="flex flex-wrap justify-between items-end gap-3 p-4 mb-6">
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                    </span>
                    <span class="text-xs font-bold text-red-600 uppercase tracking-widest">Live Updates</span>
                </div>
                <h1 class="text-4xl font-black leading-tight tracking-[-0.033em]">Competition Results</h1>
            </div>
            <button onclick="window.location.reload()" class="flex items-center justify-center rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold shadow-md hover:bg-primary/90 transition-all">
                Refresh Now
            </button>
        </div>

        <!-- Total Votes Stats -->
        <?php
        $totalVotesSql = "SELECT COUNT(*) as total FROM undian";
        $totalResult = $conn->query($totalVotesSql);
        $totalVotes = $totalResult->fetch_assoc()['total'];
        ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 mb-8">
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                <p class="text-gray-500 text-sm font-bold uppercase">Total Votes Cast</p>
                <p class="text-4xl font-black mt-2 text-primary"><?php echo $totalVotes; ?></p>
            </div>
        </div>

        <div class="flex flex-col gap-8 p-4">
             <!-- Category Breakdown -->
            <?php
            // Get all categories data first
            $catSql = "SELECT * FROM kategori";
            $catRes = $conn->query($catSql);
            
            while($cat = $catRes->fetch_assoc()) {
                $catId = $cat['id_kategori'];
                $catName = $cat['kategori'];

                // Get total votes for this category
                $totalCatVotesSql = "SELECT COUNT(*) as total FROM undian WHERE id_kategori = '$catId'";
                $totalCatVotes = $conn->query($totalCatVotesSql)->fetch_assoc()['total'];
                if ($totalCatVotes == 0) $totalCatVotes = 1; // Prevent division by zero
            ?>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                <h2 class="text-xl font-bold mb-6 border-b pb-2"><?php echo htmlspecialchars($catName); ?></h2>
                
                <div class="space-y-6">
                    <?php
                    $rankSql = "SELECT l.nama_lagu, l.id_lagu, COUNT(u.id_undi) as vote_count 
                                FROM lagu l 
                                LEFT JOIN undian u ON l.id_lagu = u.id_lagu AND u.id_kategori = '$catId'
                                GROUP BY l.id_lagu 
                                ORDER BY vote_count DESC";

                    $rankResult = $conn->query($rankSql);
                    $rank = 1;

                    while($row = $rankResult->fetch_assoc()) {
                        $percent = round(($row['vote_count'] / $totalCatVotes) * 100, 1);
                    ?>
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-end">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center justify-center size-6 rounded-full bg-gray-100 text-xs font-bold text-gray-500">#<?php echo $rank++; ?></span>
                                <h3 class="font-bold text-[#111318] dark:text-white"><?php echo htmlspecialchars($row['nama_lagu']); ?></h3>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-primary"><?php echo $row['vote_count']; ?></span>
                                <span class="text-xs text-gray-500 font-medium uppercase min-w-[60px] inline-block">Votes</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 h-3 rounded-full overflow-hidden">
                            <div class="bg-primary h-full rounded-full transition-all duration-1000" style="width: <?php echo $percent; ?>%"></div>
                        </div>
                        <p class="text-xs text-right text-gray-400 font-medium"><?php echo $percent; ?>%</p>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</main>
<footer class="mt-auto px-10 py-8 border-t border-gray-200 dark:border-gray-800 text-center text-xs text-gray-500">
    © 2024 SMJK Chung Ling. All Rights Reserved.
</footer>
</body>
</html>
