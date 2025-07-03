<?php
session_start();
require_once "koneksi.php";

$keyword = trim($_GET['search'] ?? "");

if ($keyword !== "") {
    $sql = "SELECT * FROM TbBuku WHERE judul_buku LIKE ?";
    $params = ['%' . $keyword . '%'];
} else {
    $sql = "SELECT * FROM TbBuku";
    $params = [];
}
$stmt = sqlsrv_query($conn, $sql, $params);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
        body {
            background-color: #cfeafd;
            font-family: 'Poppins', sans-serif;
        }

        .book-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            overflow: hidden;
            transition: transform 0.2s ease;
            height: 100%;
        }

        .book-card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0, 26, 255, 0.1);
        }

        .cover-img {
            width: 100%;
            max-height: 250px;
            object-fit: contain;
            background-color: #f8f9fa;
            padding: 5px;
        }

        .book-info {
            padding: 10px 12px;
            font-size: 0.9rem;
        }

        .book-info h6,
        .book-info small {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .form-pinjam input,
        .form-pinjam button {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <h3 class="text-center text-primary mb-4">📚 Daftar Buku</h3>

    <!-- Form Pencarian -->
    <form class="d-flex justify-content-center mb-4" method="get">
        <input class="form-control w-50 me-2" type="search" name="search" placeholder="Cari judul buku..." value="<?= htmlspecialchars($keyword) ?>">
        <button class="btn btn-outline-primary" type="submit">Cari</button>
    </form>

    <!-- Daftar Buku -->
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
        <?php while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
        <div class="col">
            <div class="book-card">
                <img src="<?= !empty($row['gambar']) ? 'uploads/' . htmlspecialchars($row['gambar']) : 'default_cover.jpg' ?>" class="cover-img" alt="Cover Buku">

                <div class="book-info">
                    <h6 class="mb-1"><?= htmlspecialchars($row['judul_buku']) ?></h6>
                    <small class="text-muted">Penulis: <?= htmlspecialchars($row['penulis']) ?></small><br>
                    <small class="text-muted">Penerbit: <?= htmlspecialchars($row['penerbit']) ?></small><br>
                    <small class="text-muted">Tahun: <?= htmlspecialchars($row['tahun_terbit']) ?></small><br>
                </div>

                <form action="pinjam_buku.php" method="post" class="form-pinjam px-3 pb-3">
                    <input type="hidden" name="id_buku" value="<?= $row['id_buku'] ?>">
                    <div class="mb-1">
                        <input type="date" name="tanggal_pinjam" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <input type="date" name="tanggal_kembali" class="form-control form-control-sm" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success w-100">Pinjam</button>
                </form>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const searchInput = document.querySelector('input[name="search"]');
    searchInput.addEventListener('input', function () {
        if (this.value.trim() === '') {
            this.form.submit();
        }
    });
</script>

</body>
</html>
