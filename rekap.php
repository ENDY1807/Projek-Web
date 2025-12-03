<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rekap Absen</title>
  <link rel="stylesheet" href="css/style.css">

  <style>
    .rekap-container {
      max-width: 900px;
      margin: 30px auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .date-item {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 8px;
    }

    .btn-date {
      padding: 8px 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      background: #0066cc;
      color: white;
    }
    .btn-date:hover { background: #004a99; }

    .btn-delete {
      padding: 8px 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      background: #d00000;
      color: white;
    }
    .btn-delete:hover { background: #900000; }

    .rekap-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    .rekap-table th, .rekap-table td {
      border-bottom: 1px solid #ddd;
      padding: 12px;
      text-align: center;
    }
    .rekap-table th {
      background: #0066cc;
      color: #fff;
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
        <button id="logoutBtn" class="btn-secondary">Keluar</button>
    </div>
</header>

<nav class="dashboard-nav">
    <ul>
        <li><a href="dashboard.html" class="nav-link">Dashboard</a></li>
        <li><a href="absen.php" class="nav-link">Absensi Harian</a></li>
        <li><a href="rekap.php" class="nav-link active">Rekap Absen</a></li>
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

<div class="rekap-container">
    <h2>Rekap Absensi</h2>

    <div id="dateList"></div>

    <div id="rekapDetail" style="display:none;">
      <table class="rekap-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="rekapTabelBody"></tbody>
      </table>

      <div id="summaryStats" style="margin-top:20px;"></div>
    </div>
</div>

<script>
let lastOpenedKey = null;  // untuk toggle panel

function getAbsensiKeys() {
  return Object.keys(localStorage)
    .filter(k => k.startsWith("absensi_"))
    .sort();
}

function loadDateButtons() {
  const container = document.getElementById("dateList");
  container.innerHTML = "";

  const keys = getAbsensiKeys();
  if (keys.length === 0) {
    container.innerHTML = "<p>Belum ada data absensi.</p>";
    return;
  }

  keys.forEach(key => {
    const tanggal = key.replace("absensi_", "");

    const wrap = document.createElement("div");
    wrap.className = "date-item";

    const btnTanggal = document.createElement("button");
    btnTanggal.className = "btn-date";
    btnTanggal.textContent = tanggal;
    btnTanggal.onclick = () => toggleRekap(key, tanggal);

    const btnDelete = document.createElement("button");
    btnDelete.className = "btn-delete";
    btnDelete.textContent = "Hapus";
    btnDelete.onclick = () => deleteData(key);

    wrap.appendChild(btnTanggal);
    wrap.appendChild(btnDelete);
    container.appendChild(wrap);
  });
}

function deleteData(key) {
  const tanggal = key.replace("absensi_", "");

  if (!confirm(`Yakin ingin menghapus absensi tanggal ${tanggal}?`)) return;

  localStorage.removeItem(key);

  alert("Data berhasil dihapus!");
  loadDateButtons();
  document.getElementById("rekapDetail").style.display = "none";
}

function toggleRekap(key, tanggal) {
  const panel = document.getElementById("rekapDetail");

  // jika tombol sama ditekan dua kali → tutup
  if (lastOpenedKey === key && panel.style.display === "block") {
    panel.style.display = "none";
    lastOpenedKey = null;
    return;
  }

  showRekap(key, tanggal);
  lastOpenedKey = key;
}

function showRekap(key, tanggal) {
  const raw = localStorage.getItem(key);
  if (!raw) return;

  const data = JSON.parse(raw);
  const tbody = document.getElementById("rekapTabelBody");
  tbody.innerHTML = "";

  let hadir = 0, izin = 0, sakit = 0, alpa = 0;

  Object.keys(data).forEach((nis, i) => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${i + 1}</td>
      <td>${nis}</td>
      <td>${data[nis]}</td>
    `;
    tbody.appendChild(tr);

    if (data[nis] === "Hadir") hadir++;
    else if (data[nis] === "Izin") izin++;
    else if (data[nis] === "Sakit") sakit++;
    else if (data[nis] === "Alpa") alpa++;
  });

  document.getElementById("summaryStats").innerHTML = `
    <b>Rekap tanggal ${tanggal}:</b><br>
    Hadir: ${hadir} <br>
    Izin: ${izin} <br>
    Sakit: ${sakit} <br>
    Alpa: ${alpa}
  `;

  document.getElementById("rekapDetail").style.display = "block";
}

loadDateButtons();
</script>

</body>
</html>
