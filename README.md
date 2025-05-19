# Sistem Manajemen Tugas & File

Sistem manajemen file berbasis web sederhana yang dibangun dengan PHP, mengikuti prinsip OOP dan pola desain MVC. Aplikasi ini memungkinkan pengguna untuk mengunggah, melihat, mengedit, dan mengelola file dengan kategorisasi dan pelacakan tenggat waktu.

## Fitur

- **Buat**: Unggah file dengan detail (judul, deskripsi, kategori, tenggat waktu)
- **Baca**: Lihat file dalam tata letak grid atau daftar dengan informasi lengkap
- **Perbarui**: Edit detail file
- **Hapus**: Hapus file dari sistem
- **Filter & Urut**: Atur file berdasarkan kategori, tanggal, atau tenggat waktu
- **Desain Responsif**: UI modern yang berfungsi di desktop dan perangkat mobile

## Teknologi yang Digunakan

- PHP 7.4+ dengan pendekatan OOP
- MySQL untuk penyimpanan database
- Pure CSS
- HTML5
- JavaScript

## Struktur Proyek

Aplikasi ini mengikuti arsitektur MVC (Model-View-Controller):

```
/
├── assets/
│   └── css/
│       └── styles.css
├── controllers/
│   ├── FileController.php
│   └── HomeController.php
├── core/
│   ├── Controller.php
│   ├── Database.php
│   └── Router.php
├── models/
│   └── File.php
├── uploads/
│   └── (file yang diunggah pengguna)
├── views/
│   ├── edit_form.php
│   ├── edit_form_content.php
│   ├── home.php
│   ├── home_content.php
│   ├── layout.php
│   ├── upload_form.php
│   ├── upload_form_content.php
│   ├── view_file.php
│   └── view_file_content.php
├── index.php
└── README.md
```

## Instalasi

1. Clone repositori atau unduh kode sumber
2. Buat database MySQL bernama `file_management`
3. Import skema database dari `database.sql`
4. Konfigurasi koneksi database di `core/Database.php` jika diperlukan
5. Pastikan direktori `uploads` memiliki izin tulis
6. Letakkan file di root dokumen web server atau subdirektori
7. Akses aplikasi melalui browser web Anda

## Pengaturan Database

Buat database baru bernama `file_management` dan jalankan SQL berikut:

```sql
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` int(11) NOT NULL,
  `upload_date` datetime NOT NULL,
  `deadline` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Penggunaan

1. **Halaman Utama**: Lihat semua file yang diunggah, filter berdasarkan kategori, dan urutkan dengan kriteria berbeda
2. **Unggah**: Klik "Unggah File Baru" untuk menambahkan file baru dengan detail
3. **Lihat Detail**: Klik "Lihat Detail" untuk melihat informasi lengkap tentang file
4. **Edit**: Ubah informasi file menggunakan tombol edit
5. **Hapus**: Hapus file dari sistem
6. **Unduh**: Unduh file langsung dari daftar atau tampilan detail

## Fitur Detail

### Unggah File
- Tipe file yang didukung: PDF, DOC, DOCX, JPG, PNG, dan masih banyak lagi
- Ukuran file maksimum: 5MB
- Informasi wajib: Judul, Kategori
- Opsional: Deskripsi, Tenggat Waktu

### Kategorisasi
- Buat kategori kustom sesuai kebutuhan
- Filter file berdasarkan kategori

### Pengurutan
- Urutkan berdasarkan tanggal unggah (terbaru/terlama)
- Urutkan berdasarkan judul (A-Z/Z-A)
- Urutkan berdasarkan tenggat waktu (terdekat/terjauh)

### Opsi Tampilan
- Tampilan grid (default): Menampilkan file dalam tata letak kartu
- Tampilan daftar: Menampilkan file dalam format daftar yang ringkas
