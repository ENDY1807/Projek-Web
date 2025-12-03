// Fungsi untuk menampilkan greeting berdasarkan waktu
function displayGreeting() {
    const greetingElement = document.getElementById('greeting');
    if (greetingElement) {
        const hour = new Date().getHours();
        let greeting = '';
        
        if (hour < 12) {
            greeting = 'Selamat pagi';
        } else if (hour < 15) {
            greeting = 'Selamat siang';
        } else if (hour < 19) {
            greeting = 'Selamat sore';
        } else {
            greeting = 'Selamat malam';
        }
        
        greetingElement.textContent = greeting;
    }
}

// Fungsi untuk modal
function setupModals() {
    // Modal Lupa Password
    const forgotPasswordBtn = document.getElementById('forgotPasswordBtn');
    const forgotPasswordModal = document.getElementById('forgotPasswordModal');
    
    if (forgotPasswordBtn && forgotPasswordModal) {
        forgotPasswordBtn.addEventListener('click', function() {
            forgotPasswordModal.style.display = 'block';
        });
    }
    
    // Modal Daftar
    const registerBtn = document.getElementById('registerBtn');
    const registerModal = document.getElementById('registerModal');
    
    if (registerBtn && registerModal) {
        registerBtn.addEventListener('click', function() {
            registerModal.style.display = 'block';
        });
    }
    
    // Modal Tambah Stok
    const addStockBtn = document.getElementById('addStockBtn');
    const addStockModal = document.getElementById('addStockModal');
    
    if (addStockBtn && addStockModal) {
        addStockBtn.addEventListener('click', function() {
            addStockModal.style.display = 'block';
        });
    }
    
    // Tutup modal
    const closeButtons = document.querySelectorAll('.close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            modal.style.display = 'none';
        });
    });
    
    // Tutup modal saat klik di luar
    window.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
}

// Validasi form login
function setupLoginForm() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Validasi sederhana
            if (!email || !password) {
                alert('Email dan password harus diisi');
                return;
            }
            
            // Validasi format email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Format email tidak valid');
                return;
            }
            
            // Simulasi login berhasil
            // Di aplikasi nyata, ini akan memeriksa ke backend
            if (email === 'admin@baknus.com' && password === '12345678') {
                window.location.href = 'dashboard.html';
            } else {
                alert('Email/password yang anda masukkan salah');
            }
        });
    }
}

// Setup form lupa password
function setupForgotPasswordForm() {
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('resetEmail').value;
            
            if (!email) {
                alert('Email harus diisi');
                return;
            }
            
            alert(`Link reset password telah dikirim ke ${email}`);
            document.getElementById('forgotPasswordModal').style.display = 'none';
            forgotPasswordForm.reset();
        });
    }
}

// Setup form daftar
function setupRegisterForm() {
    const registerForm = document.getElementById('registerForm');
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('regName').value;
            const email = document.getElementById('regEmail').value;
            const password = document.getElementById('regPassword').value;
            const confirmPassword = document.getElementById('regConfirmPassword').value;
            
            // Validasi
            if (!name || !email || !password || !confirmPassword) {
                alert('Semua field harus diisi');
                return;
            }
            
            if (password !== confirmPassword) {
                alert('Konfirmasi password tidak sesuai');
                return;
            }
            
            if (password.length < 6) {
                alert('Password harus minimal 6 karakter');
                return;
            }
            
            alert('Pendaftaran berhasil! Silakan login dengan akun Anda.');
            document.getElementById('registerModal').style.display = 'none';
            registerForm.reset();
        });
    }
}

// Setup tracking pengiriman
function setupTracking() {
    const searchBtn = document.getElementById('searchBtn');
    
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const doNumber = document.getElementById('doNumber').value.trim();
            const resultDiv = document.getElementById('trackingResult');
            
            if (!doNumber) {
                alert('Nomor DO harus diisi');
                return;
            }
            
            // Cari data pengiriman
            const pengiriman = dataPengiriman.find(item => item.noDO === doNumber);
            
            if (pengiriman) {
                let statusClass = '';
                if (pengiriman.status === 'Dikirim') statusClass = 'status-dikirim';
                else if (pengiriman.status === 'Diproses') statusClass = 'status-diproses';
                else if (pengiriman.status === 'Selesai') statusClass = 'status-selesai';
                
                resultDiv.innerHTML = `
                    <div class="tracking-details">
                        <h3>Detail Pengiriman</h3>
                        <p><strong>Nomor DO:</strong> ${pengiriman.noDO}</p>
                        <p><strong>Nama Mahasiswa:</strong> ${pengiriman.namaMahasiswa}</p>
                        <p><strong>Status:</strong> <span class="status-badge ${statusClass}">${pengiriman.status}</span></p>
                        
                        <div class="progress-bar">
                            <div class="progress" style="width: ${pengiriman.progress}%"></div>
                        </div>
                        <p>Progress: ${pengiriman.progress}%</p>
                        
                        <p><strong>Ekspedisi:</strong> ${pengiriman.ekspedisi}</p>
                        <p><strong>Tanggal Kirim:</strong> ${pengiriman.tanggalKirim}</p>
                        <p><strong>Jenis Paket:</strong> ${pengiriman.jenisPaket}</p>
                        <p><strong>Total Pembayaran:</strong> Rp ${pengiriman.totalPembayaran.toLocaleString('id-ID')}</p>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = '<div class="alert alert-error">Nomor DO tidak ditemukan</div>';
            }
        });
    }
}

// Setup tampilan stok bahan ajar
function setupStokBahanAjar() {
    const tableBody = document.getElementById('stockTableBody');
    
    if (tableBody) {
        // Tampilkan data awal
        displayStokData();
        
        // Setup form tambah stok
        const addStockForm = document.getElementById('addStockForm');
        
        if (addStockForm) {
            addStockForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const kodeBahan = document.getElementById('kodeBahan').value;
                const namaBahan = document.getElementById('namaBahan').value;
                const stok = parseInt(document.getElementById('stok').value);
                const status = document.getElementById('status').value;
                
                // Validasi
                if (!kodeBahan || !namaBahan || isNaN(stok) || !status) {
                    alert('Semua field harus diisi');
                    return;
                }
                
                // Tambahkan data baru
                dataBahanAjar.push({
                    kode: kodeBahan,
                    nama: namaBahan,
                    stok: stok,
                    status: status
                });
                
                // Perbarui tabel
                displayStokData();
                
                // Tutup modal dan reset form
                document.getElementById('addStockModal').style.display = 'none';
                addStockForm.reset();
                
                alert('Stok berhasil ditambahkan');
            });
        }
    }
}

// Fungsi untuk menampilkan data stok
function displayStokData() {
    const tableBody = document.getElementById('stockTableBody');
    
    if (tableBody) {
        tableBody.innerHTML = '';
        
        dataBahanAjar.forEach(item => {
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td>${item.kode}</td>
                <td>${item.nama}</td>
                <td>${item.stok}</td>
                <td>${item.status}</td>
                <td>
                    <button class="action-btn edit-btn" data-kode="${item.kode}">Edit</button>
                    <button class="action-btn delete-btn" data-kode="${item.kode}">Hapus</button>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Setup event listener untuk tombol edit dan hapus
        setupActionButtons();
    }
}

// Setup tombol aksi (edit dan hapus)
function setupActionButtons() {
    const editButtons = document.querySelectorAll('.edit-btn');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const kode = this.getAttribute('data-kode');
            alert(`Fitur edit untuk kode ${kode} akan diimplementasikan`);
        });
    });
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const kode = this.getAttribute('data-kode');
            
            if (confirm(`Apakah Anda yakin ingin menghapus stok dengan kode ${kode}?`)) {
                // Hapus dari array dataBahanAjar
                const index = dataBahanAjar.findIndex(item => item.kode === kode);
                if (index !== -1) {
                    dataBahanAjar.splice(index, 1);
                    displayStokData();
                    alert('Stok berhasil dihapus');
                }
            }
        });
    });
}

// Setup logout
function setupLogout() {
    const logoutBtn = document.getElementById('logoutBtn');
    
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                window.location.href = 'index.html';
            }
        });
    }
}

// Inisialisasi semua fungsi saat DOM siap
document.addEventListener('DOMContentLoaded', function() {
    displayGreeting();
    setupModals();
    setupLoginForm();
    setupForgotPasswordForm();
    setupRegisterForm();
    setupTracking();
    setupStokBahanAjar();
    setupLogout();
});
// Fungsi untuk menampilkan data dummy di halaman tracking
function displayDummyDataInTracking() {
    const tableBody = document.getElementById('dummyTableBody');
    
    if (tableBody && dataPengiriman) {
        tableBody.innerHTML = '';
        
        dataPengiriman.forEach(item => {
            const row = document.createElement('tr');
            
            // Tentukan class status
            let statusClass = '';
            if (item.status === 'Dikirim') statusClass = 'status-dikirim';
            else if (item.status === 'Diproses') statusClass = 'status-diproses';
            else if (item.status === 'Selesai') statusClass = 'status-selesai';
            
            row.innerHTML = `
                <td><strong>${item.noDO}</strong></td>
                <td>${item.namaMahasiswa}</td>
                <td><span class="${statusClass}">${item.status}</span></td>
                <td class="progress-cell">
                    <div class="progress-mini">
                        <div class="progress-fill" style="width: ${item.progress}%"></div>
                    </div>
                    <span>${item.progress}%</span>
                </td>
                <td>${item.ekspedisi}</td>
                <td class="action-buttons">
                    <button class="copy-do-btn" data-do="${item.noDO}">Salin</button>
                    <button class="use-do-btn" data-do="${item.noDO}">Gunakan</button>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Setup event listener untuk tombol
        setupDummyTableButtons();
    }
}

// Setup tombol di tabel dummy
function setupDummyTableButtons() {
    // Tombol "Salin"
    const copyButtons = document.querySelectorAll('.copy-do-btn');
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const doNumber = this.getAttribute('data-do');
            navigator.clipboard.writeText(doNumber).then(() => {
                alert(`Nomor DO ${doNumber} berhasil disalin!`);
            });
        });
    });
    
    // Tombol "Gunakan"
    const useButtons = document.querySelectorAll('.use-do-btn');
    useButtons.forEach(button => {
        button.addEventListener('click', function() {
            const doNumber = this.getAttribute('data-do');
            const doInput = document.getElementById('doNumber');
            
            if (doInput) {
                doInput.value = doNumber;
                doInput.focus();
                
                // Scroll ke form pencarian
                doInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
}

// Update fungsi inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    displayGreeting();
    setupModals();
    setupLoginForm();
    setupForgotPasswordForm();
    setupRegisterForm();
    setupTracking();
    setupStokBahanAjar();
    setupLogout();
    
    // Tampilkan data dummy di halaman tracking
    if (window.location.pathname.includes('tracking.html')) {
        displayDummyDataInTracking();
    }
});