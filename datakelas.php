<?php include "connect.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kelas - Absensi Kelas</title>
    <link rel="stylesheet" href="css/style.css">

<style>
/* --- Layout umum --- */
.content-wrapper {
    padding: 20px;
}

.title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 15px;
}

/* --- Buttons --- */
.btn-primary {
    background: #005bbb;
    padding: 10px 18px;
    color: white;
    border-radius: 6px;
    border: none;
    cursor: pointer;
}

.btn-danger {
    background: #d63031;
    padding: 6px 12px;
    color: white;
    border-radius: 6px;
    border: none;
    cursor: pointer;
}

.btn-secondary {
    background: #636e72;
    padding: 6px 12px;
    color: white;
    border-radius: 6px;
    border: none;
    cursor: pointer;
}

/* --- Table --- */
table {
    width: 100%;
    background: white;
    border-collapse: collapse;
    margin-top: 20px;
    border-radius: 10px;
    overflow: hidden;
}

th {
    background: #005bbb;
    color: white;
    padding: 12px;
}

td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

/* --- Popup form (modal) --- */
#popupBg {
    display: none;
    position: fixed;
    z-index: 999;
    background: rgba(0,0,0,0.4);
    width: 100%;
    height: 100%;
    top: 0; left: 0;
}

.form-popup {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    width: 380px;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0,0,0,0.2);
}

.form-popup input {
    width: 100%;
    padding: 12px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
}
</style>
</head>

<body>

<div class="dashboard-container">

        <header class="dashboard-header">
            <div class="header-left">
                <img src="assets/absensi.png" alt="Logo Absensi" class="logo">
                <h1>Absensi Kelas</h1>
            </div>
            <div class="header-right">
                <span id="greeting" class="greeting"></span>
                <button id="logoutBtn" class="btn-secondary">Keluar</button>
            </div>
        </header>
        <nav class="dashboard-nav">
            <ul>
                <li><a href="dashboard.html" class="nav-link active">Dashboard</a></li>
                <li><a href="absen.html" class="nav-link">Absensi Harian</a></li>
                <li><a href="rekap.html" class="nav-link">Rekap Absen</a></li>
                <li><a href="datasiswa.php" class="nav-link">Data Siswa</a></li>
                <li><a href="datakelas.php" class="nav-link">Data Kelas</a></li>
                <li class="dropdown">
                    <a href="#" class="nav-link">Laporan</a>
                    <div class="dropdown-content">
                        <a href="laporan_harian.html">Laporan Harian</a>
                        <a href="laporan_bulanan.html">Laporan Bulanan</a>
                    </div>
                </li>
                <li><a href="histori.html" class="nav-link">Histori Absensi</a></li>
            </ul>
        </nav>

<main class="content-wrapper">

    <div class="title">Data Kelas</div>

    <button class="btn-primary" onclick="openForm()">+ Tambah Kelas</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tbodyKelas"></tbody>
    </table>

</main>

</div>

<!-- Popup Background -->
<div id="popupBg">
    <div class="form-popup">
        <h3 style="margin-bottom:15px;">Tambah Kelas Baru</h3>

        <input type="text" id="namaKelas" placeholder="Nama kelas...">

        <button class="btn-primary" onclick="tambahKelas()">Simpan</button>
        <button class="btn-secondary" onclick="closeForm()">Batal</button>
    </div>
</div>

<script>
// --- Load kelas dari database ---
function loadKelas() {
    fetch("data-kelas.php")
    .then(res => res.json())
    .then(data => {
        let tbody = document.getElementById("tbodyKelas");
        tbody.innerHTML = "";

        data.forEach(k => {
            tbody.innerHTML += `
                <tr>
                    <td>${k.id}</td>
                    <td>${k.nama}</td>
                    <td>
                        <button class="btn-danger" onclick="hapusKelas(${k.id})">Hapus</button>
                    </td>
                </tr>
            `;
        });
    });
}

// --- Tambah kelas ---
function tambahKelas() {
    let nama = document.getElementById("namaKelas").value;

    fetch("tambah-kelas.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `nama=${nama}`
    })
    .then(res => res.text())
    .then(res => {
        if (res === "success") {
            closeForm();
            loadKelas();
        } else {
            alert("Gagal menambah kelas");
        }
    });
}

// --- Hapus kelas ---
function hapusKelas(id) {
    if (!confirm("Yakin ingin menghapus kelas ini?")) return;

    fetch("hapus-kelas.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id=${id}`
    })
    .then(res => res.text())
    .then(res => {
        if (res === "success") {
            loadKelas();
        } else {
            alert("Gagal menghapus kelas");
        }
    });
}

// --- Popup form ---
function openForm() {
    document.getElementById("popupBg").style.display = "block";
}

function closeForm() {
    document.getElementById("popupBg").style.display = "none";
    document.getElementById("namaKelas").value = "";
}

loadKelas();
</script>

</body>
</html>
