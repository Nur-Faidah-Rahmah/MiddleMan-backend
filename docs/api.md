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
      "name": "Faidah",
      "email": "faidah@gmail.com",
      "password": "hdehpwlagi",
      "password_confirmation": "hdehpwlagi",
      "role_id": 1
  }

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