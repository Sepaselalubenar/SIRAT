# PANDUAN PENGGUNA APLIKASI PINTU
### (Peminjaman Ruangan Telkom University Landmark Tower - TULT)

Aplikasi **PINTU** adalah platform berbasis web yang digunakan untuk mengelola dan memfasilitasi peminjaman serta reservasi ruangan di gedung **Telkom University Landmark Tower (TULT)**. Sistem ini memiliki alur kerja otomatisasi persetujuan, pemisahan hak akses administrator per lantai, serta validasi jadwal untuk menghindari bentrok penggunaan ruangan.

---

## DAFTAR ISI
1. [Peran Pengguna (User Roles)](#1-peran-pengguna-user-roles)
2. [Panduan Pengguna - Sisi Dosen (Peminjam)](#2-panduan-pengguna---sisi-dosen-peminjam)
   - [Cara Login Dosen](#a-cara-login-dosen)
   - [Melihat Daftar & Detail Ruangan](#b-melihat-daftar--detail-ruangan)
   - [Prosedur Pengajuan Reservasi](#c-prosedur-pengajuan-reservasi)
   - [Kalender Jadwal Ruangan](#d-kalender-jadwal-ruangan)
   - [Riwayat Peminjaman & Otomatisasi Selesai](#e-riwayat-peminjaman--otomatisasi-selesai)
3. [Panduan Pengguna - Sisi Administrator (Pengelola)](#3-panduan-pengguna---sisi-administrator-pengelola)
   - [Cara Login Admin](#a-cara-login-admin)
   - [Dashboard Statistik & Pembagian Wilayah](#b-dashboard-statistik--pembagian-wilayah)
   - [Proses Persetujuan Pengajuan (Persetujuan & Penolakan)](#c-proses-persetujuan-pengajuan-persetujuan--penolakan)
   - [Membatalkan Reservasi Aktif](#d-membatalkan-reservasi-aktif)
   - [Kelola Data Ruangan (CRUD & Foto)](#e-kelola-data-ruangan-crud--foto)
   - [Kelola Data Akun Dosen](#f-kelola-data-akun-dosen)
4. [Validasi Keamanan & Aturan Sistem](#4-validasi-keamanan-&-aturan-sistem)
5. [Referensi Akun Demo / Uji](#5-referensi-akun-demo--uji)

---

## 1. PERAN PENGGUNA (USER ROLES)

Aplikasi PINTU membagi pengguna menjadi tiga kelompok otoritas utama:

| Peran (Role) | Ruang Lingkup Otoritas | Fitur Utama |
| :--- | :--- | :--- |
| **Dosen (Lecturer)** | Seluruh lantai di TULT | Melihat jadwal kalender, melihat ketersediaan ruangan, melakukan pengajuan reservasi, dan melihat riwayat pribadinya. |
| **Admin TULT (Admin 1)** | Lantai selain Lantai 19 | Mengelola data ruangan, memproses persetujuan reservasi, membatalkan reservasi, serta mengelola data dosen di lantai 1 s.d. 18 & 20 s.d. 22. |
| **Admin Lantai 19 (Admin 2)** | Khusus Lantai 19 | Memiliki hak penuh yang sama dengan Admin TULT, namun **hanya** untuk ruangan dan reservasi yang berada di Lantai 19. |

---

## 2. PANDUAN PENGGUNA - SISI DOSEN (PEMINJAM)

### A. Cara Login Dosen
Untuk masuk ke sistem PINTU sebagai Dosen:
1. Akses halaman utama aplikasi di URL: `/` (contoh: `http://localhost/` atau `http://127.0.0.1:8000/`).
2. Masukkan **NIP** dan **Email** resmi yang telah didaftarkan oleh Administrator.
3. Klik tombol **Login**.
4. Sistem akan mencocokkan NIP & Email Anda. Jika sesuai, Anda akan diarahkan ke halaman **Dashboard Dosen**.

> [!NOTE]
> Akun Dosen tidak menggunakan kata sandi (password). Keamanan login didasarkan pada kecocokan pasangan NIP dan Email unik yang telah terdaftar di pangkalan data.

---

### B. Melihat Daftar & Detail Ruangan
1. Masuk ke menu **Daftar Ruangan** (`/reservation`).
2. Halaman ini akan menampilkan seluruh daftar ruangan di TULT lengkap dengan status ketersediaannya (Tersedia / Sedang Dipakai).
3. Anda dapat menyaring ruangan berdasarkan kapasitas atau kata kunci.
4. Klik tombol **Detail** pada salah satu ruangan untuk melihat informasi terperinci berupa:
   - Galeri foto ruangan.
   - Nama ruangan, nomor lantai, kapasitas tempat duduk.
   - Fasilitas penunjang (seperti AC, Proyektor, TV, WiFi, Whiteboard).
   - Deskripsi ruangan.

---

### C. Prosedur Pengajuan Reservasi

Formulir peminjaman terletak di halaman detail masing-masing ruangan. Isi formulir dengan data berikut:
* **Tanggal Pelaksanaan**: Hari peminjaman ruangan.
* **Jam Mulai & Jam Selesai**: Rentang waktu peminjaman (Wajib berada dalam Jam Operasional: **08:00 - 17:00**).
* **Tujuan**: Alasan atau jenis acara (misal: "Ujian Sidang Akhir", "Rapat Koordinasi").
* **Keterangan**: Catatan tambahan (misal: "Butuh tambahan 5 kursi").

> [!IMPORTANT]
> **ATURAN KHUSUS LANTAI 19 (Lantai VIP/Rektorat):**
> * **Aturan Waktu Pengajuan**: Reservasi untuk ruangan di Lantai 19 harus diajukan **minimal H+2** (dua hari sebelum tanggal pelaksanaan). Peminjaman untuk hari ini atau esok hari akan otomatis ditolak oleh sistem.
> * **Alur Persetujuan**: Reservasi Lantai 19 berstatus **Pending** secara default dan memerlukan persetujuan manual oleh Admin Lantai 19 sebelum dapat digunakan.
> * **Kerahasiaan/Privasi**: Field **Keterangan** pada pengajuan Lantai 19 otomatis diabaikan dan disimpan sebagai `null` (kosong) oleh sistem. Field **Tujuan** tetap wajib diisi.

> [!TIP]
> **Reservasi di Lantai Selain 19 (Lantai Biasa):**
> Jika jadwal yang Anda ajukan kosong/tersedia, sistem akan langsung memberikan persetujuan otomatis (Status: **Disetujui / Approved**) sesaat setelah Anda mengklik tombol kirim.

---

### D. Kalender Jadwal Ruangan
1. Akses menu **Kalender** (`/calendar`).
2. Kalender interaktif ini menampilkan visualisasi seluruh peminjaman ruangan yang **sudah disetujui (Approved)** oleh sistem maupun admin.
3. Anda dapat mengganti tampilan secara bulanan, mingguan, atau harian untuk memantau jadwal yang padat guna mempermudah pemilihan slot kosong.

---

### E. Riwayat Peminjaman & Otomatisasi Selesai
1. Akses menu **Riwayat Peminjaman** (`/history`).
2. Di halaman ini, Anda dapat memantau status semua reservasi yang pernah Anda ajukan:
   - <span style="color:#d97706">**Pending**</span>: Menunggu persetujuan admin (khusus Lantai 19).
   - <span style="color:#16a34a">**Disetujui (Approved)**</span>: Reservasi sah dan ruangan siap digunakan pada jadwal tersebut.
   - <span style="color:#dc2626">**Ditolak (Rejected)**</span>: Pengajuan ditolak oleh admin dengan alasan penolakan yang dilampirkan.
   - <span style="color:#7c3aed">**Dibatalkan (Cancelled)**</span>: Reservasi yang sempat disetujui namun kemudian dibatalkan oleh admin karena kondisi darurat.
   - <span style="color:#4b5563">**Selesai (Completed)**</span>: Waktu reservasi telah berakhir.
3. **Format Tanggal**: Format penulisan tanggal di riwayat telah disesuaikan secara lokal dengan menyebutkan nama hari di depannya (Contoh: **Senin, 13 Jul 2026**).

> [!NOTE]
> **Sistem Otomatisasi Status Selesai:**
> Ketika jam selesai peminjaman telah terlewati, sistem secara otomatis mengubah status reservasi tersebut menjadi **Selesai** (*Completed*). Setelah status berubah menjadi Selesai, tombol aksi seperti batal/hapus tidak akan tersedia lagi demi integritas data riwayat.

---

## 3. PANDUAN PENGGUNA - SISI ADMINISTRATOR (PENGELOLA)

### A. Cara Login Admin
1. Akses halaman login khusus administrator di URL: `/admin/login`.
2. Masukkan **Email** administrator dan **Password** Anda.
3. Klik tombol **Login** untuk masuk ke **Dashboard Admin**.

---

### B. Dashboard Statistik & Pembagian Wilayah
Dashboard administrator menyajikan visualisasi data yang dibatasi berdasarkan wilayah kerja masing-masing akun:
* **Admin TULT (Admin 1)**: Hanya melihat data statistik total ruangan, total dosen, jumlah pengajuan pending, dan reservasi aktif untuk lantai 1 s.d. 18 dan 20 s.d. 22.
* **Admin Lantai 19 (Admin 2)**: Hanya melihat statistik dan aktivitas khusus ruangan yang berlokasi di Lantai 19.

> [!WARNING]
> Sistem memiliki fitur pembatasan wilayah kerja yang ketat. Jika Admin 1 mencoba membuka URL detail ruangan/reservasi Lantai 19 secara paksa (atau sebaliknya), sistem akan memblokir tindakan tersebut dan menampilkan pesan kesalahan **403 Forbidden (Unauthorized)**.

---

### C. Proses Persetujuan Pengajuan (Persetujuan & Penolakan)
Jika terdapat pengajuan reservasi baru yang memerlukan persetujuan manual (status Pending), administrator dapat memprosesnya melalui halaman utama dashboard:

#### 1. Menyetujui Reservasi (Approve):
1. Cari pengajuan berstatus **Pending** di tabel dashboard.
2. Klik tombol **Setujui** (Approve).
3. Status reservasi akan berubah menjadi **Disetujui** dan pengaju (dosen) akan mendapatkan notifikasi email otomatis.

> [!IMPORTANT]
> **Pencegahan Bentrok Otomatis:**
> Sistem secara ketat mendeteksi adanya bentrok jadwal. Apabila administrator mencoba menyetujui reservasi "B" yang jadwalnya tumpang tindih dengan reservasi "A" (yang sudah disetujui sebelumnya) pada ruangan yang sama, sistem akan menolak persetujuan tersebut dan memunculkan pesan peringatan bentrok. Status reservasi "B" akan tetap tertahan pada status Pending.

#### 2. Menolak Reservasi (Reject):
1. Klik tombol **Tolak** (Reject) pada baris pengajuan pending.
2. Sebuah jendela pop-up modal akan muncul, meminta Anda memasukkan **Alasan Penolakan**.
3. Tulis alasan penolakan secara jelas (misal: "Ruangan sedang digunakan untuk rapat rektorat").
4. Klik **Submit**. Status reservasi berubah menjadi **Ditolak** dan alasan tersebut dikirimkan ke email pengaju.

---

### D. Membatalkan Reservasi Aktif
Apabila terdapat keperluan mendadak/kondisi darurat di mana ruangan yang sudah disetujui terpaksa harus digunakan untuk acara universitas lainnya:
1. Masuk ke menu **Daftar Reservasi** (`/admin/reservations`).
2. Cari data peminjaman yang berstatus **Approved** (Disetujui).
3. Klik tombol **Hapus / Batal**.
4. Masukkan **Alasan Pembatalan** pada jendela yang muncul.
5. Klik simpan. Status akan berubah menjadi **Dibatalkan (Cancelled)** dan sistem otomatis melayangkan email pemberitahuan pembatalan ke dosen terkait.

---

### E. Kelola Data Ruangan (CRUD & Foto)
Menu ini dapat diakses melalui **Kelola Ruangan** (`/admin/rooms`). Fitur-fitur yang tersedia:
1. **Tambah Ruangan**: Masukkan nama ruangan, jenis (Ruang Sidang / Ruang Meeting), nomor lantai, kapasitas orang, centang fasilitas yang tersedia, serta tulis deskripsi singkat.
2. **Edit Ruangan**: Mengubah informasi detail ruangan yang sudah ada (misalnya mengganti kapasitas tempat duduk atau memperbarui fasilitas).
3. **Upload Foto Ruangan**: 
   - Klik menu detail/foto ruangan.
   - Unggah file gambar format `.png`, `.jpg`, atau `.jpeg`. Gambar ini akan tampil pada galeri detail ruangan di sisi dosen.
4. **Hapus Foto**: Administrator dapat menghapus foto-foto ruangan lama yang sudah tidak relevan.
5. **Hapus Ruangan**: Menghapus data ruangan dari sistem.

---

### F. Kelola Data Akun Dosen
Akses menu **Kelola Dosen** (`/admin/users`) untuk mengelola akses masuk bagi para dosen:
1. **Tambah Dosen Baru**: Masukkan data lengkap berupa Nama Lengkap (beserta gelar jika perlu), Email aktif Telkom University, Nomor Induk Pegawai (NIP), dan Nomor Telepon.
2. **Edit Data Dosen**: Memperbarui informasi dosen seperti perubahan nomor telepon atau perbaikan e-mail.
3. **Hapus Dosen**: Menghapus akun dosen dari pangkalan data sistem. Dosen yang dihapus tidak akan dapat masuk kembali ke aplikasi PINTU.

---

## 4. VALIDASI KEAMANAN & ATURAN SISTEM

Sistem PINTU dirancang tangguh dengan mengandalkan validasi di sisi server (*server-side validation*) untuk memastikan aturan bisnis berikut dipatuhi:

1. **Jam Operasional**: Peminjaman ruangan hanya diizinkan di antara jam **08:00 WIB s.d. 17:00 WIB**.
2. **Urutan Waktu**: Waktu selesai peminjaman harus lebih besar daripada waktu mulai (Jam Mulai < Jam Selesai).
3. **Double Booking Protection**: Sistem akan memblokir pengajuan baru jika terdapat irisan waktu dengan reservasi ruangan sejenis yang sudah berstatus **Approved** pada tanggal yang sama.
4. **Pembatasan Hak Akses Admin**: 
   - Admin 1 (Admin TULT) tidak dapat mengelola, melihat, menyetujui, atau menghapus aset di Lantai 19.
   - Admin 2 (Admin Lantai 19) tidak dapat mengelola, melihat, menyetujui, atau menghapus aset di luar Lantai 19.
5. **Aturan H+2 Lantai 19**: Pengajuan reservasi di Lantai 19 di luar ketentuan H+2 dari hari pengajuan akan digagalkan langsung oleh validasi sistem.

---

## 5. REFERENSI AKUN DEMO / UJI

Gunakan akun berikut untuk melakukan pengujian fungsionalitas sistem sesuai skenario UAT:

### A. Akun Administrator
| Nama Pengguna | Email | Kata Sandi | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Admin TULT** (Admin 1) | `admin@telkomuniversity.ac.id` | `password` | Mengelola Lantai 1 s.d. 18 & Lantai 20 s.d. 22 |
| **Admin Lantai 19** (Admin 2) | `admin19@telkomuniversity.ac.id` | `password` | Mengelola khusus Lantai 19 |

### B. Akun Dosen (Peminjam)
*Login tanpa password, cukup masukkan pasangan email dan NIP berikut:*

* **Dosen 1:**
  - Email: `ahmad.fauzi@telkomuniversity.ac.id`
  - NIP: `123456`
* **Dosen 2:**
  - Email: `siti.nurhaliza@telkomuniversity.ac.id`
  - NIP: `654321`
