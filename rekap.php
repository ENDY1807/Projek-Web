<?php
include 'connect.php';
$absenData = [];

// Ambil data dari siswa
$q = $conn->query("SELECT no, Nama, Kelas, status, tanggal FROM siswa ORDER BY no ASC");
while ($r = $q->fetch_assoc()) {
  $absenData[] = $r;
}

// Ambil data dari siswaXI
$q2 = $conn->query("SELECT no, Nama, Kelas, status, tanggal FROM siswaXI ORDER BY no ASC");
while ($r = $q2->fetch_assoc()) {
  $absenData[] = $r;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Rekap Absensi</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      margin: 0;
    }

    .container {
      max-width: 900px;
      margin: 30px auto;
      padding: 20px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .filter-box {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 15px;
    }

    select,
    input {
      padding: 7px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .btn {
      padding: 7px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      background: #2b6cb0;
      color: white;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th,
    td {
      padding: 10px;
      text-align: center;
      border: 1px solid #ddd;
    }

    th {
      background: #2b6cb0;
      color: white;
    }

    .status {
      padding: 3px 7px;
      border-radius: 5px;
      color: white;
      font-weight: bold;
      display: inline-block;
    }

    .Hadir {
      background: #38a169;
    }

    .Izin {
      background: #3182ce;
    }

    .Sakit {
      background: #dd6b20;
    }

    .Alpa {
      background: #e53e3e;
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
        <a href="absen.php"><button class="btn">Kembali</button></a>
    </div>
</header>
  <div class="container">
    <h2>Rekap Absensi</h2>

    <div class="filter-box">
      <select id="pilihKelas">
        <option value="">Semua Kelas</option>
        <?php
        $kelas1 = $conn->query("SELECT DISTINCT Kelas FROM siswa ORDER BY Kelas ASC");
        while ($k = $kelas1->fetch_assoc())
          echo "<option value='{$k['Kelas']}'>{$k['Kelas']}</option>";
        $kelas2 = $conn->query("SELECT DISTINCT Kelas FROM siswaXI ORDER BY Kelas ASC");
        while ($k = $kelas2->fetch_assoc())
          echo "<option value='{$k['Kelas']}'>{$k['Kelas']}</option>";
        ?>
      </select>

      <select id="filterStatus">
        <option value="">Semua Status</option>
        <option value="Hadir">Hadir</option>
        <option value="Izin">Izin</option>
        <option value="Sakit">Sakit</option>
        <option value="Alpa">Alpa</option>
      </select>

      <input type="text" id="filterNama" placeholder="Cari nama...">
      <button class="btn" onclick="applyFilters()">Filter</button>
      <button class="btn" onclick="resetFilters()">Reset</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Nama</th>
          <th>Kelas</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody id="tbody"></tbody>
    </table>
  </div>

  <script>
    const data = <?= json_encode($absenData); ?>;

    function loadTable(list) {
      const tbody = document.getElementById("tbody");
      tbody.innerHTML = "";
      list.forEach(r => {
        const statusClass = r.status ? r.status : '';
        const tanggal = r.tanggal ? r.tanggal : '-';
        tbody.innerHTML += `
      <tr>
        <td>${tanggal}</td>
        <td>${r.Nama}</td>
        <td>${r.Kelas}</td>
        <td class="status ${statusClass}">${r.status}</td>
      </tr>
    `;
      });
    }

    function applyFilters() {
      const kelas = document.getElementById("pilihKelas").value;
      const status = document.getElementById("filterStatus").value;
      const nama = document.getElementById("filterNama").value.toLowerCase();

      const filtered = data.filter(r =>
        (kelas === "" || r.Kelas === kelas) &&
        (status === "" || r.status === status) &&
        (nama === "" || r.Nama.toLowerCase().includes(nama))
      );
      loadTable(filtered);
    }

    function resetFilters() {
      document.getElementById("pilihKelas").value = "";
      document.getElementById("filterStatus").value = "";
      document.getElementById("filterNama").value = "";
      loadTable(data);
    }

    // Load awal
    loadTable(data);
  </script>

</body>

</html>
