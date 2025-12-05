<?php include "connect.php"; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Harian</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #0066cc;
            color: white;
        }

        .btn {
            padding: 10px 15px;
            background: #0066cc;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 7px;
        }

        .btn:hover {
            background: #004a99;
        }

        select, input {
            padding: 8px;
            border-radius: 7px;
        }

        .container {
            padding: 20px;
            margin: 20px;
            border-radius: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

<header class="dashboard-header">
    <div class="header-left">
        <img src="assets/absensi.png" alt="Logo Absensi" class="logo">
        <h1>Absensi Kelas</h1>
    </div>
    <div class="header-right">
        <span id="greeting" class="greeting"></span>
        <a href="rekap.php"><button class="btn">Rekap Absen</button></a>
    </div>
</header>

<div class="container">

    <label>Pilih Tanggal:</label>
    <input type="date" id="tanggalAbsen">
    <br><br>

    <label>Pilih Kelas:</label>
    <select id="pilihKelas" onchange="loadSiswa()">
        <option value="">Pilih Kelas</option>

        <?php
        // kelas dari siswa
        $kelas1 = $conn->query("SELECT DISTINCT kelas FROM siswa ORDER BY kelas ASC");
        while ($k = $kelas1->fetch_assoc())
            echo "<option value='{$k['kelas']}'>{$k['kelas']}</option>";

        // kelas dari siswaXI
        $kelas2 = $conn->query("SELECT DISTINCT kelas FROM siswaXI ORDER BY kelas ASC");
        while ($k = $kelas2->fetch_assoc())
            echo "<option value='{$k['kelas']}'>{$k['kelas']}</option>";
        ?>
    </select>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="tbodySiswa"></tbody>
    </table>

    <button class="btn" onclick="simpanAbsensi()">Simpan Absensi</button>

</div>

<script>

// LOAD SISWA
function loadSiswa() {
    const kelas = document.getElementById("pilihKelas").value;
    if (!kelas) return;

    fetch("api_datasiswa.php?kelas=" + kelas)
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById("tbodySiswa");
            tbody.innerHTML = "";

            data.forEach((s, i) => {
                tbody.innerHTML += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${s.nama}</td>
                        <td>
                            <select class="statusSelect" data-nis="${s.nis}">
                                <option value="">Pilih Status</option>
                                <option value="Hadir">Hadir</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpa">Alpa</option>
                            </select>
                        </td>
                    </tr>`;
            });
        });
}


// SIMPAN ABSEN
function simpanAbsensi() {
    const tanggal = document.getElementById("tanggalAbsen").value;
    const kelas = document.getElementById("pilihKelas").value;

    if (!tanggal || !kelas) {
        alert("Tanggal dan kelas harus diisi!");
        return;
    }

    const selects = document.querySelectorAll(".statusSelect");
    let absensi = [];
    let kosong = false;

    selects.forEach(s => {
        if (!s.value) kosong = true;
        absensi.push({
            nis: s.dataset.nis,
            status: s.value
        });
    });

    if (kosong) return alert("Semua siswa harus diberi status!");

    fetch("save_absen.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({ tanggal, kelas, absensi })
    })
    .then(r => r.text())
    .then(r => {
        alert("Absensi berhasil disimpan!");
    });
}

</script>

</body>
</html>


<!-- $q1 = $conn->query("SELECT * FROM siswaXI ORDER BY id DESC");

while ($row = $q1->fetch_assoc()) {
    $absenData[] = [
        "tanggal" => $row['tanggal'] ?? '-',
        "nama" => $row['nama'] ?? '-',
        "kelas" => $row['kelas'] ?? 'XI',
        "status" => $row['status'] ?? '-',
        "alasan" => $row['alasan'] ?? '-'
    ];
}

$q2 = $conn->query("SELECT * FROM siswaX ORDER BY id DESC");

while ($row = $q2->fetch_assoc()) {
    $absenData[] = [
        "tanggal" => $row['tanggal'] ?? '-',
        "nama" => $row['nama'] ?? '-',
        "kelas" => $row['kelas'] ?? 'X',
        "status" => $row['status'] ?? '-',
        "alasan" => $row['alasan'] ?? '-'
    ];
} -->
