# DOKUMEN USER ACCEPTANCE TESTING (UAT)
## APLIKASI PINTU (Peminjaman Ruangan Telkom University Landmark Tower)

Dokumen ini digunakan untuk memandu proses pengujian penerimaan pengguna (*User Acceptance Testing*) untuk aplikasi **PINTU**. Pengujian ini bertujuan untuk memastikan semua fungsi sistem berjalan sesuai dengan kebutuhan bisnis dan spesifikasi teknis yang telah ditentukan.

---

### I. INFORMASI PENGUJIAN

| Parameter | Detail |
| :--- | :--- |
| **Nama Aplikasi** | PINTU (Peminjaman Ruangan TULT) |
| **Tipe Aplikasi** | Web Application (Laravel) |
| **Tanggal Pengujian** | .................................................... |
| **Nama Penguji (Tester)** | .................................................... |
| **Jabatan/Peran** | .................................................... |
| **Lingkungan Uji (Environment)** | Lokal (Laragon / php artisan serve) |
| **Peramban (Browser)** | Chrome / Edge / Firefox / Safari (core: Blink/Gecko/WebKit) |

---

### II. DATA AKUN UJI (TEST ACCOUNTS)

Berikut adalah data akun yang terdaftar pada sistem (berdasarkan *Database Seeder*) yang dapat digunakan selama proses pengujian:

| Peran (Role) | Email / Username | Password | Deskripsi / Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin TULT (Admin 1)** | `admin@telkomuniversity.ac.id` | `password` | Mengelola semua ruangan & reservasi di seluruh lantai, **kecuali Lantai 19**. |
| **Admin Lantai 19 (Admin 2)** | `admin19@telkomuniversity.ac.id` | `password` | Mengelola **hanya** ruangan & reservasi di **Lantai 19**. |
| **Dosen 1** | Email: `ahmad.fauzi@telkomuniversity.ac.id`<br>NIP: `123456` | *Tanpa Password* | Login menggunakan kombinasi NIP dan Email. |
| **Dosen 2** | Email: `siti.nurhaliza@telkomuniversity.ac.id`<br>NIP: `654321` | *Tanpa Password* | Login menggunakan kombinasi NIP dan Email. |

---

### III. MATRIKS SKENARIO PENGUJIAN

#### MODUL 1: AUTENTIKASI & OTORISASI

| ID Test | Skenario Pengujian | Langkah Pengujian | Data Masukan (Input) | Hasil yang Diharapkan (Expected Result) | Status (Lulus/Gagal) | Catatan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **UAT-1.1** | Login Dosen (Valid) | 1. Buka halaman utama (`/`) <br>2. Masukkan NIP & Email Dosen 1 yang valid <br>3. Klik tombol Login | NIP: `123456`<br>Email: `ahmad.fauzi@telkomuniversity.ac.id` | Pengguna berhasil masuk dan dialihkan ke dashboard dosen (`/dashboard`). | [ ] Lulus<br>[ ] Gagal | |
| **UAT-1.2** | Login Dosen (Tidak Valid/Data Salah) | 1. Buka halaman utama (`/`) <br>2. Masukkan NIP/Email yang salah <br>3. Klik tombol Login | NIP: `123456`<br>Email: `salah@telkomuniversity.ac.id` | Sistem menampilkan pesan kesalahan "NIP dan email tidak ditemukan atau tidak cocok." | [ ] Lulus<br>[ ] Gagal | |
| **UAT-1.3** | Login Admin TULT / Admin 1 (Valid) | 1. Buka halaman login admin (`/admin/login`) <br>2. Masukkan Email & Password Admin 1 <br>3. Klik tombol Login | Email: `admin@telkomuniversity.ac.id`<br>Password: `password` | Pengguna berhasil masuk dan dialihkan ke dashboard admin (`/admin`). | [ ] Lulus<br>[ ] Gagal | |
| **UAT-1.4** | Login Admin Lantai 19 / Admin 2 (Valid) | 1. Buka halaman login admin (`/admin/login`) <br>2. Masukkan Email & Password Admin 2 <br>3. Klik tombol Login | Email: `admin19@telkomuniversity.ac.id`<br>Password: `password` | Pengguna berhasil masuk dan dialihkan ke dashboard admin (`/admin`). | [ ] Lulus<br>[ ] Gagal | |
| **UAT-1.5** | Login Admin (Password Salah) | 1. Buka halaman login admin (`/admin/login`) <br>2. Masukkan Email valid, tapi Password salah <br>3. Klik tombol Login | Email: `admin@telkomuniversity.ac.id`<br>Password: `salahpass` | Sistem menampilkan pesan kesalahan "Email atau password salah." | [ ] Lulus<br>[ ] Gagal | |
| **UAT-1.6** | Logout Dosen | 1. Login sebagai Dosen <br>2. Klik tombol Logout pada menu navigasi | - | Sesi dosen berakhir, pengguna dialihkan kembali ke halaman login utama (`/`). | [ ] Lulus<br>[ ] Gagal | |
| **UAT-1.7** | Logout Admin | 1. Login sebagai Admin <br>2. Klik tombol Logout pada menu admin | - | Sesi admin berakhir, pengguna dialihkan kembali ke halaman login admin (`/admin/login`). | [ ] Lulus<br>[ ] Gagal | |

---

#### MODUL 2: PEMINJAMAN RUANGAN (SISI DOSEN)

| ID Test | Skenario Pengujian | Langkah Pengujian | Data Masukan (Input) | Hasil yang Diharapkan (Expected Result) | Status (Lulus/Gagal) | Catatan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **UAT-2.1** | Melihat Daftar Ruangan | 1. Login sebagai Dosen <br>2. Buka menu Ruangan (`/reservation`) | - | Sistem menampilkan daftar semua ruangan beserta status ketersediaannya. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-2.2** | Melihat Detail Ruangan | 1. Pada daftar ruangan, pilih salah satu ruangan <br>2. Klik "Detail" | - | Sistem menampilkan detail lengkap ruangan meliputi: Foto, Nama, Lantai, Kapasitas, Fasilitas, dan Deskripsi. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-2.3** | Reservasi Ruangan Biasa (Lantai selain 19) | 1. Buka detail ruangan non-Lantai 19 (misal: Lantai 3) <br>2. Isi form peminjaman <br>3. Klik "Ajukan Reservasi" | Tanggal: Besok<br>Jam Mulai: `09:00`<br>Jam Selesai: `11:00`<br>Tujuan: Rapat internal<br>Keterangan: Butuh 10 kursi tambahan | Reservasi berhasil diajukan, status reservasi langsung tersimpan dan pengguna diarahkan ke Riwayat Peminjaman (`/history`). | [ ] Lulus<br>[ ] Gagal | |
| **UAT-2.4** | Aturan Khusus Lantai 19: Reservasi Minimal H+2 | 1. Buka detail ruangan Lantai 19 <br>2. Isi form reservasi untuk tanggal Hari Ini atau Besok <br>3. Klik "Ajukan" | Tanggal: Hari ini / Besok<br>Jam Mulai: `10:00`<br>Jam Selesai: `12:00`<br>Tujuan: Rapat Direksi | Sistem menolak pengajuan. Muncul validasi yang menyatakan peminjaman lantai 19 harus diajukan minimal H+2. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-2.5** | Aturan Khusus Lantai 19: Tujuan Wajib & Keterangan Dihapus (Null) | 1. Buka detail ruangan Lantai 19 <br>2. Isi form reservasi untuk tanggal H+2 atau lebih <br>3. Isi field keterangan dengan teks <br>4. Klik "Ajukan" | Tanggal: H+2<br>Jam Mulai: `09:00`<br>Jam Selesai: `11:00`<br>Tujuan: Pertemuan Stakeholder<br>Keterangan: "Teks ini harus dihapus otomatis" | Pengajuan berhasil. Di database, nilai `tujuan` tersimpan sedangkan `keterangan` otomatis bernilai `null` (dikosongkan oleh sistem). Status reservasi adalah `pending` (butuh persetujuan). | [ ] Lulus<br>[ ] Gagal | |
| **UAT-2.6** | Pengajuan Bentrok pada Ruangan yang Sama | 1. Ajukan reservasi di Ruang X pada jam 09:00 - 11:00 (Reservasi 1 - telah disetujui/approved)<br>2. Coba ajukan reservasi di Ruang X pada tanggal yang sama jam 10:00 - 12:00 (Reservasi 2) | Ruangan: Ruang X<br>Jam: `10:00 - 12:00` | Sistem memvalidasi bentrok waktu dan menolak pemesanan kedua dengan pesan error. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-2.7** | Validasi Jam Operasional & Urutan Waktu | 1. Masukkan Jam Mulai/Selesai di luar jam operasional (08:00 - 17:00)<br>2. Masukkan Jam Selesai yang lebih awal dari Jam Mulai | Jam Mulai: `07:00` atau Jam Selesai: `13:00` (mulai: `14:00`) | Sistem menampilkan pesan kesalahan validasi waktu operasional / urutan waktu. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-2.8** | Melihat Kalender Reservasi | 1. Login sebagai Dosen<br>2. Klik menu Kalender (`/calendar`) | - | Menampilkan kalender interaktif yang memuat jadwal-jadwal peminjaman ruangan yang sudah disetujui. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-2.9** | Format Tanggal dengan Hari (Bahasa Indonesia) | 1. Masuk ke halaman Riwayat Peminjaman (`/history`) | - | Semua tanggal pelaksanaan peminjaman menampilkan nama hari di depannya (contoh: **Senin, 13 Jul 2026**). | [ ] Lulus<br>[ ] Gagal | |

---

#### MODUL 3: MANAJEMEN & PERSETUJUAN RESERVASI (SISI ADMIN)

| ID Test | Skenario Pengujian | Langkah Pengujian | Data Masukan (Input) | Hasil yang Diharapkan (Expected Result) | Status (Lulus/Gagal) | Catatan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **UAT-3.1** | Statistik Dashboard Admin | 1. Login sebagai Admin TULT (Admin 1) / Admin Lantai 19 (Admin 2) <br>2. Lihat halaman utama admin (`/admin`) | - | Dashboard menampilkan total ruangan, jumlah reservasi pending, dan reservasi disetujui yang **sesuai dengan wilayah otorisasi** admin tersebut. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-3.2** | Persetujuan Pengajuan (Approve) | 1. Buka daftar reservasi pending di Dashboard Admin <br>2. Klik tombol "Setujui" pada salah satu pengajuan | - | Status reservasi berubah menjadi **Disetujui (Approved)**. Pengaju menerima email notifikasi bahwa reservasi disetujui. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-3.3** | Penolakan Pengajuan (Reject) | 1. Klik tombol "Tolak" pada pengajuan pending <br>2. Masukkan alasan penolakan pada pop-up modal <br>3. Klik submit | Alasan Penolakan: "Ruangan akan digunakan untuk acara Rektorat" | Status reservasi berubah menjadi **Ditolak (Rejected)** dengan alasan penolakan terlampir. Pengaju menerima email notifikasi penolakan. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-3.4** | Pembatalan Reservasi Aktif (Cancel) | 1. Buka menu Reservasi (`/admin/reservations`) <br>2. Cari reservasi berstatus Approved <br>3. Klik tombol "Hapus" (Membatalkan) <br>4. Isi alasan pembatalan <br>5. Klik submit | Alasan Pembatalan: "Ada pemeliharaan listrik mendadak" | Status reservasi berubah menjadi **Dibatalkan (Cancelled)** dengan alasan pembatalan terlampir. Pengaju menerima email notifikasi pembatalan. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-3.5** | Pembatasan Wilayah Kerja: Admin TULT (Admin 1) | 1. Login sebagai Admin 1 (`admin@telkomuniversity.ac.id`) <br>2. Coba akses ruangan atau menyetujui reservasi yang berada di **Lantai 19** secara paksa (via API/URL direct) | ID Reservasi Lantai 19 | Sistem menolak akses dan memunculkan error **403 (Forbidden / Unauthorized)**. Reservasi Lantai 19 juga tidak muncul di dashboard Admin 1. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-3.6** | Pembatasan Wilayah Kerja: Admin Lantai 19 (Admin 2) | 1. Login sebagai Admin 2 (`admin19@telkomuniversity.ac.id`) <br>2. Coba akses ruangan atau menyetujui reservasi yang berada di **lantai selain Lantai 19** secara paksa (via API/URL direct) | ID Reservasi Lantai 3 | Sistem menolak akses dan memunculkan error **403 (Forbidden / Unauthorized)**. Reservasi non-Lantai 19 tidak muncul di dashboard Admin 2. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-3.7** | Pencegahan Menyetujui Reservasi Bentrok | 1. Terdapat Reservasi A (Approved, jam 08:00 - 10:00) dan Reservasi B (Pending, jam 09:00 - 11:00) pada ruangan yang sama. <br>2. Admin mencoba mengklik "Setujui" pada Reservasi B. | - | Sistem menolak persetujuan Reservasi B dan menampilkan pesan error bahwa jadwal tersebut bentrok dengan reservasi yang sudah disetujui. Status Reservasi B tetap pending. | [ ] Lulus<br>[ ] Gagal | |

---

#### MODUL 4: MANAJEMEN RUANGAN (SISI ADMIN)

| ID Test | Skenario Pengujian | Langkah Pengujian | Data Masukan (Input) | Hasil yang Diharapkan (Expected Result) | Status (Lulus/Gagal) | Catatan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **UAT-4.1** | Menambah Ruangan Baru | 1. Login sebagai Admin <br>2. Buka menu Ruangan (`/admin/rooms`) <br>3. Klik "Tambah Ruangan" <br>4. Isi form dan submit | Nama: "Ruang Rapat 301"<br>Jenis: "Ruang Rapat"<br>Lantai: "3"<br>Kapasitas: 15<br>Fasilitas: AC, Proyektor, WiFi<br>Deskripsi: Ruang rapat sedang. | Ruangan baru berhasil ditambahkan dan tampil pada daftar ruangan (sesuai filter lantai admin). | [ ] Lulus<br>[ ] Gagal | |
| **UAT-4.2** | Mengubah Data Ruangan | 1. Pilih ruangan dari daftar <br>2. Klik tombol "Edit" <br>3. Ubah beberapa informasi <br>4. Klik Simpan | Kapasitas diubah menjadi: 25 | Data ruangan berhasil diperbarui dan perubahannya tersimpan di sistem. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-4.3** | Mengunggah Foto Ruangan | 1. Pada detail ruangan di menu admin, cari section Foto <br>2. Klik "Upload Foto" <br>3. Pilih file gambar (*.png, *.jpg) dan submit | File Gambar valid | Gambar berhasil diunggah, disimpan di storage, dan ditampilkan sebagai galeri foto ruangan. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-4.4** | Menghapus Foto Ruangan | 1. Klik ikon hapus (tempat sampah/cross) pada salah satu foto ruangan | - | Foto berhasil dihapus dari storage dan dari daftar foto ruangan. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-4.5** | Menghapus Data Ruangan | 1. Klik tombol "Hapus" pada salah satu ruangan dari daftar | - | Ruangan berhasil dihapus dari sistem (soft delete atau hard delete sesuai konfigurasi). | [ ] Lulus<br>[ ] Gagal | |

---

#### MODUL 5: MANAJEMEN DOSEN (SISI ADMIN)

| ID Test | Skenario Pengujian | Langkah Pengujian | Data Masukan (Input) | Hasil yang Diharapkan (Expected Result) | Status (Lulus/Gagal) | Catatan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **UAT-5.1** | Menambahkan Akun Dosen Baru | 1. Login sebagai Admin <br>2. Buka menu Kelola Dosen (`/admin/users`) <br>3. Klik "Tambah Dosen" <br>4. Isi data dan submit | Nama: "Prof. Budi Utomo"<br>Email: `budi.utomo@telkomuniversity.ac.id`<br>NIP: `889900`<br>No. Telp: `081233445566` | Dosen baru berhasil didaftarkan dan dapat digunakan untuk masuk ke sisi dosen menggunakan NIP & Email tersebut. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-5.2** | Mengubah Data Dosen | 1. Klik tombol "Edit" pada salah satu dosen <br>2. Ubah data dosen <br>3. Klik Simpan | No. Telp diubah: `081122334455` | Data dosen berhasil diperbarui di database. | [ ] Lulus<br>[ ] Gagal | |
| **UAT-5.3** | Menghapus Akun Dosen | 1. Klik tombol "Hapus" pada salah satu dosen dari tabel | - | Akun dosen dihapus dari sistem, sehingga tidak bisa login lagi. | [ ] Lulus<br>[ ] Gagal | |

---

#### MODUL 6: OTOMATISASI & FITUR PENDUKUNG

| ID Test | Skenario Pengujian | Langkah Pengujian | Data Masukan (Input) | Hasil yang Diharapkan (Expected Result) | Status (Lulus/Gagal) | Catatan |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **UAT-6.1** | Perubahan Status Otomatis ke "Selesai" | 1. Terdapat reservasi berstatus Approved yang waktu selesainya sudah terlewati (misal: reservasi kemarin). <br>2. Login sebagai Dosen (lihat `/history`) atau Admin (lihat `/admin/reservations`). | - | Reservasi tersebut secara otomatis berlabel/berstatus **Selesai** (warna abu-abu) tanpa perlu diubah manual. Tombol aksi (Batal/Hapus) disembunyikan/tidak aktif. | [ ] Lulus<br>[ ] Gagal | |

---

### IV. KESIMPULAN & TANDA TANGAN PERSETUJUAN (SIGN-OFF)

Berdasarkan hasil pengujian di atas, diambil keputusan sebagai berikut:

*   [ ] **Diterima Penuh**: Aplikasi berjalan sesuai dengan seluruh kriteria penerimaan tanpa kendala kritis.
*   [ ] **Diterima dengan Catatan**: Aplikasi diterima, namun dengan beberapa perbaikan minor yang dicatat di kolom catatan.
*   [ ] **Ditolak**: Terdapat kendala kritis yang harus diperbaiki terlebih dahulu sebelum dideploy ke lingkungan produksi.

**Catatan Tambahan:**
..................................................................................................................................................................................................
..................................................................................................................................................................................................
..................................................................................................................................................................................................

#### PIHAK-PIHAK YANG MENYETUJUI:

| **Perwakilan Penguji (Tester)** | **Perwakilan Pengembang (Developer)** | **Pemilik Proyek (Project Owner)** |
| :---: | :---: | :---: |
| | | |
| | | |
| Nama: .................................... | Nama: .................................... | Nama: .................................... |
| Tgl: ........................................ | Tgl: ........................................ | Tgl: ........................................ |
