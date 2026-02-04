<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Create Database
$sql = "CREATE DATABASE IF NOT EXISTS `db_undian_lagu`";
$conn->query($sql);
$conn->select_db("db_undian_lagu");

// 2. Table `kategori`
$conn->query("CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` varchar(5) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$conn->query("INSERT IGNORE INTO `kategori` VALUES ('K1', 'Menengah atas'), ('K2', 'Menengah rendah')");

// 3. Table `lagu`
$conn->query("CREATE TABLE IF NOT EXISTS `lagu` (
  `id_lagu` varchar(5) NOT NULL,
  `nama_lagu` varchar(100) NOT NULL,
  `video` varchar(100) NOT NULL,
  PRIMARY KEY (`id_lagu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 4. Table `pengundi` (Updated with Password)
$sql = "CREATE TABLE IF NOT EXISTS `pengundi` (
  `nokp` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`nokp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($sql) === TRUE) {
    echo "Table `pengundi` checked/created.<br>";
}

// Add 'password' column if it doesn't exist (for existing DBs)
// We use a try-catch equivalent or simple query that might fail if exists, suppressed.
// Or check column exists.
$checkCol = $conn->query("SHOW COLUMNS FROM `pengundi` LIKE 'password'");
if ($checkCol->num_rows == 0) {
    $conn->query("ALTER TABLE `pengundi` ADD `password` VARCHAR(255) NOT NULL DEFAULT '12345'");
    echo "Added password column to `pengundi`.<br>";
}

// 5. Table `undian`
$conn->query("CREATE TABLE IF NOT EXISTS `undian` (
  `id_undi` int(11) NOT NULL AUTO_INCREMENT,
  `nokp` varchar(20) DEFAULT NULL,
  `id_kategori` varchar(5) DEFAULT NULL,
  `id_lagu` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_undi`),
  KEY `nokp` (`nokp`),
  KEY `id_kategori` (`id_kategori`),
  KEY `id_lagu` (`id_lagu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 6. Table `admins` (New)
$sql = "CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
$conn->query($sql);

// Insert Default Admin (admin / 12345)
// Using plain text '12345' as requested implies simplicity, but let's see. 
// User asked: "add lines for admin and password 12345". 
// I will treat it as exact string mathching for simplicity unless they want hash.
// BUT for `pengundi`, "no password when register or login" -> now "add password".
$checkAdmin = $conn->query("SELECT * FROM admins WHERE username='admin'");
if ($checkAdmin->num_rows == 0) {
    $conn->query("INSERT INTO admins (username, password) VALUES ('admin', '12345')");
    echo "Admin account created (admin/12345).<br>";
}

echo "Database setup updated successfully.";
$conn->close();
?>
