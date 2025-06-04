
## Fitur

- **Create**: Unggah file dengan detail (judul, deskripsi, kategori, tenggat waktu)
- **Read**: Lihat file dalam tata letak grid atau daftar dengan informasi lengkap
- **Update**: Edit detail file
- **Delete**: Hapus file dari sistem
- **Filter & Sort**: Atur file berdasarkan kategori, tanggal, Ddeadline

## Teknologi yang Digunakan

- PHP 
- MySQL
- CSS
- HTML
- JavaScript

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
- Opsional: Deskripsi,

### Kategorisasi
- Buat kategori kustom sesuai kebutuhan
- Filter file berdasarkan kategori

### Pengurutan
- Urutkan berdasarkan tanggal unggah (terbaru/terlama)
- Urutkan berdasarkan judul (A-Z/Z-A)
- Urutkan berdasarkan tenggat waktu (terdekat/terjauh)