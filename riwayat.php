<?php
require 'config/db.php';
session_start();

$db = new Database();
$conn = $db->connect();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header("Location: login.php");
    exit();
}

$user_query = $conn->prepare("SELECT username FROM users WHERE id = :user_id");
$user_query->bindParam(':user_id', $user_id);
$user_query->execute();
$user_data = $user_query->fetch(PDO::FETCH_ASSOC);
$username = $user_data['username'] ?? 'Tidak Diketahui';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_stmt = $conn->prepare("DELETE FROM rental WHERE rental_id = :id AND user_id = :user_id");
    $delete_stmt->bindParam(':id', $delete_id);
    $delete_stmt->bindParam(':user_id', $user_id);
    $delete_stmt->execute();
}

$stmt = $conn->prepare("SELECT rental.rental_id, rental.tanggal_sewa, rental.tanggal_digunakan, barang.nama, rental.jumlah, rental.total_harga 
    FROM rental 
    JOIN barang ON rental.item_id = barang.item_id 
    WHERE rental.user_id = :user_id 
    ORDER BY rental.tanggal_sewa DESC");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Wedding</title>
    <link rel="stylesheet" href="css/homepage.css">
</head>
<body>
<header>
    <h2>Riwayat Penyewaan</h2>
    <strong>Nama Pemesan:</strong> <?php echo htmlspecialchars($username); ?>
</header>

<table>
    <tr>
        <th>Tanggal Sewa</th>
        <th>Tanggal Digunakan</th>
        <th>Nama Item</th>
        <th>Jumlah</th>
        <th>Total Harga</th>
        <th>Batal</th>
    </tr>
    <?php if (empty($history)): ?>
        <tr>
            <td colspan="7">Tidak ada riwayat penyewaan.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($history as $rental): ?>
        <tr>
            <td><?php echo htmlspecialchars($rental['tanggal_sewa']); ?></td>
            <td><?php echo htmlspecialchars($rental['tanggal_digunakan']); ?></td> 
            <td><?php echo htmlspecialchars($rental['nama']); ?></td>
            <td><?php echo htmlspecialchars($rental['jumlah']); ?></td>
            <td>Rp<?php echo number_format($rental['total_harga'], 3); ?></td>
            <td>
                <form action="" method="post" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($rental['rental_id']); ?>">
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">Batal</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<form action="homepage.php" method="post" style="margin-top: 20px;">
    <button type="submit" class="logoutBtn">Kembali</button>
</form>
</body>
</html>