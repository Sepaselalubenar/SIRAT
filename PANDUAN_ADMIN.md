# PANDUAN PENGGUNA APLIKASI SIRAT - SISI ADMINISTRATOR (PENGELOLA)
### (Sistem Reservasi Ruangan Fakultas Teknik Elektro)

Aplikasi **SIRAT** adalah platform berbasis web yang digunakan untuk mengelola dan memfasilitasi peminjaman serta reservasi ruangan. Halaman panduan ini khusus memuat prosedur pengelolaan bagi administrator sistem.

---

## DAFTAR ISI
1. [Peran Administrator (User Roles)](#1-peran-administrator-user-roles)
2. [Cara Login Admin](#2-cara-login-admin)
3. [Dashboard Statistik & Pembagian Wilayah](#3-dashboard-statistik--pembagian-wilayah)
4. [Proses Persetujuan Pengajuan (Persetujuan & Penolakan)](#4-proses-persetujuan-pengajuan-persetujuan--penolakan)
5. [Membatalkan Reservasi Aktif](#5-membatalkan-reservasi-aktif)
6. [Kelola Data Ruangan (CRUD & Foto)](#6-kelola-data-ruangan-crud--foto)
7. [Kelola Data Akun Dosen/Pegawai](#7-kelola-data-akun-dosenpegawai)
8. [Validasi Keamanan & Aturan Sistem](#8-validasi-keamanan--aturan-sistem)

---

## 1. PERAN ADMINISTRATOR (USER ROLES)

Otoritas administrator terbagi menjadi dua peran dengan wilayah tugas terpisah:

| Peran (Role) | Ruang Lingkup Otoritas | Fitur Utama |
| :--- | :--- | :--- |
| **Admin 1** | Ruangan (Lantai 3 & 14) | Mengelola data ruangan (CRUD), mengunggah/menghapus foto ruangan, memproses persetujuan reservasi, membatalkan reservasi aktif, serta mengelola data user. |
| **Admin 2** | Khusus Ruang Rapat 19.07 | Mengelola informasi detail dan foto Ruang Rapat 19.07, memproses persetujuan reservasi, serta membatalkan reservasi aktif di Ruang Rapat 19.07. **Tidak memiliki akses** untuk menambahkan (*create*) atau menghapus (*delete*) data ruangan. |

---

## 2. CARA LOGIN ADMIN
1. Akses halaman login khusus administrator di URL: `/admin/login` 
2. Masukkan **Email** administrator dan **Password** Anda.
3. Klik tombol **Login** untuk masuk ke **Dashboard Admin**.

---

## 3. DASHBOARD STATISTIK & PEMBAGIAN WILAYAH
Dashboard administrator menyajikan visualisasi data yang dibatasi berdasarkan wilayah kerja masing-masing akun:
* **Admin 1**: Hanya melihat data statistik total ruangan, total dosen, dan reservasi aktif untuk ruangan di lantai 3 dan 14.
* **Admin 2**: Hanya melihat statistik dan aktivitas khusus ruang rapat 19.07.

> [!NOTE]
> Pengajuan reservasi bertipe Sehari Penuh (Multi-day) akan ditampilkan secara berkelompok di dashboard admin guna memudahkan pemantauan dan pengelolaan.

---

## 4. PROSES PERSETUJUAN PENGAJUAN (Khusus Admin 2)
Jika terdapat pengajuan reservasi baru yang memerlukan persetujuan manual (status Pending), admin 2 dapat memprosesnya melalui halaman utama dashboard:

* **Persetujuan Reservasi Berkelompok**: Untuk pengajuan bertipe Sehari Penuh (Multi-day), menyetujui atau menolak salah satu hari dalam kelompok reservasi tersebut akan otomatis memproses seluruh hari dalam kelompok tersebut sekaligus.
* **Validasi Bentrok Bersama**: Saat menyetujui reservasi berkelompok, sistem akan memvalidasi ketersediaan ruangan untuk seluruh hari dalam rentang tersebut. Jika terdapat bentrok pada salah satu hari saja, persetujuan akan dibatalkan secara keseluruhan dan sistem menampilkan daftar tanggal yang bentrok.

### 1. Menyetujui Reservasi:
1. Cari pengajuan berstatus **Pending** di tabel dashboard.
2. Klik tombol **Setujui**.
3. Status reservasi akan berubah menjadi **Disetujui** dan pengaju akan mendapatkan notifikasi email konfirmasi (baik untuk reservasi tunggal maupun berkelompok).

### 2. Menolak Reservasi:
1. Klik tombol **Tolak** pada baris pengajuan pending.
2. Sebuah jendela pop-up modal akan muncul, meminta Anda memasukkan **Alasan Penolakan**.
3. Tulis alasan penolakan secara jelas (misal: "Ruangan sedang digunakan untuk rapat rektorat").
4. Klik **Submit**. Status reservasi berubah menjadi **Ditolak** (seluruh hari dalam grup jika berkelompok) dan alasan tersebut dikirimkan ke email pengaju.

---

## 5. MEMBATALKAN RESERVASI AKTIF
Apabila terdapat keperluan mendadak/kondisi darurat di mana ruangan yang sudah disetujui terpaksa harus digunakan untuk acara universitas lainnya:
1. Masuk ke menu **Daftar Reservasi** (`/admin/reservations`).
2. Cari data peminjaman yang berstatus **Disetujui**.
3. Klik tombol **Batalkan**.
4. Masukkan **Alasan Pembatalan** pada jendela modal yang muncul.
5. Klik simpan. Status reservasi akan berubah menjadi **Dibatalkan** (seluruh hari dalam grup jika berkelompok) dan sistem otomatis melayangkan email pemberitahuan pembatalan beserta alasannya ke dosen terkait.

---

## 6. KELOLA DATA RUANGAN (CRUD & FOTO)
Menu ini dapat diakses melalui **Kelola Ruangan** (`/admin/rooms`). Fitur-fitur yang tersedia:
1. **Tambah Ruangan**: Masukkan nama ruangan, jenis (Ruang Sidang / Ruang Rapat), nomor lantai, kapasitas orang, centang fasilitas yang tersedia, serta tulis deskripsi singkat. **(Hanya dapat diakses oleh Admin 1; Admin 2 tidak memiliki akses)**.
2. **Edit Ruangan**: Mengubah informasi detail ruangan yang sudah ada (misalnya mengganti kapasitas tempat duduk atau memperbarui fasilitas).
3. **Upload Foto Ruangan**: 
   - Klik tombol galeri/foto ruangan.
   - Unggah file gambar format `.png`, `.jpg`, atau `.jpeg`. Gambar ini akan tampil pada galeri detail ruangan di sisi dosen.
4. **Hapus Foto**: Administrator dapat menghapus foto-foto ruangan lama yang sudah tidak relevan.
5. **Hapus Ruangan**: Menghapus data ruangan dari sistem beserta seluruh fotonya. **(Hanya dapat diakses oleh Admin 1; Admin 2 tidak memiliki akses)**.

---

## 7. KELOLA DATA AKUN DOSEN/PEGAWAI
Akses menu **Kelola Dosen** (`/admin/users`) untuk mengelola akses masuk bagi para dosen/pegawai:
1. **Tambah User Baru**: Masukkan data lengkap berupa Role(Dosen/Pegawai), Nama Lengkap, Email aktif Telkom University, Nomor Induk Pegawai (NIP), dan Nomor Telepon.
2. **Edit Data User**: Memperbarui informasi dosen seperti perubahan nomor telepon atau perbaikan e-mail.
3. **Hapus User**: Menghapus akun user dari database sistem.

---

## 8. VALIDASI KEAMANAN & ATURAN SISTEM
Sistem SIRAT menerapkan validasi ketat di sisi server untuk mematuhi aturan bisnis berikut:
1. **Jam Operasional**: Peminjaman ruangan hanya diizinkan di antara jam **07:00 WIB s.d. 18:30 WIB**.
2. **Hari Operasional**: Pemesanan ruangan ditutup pada hari Minggu. Sistem memblokir reservasi biasa pada hari Minggu dan melewati hari Minggu secara otomatis pada reservasi sehari penuh.
3. **Urutan Waktu**: Waktu selesai peminjaman harus lebih besar daripada waktu mulai (Jam Mulai < Jam Selesai).
4. **Double Booking Protection**: Sistem akan memblokir pengajuan baru jika terdapat irisan waktu dengan reservasi ruangan yang sama yang sudah berstatus **Disetujui** pada tanggal yang sama.
5. **Pembatasan Durasi Multi-Day**: Durasi reservasi sehari penuh (sehari penuh) dibatasi maksimal **14 hari** dalam sekali pengajuan.
6. **Pembatasan Tambah & Hapus Ruangan**: 
   - Admin 2 tidak memiliki hak akses untuk menambah (*create*) atau menghapus (*delete*) data ruangan dari sistem.
7. **Pembatasan Wilayah Kerja Admin**: 
   - Admin 1 tidak dapat mengelola, melihat, menyetujui, membatalkan, atau menghapus aset/reservasi Ruang Rapat 19.07.
   - Admin 2 tidak dapat mengelola, melihat, menyetujui, membatalkan, atau menghapus aset/reservasi di luar Ruang Rapat 19.07.
