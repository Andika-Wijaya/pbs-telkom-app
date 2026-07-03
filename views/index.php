<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pelanggan</title>
</head>
<body>

<h2>Tambah Data</h2>

<form id="formTambah">
    <input type="text" name="nama" placeholder="Nama" required><br><br>
    
    <select name="layanan">
        <option value="Indihome">Indihome</option>
        <option value="Wifi">Wifi</option>
    </select><br><br>

    <button type="submit">Simpan</button>
</form>

<br>
<a href="index.php?action=dashboard">Ke Dashboard</a>

<script>
document.getElementById("formTambah").addEventListener("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("index.php?action=create", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(() => {
        alert("Data berhasil ditambahkan");
        window.location.href = "index.php?action=dashboard";
    });
});
</script>

</body>
</html>