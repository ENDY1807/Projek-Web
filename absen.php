<?php include "connect.php"; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Harian</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background:white; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; }
        th { background: #0066cc; color: white; }
        .btn { padding: 10px 15px; background: #0066cc; color: #fff; border: none; cursor: pointer; }
        .btn:hover { background: #004a99; }
        select, input { padding: 8px; }
        .container { padding: 20px; }
    </style>
</head>

<body>

<header class="dashboard-header">
    <div class="header-left">
        <img src="assets/absensi.png" alt="Logo Absensi" class="logo">
        <h1>Absensi Kelas</h1>
    </div>
    <div class="header-right">
        <span id="greeting"></span>
        <button id="logoutBtn" class="btn-secondary">Keluar</button>
    </div>
</header>

<nav class="dashboard-nav">
    <ul>
        <li><a href="dashboard.html" class="nav-link">Dashboard</a></li>
        <li><a href="absen.php" class="nav-link active">Absensi Harian</a></li>
        <li><a href="rekap.php" class="nav-link">Rekap Absen</a></li>
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

<div class="container">

    <label>Pilih Tanggal:</label>
    <input type="date" id="tanggalAbsen">

    <br><br>

    <label>Pilih Kelas:</label>
    <select id="pilihKelas" onchange="loadSiswa()" style="padding:8px; border-radius:6px;">
                <option value="">Pilih Kelas</option>
                <?php
                $kelas = $conn->query("SELECT DISTINCT kelas FROM siswaXI ORDER BY kelas ASC");
                while ($k = $kelas->fetch_assoc()) {
                    echo "<option value='$k[kelas]'>$k[kelas]</option>";
                }
                ?>
    </select>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpha</th>
            </tr>
        </thead>
        <tbody id="tbodySiswa"></tbody>
    </table>

    <button class="btn" onclick="simpanAbsensi()">Simpan Absensi</button>

</div>

<script>

document.getElementById("pilihKelas").addEventListener("change", loadSiswa);

function loadSiswa() {
    const kelas = document.getElementById("pilihKelas").value;
    if (!kelas) return;

    fetch("api_datasiswa.php?kelas=" + kelas)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("tbodySiswa");
            tbody.innerHTML = "";

            data.forEach((siswa, i) => {
                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <td>${i + 1}</td>
                    <td>${siswa.Nama}</td>
                    <td>
                        <label><input type="radio" name="status_${siswa.NIS}" value="Hadir"></label>
                    </td>
                    <td>
                        <label><input type="radio" name="status_${siswa.NIS}" value="Izin"></label>
                    </td>
                    <td>
                        <label><input type="radio" name="status_${siswa.NIS}" value="Sakit"></label>
                    </td>
                    <td>
                        <label><input type="radio" name="status_${siswa.NIS}" value="Alpa"></label>
                    </td>
                `;

                tbody.appendChild(tr);
            });
        });
}

function simpanAbsensi() {
    const tanggal = document.getElementById("tanggalAbsen").value;
    const kelas = document.getElementById("pilihKelas").value;

    if (!tanggal) return alert("Tanggal belum dipilih!");
    if (!kelas) return alert("Kelas belum dipilih!");

    const radios = document.querySelectorAll("tbody input[type=radio]");
    let hasilAbsen = {};

    radios.forEach(r => {
        if (r.checked) {
            const nis = r.name.replace("status_", "");
            hasilAbsen[nis] = r.value;
        }
    });

    const key = `absensi_${tanggal}_${kelas}`;
    localStorage.setItem(key, JSON.stringify(hasilAbsen));

    alert("Absensi tersimpan untuk kelas " + kelas);
}

</script>
</body>
</html>
