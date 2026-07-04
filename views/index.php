<link rel="stylesheet" href="assets/css/style.css">

<div class="header">TELKOM APP</div>

<div class="container">
<div class="card">

<h2>Tambah Pelanggan</h2>

<form method="POST" action="index.php?action=create">

    <input type="text" name="no_internet" placeholder="No Internet" required><br><br>
    <input type="text" name="nama" placeholder="Nama" required><br><br>
    <input type="text" name="no_tlp" placeholder="No Tlp" required><br><br>
    <input type="text" name="layanan" placeholder="Layanan" required><br><br>
    <input type="number" name="harga" placeholder="Harga" required><br><br>

    <select name="tagihan">
        <option value="lunas">Lunas</option>
        <option value="belum bayar">Belum Bayar</option>
    </select><br><br>

    <select name="status">
        <option value="aktif">Aktif</option>
        <option value="pending">Pending</option>
        <option value="terisolir">Terisolir</option>
    </select><br><br>

    <button type="submit">Simpan</button>
</form>

<br>
<a href="index.php?action=dashboard" class="btn">Lihat Data</a>

</div>
</div>