<?php require_once __DIR__ . '/../config/layanan.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">

<div class="header">TELKOM APP</div>

<div class="container">
<div class="card">

<h2>Tambah Pelanggan</h2>

<form id="formTambah">

    <input type="text" name="no_internet" placeholder="No Internet" required><br><br>
    <input type="text" name="nama" placeholder="Nama" required><br><br>
    <input type="text" name="no_tlp" placeholder="No Tlp" required><br><br>

    <select name="layanan" id="layananSelect" required>
        <option value="">-- Pilih Layanan --</option>

        <?php foreach($layananList as $l): ?>
            <option value="<?= $l['nama']; ?>" data-harga="<?= $l['harga']; ?>">
    <?= $l['nama']; ?> - <?= $l['harga']; ?>
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
<a href="index.php?action=pelanggan" class="btn">Lihat Data</a>

<script>
document.getElementById("formTambah").addEventListener("submit", function(e){
    e.preventDefault(); // 🔥 WAJIB

    let form = this;
    let formData = new FormData(form);

    fetch("index.php?action=create", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(res => {

        if(res.status === "success"){

            let d = res.data;

            let table = document.querySelector("#tabelPelanggan tbody");

            let row = `
                <tr>
                    <td>${d.id}</td>
                    <td>${d.no_internet}</td>
                    <td>${d.nama}</td>
                    <td>${d.no_tlp}</td>
                    <td>${d.layanan}</td>
                    <td>${d.harga}</td>
                    <td>${d.tagihan}</td>
                    <td>${d.status_pelanggan}</td>
                </tr>
            `;

            table.insertAdjacentHTML("afterbegin", row);

            form.reset();

            Swal.fire({
                title: 'Berhasil!',
                text: 'Data ditambahkan',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        }

    });
});
</script>

<script>
document.getElementById("layananSelect").addEventListener("change", function(){

    let selected = this.options[this.selectedIndex];
    let harga = selected.getAttribute("data-harga");

    console.log("Harga terdeteksi:", harga); // debug

    document.querySelector("input[name='harga']").value = harga;

});
</script>

</div>
</div>