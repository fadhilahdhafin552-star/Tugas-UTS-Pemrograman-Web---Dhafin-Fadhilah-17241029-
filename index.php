<?php
session_start();
$_SESSION['barang'] ??= [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Ambil data dari form
    $kode     = trim($_POST['kode'] ?? '');
    $nama     = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $stok     = filter_var($_POST['stok'] ?? '', FILTER_VALIDATE_INT);
    $harga    = filter_var($_POST['harga'] ?? '', FILTER_VALIDATE_INT);

    // 2. Periksa data
    if ($kode === '' || $nama === '' || $kategori === '') {
        $error = 'Kode, nama, dan kategori wajib diisi.';
    } elseif ($stok === false || $stok < 0) {
        $error = 'Stok harus berupa bilangan bulat, minimal 0.';
    } elseif ($harga === false || $harga < 0) {
        $error = 'Harga harus berupa bilangan bulat, minimal 0.';
    } else {
        // 3. Simpan ke session
        $_SESSION['barang'][] = [
            'kode'     => $kode,
            'nama'     => $nama,
            'kategori' => $kategori,
            'stok'     => $stok,
            'harga'    => $harga
        ];

        // 4. Muat ulang agar data tidak terkirim dua kali saat refresh
        header('Location: index.php?status=sukses');
        exit;
    }
}

// Pencarian dengan GET
$cari = trim($_GET['cari'] ?? '');
$hasil = $_SESSION['barang'];

if ($cari !== '') {
    $hasil = array_filter($_SESSION['barang'], function ($barang) use ($cari) {
        return stripos($barang['kode'], $cari) !== false
            || stripos($barang['nama'], $cari) !== false
            || stripos($barang['kategori'], $cari) !== false;
    });
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Inventaris Barang</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Sistem Inventaris Barang</h1>
    </header>

    <nav>
        <a href="#tambah">Tambah Barang</a>
        <a href="#daftar">Daftar Barang</a>
    </nav>

    <main>
        <section id="tambah">
            <h2>Tambah Barang</h2>

            <?php if ($error !== ''): ?>
                <p class="pesan-error"><?= htmlspecialchars($error) ?></p>
            <?php elseif (($_GET['status'] ?? '') === 'sukses'): ?>
                <p class="pesan-sukses">Data barang berhasil disimpan.</p>
            <?php endif; ?>

            <form id="form-barang" method="post" action="index.php">
                <label for="kode">Kode Barang</label>
                <input type="text" id="kode" name="kode">

                <label for="nama">Nama Barang</label>
                <input type="text" id="nama" name="nama">

                <label for="stok">Stok</label>
                <input type="number" id="stok" name="stok">

                <label for="kategori">Kategori Barang</label>
                <input type="text" id="kategori" name="kategori">

                <label for="harga">Harga Barang</label>
                <input type="number" id="harga" name="harga">

                <button type="submit">Simpan</button>
            </form>
        </section>

        <section id="daftar">
            <h2>Daftar Barang</h2>

            <!-- FORM 2: Cari barang (GET) -->
            <form method="get" action="index.php">
                <label for="cari">Cari Barang</label>
                <input type="search" id="cari" name="cari" value="<?= htmlspecialchars($cari) ?>">
                <button type="submit">Cari</button>
                <a href="index.php">Tampilkan Semua</a>
            </form>
            <!-- Akhir FORM 2 -->

            <article>
                <h3>Data Inventaris</h3>

                <div class="tabel-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Harga</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($hasil)): ?>
                                <tr>
                                    <td colspan="5">
                                        <?= $cari === '' ? 'Belum ada data.' : 'Barang tidak ditemukan.' ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($hasil as $barang): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($barang['kode']) ?></td>
                                        <td><?= htmlspecialchars($barang['nama']) ?></td>
                                        <td><?= htmlspecialchars($barang['kategori']) ?></td>
                                        <td><?= $barang['stok'] ?></td>
                                        <td>Rp <?= number_format($barang['harga'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </main>

    <footer>
        <p>Dhafin Fadhilah - 17241029</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
