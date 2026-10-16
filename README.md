# SuaraKita

**SuaraKita** adalah aplikasi web untuk menyampaikan, mengelola, dan memantau pengaduan di lingkungan institusi pendidikan. Aplikasi menyediakan portal untuk siswa dan admin, alur tindak lanjut pengaduan, serta asisten AI untuk pertanyaan seputar penggunaan platform.

> **Status proyek:** aplikasi PHP dengan skema MariaDB/MySQL dan konfigurasi Docker Compose untuk pengembangan lokal. Belum ada dokumentasi pengujian otomatis atau pipeline CI di repositori ini.

## Daftar Isi

- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Persyaratan](#persyaratan)
- [Menjalankan secara lokal dengan Docker](#menjalankan-secara-lokal-dengan-docker)
- [Menjalankan tanpa Docker](#menjalankan-tanpa-docker)
- [Konfigurasi](#konfigurasi)
- [Struktur Proyek](#struktur-proyek)
- [Database dan Migrasi](#database-dan-migrasi)
- [Asisten AI](#asisten-ai)
- [Deployment dan Keamanan](#deployment-dan-keamanan)
- [Pemecahan Masalah](#pemecahan-masalah)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)

## Fitur

- **Portal siswa:** membuat pengaduan, melihat riwayat dan status, berkomunikasi melalui tanggapan, mengelola profil, dan memberikan rating.
- **Pengelolaan admin:** meninjau pengaduan, memperbarui status, membalas laporan, mengelola data siswa, serta mengekspor atau mencetak rekap.
- **Data pengaduan:** kategori, tingkat urgensi, lampiran, pilihan pengiriman anonim, status penanganan, dan ulasan.
- **Notifikasi:** dukungan notifikasi dalam aplikasi dan integrasi gateway yang dikonfigurasi server.
- **Asisten SuaraKita:** widget tanya jawab di portal dan endpoint API; dokumentasi endpoint tersedia di [`api/chat_ai.md`](./api/chat_ai.md).

Fitur anonim adalah pilihan pada laporan, bukan jaminan anonimitas absolut. Tinjau akses aplikasi, database, log, dan kebijakan institusi sebelum menggunakannya untuk laporan sensitif.

## Teknologi

- PHP 8.2 dan Apache (konfigurasi Docker).
- MariaDB 10.11 pada Docker Compose; skema juga kompatibel dengan MySQL/MariaDB.
- MySQLi untuk koneksi aplikasi ke database.
- HTML, JavaScript, dan Tailwind CSS untuk antarmuka.
- Docker dan Docker Compose untuk menjalankan layanan lokal.

Proyek ini tidak memiliki `composer.json` atau `package.json` di root; dependency aplikasi saat ini tidak dipasang melalui Composer atau npm.

## Persyaratan

Untuk menjalankan menggunakan Docker:

- Docker Engine atau Docker Desktop.
- Plugin Docker Compose v2 (`docker compose`).

Untuk menjalankan langsung di mesin:

- PHP 8.2.
- Ekstensi PHP: `mysqli`, `curl`, `mbstring`, dan `fileinfo`.
- MySQL atau MariaDB.

Pastikan ekstensi PHP yang dibutuhkan tersedia di lingkungan runtime. Dockerfile saat ini memasang `mysqli` dan `pdo_mysql`; endpoint AI juga menggunakan cURL dan fungsi multibyte.

## Menjalankan secara lokal dengan Docker

Dari direktori root repositori, jalankan:

```bash
docker compose up --build
```

Setelah container aktif, buka [http://localhost:8080](http://localhost:8080). Docker Compose akan:

1. Menjalankan MariaDB pada port `3306`.
2. Membuat database `suarakita` dan mengimpor [`database/suarakita.sql`](./database/suarakita.sql) saat volume database pertama kali dibuat.
3. Menjalankan aplikasi PHP dengan Apache pada port `8080`.

Untuk menghentikan layanan, tekan `Ctrl+C` lalu jalankan:

```bash
docker compose down
```

Data database disimpan pada volume Docker `db_data` dan tetap ada setelah container dihentikan. Inisialisasi SQL tidak dijalankan ulang pada volume yang sudah berisi database.

> Konfigurasi Compose saat ini menggunakan password root database kosong untuk kemudahan lokal. Jangan gunakan konfigurasi tersebut untuk lingkungan bersama atau produksi.

## Menjalankan tanpa Docker

1. Siapkan PHP 8.2 dengan ekstensi yang tercantum pada [Persyaratan](#persyaratan), serta server MySQL/MariaDB.
2. Buat database, lalu impor skema dan data awal:

   ```bash
   mysql -u root -p -e "CREATE DATABASE suarakita CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
   mysql -u root -p suarakita < database/suarakita.sql
   ```

3. Atur variabel database sesuai lingkungan. Contoh di PowerShell:

   ```powershell
   $env:DB_HOST = "127.0.0.1"
   $env:DB_USER = "root"
   $env:DB_PASS = "password-database-anda"
   $env:DB_NAME = "suarakita"
   ```

4. Jalankan server pengembangan PHP dari root proyek:

   ```bash
   php -S 127.0.0.1:8000
   ```

5. Buka [http://127.0.0.1:8000](http://127.0.0.1:8000).

Server bawaan PHP hanya ditujukan untuk pengembangan lokal, bukan untuk menerima trafik produksi.

## Konfigurasi

Aplikasi membaca pengaturan database dari environment variable berikut:

| Variabel | Kegunaan | Nilai default lokal |
| --- | --- | --- |
| `DB_HOST` | Host database | `127.0.0.1` |
| `DB_PORT` | Port database | `3306` pada konfigurasi Docker; koneksi PHP saat ini tidak membaca variabel ini |
| `DB_USER` | Username database | `root` |
| `DB_PASS` | Password database | Kosong |
| `DB_NAME` | Nama database | `suarakita` |
| `FONNTE_TOKEN` | Token gateway notifikasi | Tidak disarankan menggunakan nilai fallback yang ada di source code; tetapkan token baru melalui environment |
| `GEMINI_API_KEY` | API key Google Gemini untuk asisten AI | Tidak diatur |
| `OPENAI_API_KEY` | API key OpenAI sebagai penyedia alternatif | Tidak diatur |

Tetapkan rahasia melalui konfigurasi environment pada mesin atau platform deployment. Jangan menyimpan token/API key di frontend, dokumentasi, atau repositori.

## Struktur Proyek

```text
.
├── admin/                 # Halaman dan aksi portal admin
├── aksi/                  # Handler autentikasi dan pengelolaan data
├── api/                   # Endpoint API, termasuk asisten AI
├── assets/                # CSS, JavaScript, gambar, dan unggahan
├── auth/                  # Halaman login dan logout
├── database/              # Dump SQL dan skrip migrasi
├── includes/              # Helper database, autentikasi, notifikasi, dan upload
├── partials/              # Komponen antarmuka bersama
├── siswa/                 # Halaman portal siswa
├── config.php             # Konfigurasi koneksi database/integrasi
├── docker-compose.yml     # Konfigurasi layanan lokal
└── Dockerfile             # Image PHP Apache
```

## Database dan Migrasi

Dump database utama tersedia di [`database/suarakita.sql`](./database/suarakita.sql). Skrip tambahan dalam folder `database/` mencakup perubahan kategori, status, urgensi, lampiran, rating, notifikasi, audit/SLA, dan akun.

Sebelum menjalankan skrip migrasi:

1. Buat backup database.
2. Tinjau isi skrip dan pastikan sesuai dengan versi skema saat ini.
3. Jalankan setiap migrasi pada database yang benar.
4. Verifikasi aplikasi setelah migrasi berhasil.

Dump SQL menyertakan data seed. Tinjau dan ganti atau hapus seluruh data contoh sebelum menggunakan database untuk institusi atau lingkungan produksi. Jangan mengandalkan akun yang berasal dari data seed.

## Asisten AI

Asisten menerima pertanyaan melalui `POST /api/chat_ai.php`. Jika dikonfigurasi, aplikasi mencoba Gemini terlebih dahulu, lalu OpenAI; jika tidak ada jawaban dari penyedia tersebut, endpoint menggunakan respons fallback lokal.

Untuk format request/response, konfigurasi penyedia, dan cakupan jawaban, lihat [`api/chat_ai.md`](./api/chat_ai.md). API key bersifat opsional untuk menjalankan aplikasi; tanpa API key, fallback lokal tetap digunakan.

## Deployment dan Keamanan

Sebelum deployment:

- Ganti kredensial database lokal dengan kredensial khusus aplikasi yang memiliki hak minimum.
- **Audit dan rotasi rahasia integrasi yang ada di source code.** `config.php` memiliki nilai fallback untuk integrasi gateway; jangan menerbitkan atau menggunakan fallback tersebut. Tetapkan rahasia baru di environment deployment.
- Hapus data seed dan buat akun admin baru dengan kata sandi kuat.
- Pastikan kata sandi akun yang digunakan di produksi disimpan sebagai hash. Tinjau dan migrasikan akun lama sebelum membuka layanan.
- Pastikan direktori `assets/uploads/` dapat ditulis oleh proses web, tetapi jangan memberi izin tulis yang lebih luas dari yang dibutuhkan.
- Gunakan HTTPS, backup database, dan penyimpanan persisten untuk database serta file unggahan.
- Batasi akses ke database, file konfigurasi, log, dan halaman administrasi.
- Tinjau kebijakan retensi dan akses untuk laporan anonim serta informasi pribadi.

Konfigurasi Docker Compose dan server PHP bawaan ditujukan untuk pengembangan. [`render.yaml`](./render.yaml) menyediakan konfigurasi deployment Render, tetapi lingkungan produksi tetap perlu dikonfigurasi secara aman, termasuk rahasia, penyimpanan persisten, database, serta ekstensi PHP yang diperlukan.

## Pemecahan Masalah

| Gejala | Pemeriksaan |
| --- | --- |
| Koneksi database gagal | Pastikan service database aktif dan `DB_HOST`, `DB_USER`, `DB_PASS`, serta `DB_NAME` sesuai. |
| Aplikasi tidak dapat menulis unggahan | Pastikan `assets/uploads/` tersedia dan dapat ditulis oleh user proses PHP/Apache. |
| Asisten AI selalu memakai jawaban fallback | Periksa API key di environment server dan pastikan server dapat melakukan koneksi HTTPS keluar. |
| Perubahan SQL tidak terlihat setelah `docker compose up` | Skrip init hanya berjalan ketika volume database dibuat pertama kali. Untuk mempertahankan data, jalankan migrasi yang sesuai secara terpisah. |
| Fungsi AI atau upload menghasilkan error PHP | Pastikan ekstensi `curl`, `mbstring`, dan `fileinfo` aktif pada PHP runtime. |

## Kontribusi

Kontribusi dan perbaikan dapat diajukan melalui pull request. Sebelum mengajukan perubahan:

1. Jelaskan masalah atau perubahan perilaku yang dituju.
2. Jaga agar perubahan tetap terfokus dan konsisten dengan struktur proyek.
3. Uji alur terkait pada lingkungan lokal.
4. Jangan menyertakan kredensial, data pribadi, unggahan pengguna, atau dump database produksi.

## Lisensi

Belum ada file lisensi yang disertakan dalam repositori ini. Hak penggunaan dan distribusi kode belum dinyatakan; tambahkan lisensi yang sesuai sebelum mendistribusikan atau menggunakan proyek di luar konteks yang diizinkan pemiliknya.
