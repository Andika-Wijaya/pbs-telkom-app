<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/layanan.php';

// 🔐 Proteksi login
if (!isset($_SESSION['login'])) {
    header("Location: index.php?action=login");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<div class="page-shell">
    <div class="topbar">
        <div class="brand">
            <span class="logo" aria-hidden="true">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="24" height="24" rx="6" fill="#d32f2f"/>
                    <path d="M6 12h12" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 6v12" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            Telkom Customer Management
        </div>
        <div class="nav-links">
            <a href="index.php?action=dashboard">🏠 Dashboard</a>
            <a class="active" href="index.php?action=pelanggan">👥 Data Pelanggan</a>
            <a href="index.php?action=logout">🚪 Logout</a>
        </div>
    </div>

    <div class="page-header">
        <div>
            <p class="eyebrow">Manajemen Data</p>
            <h1>Data Pelanggan</h1>
            <p>Kelola data pelanggan dengan tampilan yang lebih rapi dan mudah dipakai.</p>
        </div>
        <div class="header-chip">Selamat datang, <b><?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?></b> 👋</div>
    </div>

    <div class="container">
        <div class="panel">
            <div class="panel-toolbar">
                <div class="search-box">
                    <input type="text" id="search" placeholder="Cari nama atau No Internet...">
                </div>
                <div style="display:flex; gap:8px; align-items:center;">
                    <button class="btn btn-primary" onclick="openTambah()">+ Tambah Pelanggan</button>
                    <a class="btn btn-outline" href="index.php?action=export">Export CSV</a>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Internet</th>
                            <th>Nama</th>
                            <th>No Tlp</th>
                            <th>Layanan</th>
                            <th>Harga</th>
                            <th>Tagihan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php if (empty($data)): ?>
                        <tr>
                            <td colspan="9" class="empty-state">Belum ada data pelanggan.</td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1; foreach($data as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['no_internet']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['no_tlp']) ?></td>
                            <td><?= htmlspecialchars($row['layanan']) ?></td>
                            <td>Rp <?= number_format($row['harga']) ?></td>
                            <td>
                                <span class="badge <?= $row['tagihan'] == 'lunas' ? 'lunas' : 'belum'; ?>">
                                    <?= ucfirst($row['tagihan']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $row['status']; ?>">
                                    <?= ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-btn" type="button" onclick="openEditModal(
                                        '<?= $row['id'] ?>',
                                        '<?= htmlspecialchars($row['no_internet'], ENT_QUOTES) ?>',
                                        '<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>',
                                        '<?= htmlspecialchars($row['no_tlp'], ENT_QUOTES) ?>',
                                        '<?= htmlspecialchars($row['layanan'], ENT_QUOTES) ?>',
                                        '<?= $row['harga'] ?>',
                                        '<?= $row['tagihan'] ?>',
                                        '<?= $row['status'] ?>'
                                    )">Edit</button>
                                    <button class="delete-btn" type="button" onclick="hapusData(<?= $row['id'] ?>)">Hapus</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Edit Data Pelanggan</h3>
            <button type="button" class="close-btn" onclick="closeModal()">×</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="index.php?action=update" class="form-grid">
                <input type="hidden" name="id" id="edit_id">

                <div class="field">
                    <label>No Internet</label>
                    <input type="text" name="no_internet" id="edit_no_internet" placeholder="No Internet" required>
                </div>
                <div class="field">
                    <label>Nama</label>
                    <input type="text" name="nama" id="edit_nama" placeholder="Nama" required>
                </div>
                <div class="field">
                    <label>No Tlp</label>
                    <input type="text" name="no_tlp" id="edit_no_tlp" placeholder="No Tlp" required>
                </div>
                <div class="field">
                    <label>Layanan</label>
                    <select name="layanan" id="edit_layanan" required>
                        <option value="">-- Pilih Layanan --</option>
                        <?php foreach($layananList as $l): ?>
                            <option value="<?= htmlspecialchars($l['nama']); ?>" data-harga="<?= $l['harga']; ?>" data-kode="<?= $l['kode']; ?>">
                                <?= htmlspecialchars($l['nama']); ?> - <?= number_format($l['harga'], 0, ',', '.'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Harga</label>
                    <input type="number" name="harga" id="edit_harga" placeholder="Harga" required>
                </div>
                <div class="field">
                    <label>Tagihan</label>
                    <select name="tagihan" id="edit_tagihan">
                        <option value="lunas">Lunas</option>
                        <option value="belum bayar">Belum Bayar</option>
                    </select>
                </div>
                <div class="field">
                    <label>Status</label>
                    <select name="status" id="edit_status">
                        <option value="aktif">Aktif</option>
                        <option value="pending">Pending</option>
                        <option value="terisolir">Terisolir</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" onclick="closeModal()">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalTambah" class="modal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Tambah Pelanggan Baru</h3>
            <button type="button" class="close-btn" onclick="closeTambah()">×</button>
        </div>
        <div class="modal-body">
            <form id="formTambahModal" class="form-grid" method="POST" action="index.php?action=create">
                <div class="field">
                    <label>No Internet</label>
                    <input type="text" name="no_internet" id="tambah_no_internet" placeholder="No Internet" readonly required>
                </div>
                <div class="field">
                    <label>Nama</label>
                    <input type="text" name="nama" placeholder="Nama" required>
                </div>
                <div class="field">
                    <label>No Tlp</label>
                    <input type="text" name="no_tlp" placeholder="No Tlp" required>
                </div>
                <div class="field">
                    <label>Layanan</label>
                    <select name="layanan" id="layanan" required>
                        <option value="">-- Pilih Layanan --</option>
                        <?php foreach($layananList as $l): ?>
                            <option value="<?= $l['nama']; ?>" data-harga="<?= $l['harga']; ?>" data-kode="<?= $l['kode']; ?>">
                                <?= $l['nama']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Harga</label>
                    <input type="number" name="harga" id="harga" placeholder="Harga" required>
                </div>
                <div class="field">
                    <label>Tagihan</label>
                    <select name="tagihan">
                        <option value="lunas">Lunas</option>
                        <option value="belum bayar">Belum Bayar</option>
                    </select>
                </div>
                <div class="field">
                    <label>Status</label>
                    <select name="status">
                        <option value="aktif">Aktif</option>
                        <option value="pending">Pending</option>
                        <option value="terisolir">Terisolir</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" onclick="closeTambah()">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const searchInput = document.getElementById('search');
if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        let keyword = this.value;

        fetch('index.php?action=searchAjax&keyword=' + keyword)
        .then(res => res.json())
        .then(data => {
            let html = '';

            if (data.length === 0) {
                html = '<tr><td colspan="9" class="empty-state">Data tidak ditemukan</td></tr>';
            } else {
                let no = 1;
                data.forEach(row => {
                    html += `
                    <tr>
                        <td>${no++}</td>
                        <td>${row.no_internet}</td>
                        <td>${row.nama}</td>
                        <td>${row.no_tlp}</td>
                        <td>${row.layanan}</td>
                        <td>Rp ${row.harga}</td>
                        <td><span class="badge ${row.tagihan === 'lunas' ? 'lunas' : 'belum'}">${row.tagihan}</span></td>
                        <td><span class="badge ${row.status}">${row.status}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="edit-btn" type="button" onclick="openEditModal('${row.id}', '${row.no_internet}', '${row.nama}', '${row.no_tlp}', '${row.layanan}', '${row.harga}', '${row.tagihan}', '${row.status}')">Edit</button>
                                <button class="delete-btn" type="button" onclick="hapusData(${row.id})">Hapus</button>
                            </div>
                        </td>
                    </tr>`;
                });
            }

            document.getElementById('tableBody').innerHTML = html;
        });
    });
}

function openEditModal(id, no_internet, nama, no_tlp, layanan, harga, tagihan, status) {
    document.getElementById('editModal').classList.add('active');
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_no_internet').value = no_internet;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_no_tlp').value = no_tlp;
    document.getElementById('edit_harga').value = harga;
    document.getElementById('edit_tagihan').value = tagihan;
    document.getElementById('edit_status').value = status;

    let layananSelect = document.getElementById('edit_layanan');
    let found = false;

    for (let option of layananSelect.options) {
        if (option.value === layanan) {
            layananSelect.value = layanan;
            found = true;
            break;
        }
    }

    if (!found) {
        layananSelect.value = '';
    }
}

function closeModal() {
    document.getElementById('editModal').classList.remove('active');
}

function openTambah() {
    const form = document.getElementById('formTambahModal');
    if (form) {
        form.reset();
        const noInternetInput = document.getElementById('tambah_no_internet');
        if (noInternetInput) {
            noInternetInput.value = '';
        }
    }
    document.getElementById('modalTambah').classList.add('active');
}

function closeTambah() {
    document.getElementById('modalTambah').classList.remove('active');
}

function hapusData(id) {
    Swal.fire({
        title: 'Yakin?',
        text: 'Data akan dihapus!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('index.php?action=delete&id=' + id)
            .then(res => res.text())
            .then(() => {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data berhasil dihapus.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            });
        }
    });
}

function logoutConfirm() {
    Swal.fire({
        title: 'Logout?',
        text: 'Kamu akan keluar dari sistem!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'index.php?action=logout';
        }
    });
}

<?php if (isset($_SESSION['success'])): ?>
Swal.fire({
    title: 'Login Berhasil!',
    text: 'Selamat datang, <?= $_SESSION['username']; ?> 👋',
    icon: 'success',
    showConfirmButton: false,
    timer: 2000
});
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

document.getElementById('formTambahModal')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    fetch('index.php?action=create', {
        method: 'POST',
        body: formData
    })
    .then(async (res) => {
        const text = await res.text();
        try {
            return JSON.parse(text);
        } catch (err) {
            throw new Error(text || 'Respons server tidak valid');
        }
    })
    .then((res) => {
        if (res.status === 'success') {
            form.reset();
            closeTambah();
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data pelanggan berhasil ditambahkan.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: res.message || 'Data gagal ditambahkan.',
                icon: 'error'
            });
        }
    })
    .catch((err) => {
        console.error(err);
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan data.',
            icon: 'error'
        });
    });
});

function generateNoInternet(kode) {
    if (!kode) return '';
    const now = Date.now().toString().slice(-6);
    const random = Math.floor(Math.random() * 900 + 100);
    return `${kode}-${now}${random}`;
}

function updateFormFields(selectElement) {
    const selected = selectElement.options[selectElement.selectedIndex];
    const harga = selected.getAttribute('data-harga');
    const kode = selected.getAttribute('data-kode');
    const form = selectElement.closest('form');

    if (!form) return;

    const inputHarga = form.querySelector("input[name='harga']");
    const inputNoInternet = form.querySelector("input[name='no_internet']");

    if (inputHarga) {
        inputHarga.value = harga || '';
    }
    if (inputNoInternet) {
        inputNoInternet.value = generateNoInternet(kode);
    }
}

document.addEventListener('change', function(e) {
    if (e.target.name === 'layanan') {
        updateFormFields(e.target);
    }
});
</script>

<footer style="margin-top:24px; text-align:center; color:#94a3b8; padding:18px 0;">
    &copy; <?= date('Y'); ?> Telkom Customer Management — dibuat dengan PHP
</footer>

</body>
</html>