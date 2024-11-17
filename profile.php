<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'config/db.php';

$db = new Database();
$conn = $db->connect();

$user_id = $_SESSION['user_id']; 

$query = "SELECT username, email, telepon, umur FROM users WHERE id = :user_id"; 
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id); 
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Penyewaan Perlengkapan Pernikahan</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<header>
    <h2>Profile Pengguna</h2>
</header>

<div class="profile-container">
    <h3>Informasi Profil</h3>
    
    <div class="profile-info">
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Nomor Telepon:</strong> <?php echo htmlspecialchars($user['telepon']); ?></p>
        <p><strong>Umur:</strong> <?php echo htmlspecialchars($user['umur']); ?> tahun</p>
    </div>
</div>

<div class="button-container" style="margin-top: 20px;">
    <form action="logout.php" method="post" style="display:inline;">
        <button type="submit" class="button logoutBtn">Logout</button>
    </form>

    <form action="homepage.php" method="get" style="display:inline;">
        <button type="submit" class="button profileBtn">Kembali ke Homepage</button>
    </form>
</div>

</body>
</html>
