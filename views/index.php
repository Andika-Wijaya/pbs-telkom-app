<?php include 'config/layanan.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">

<div class="header">TELKOM APP</div>

<div class="container">
<div class="card">

<h2>Tambah Pelanggan</h2>

<form method="POST" action="index.php?action=create">

    <input type="text" name="no_internet" placeholder="No Internet" required><br><br>
    <input type="text" name="nama" placeholder="Nama" required><br><br>
    <input type="text" name="no_tlp" placeholder="No Tlp" required><br><br>

    <select name="layanan" id="layananSelect" required>
    <option value="">-- Pilih Layanan --</option>

    <?php foreach($layananList as $l): ?>
        <option value="<?= $l['nama']; ?>" data-harga="<?= $l['harga']; ?>">
            <?= $l['nama']; ?>
        </option>
    <?php endforeach; ?>

</select>
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

<script>
document.getElementById("layananSelect").addEventListener("change", function() {
    let selected = this.options[this.selectedIndex];
    let harga = selected.getAttribute("data-harga");

    document.querySelector("input[name='harga']").value = harga;
});
</script>

</div>
</div>