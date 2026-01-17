<h1 align="center">SIM STOK</h1>
<p align="center">
  Sistem Informasi Manajemen Stok berbasis web untuk pencatatan, monitoring, dan pelaporan persediaan barang.
</p>

---

## 📌 Deskripsi Singkat

**SIM STOK** adalah aplikasi manajemen stok berbasis **Laravel 11** yang dirancang untuk membantu pencatatan barang masuk dan keluar, pemantauan stok minimum, serta penyusunan laporan inventori secara terstruktur.

Project ini dikembangkan sebagai **project implementatif Laravel**, dengan fokus pada:
- Struktur MVC yang rapi
- Relasi database yang jelas
- Role-based access (Admin & Owner)
- Fitur yang mendekati kebutuhan dunia nyata

---

## ✨ Fitur Utama

- 🔐 Autentikasi pengguna
- 👥 Manajemen role (Admin & Owner)
- 📦 Manajemen produk & kategori
- 🏭 Manajemen supplier
- 🔄 Transaksi stok masuk & keluar
- ⚠️ Monitoring stok menipis & habis
- 📊 Laporan stok & ringkasan bulanan
- 🧾 Export laporan (PDF)
- 🔔 Notifikasi stok

---

## 🛠️ Tech Stack

- **Backend**: [Laravel 11](https://laravel.com/)
- **Frontend**: Blade Template Engine
- **UI**: Bootstrap 5.3
- **Database**: MySQL
- **Build Tool**: Vite

---

## 📋 Prerequisites

Pastikan environment kamu sudah memiliki:
- PHP ^8.2
- Composer ^2.2
- Node.js & NPM
- MySQL

---

## ⚙️ Installation & Setup

### 1. Clone Repository
```bash
git clone https://github.com/rivaelsaputra107387/sim-stok-laravel.git
cd sim-stok-laravel
```

### 2. Install Dependency
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
Salin file `.env.example` menjadi `.env`, lalu sesuaikan database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sim-stok
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi
```bash
# Tanpa seeder
php artisan migrate

# Dengan seeder (data dummy)
php artisan migrate --seed
```

### 6. Storage Link
```bash
php artisan storage:link
```

### 7. Jalankan Aplikasi
```bash
php artisan serve
```

Buka terminal baru untuk asset frontend:
```bash
npm run dev
```

Aplikasi dapat diakses di: `http://localhost:8000`

---

## 📷 Screenshot

(Coming Soon)

---

## 🚧 Status Project

Project ini masih dapat dikembangkan lebih lanjut, terutama pada:
- API integration
- Automated testing
- UI/UX improvement

---

## 👤 Author

**Rivael Saputra**  
Mahasiswa Teknik Informatika & Web Developer  
GitHub: [@rivaelsaputra107387](https://github.com/rivaelsaputra107387)

---

## 📝 License

Project ini bersifat open-source untuk keperluan pembelajaran.
