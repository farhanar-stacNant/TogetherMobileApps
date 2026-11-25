# Together — Real People, Real Moments

![Platform](https://img.shields.io/badge/Mobile-Flutter%20%2F%20Dart-02569B?logo=flutter&logoColor=white)
![Backend](https://img.shields.io/badge/Backend-Laravel%20REST%20API-FF2D20?logo=laravel&logoColor=white)
![Auth](https://img.shields.io/badge/Auth-Laravel%20Sanctum-14171A)
![Database](https://img.shields.io/badge/Database-MySQL%20%2F%20SQLite-4479A1)

**Together** (*"REAL PEOPLE, REAL MOMENTS"*) adalah aplikasi mobile berbasis komunitas dan aktivitas lokal yang dirancang untuk membantu pengguna menemukan teman baru di sekitar, menjelajahi momen atau kegiatan bersama (seperti *hiking*, olahraga, dan diskusi komunitas), bergabung ke acara secara langsung, serta berkomunikasi melalui fitur obrolan personal maupun grup.

Proyek ini dibangun dengan arsitektur *client-server* yang memisahkan antarmuka mobile (**Flutter/Dart**) dan layanan backend (**Laravel REST API + Sanctum**).

---

## Daftar Isi

1. [Gambaran Arsitektur & Struktur Proyek](#1-gambaran-arsitektur--struktur-proyek)
2. [Daftar Menu & Kegunaannya](#2-daftar-menu--kegunaannya)
3. [Prasyarat Sistem](#3-prasyarat-sistem)
4. [Cara Instalasi & Menjalankan Backend (Laravel)](#4-cara-instalasi--menjalankan-backend-laravel)
5. [Cara Instalasi & Menjalankan Mobile App (Flutter)](#5-cara-instalasi--menjalankan-mobile-app-flutter)
6. [Konfigurasi Akses Perangkat Lain / Jaringan Publik](#6-konfigurasi-akses-perangkat-lain--jaringan-publik)
7. [Daftar Endpoint API Utama](#7-daftar-endpoint-api-utama)
8. [Pemecahan Masalah Umum (Troubleshooting)](#8-pemecahan-masalah-umum-troubleshooting)

---

## 1. Gambaran Arsitektur & Struktur Proyek

```text
together-app/
├── test/                   # Backend Laravel REST API (Autentikasi, Event, Explore, Chat)
│   ├── app/
│   ├── database/
│   └── routes/api.php
├── together_mobile/        # Aplikasi Mobile Flutter (Dart)
│   ├── lib/
│   │   ├── main.dart
│   │   ├── models/         # Model data (User, Event, Chat)
│   │   ├── services/       # Koneksi HTTP & manajemen token (ApiService)
│   │   └── screens/        # 8 alur tampilan utama aplikasi
│   └── pubspec.yaml
├── PRD.md                  # Product Requirements Document
└── README.md               # Dokumentasi proyek (file ini)
```

---

## 2. Daftar Menu & Kegunaannya

Aplikasi **Together** memiliki 8 alur layar dan menu utama yang saling terhubung:

| No | Menu / Layar | Kegunaan Utama |
| :-: | :--- | :--- |
| 1 | **Splash Screen** | Layar pembuka yang menampilkan identitas visual *together* dan tagline *"REAL PEOPLE, REAL MOMENTS"* saat aplikasi pertama kali dijalankan. |
| 2 | **Onboarding** | Layar pengenalan konsep utama aplikasi (*"Find your people — Real connections. Shared moments."*) lengkap dengan tombol **Skip** untuk melewati pengenalan atau **Next** untuk melanjutkan. |
| 3 | **Sign Up (Registrasi)** | Menu pendaftaran akun baru menggunakan **Nama**, **Nomor HP atau Email**, dan **Password**. Setelah berhasil, aplikasi menyimpan token autentikasi dari server Laravel. |
| 4 | **Profile Setup** | Menu pengaturan profil awal setelah registrasi, meliputi foto profil, nama tampilan, usia, dan kota/lokasi domisili pengguna. |
| 5 | **Home (Beranda)** | Halaman utama yang menampilkan dua bagian:<br>• **People near you:** Daftar pengguna lain di sekitar lokasi pengguna.<br>• **Upcoming moments:** Kartu acara/kegiatan mendatang beserta lokasi, jadwal, dan jumlah peserta yang bergabung. |
| 6 | **Create Moment (`+`)** | Tombol aksi cepat di bagian tengah navigasi bawah untuk membuat dan memublikasikan acara atau momen baru ke publik. |
| 7 | **Explore (Jelajah)** | Menu pencarian dan penyaringan aktivitas berdasarkan kategori tab (`All`, `Events`, `People`, dan `Places`) melalui kolom pencarian maupun filter chip. |
| 8 | **Chats (Pesan)** | Menu komunikasi untuk melihat daftar percakapan personal (*direct message*) maupun grup komunitas (seperti *Hiking Group* atau *Design Folks*) beserta waktu pesan terakhir dan indikator pesan belum dibaca. |
| 9 | **Event Detail** | Halaman rincian saat salah satu kartu momen dipilih. Menampilkan foto banner, judul acara, titik lokasi, tanggal & jam, jumlah peserta (*"going"*), deskripsi kegiatan, serta tombol **"I'm In"** untuk bergabung atau membatalkan keikutsertaan. |

---

## 3. Prasyarat Sistem

Sebelum menginstal dan menjalankan proyek di perangkat baru, pastikan perangkat lunak berikut sudah terpasang:

* **Backend (Laravel):**
  * PHP `>= 8.2` beserta ekstensi standar (`pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
  * Composer
  * Database **MySQL** (misalnya melalui Laragon/XAMPP) atau **SQLite**
* **Frontend Mobile (Flutter):**
  * Flutter SDK `>= 3.x` & Dart SDK
  * Android Studio (Android SDK & Emulator) atau perangkat Android/iOS fisik dengan mode *USB Debugging* aktif
  * Git

---

## 4. Cara Instalasi & Menjalankan Backend (Laravel)

Buka terminal (jika di Windows menggunakan Laragon, disarankan membuka **Terminal bawaan Laragon** agar perintah `php` dan `composer` langsung dikenali).

1. **Clone repository dan masuk ke folder backend:**
   ```bash
   git clone <URL_REPOSITORY_GIT_ANDA>
   cd together-app/test
   ```

2. **Instal dependensi PHP melalui Composer:**
   ```bash
   composer install
   ```

3. **Salin file konfigurasi `.env` dan buat *application key*:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *(Pengguna Windows CMD dapat menggunakan `copy .env.example .env`).*

4. **Atur koneksi database pada file `.env`:**
   * **Opsi A — Menggunakan MySQL (Laragon/XAMPP):** Buat database baru bernama `together_db`, lalu sesuaikan `.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=together_db
     DB_USERNAME=root
     DB_PASSWORD=
     ```
   * **Opsi B — Menggunakan SQLite (tanpa server database terpisah):**
     ```env
     DB_CONNECTION=sqlite
     ```

5. **Jalankan migrasi tabel database:**
   ```bash
   php artisan migrate
   ```

6. **Jalankan server API Laravel:**
   Gunakan `--host=0.0.0.0` agar server dapat menerima koneksi dari emulator maupun perangkat lain:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
   > **Catatan:** Jangan membuka `http://0.0.0.0:8000` di browser. Untuk memastikan server aktif pada komputer lokal, buka `http://127.0.0.1:8000`.

---

## 5. Cara Instalasi & Menjalankan Mobile App (Flutter)

Buka jendela terminal baru (biarkan server Laravel tetap berjalan di terminal sebelumnya).

1. **Masuk ke direktori aplikasi Flutter:**
   ```bash
   cd together-app/together_mobile
   ```

2. **Unduh seluruh package Flutter yang dibutuhkan:**
   ```bash
   flutter pub get
   ```

3. **Periksa kesiapan perangkat / emulator:**
   ```bash
   flutter doctor
   flutter devices
   ```

4. **Sesuaikan alamat server API (`baseUrl`)** pada file `lib/services/api_service.dart` sesuai target perangkat Anda (lihat panduan pada Bagian 6).

5. **Jalankan aplikasi Flutter:**
   ```bash
   flutter run
   ```

---

## 6. Konfigurasi Akses Perangkat Lain / Jaringan Publik

Agar aplikasi Flutter di perangkat lain dapat berkomunikasi dengan backend Laravel, sesuaikan nilai `baseUrl` di dalam file `together_mobile/lib/services/api_service.dart`:

```dart
class ApiService {
  // Ganti URL di bawah ini sesuai skenario jaringan Anda:
  static const String baseUrl = 'http://10.0.2.2:8000/api';
  // ...
}
```

Pilih salah satu skenario berikut:

### Skenario A — Android Emulator di Komputer yang Sama
Gunakan IP khusus emulator Android yang mengarah ke `localhost` komputer host:
```dart
static const String baseUrl = 'http://10.0.2.2:8000/api';
```

### Skenario B — HP Fisik / Perangkat Lain dalam Satu Wi-Fi (LAN)
1. Pastikan komputer server dan HP fisik terhubung ke jaringan Wi-Fi yang sama.
2. Cek alamat IPv4 komputer server:
   * **Windows:** jalankan `ipconfig` di terminal, lalu lihat baris **IPv4 Address** (misalnya `192.168.1.15`).
   * **macOS/Linux:** jalankan `ifconfig` atau `ip addr`.
3. Pastikan server Laravel dijalankan dengan `php artisan serve --host=0.0.0.0 --port=8000` dan izin firewall Windows/OS mengizinkan koneksi masuk pada port `8000`.
4. Ubah `baseUrl` pada `lib/services/api_service.dart` menjadi:
   ```dart
   static const String baseUrl = 'http://192.168.1.15:8000/api';
   ```

### Skenario C — Akses Publik (Beda Jaringan / Internet / Demo Dosen & Penguji)
Jika aplikasi dijalankan di perangkat lain yang berada di luar jaringan Wi-Fi lokal (atau sudah di-deploy ke server cloud/VPS):
* **Menggunakan Tunneling Sementara (Ngrok / Cloudflare Tunnel):**
  1. Jalankan tunnel ke port `8000` (contoh: `ngrok http 8000`).
  2. Salin URL HTTPS publik yang diberikan (contoh: `https://nama-subdomain.ngrok-free.app`).
  3. Ubah `baseUrl` di Flutter menjadi:
     ```dart
     static const String baseUrl = 'https://nama-subdomain.ngrok-free.app/api';
     ```
* **Menggunakan Hosting / VPS Produksi:**
  Arahkan `baseUrl` langsung ke domain API publik Anda, misalnya:
  ```dart
  static const String baseUrl = 'https://api.together-app.domainanda.com/api';
  ```

### Membangun File Instalasi Android (`.apk`) untuk Dibagikan
Setelah `baseUrl` diarahkan ke alamat LAN atau publik yang valid, Anda dapat membuat file APK untuk dipasang di perangkat Android lain:
```bash
flutter build apk --release
```
File APK hasil build akan tersedia di:
```text
together_mobile/build/app/outputs/flutter-apk/app-release.apk
```

---

## 7. Daftar Endpoint API Utama

| Method | Endpoint | Autentikasi | Keterangan |
| :--- | :--- | :---: | :--- |
| `POST` | `/api/auth/register` | Publik | Mendaftarkan pengguna baru dan menghasilkan token Sanctum |
| `POST` | `/api/profile/setup` | Bearer Token | Menyimpan data profil tambahan (nama, usia, kota) |
| `GET` | `/api/home` | Bearer Token | Mengambil daftar pengguna sekitar & event mendatang |
| `GET` | `/api/explore?tab=All&q=` | Bearer Token | Mencari & memfilter event, orang, atau tempat |
| `POST` | `/api/events` | Bearer Token | Membuat momen/event baru |
| `GET` | `/api/events/{event}` | Bearer Token | Menampilkan detail event & status keikutsertaan |
| `POST` | `/api/events/{event}/toggle-join` | Bearer Token | Mengubah status keikutsertaan (*"I'm In"*) pada event |
| `GET` | `/api/chats` | Bearer Token | Mengambil daftar percakapan personal & grup |
| `POST` | `/api/chats/{conversation}/messages` | Bearer Token | Mengirim pesan baru ke percakapan |

---

## 8. Pemecahan Masalah Umum (Troubleshooting)

* **`'php' is not recognized as an internal or external command`:**
  Path PHP belum terdaftar di Environment Variables Windows. Jalankan perintah melalui **Terminal Laragon** atau tambahkan folder `php` milik Laragon ke *System PATH* Windows.
* **`ERR_ADDRESS_INVALID` saat membuka `http://0.0.0.0:8000` di browser:**
  Alamat `0.0.0.0` hanya digunakan saat menjalankan perintah `php artisan serve` agar server menerima koneksi dari luar. Untuk mengakses dari browser komputer lokal, gunakan `http://127.0.0.1:8000`.
* **Aplikasi Flutter gagal terhubung ke API (`SocketException` / `Connection refused`):**
  1. Pastikan server Laravel sedang berjalan (`php artisan serve --host=0.0.0.0 --port=8000`).
  2. Periksa kembali `baseUrl` di `lib/services/api_service.dart` (jangan gunakan `localhost` atau `127.0.0.1` pada perangkat Android).
  3. Nonaktifkan sementara Windows Defender Firewall untuk jaringan privat atau buat *Inbound Rule* baru untuk TCP port `8000`.