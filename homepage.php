<?php
require 'config/db.php';
session_start();

$db = new Database();
$conn = $db->connect();
$stmt = $conn->query("SELECT * FROM barang");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Wedding</title>
    <link rel="stylesheet" href="css/homepage.css">
    <link rel="stylesheet" href="css/item.css">
</head>
<body>
<header>
    <h2>Penyewaan Perlengkapan Pernikahan</h2>
</header>

<form action="checkout.php" method="post" id="rentalForm">
    <div>
        <label for="tanggal_digunakan">Tanggal Digunakan:</label>
        <input type="date" name="tanggal_digunakan" id="tanggal_digunakan" required>
    </div>

    <div class="item-list">
        <?php foreach ($items as $item): ?>
            <div class="item-container">
                <img src="<?php echo htmlspecialchars($item['gambar']); ?>" alt="<?php echo htmlspecialchars($item['nama']); ?>" onerror="this.onerror=null; this.src='placeholder.jpg';">
                <h3><?php echo htmlspecialchars($item['nama']); ?></h3>
                <p>Harga: Rp<?php echo htmlspecialchars($item['harga']); ?> per unit</p>
                <input type="number" name="jumlah[<?php echo htmlspecialchars($item['item_id']); ?>]" placeholder="Jumlah" min="0">
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="button-container">
        <button type="submit" class="button checkoutBtn">Checkout</button>
    </div>
</form>

<div class="button-container" style="margin-top: 20px;">
    <form action="logout.php" method="post" style="display:inline;">
        <button type="submit" class="button logoutBtn">Logout</button>
    </form>
    
    <form action="riwayat.php" method="get" style="display:inline;">
        <button type="submit" class="button logoutBtn">Riwayat</button>
    </form>

    <form action="profile.php" method="get" style="display:inline;">
        <button type="submit" class="button profileBtn">Profile</button>
    </form>
</div>

<script> 
    document.getElementById('rentalForm').onsubmit = function() {
        let inputs = document.querySelectorAll('input[type="number"]');
        let tanggalDigunakan = document.getElementById('tanggal_digunakan').value;

        if (!tanggalDigunakan) {
            alert('Silakan pilih tanggal digunakan.');
            return false;
        }

        let hasValidJumlah = false;
        for (let input of inputs) {
            if (input.value && parseInt(input.value) > 0) {  
                hasValidJumlah = true;
                break;
            }
        }

        if (!hasValidJumlah) {
            alert('Silakan masukkan jumlah untuk setidaknya satu item.');
            return false;
        }

        return true;
    };
</script>
</body>
</html>