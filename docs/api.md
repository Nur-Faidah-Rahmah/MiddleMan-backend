# Dokumentasi API Middle Man (v1)

- **Base URL:** `http://localhost:8000/api/v1`
- **Format Response Standar:** Semua API akan mengembalikan struktur JSON seperti berikut.
  - **Success:** `{ "success": true, "message": "...", "data": {...} }`
  - **Error:** `{ "success": false, "message": "...", "errors": {...} }`

---

# 1. Authentication

### A. Register

* **Endpoint:** `POST /register`
* **Akses:** Public

```json
{
    "name":"Requester",
    "email":"requester@gmail.com",
    "password":"password123",
    "password_confirmation":"password123",
    "role_id":2
}
```

---

### B. Login

* **Endpoint:** `POST /login`
* **Akses:** Public

```json
{
    "email":"requester@gmail.com",
    "password":"password123"
}
```

---

### C. Logout

* **Endpoint:** `POST /logout`
* **Akses:** Login

---

### D. Profil Login

* **Endpoint:** `GET /me`
* **Akses:** Login

---

# 2. Category

### A. Daftar Kategori

* **Endpoint:** `GET /categories`
* **Akses:** Login

---

### B. Detail Kategori

* **Endpoint:** `GET /categories/{id}`
* **Akses:** Login

---

### C. Tambah Kategori

* **Endpoint:** `POST /categories`
* **Akses:** Admin

```json
{
    "name":"Design"
}
```

---

### D. Update Kategori

* **Endpoint:** `PUT /categories/{id}`
* **Akses:** Admin

---

### E. Hapus Kategori

* **Endpoint:** `DELETE /categories/{id}`
* **Akses:** Admin

---

# 3. Job

### A. Membuat Quest

* **Endpoint:** `POST /jobs`
* **Akses:** Customer

```json
{
    "category_id":1,
    "title":"Desain Logo",
    "description":"Logo UMKM",
    "budget":50000,
    "deadline":"2026-08-01"
}
```

---

### B. Daftar Quest Saya

* **Endpoint:** `GET /jobs/mine`
* **Akses:** Customer

---

### C. Daftar Quest Terbuka

* **Endpoint:** `GET /jobs`
* **Akses:** Login

---

### D. Detail Quest

* **Endpoint:** `GET /jobs/{id}`
* **Akses:** Login

---

### E. Update Quest

* **Endpoint:** `PUT /jobs/{id}`
* **Akses:** Customer (Pemilik Quest)

---

### F. Hapus Quest

* **Endpoint:** `DELETE /jobs/{id}`
* **Akses:** Customer (Pemilik Quest)

---

# 4. Application

### A. Melamar Quest

* **Endpoint:** `POST /applications/jobs/{job}/apply`
* **Akses:** Worker

Body

```json
{
    "terms_accepted": true
}
```

---

### B. Daftar Pelamar

* **Endpoint:** `GET /applications/jobs/{job}/applications`
* **Akses:** Customer

---

### C. Riwayat Lamaran

* **Endpoint:** `GET /applications/mine`
* **Akses:** Worker

---

### D. Terima Lamaran

* **Endpoint:** `PATCH /applications/{application}/accept`
* **Akses:** Customer

---

### E. Tolak Lamaran

* **Endpoint:** `PATCH /applications/{application}/reject`
* **Akses:** Customer

---

# 5. Submission

### A. Submit Hasil Pekerjaan

* **Endpoint:** `POST /submissions/jobs/{job}`
* **Akses:** Worker

Body

* attachment (file)
* note

---

### B. Detail Submission

* **Endpoint:** `GET /submissions/jobs/{job}`
* **Akses:** Customer & Worker terkait

---

# 6. Escrow

### A. Deposit Dana

* **Endpoint:** `POST /escrows/jobs/{job}/fund`
* **Akses:** Customer

---

### B. Release Dana

* **Endpoint:** `PATCH /escrows/jobs/{job}/release`
* **Akses:** Customer

---

### C. Refund Dana

* **Endpoint:** `PATCH /escrows/jobs/{job}/refund`
* **Akses:** Customer

---

# 7. Transaction

### A. Riwayat Transaksi

* **Endpoint:** `GET /transactions`
* **Akses:** Login

---

### B. Detail Transaksi

* **Endpoint:** `GET /transactions/{transaction}`
* **Akses:** Login

---

# 8. Verification Document

### A. Upload Dokumen

* **Endpoint:** `POST /verification`
* **Akses:** Worker

Body (form-data)

* document
* document_type

---

### B. Status Verifikasi

* **Endpoint:** `GET /verification`
* **Akses:** Worker

---

### C. Daftar Verifikasi

* **Endpoint:** `GET /admin/verifications`
* **Akses:** Admin

---

### D. Approve Dokumen

* **Endpoint:** `PUT /admin/verifications/{document}/approve`
* **Akses:** Admin

---

### E. Reject Dokumen

* **Endpoint:** `PUT /admin/verifications/{document}/reject`
* **Akses:** Admin

Body

```json
{
    "review_note":"Dokumen buram."
}
```

---

# 9. User Profile

### A. Profil Saya

* **Endpoint:** `GET /profile`
* **Akses:** Login

---

### B. Update Profil

* **Endpoint:** `PUT /profile`
* **Akses:** Login

---

### C. Profil Publik

* **Endpoint:** `GET /users/{id}`
* **Akses:** Login

---

# 10. Global Exception Response

### 401 Unauthorized

```json
{
    "success": false,
    "message": "Unauthenticated."
}
```

### 403 Forbidden

```json
{
    "success": false,
    "message": "Anda tidak memiliki akses."
}
```

### 404 Not Found

```json
{
    "success": false,
    "message": "Data tidak ditemukan."
}
```

### 422 Validation Error

```json
{
    "success": false,
    "message": "Validation Error",
    "errors": {}
}
```

### 500 Internal Server Error

```json
{
    "success": false,
    "message": "Internal Server Error"
}
```
