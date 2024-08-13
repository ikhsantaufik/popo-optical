<?php
include 'header.php';

// Ambil atau inisialisasi array keranjang dari sesi
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Tangani penambahan item ke dalam keranjang
if (isset($_GET['add_to_cart']) && isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    // Anda mungkin perlu mengambil detail item dari database berdasarkan item_id

    // Tambahkan item ke dalam array keranjang di sesi
    $_SESSION['cart'][] = array(
        'item_id' => $item_id,
        'quantity' => 1, // Anda dapat mengubah ini berdasarkan input pengguna
        // Tambahkan detail item lain di sini
    );
}

// Skrip untuk menghapus item dari keranjang
if (isset($_GET['hapus_item']) && isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    // Hapus item dari keranjang berdasarkan item_id
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($cart_item) use ($item_id) {
        return $cart_item['item_id'] != $item_id;
    });
}

?>

<div id="cart">
    <div class="container-utama">
        <div class="box">
		<a href="index.php">Lanjutkan Belanja</a>
            <div class="box-header">
                <h3>Keranjang Belanja</h3>
            </div>
            <div class="box-body">
                <?php
                // Periksa apakah keranjang tidak kosong
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $cart_item) {
                        // Tampilkan setiap item di dalam keranjang
                        echo '<div>';
                        echo 'ID Item: ' . $cart_item['item_id'] . ' - Kuantitas: ' . $cart_item['quantity'];
                        // Tampilkan tombol hapus item
                        echo ' <a href="hapus_item.php?item_id=' . $cart_item['item_id'] . '">Hapus</a>';
                        echo '</div>';
                    }

                    // Hitung total harga
                    $total_harga = 0;
                    foreach ($_SESSION['cart'] as $cart_item) {
                        // Hitung total harga untuk setiap item (gantilah dengan harga sesuai dengan struktur data Anda)
                        $total_harga += $cart_item['quantity'] * $harga_item;
                    }

                    // Tampilkan total harga
                    echo '<div>Total Harga: Rp.' . number_format($total_harga, 0, ",", ".") . '</div>';

                    // Tampilkan tombol checkout
                    echo '<a href="checkout.php">Checkout</a>';
                } else {
                    echo 'Keranjang Anda kosong.';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

