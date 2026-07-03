<link rel="stylesheet" href="assets/css/style.css">

<div class="header">EDIT DATA</div>

<div class="container">
<div class="card">

<form method="POST" action="index.php?action=update">
    <input type="hidden" name="id" value="<?= $data['id'] ?>">

    <input type="text" name="nama" value="<?= $data['nama'] ?>"><br><br>
    <input type="text" name="layanan" value="<?= $data['layanan'] ?>"><br><br>

    <button type="submit">Update</button>
</form>

</div>
</div>