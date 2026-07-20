# Dokumentasi API Middle Man (v1)

- **Base URL:** `http://localhost:8000/api/v1`
- **Format Response Standar:** Semua API akan mengembalikan struktur JSON seperti berikut.
  - **Success:** `{ "success": true, "message": "...", "data": {...} }`
  - **Error:** `{ "success": false, "message": "...", "errors": {...} }`

---

## 1. Authentication

### A. Register User Baru
- **Endpoint:** `POST /register`
- **Akses:** Publik
- **Body Request (JSON):**
  ```json
  {
      "name": "admin",
      "email": "admin@gmail.com",
      "password": "admin123",
      "password_confirmation": "admin123",
      "role_id": 1
  }

### B. Login User
- **Endpoint:** `POST /login`
- **Akses:** Publik
- **Body Request (JSON):**
  ```json
  {
      "email": "admin@gmail.com",
      "password": "admin123",
  }
- **Response Sukses (200):**
  ```json
  {
    "success": true,
    "message": "Login berhasil.",
    "data": {
        "token": "1|laravel_sanctum_token_string...",
        "user": { "name": "admin", "email": "admin@gmail.com", "role_id": 1 }
    }
  }

### C. Logout User
- **Endpoint:** `POST /logout`
- **Akses:** Privat (Bearer Token Required)


## 2. Category Management (Admin Only)

### A. Menampilkan Semua Kategori
- **Endpoint:** `GET /categories`
- **Akses:** Privat (Semua User yang sudah login)

### B. Membuat Kategori Baru
- **Endpoint:** `POST /categories`
- **Akses:** Privat (Khusus Admin)
- **Body Request (JSON):**
  ```json
  { "name": "Kategori Baru" }


## 3. Job Management

### A. Customer: Membuat Tugas Baru
- **Endpoint:** `POST /jobs`
- **Akses:** Privat (Khusus Customer)
- **Body Request (JSON):**
  ```json
  {
      "category_id": 1,
      "title": "Bersih-bersih Garasi",
      "description": "Sapu dan pel garasi rumah sampai bersih.",
      "price": 50000,
      "deadline": "2026-07-20"
  }

### B. Customer: Riwayat Tugas Saya
- **Endpoint:** `GET /customer/jobs`
- **Akses:** Privat (Khusus Customer)
- **Keterangan:** Mengambil semua tugas yang pernah dibuat oleh customer yang sedang login.

### C. Admin: Antrean Verifikasi Tugas
- **Endpoint:** `GET /pending-jobs`
- **Akses:** Privat (Khusus Admin)
- **Keterangan:** Menampilkan tugas-tugas baru masuk berstatus pending yang perlu diverifikasi.

### D. Admin: Menyetujui Tugas (Approve)
- **Endpoint:** `PUT /jobs/{job_id}/verify`
- **Akses:** Privat (Khusus Admin)
- **Keterangan:** Mengubah status tugas dari pending menjadi approved agar tampil di bursa kerja Worker.

### E. Worker: Bursa Kerja Tersedia
- **Endpoint:** `GET /available-jobs`
- **Akses:** Privat (Khusus Worker)
- **Keterangan:** Menampilkan semua tugas yang berstatus approved dan siap untuk diambil.

### F. Worker: Mengambil Tugas (Take Job)
- **Endpoint:** `PUT /jobs/{job_id}/take`
- **Akses:** Privat (Khusus Worker)
- **Keterangan:** Pekerja mengklaim tugas. Status berubah menjadi taken.

### G. Worker: Menyelesaikan Tugas (Complete Job)
- **Endpoint:** `PUT /jobs/{job_id}/complete`
- **Akses:** Privat (Khusus Worker)
- **Keterangan:** Menyatakan tugas selesai dikerjakan. Status berubah menjadi completed.


## 4. Global Exception Responses (Jaring Pengaman Sistem)

### A. Error 401 (Belum Login / Token Kadaluwarsa)
  ```json
  {
      "success": false,
      "message": "Akses ditolak. Sesi tidak valid atau Anda belum login.",
      "errors": null
  }
  ```
 
### B. Error 403 (Akses Ditolak / Salah Role)
  ```json
  {
      "success": false,
      "message": "Akses ditolak. Anda tidak memiliki izin untuk tindakan ini.",
      "errors": null
  }
  ```

### C. Error 404 (Data atau URL Salah)
  ```json
  {
      "success": false,
      "message": "Data tidak ditemukan atau rute tidak valid.",
      "errors": null
  }
  ```

### D. Error 405 (Salah Metode HTTP, misal GET rute POST)
  ```json
  {
      "success": false,
      "message": "Metode HTTP tidak diizinkan. Cek kembali apakah harusnya GET, POST, PUT, atau DELETE.",
      "errors": null
  }
  ```

### E. Error 500+ (Internal Server Error / Bug Kode PHP)
  ```json
  {
      "success": false,
      "message": "Terjadi kegagalan sistem pada server (Internal Server Error).",
      "problem": "Inti masalah error bawaan PHP ditampilkan di sini",
      "solution": "Solusi: Silakan periksa log server, pastikan database menyala, atau cek apakah ada typo kode di file Service/Controller Anda.",
      "errors": null
  }
  ```