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
      "name": "Budi Pekerja",
      "email": "budi@worker.com",
      "password": "password123",
      "password_confirmation": "password123",
      "role_id": 3
  }