<?php
include "koneksi.php";

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];

    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "uploads/".$gambar);

    mysqli_query($conn, "INSERT INTO produk (nama, harga, gambar) VALUES ('$nama','$harga','$gambar')");
    header("Location: produk.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM produk WHERE id=$id");
    header("Location: produk.php");
}

$produk = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk</title>
</head>
<body>
<h2>Kelola Produk</h2>
<form method="POST" enctype="multipart/form-data">
    Nama: <input type="text" name="nama" required>
    Harga: <input type="number" name="harga" required>
    Gambar: <input type="file" name="gambar" required>
    <button type="submit" name="tambah">Tambah</button>
</form>

<h3>Daftar Produk</h3>
<table border="1" cellpadding="5">
    <tr><th>No</th><th>Nama</th><th>Harga</th><th>Gambar</th><th>Aksi</th></tr>
    <?php $no=1; while($row = mysqli_fetch_assoc($produk)) { ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $row['nama']; ?></td>
            <td>Rp <?php echo number_format($row['harga']); ?></td>
            <td><img src="uploads/<?php echo $row['gambar']; ?>" width="80"></td>
            <td><a href="produk.php?hapus=<?php echo $row['id']; ?>">Hapus</a></td>
        </tr>
    <?php } ?>
</table>
</body>
</html>
