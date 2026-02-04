<?php
session_start();
include 'db_connect.php';

// Check Auth
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$id = $_GET['id'] ?? '';
if (!$id) {
    header("Location: admin.php");
    exit();
}

// Fetch Song Logic
$stmt = $conn->prepare("SELECT * FROM lagu WHERE id_lagu = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$song = $result->fetch_assoc();

if (!$song) {
    echo "Lagu tidak dijumpai.";
    exit();
}

// Handle Update
if (isset($_POST['update_song'])) {
    $name = $_POST['nama_lagu'];
    $video = $_POST['video'];

    $updateStmt = $conn->prepare("UPDATE lagu SET nama_lagu = ?, video = ? WHERE id_lagu = ?");
    $updateStmt->bind_param("sss", $name, $video, $id);
    
    if ($updateStmt->execute()) {
        header("Location: admin.php"); // Redirect back to admin on success
        exit();
    } else {
        $error = "Ralat kemaskini: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Lagu - Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
</head>
<body class="bg-gray-50 font-[Inter] flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Lagu: <?php echo $song['id_lagu']; ?></h2>
        
        <?php if(isset($error)) echo "<p class='text-red-500 mb-4'>$error</p>"; ?>

        <form method="post" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lagu</label>
                <input type="text" name="nama_lagu" value="<?php echo htmlspecialchars($song['nama_lagu']); ?>" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fail Video</label>
                <input type="text" name="video" value="<?php echo htmlspecialchars($song['video']); ?>" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" name="update_song" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Simpan</button>
                <a href="admin.php" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg text-center hover:bg-gray-300">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
