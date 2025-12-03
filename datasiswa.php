<?php include "connect.php"; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Absensi Kelas</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* styling dipersingkat */
        .content-wrapper {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
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

        .btn-primary,
        .btn-secondary,
        .btn-danger {
            padding: 8px 14px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 6px;
        }

        .btn-primary {
            background: #005bbb;
        }

        .btn-secondary {
            background: #636e72;
        }

        .btn-danger {
            background: #d63031;
        }

        #popupBg {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
        }

        .form-popup {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 350px;
        }

        .form-popup input {
            width: 100%;
            padding: 10px;
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

        <!-- NAVIGATION -->
        <nav class="dashboard-nav">
            <ul>
                <li><a href="dashboard.html" class="nav-link active">Dashboard</a></li>
                <li><a href="absen.php" class="nav-link">Absensi Harian</a></li>
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

        <main class="content-wrapper">

            <h2>Data Siswa</h2>

            <select id="pilihKelas" onchange="loadSiswa()" style="padding:8px; border-radius:6px;">
                <option value="">Pilih Kelas</option>
                <?php
                $kelas = $conn->query("SELECT DISTINCT kelas FROM siswaXI ORDER BY kelas ASC");
                while ($k = $kelas->fetch_assoc()) {
                    echo "<option value='$k[kelas]'>$k[kelas]</option>";
                }
                ?>
            </select>

            <input type="text" id="cariSiswa" placeholder="Cari siswa..." onkeyup="searchSiswa()"
                style="padding:9px; width:220px;">

            <button class="btn-primary" onclick="openForm()">+ Tambah Siswa</button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbodySiswa"></tbody>
            </table>

        </main>
    </div>

    <!-- POPUP TAMBAH -->
    <div id="popupBg">
        <div class="form-popup">
            <h3>Tambah Siswa</h3>
            <input type="text" id="namaBaru" placeholder="Nama siswa">
            <select id="kelasBaru" onchange="tambahsiswa()" style="padding:8px; border-radius:6px;">
                <option value="">Pilih Kelas</option>
                <?php
                $kelas = $conn->query("SELECT DISTINCT kelas FROM siswaXI ORDER BY kelas ASC");
                while ($k = $kelas->fetch_assoc()) {
                    echo "<option value='$k[kelas]'>$k[kelas]</option>";
                }
                ?>
            </select><br>
            <button class="btn-primary" onclick="tambahSiswa()">Simpan</button>
            <button class="btn-secondary" onclick="closeForm()">Batal</button>
        </div>
    </div>

    <script>

        let dataGlobal = [];

        function loadSiswa() {
            let kelas = document.getElementById("pilihKelas").value;
            if (kelas === "") return;

            fetch("api_datasiswa.php?kelas=" + kelas)
                .then(res => res.json())
                .then(data => {
                    dataGlobal = data;
                    renderTable(data);
                });
        }

        function renderTable(data) {
            let tbody = document.getElementById("tbodySiswa");
            tbody.innerHTML = "";

            data.forEach(s => {
                tbody.innerHTML += `
        <tr>
            <td>${s.id}</td>
            <td>${s.NIS}</td>
            <td>${s.Nama}</td>
            <td>
                <button class="btn-danger" onclick="hapusSiswa(${s.id})">Hapus</button>
            </td>
        </tr>`;
            });
        }

        function searchSiswa() {
            let q = document.getElementById("cariSiswa").value.toLowerCase();
            let filtered = dataGlobal.filter(s => s.nama.toLowerCase().includes(q));
            renderTable(filtered);
        }

        function tambahSiswa() {
            let nama = document.getElementById("namaBaru").value;
            let kelasB = document.getElementById("kelasBaru").value;
            let kelas = document.getElementById("pilihKelas").value;

            fetch("tambah-siswa.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `nama=${nama}&kelas=${kelas}`
            })
                .then(r => r.text())
                .then(r => {
                    closeForm();
                    loadSiswa();
                });
        }

        function hapusSiswa(id) {
            if (!confirm("Hapus siswa ini?")) return;

            fetch("hapus-siswa.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${id}`
            })
                .then(r => r.text())
                .then(r => loadSiswa());
        }

        function openForm() { document.getElementById("popupBg").style.display = "block"; }
        function closeForm() { document.getElementById("popupBg").style.display = "none"; }

    </script>

</body>

</html>