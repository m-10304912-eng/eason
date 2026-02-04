<?php
include 'session.php';
include 'db_connect.php';
checkLogin(); // Allows User OR Admin

$isAdmin = isset($_SESSION['admin_logged_in']);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ($isAdmin ? 'Administrator' : 'Guest');

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
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Vote | SMJK Chung Ling</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: { "primary": "#0d59f2", "background-light": "#f5f6f8", "background-dark": "#101622", "success": "#10b981", "warning": "#f59e0b" },
                fontFamily: { "display": ["Lexend"] },
            },
        },
    }
</script>
<style> body { font-family: 'Lexend', sans-serif; } .material-symbols-outlined { font-variation-settings: 'FILL' 1; } </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-[#111318] dark:text-white min-h-screen relative">

<!-- FEEDBACK MODALS OVERLAY -->
<?php if ($status == 'success'): ?>
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="w-full max-w-[480px] bg-white dark:bg-[#1a212e] rounded-xl shadow-2xl p-8 flex flex-col items-center text-center">
        <div class="size-20 bg-success/20 rounded-full flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-success text-5xl">check_circle</span>
        </div>
        <h1 class="text-2xl font-bold mb-2">Vote Submitted!</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-8 leading-relaxed px-4">
            Thank you! Your vote has been securely recorded.
        </p>
        <a href="vote.php" class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-primary/90 transition-colors block">
            Continue Voting
        </a>
    </div>
</div>
<?php elseif ($status == 'duplicate'): ?>
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="w-full max-w-[480px] bg-white dark:bg-[#1a212e] rounded-xl shadow-2xl p-8 flex flex-col items-center text-center">
        <div class="size-20 bg-warning/20 rounded-full flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-warning text-5xl">warning</span>
        </div>
        <h1 class="text-2xl font-bold mb-2">Already Voted</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-8 leading-relaxed px-4">
            You have already cast a vote for this specific category.
        </p>
        <a href="vote.php" class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-primary/90 transition-colors block">
            Return to Dashboard
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Top Navigation Bar -->
<header class="sticky top-0 z-50 w-full bg-white dark:bg-background-dark border-b border-solid border-[#f0f1f5] dark:border-gray-800 px-4 md:px-10 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <h1 class="text-lg md:text-xl font-bold leading-tight tracking-tight text-primary">SMJK Chung Ling Voting</h1>
    </div>
    <div class="flex items-center gap-4">
        <div class="hidden md:flex flex-col items-end">
            <span class="text-xs text-[#606e8a] dark:text-gray-400">Logged in as</span>
            <span class="text-sm font-bold"><?php echo htmlspecialchars($user_name); ?></span>
        </div>
        <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
            <?php echo substr($user_name, 0, 1); ?>
        </div>
        <a href="logout.php" class="bg-[#f0f1f5] dark:bg-gray-800 text-[#111318] dark:text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-200 transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">logout</span>
            <span class="hidden sm:inline">Logout</span>
        </a>
    </div>
</header>

<main class="max-w-[1200px] mx-auto px-4 py-8">
    <!-- Page Heading -->
    <div class="mb-8">
        <h2 class="text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em] mb-2">Cast Your Vote</h2>
        <p class="text-[#606e8a] dark:text-gray-400 text-base max-w-2xl">
            Select a category below to view performances. 
            <?php if($isAdmin): ?> <span class="text-red-500 font-bold">(Admin Mode: Voting Disabled)</span> 
            <?php else: ?> You are entitled to <b>one vote per category</b>. <?php endif; ?>
        </p>
        <?php if($isAdmin): ?>
            <a href="admin.php" class="inline-flex items-center gap-2 mt-4 text-primary font-bold hover:underline">
                <span class="material-symbols-outlined">arrow_back</span> Back to Admin Panel
            </a>
        <?php endif; ?>
    </div>

    <!-- Category Tabs -->
    <div class="mb-8 border-b border-[#dbdfe6] dark:border-gray-800 flex overflow-x-auto no-scrollbar gap-2">
        <?php foreach ($categories as $cat): 
            $isActive = $cat['id_kategori'] == $active_cat;
            $class = $isActive 
                ? "border-b-[3px] border-primary text-primary" 
                : "border-b-[3px] border-transparent text-[#606e8a] hover:text-primary";
        ?>
        <a href="vote.php?cat=<?php echo $cat['id_kategori']; ?>" class="flex flex-col items-center justify-center pb-3 pt-2 px-6 whitespace-nowrap transition-colors <?php echo $class; ?>">
            <p class="text-sm font-bold leading-normal tracking-wide"><?php echo htmlspecialchars($cat['kategori']); ?></p>
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
        <div class="bg-gray-100 p-6 rounded-xl text-center mb-8 border border-gray-200">
            <span class="material-symbols-outlined text-green-600 text-4xl mb-2">check_circle</span>
            <h3 class="text-xl font-bold text-gray-800">You have voted for this category.</h3>
            <p class="text-gray-500">Please select another category to vote.</p>
        </div>
    <?php else: ?>
        <!-- Competition Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($songs as $song): ?>
            <!-- Song Card -->
            <div class="bg-white dark:bg-gray-900 rounded-xl overflow-hidden border border-[#f0f1f5] dark:border-gray-800 shadow-sm flex flex-col">
                <div class="relative aspect-video bg-black group">
                    <video controls class="w-full h-full object-cover">
                        <source src="<?php echo htmlspecialchars($song['video']); ?>" type="video/mp4">
                    </video>
                </div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="mb-4">
                        <h3 class="text-[#111318] dark:text-white text-lg font-bold mb-1"><?php echo htmlspecialchars($song['nama_lagu']); ?></h3>
                        <p class="text-sm text-[#606e8a]">ID: <?php echo $song['id_lagu']; ?></p>
                    </div>
                    <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-800 flex gap-3">
                        <form action="submit_vote.php" method="POST" class="w-full">
                            <input type="hidden" name="id_kategori" value="<?php echo $active_cat; ?>">
                            <input type="hidden" name="id_lagu" value="<?php echo $song['id_lagu']; ?>">
                            <?php if ($isAdmin): ?>
                                <button type="button" disabled class="w-full bg-gray-400 cursor-not-allowed text-white py-2.5 rounded-lg font-bold text-sm flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-lg">lock</span>
                                    Admin View Only
                                </button>
                            <?php else: ?>
                                <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg font-bold text-sm hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-lg">how_to_vote</span>
                                    Vote Now
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

<footer class="mt-12 py-8 border-t border-gray-200 dark:border-gray-800 text-center">
    <p class="text-[#606e8a] dark:text-gray-400 text-sm">
        © 2024 SMJK Chung Ling. Electronic Voting System.<br/>
        Need help? Contact the AV Club or the IT Department.
    </p>
</footer>

</body>
</html>
