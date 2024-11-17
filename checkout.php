<?php
require 'config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->connect();

    $user_id = $_SESSION['user_id'];
    $tanggal_digunakan = $_POST['tanggal_digunakan'];

    $queue = [];
    
    foreach ($_POST['jumlah'] as $item_id => $jumlah) {
        if ($jumlah > 0) {
            $queue[] = ['item_id' => $item_id, 'jumlah' => $jumlah];
        }
    }

    while (!empty($queue)) {
        $current = array_shift($queue); 
        $item_id = $current['item_id'];
        $jumlah = $current['jumlah'];

        $stmt = $conn->prepare("SELECT harga FROM barang WHERE item_id = :item_id");
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($item) {
            $harga = $item['harga'];
            $total_harga = $harga * $jumlah;

            $insert_stmt = $conn->prepare("INSERT INTO rental (user_id, item_id, jumlah, total_harga, tanggal_sewa, tanggal_digunakan) VALUES (:user_id, :item_id, :jumlah, :total_harga, NOW(), :tanggal_digunakan)");
            $insert_stmt->bindParam(':user_id', $user_id);
            $insert_stmt->bindParam(':item_id', $item_id);
            $insert_stmt->bindParam(':jumlah', $jumlah);
            $insert_stmt->bindParam(':total_harga', $total_harga);
            $insert_stmt->bindParam(':tanggal_digunakan', $tanggal_digunakan);
            $insert_stmt->execute();
        }
    }
    
    header("Location: riwayat.php");
    exit();
}
?>