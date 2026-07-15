# PANDUAN PENGGUNA APLIKASI SIRAT - DOSEN / PEGAWAI
### (Sistem Reservasi Ruangan Fakultas Teknik Elektro)

Aplikasi **SIRAT** adalah platform berbasis web yang digunakan untuk mengelola dan memfasilitasi peminjaman serta reservasi ruangan di Fakultas Teknik Elektro. 

---

## DAFTAR ISI
1. [Peran Pengguna - Sisi Dosen / Pegawai](#1-peran-pengguna---sisi-dosen-pegawai)
2. [Cara Login Dosen/Pegawai](#2-cara-login-dosenpegawai)
3. [Melihat Daftar & Detail Ruangan](#3-melihat-daftar--detail-ruangan)
4. [Prosedur Pengajuan Reservasi](#4-prosedur-pengajuan-reservasi)
   - [Reservasi Biasa (Single Day / Per Jam)](#1-reservasi-biasa-single-day--per-jam)
   - [Reservasi Sehari Penuh (Multi-Day / Rentang Hari)](#2-reservasi-sehari-penuh-multi-day--rentang-hari)
5. [Kalender Jadwal Ruangan](#5-kalender-jadwal-ruangan)
6. [Riwayat Peminjaman & Fitur Pembatalan](#6-riwayat-peminjaman--fitur-pembatalan)
7. [Validasi Keamanan & Aturan Sistem](#7-validasi-keamanan--aturan-sistem)
---

## 1. PERAN PENGGUNA

| Peran (Role) | Ruang Lingkup Otoritas | Fitur Utama |
| :--- | :--- | :--- |
| **Dosen / Pegawai (Lecturer)** | Seluruh ruangan FTE | Melihat jadwal kalender, melihat ketersediaan ruangan, melakukan pengajuan reservasi (biasa atau sehari penuh), membatalkan reservasi aktif secara mandiri, dan melihat riwayat pribadinya. |

---

## 2. CARA LOGIN DOSEN/PEGAWAI
Untuk masuk ke sistem SIRAT sebagai Dosen/Pegawai:
1. Akses halaman utama aplikasi di URL: 
2. Masukkan **NIP** dan **Email** resmi yang telah didaftarkan oleh Administrator.
3. Klik tombol **Masuk ke Dashboard**.
4. Sistem akan mencocokkan NIP & Email Anda. Jika sesuai, Anda akan diarahkan ke halaman **Dashboard**.

> [!NOTE]
> Akun Dosen/Pegawai tidak menggunakan kata sandi (password). Keamanan login didasarkan pada kecocokan pasangan NIP dan Email unik yang telah terdaftar di pangkalan data.

---

## 3. MELIHAT DAFTAR & DETAIL RUANGAN
1. Masuk ke menu **Daftar Ruangan** (`/reservation`).
2. Halaman ini akan menampilkan seluruh daftar ruangan lengkap dengan status ketersediaannya (Tersedia / Sedang Digunakan).
3. Anda dapat menyaring ruangan berdasarkan lantai.
4. Klik tombol **Detail** pada salah satu ruangan untuk melihat informasi terperinci berupa:
   - Galeri foto ruangan.
   - Nama ruangan, nomor lantai, kapasitas tempat duduk.
   - Fasilitas penunjang (seperti AC, Proyektor, TV, WiFi, Whiteboard).
   - Deskripsi ruangan.

---

## 4. PROSEDUR PENGAJUAN RESERVASI

Formulir peminjaman terletak di halaman detail masing-masing ruangan. Terdapat dua tipe reservasi yang dapat dipilih:

### 1. Reservasi Biasa (Single Day / Per Jam)
Gunakan opsi ini jika Anda hanya ingin memesan ruangan pada jam tertentu di satu hari tertentu:
* **Tanggal**: Hari peminjaman ruangan.
* **Jam Mulai & Jam Selesai**: Rentang waktu peminjaman (Wajib berada dalam Jam Operasional: **07:00 - 18:30**).

### 2. Reservasi Sehari Penuh (Multi-Day / Rentang Hari)
Gunakan opsi ini untuk reservasi ruangan seharian penuh selama beberapa hari berturut-turut:
* **Tipe Reservasi**: Pilih opsi **Sehari Penuh**.
* **Tanggal Mulai & Tanggal Selesai**: Rentang hari peminjaman.
* **Maksimal Durasi**: Pemesanan hanya diperbolehkan maksimal **14 hari** dalam satu kali pengajuan.
* **Pemesanan Hari Ini**: Anda diperbolehkan membuat reservasi sehari penuh mulai hari ini selama jam operasional gedung belum berakhir (belum melewati **18:30**). Jika jam buka (**07:00**) hari ini sudah lewat, maka pemesanan hari pertama akan dimulai dari waktu pengajuan saat itu juga, sedangkan hari-hari berikutnya dimulai normal sejak jam buka.

### Input Informasi Tambahan:
* **Tujuan**: Alasan atau jenis acara.
  - Untuk ruangan di **Lantai 3 & 14**, pilih tujuan dari dropdown yang tersedia (**Sidang, Rapat, Bimbingan, Ujian Sidang Tugas Akhir, Seminar, Lainnya**).
  - Khusus **Lantai 19**, ketik tujuan reservasi Anda secara manual pada kolom input teks yang disediakan.
* **Keterangan**: Catatan tambahan (misal: "Butuh proyektor dan colokan tambahan"). Pengisian keterangan ini bersifat **wajib** untuk semua lantai.

> [!IMPORTANT]
> **ATURAN KHUSUS LANTAI 19:**
> * **Aturan Waktu Pengajuan**: Reservasi untuk Ruang Rapat 19.07 harus diajukan **minimal H-2** (dua hari sebelum tanggal pelaksanaan). Peminjaman untuk hari ini atau esok hari tidak bisa dipilih.
> * **Alur Persetujuan**: Reservasi Ruang Rapat 19.07 berstatus **Pending** secara default dan memerlukan persetujuan manual oleh Admin 2 sebelum dapat digunakan.
> * **Tujuan & Keterangan**: Dosen wajib mengisi alasan (tujuan) secara manual dan mengisi keterangan secara lengkap (tidak boleh kosong).

> [!TIP]
> **Reservasi Ruangan di Lantai 3 dan 14:**
> Jika jadwal yang Anda ajukan kosong/tersedia, sistem akan langsung memberikan persetujuan otomatis (Status: **Disetujui**) sesaat setelah Anda mengirim pengajuan.

---

## 5. KALENDER JADWAL RUANGAN
1. Akses menu **Kalender** (`/calendar`).
2. Kalender interaktif ini menampilkan visualisasi seluruh peminjaman ruangan yang **sudah disetujui** oleh sistem maupun admin.
3. Anda dapat memantau jadwal guna mempermudah pemilihan slot kosong.

---

## 6. RIWAYAT PEMINJAMAN & FITUR PEMBATALAN
1. Akses menu **Riwayat Peminjaman** (`/history`).
2. Di halaman ini, Anda dapat memantau status semua reservasi yang pernah Anda ajukan:
   - <span style="color:#d97706">**Pending**</span>: Menunggu persetujuan admin (khusus Ruang Rapat 19.07).
   - <span style="color:#16a34a">**Disetujui**</span>: Reservasi sah dan ruangan siap digunakan pada jadwal tersebut.
   - <span style="color:#dc2626">**Ditolak**</span>: Pengajuan ditolak oleh admin dengan alasan penolakan yang dilampirkan.
   - <span style="color:#7c3aed">**Dibatalkan**</span>: Reservasi yang dibatalkan oleh admin (disertai alasan) atau dibatalkan secara mandiri oleh dosen sebelum waktu pelaksanaan dimulai.
   - <span style="color:#4b5563">**Selesai**</span>: Waktu reservasi telah berakhir.
3. **Pembatalan Mandiri oleh User**: 
   - Dosen/Pegawai dapat membatalkan reservasi aktif (berstatus **Pending** atau **Disetujui**) secara mandiri dengan mengeklik tombol **Batalkan** pada baris riwayat yang bersangkutan.
   - Pembatalan hanya bisa dilakukan sebelum waktu pelaksanaan peminjaman berakhir.
   - Khusus reservasi tipe sehari penuh (multi-day), membatalkan salah satu hari dalam rentang grup akan otomatis membatalkan seluruh hari lainnya dalam grup tersebut yang belum terlewati.
4. **Format Tanggal**: Format penulisan tanggal di riwayat telah disesuaikan secara lokal dengan menyebutkan nama hari di depannya (Contoh: **Senin, 13 Jul 2026**).

> [!NOTE]
> **Sistem Otomatisasi Status Selesai:**
> Ketika jam selesai peminjaman telah terlewati, sistem secara otomatis mengubah status reservasi tersebut menjadi **Selesai**. Setelah status berubah menjadi Selesai, tombol aksi seperti batal tidak akan tersedia lagi demi integritas data riwayat.

---

## 7. VALIDASI KEAMANAN & ATURAN SISTEM
Sistem SIRAT menerapkan beberapa validasi server-side berikut pada pengajuan Anda:
1. **Jam Operasional**: Peminjaman ruangan hanya diizinkan di antara jam **07:00 WIB s.d. 18:30 WIB**.
2. **Hari Operasional**: Pemesanan ruangan ditutup pada hari Minggu. Sistem memblokir reservasi biasa pada hari Minggu dan melewati hari Minggu secara otomatis pada reservasi sehari penuh.
3. **Urutan Waktu**: Waktu selesai peminjaman harus lebih besar daripada waktu mulai (Jam Mulai < Jam Selesai).
4. **Double Booking Protection**: Sistem akan memblokir pengajuan baru jika terdapat bentrok waktu dengan reservasi ruangan yang sama yang sudah berstatus **Approved** pada tanggal yang sama.
5. **Pembatasan Durasi Multi-Day**: Durasi reservasi sehari penuh (sehari penuh) dibatasi maksimal **14 hari** dalam sekali pengajuan.


