# Product Requirements Document (PRD) — Together App

## 1. Ringkasan Produk
**Together** adalah aplikasi mobile berbasis komunitas dan aktivitas nyata (*real people, real moments*) yang membantu pengguna menemukan orang di sekitar, mengikuti momen/event lokal, menjelajah kategori aktivitas & tempat, serta berdiskusi melalui chat personal maupun grup.

## 2. Target Pengguna
- Mahasiswa, pekerja muda, dan komunitas lokal yang ingin memperluas relasi serta mengikuti kegiatan offline/komunitas (hiking, olahraga, diskusi desain, hangout).

## 3. Fitur Utama (Sesuai Desain 8 Layar)
1. **Splash Screen (Step 1):** Menampilkan branding *together* dan tagline *"REAL PEOPLE, REAL MOMENTS"*.
2. **Onboarding (Step 2):** Pengenalan fitur *"Find your people — Real connections. Shared moments."* dengan navigasi *Skip* dan *Next*.
3. **Sign Up (Step 3):** Registrasi akun menggunakan Nama, No. HP/Email, dan Password (+ opsi lanjutan *Continue with Google/Apple/Phone* & tautan *Terms & Privacy*).
4. **Profile Setup (Step 4):** Pengaturan foto profil, nama tampilan, usia, dan lokasi/kota (contoh: *Bengaluru* / *Malang*).
5. **Home Feed (Step 5):**
   - Bagian **People near you** (avatar horizontal + nama + tombol *See all*).
   - Bagian **Upcoming moments** (kartu event seperti *Sunset Hike*, lokasi, waktu, avatar peserta + jumlah `+12`, dan tombol panah ke detail).
   - Bottom Navigation Bar dengan tombol tengah `+` untuk membuat momen/event baru.
6. **Explore (Step 6):** Pencarian dan filter chip (`All`, `Events`, `People`, `Places`) serta daftar aktivitas/tempat (contoh: *Badminton Hangout* dengan badge kategori *Sports*).
7. **Chats (Step 7):** Daftar percakapan personal dan grup (contoh: *Akhil*, *Priya*, *Rohan*, *Meera*, *Hiking Group*, *Design Folks*) dengan waktu pesan terakhir, indikator pesan belum dibaca, dan tombol *compose* chat baru.
8. **Event Detail (Step 8):** Detail lengkap event (banner foto, judul, lokasi, tanggal & jam, jumlah peserta *"18 going"*, deskripsi singkat, dan tombol CTA **"I'm In"** untuk bergabung/keluar dari event).

## 4. Arsitektur Teknis
- **Backend:** Laravel REST API + Laravel Sanctum (autentikasi token).
- **Database:** MySQL / SQLite (`users`, `events`, `event_participants`, `conversations`, `messages`).
- **Mobile Frontend:** Flutter (Dart) dengan arsitektur modular (`models`, `services`, `screens`, `widgets`).

## 5. Kriteria Keberhasilan (MVP)
- Pengguna dapat mendaftar, melengkapi profil, dan mendapatkan token API.
- Pengguna dapat melihat daftar pengguna sekitar & event mendatang, memfilter di Explore, melihat detail event, dan menekan **"I'm In"**.
- Pengguna dapat melihat daftar chat dan mengirim/memuat pesan.