# TESTING GUIDE - FASE 4 PENDAFTARAN

## 📌 How to Access Registration

### Step-by-Step:

1. **Go to Login Page:**
   - URL: `http://localhost:8080/auth/login`

2. **Login with Demo User:**
   - Email/Username: `demo_user` atau `user@ppdb.test`
   - Password: `user123`

3. **You will be redirected to User Dashboard:**
   - URL: `http://localhost:8080/user/dashboard`

4. **Click "Daftar Sekarang" button**
   - This creates a new pendaftaran with status `draft`
   - Automatically redirects to: `http://localhost:8080/user/pendaftaran/form/1/step/1`

5. **Fill Step 1: Data Siswa**
   - Isi semua field yang required
   - Klik "Lanjut ke Step 2"

6. **Fill Step 2: Data Orang Tua**
   - Isi data ayah dan ibu
   - Optional: isi data wali
   - Klik "Lanjut ke Step 3"

7. **Step 3: Upload Dokumen**
   - Upload Kartu Keluarga (KK)
   - Upload Akta Kelahiran
   - Upload Foto Siswa
   - Klik "Lanjut ke Step 4"

8. **Step 4: Review & Submit**
   - Review semua data
   - Centang checkbox pernyataan
   - Klik "Submit Pendaftaran"

---

## ⚠️ DO NOT try to access:

❌ `http://localhost:8080/user/pendaftaran/form/step/1`

✅ **ALWAYS** start from dashboard and click button, atau akses:

✅ `http://localhost:8080/user/dashboard` → Click "Daftar Sekarang"

---

## 🔑 Test Credentials

**Admin:**
- Username: `admin`
- Email: `admin@ppdb.test`
- Password: `admin123`
- URL: `http://localhost:8080/admin/dashboard`

**Orang Tua (Parent):**
- Username: `demo_user`
- Email: `user@ppdb.test`
- Password: `user123`
- URL: `http://localhost:8080/user/dashboard` (after login)

---

## 📋 Test Scenarios

### Scenario 1: New Registration
1. Login as `demo_user`
2. Dashboard shows "Anda Belum Mendaftar"
3. Click "Daftar Sekarang"
4. Fill all 4 steps
5. Submit → Status changes to `pending`

### Scenario 2: Edit Existing Registration
1. After submit (status = `pending`)
2. Dashboard shows registration card with "Edit Pendaftaran" button
3. Click "Edit Pendaftaran"
4. Can modify data again
5. Submit again

### Scenario 3: View Registration
1. After submit (status = `pending`)
2. Dashboard shows "Preview Data" button
3. Click to view all submitted data

---

## ✨ Features to Test

- [x] Auto-generate nomor pendaftaran (PPDB/2025/001)
- [x] Validasi NIK (16 digit)
- [x] Validasi umur (3-7 tahun)
- [x] Validasi nomor telepon (08xxx)
- [x] File upload dengan validation
- [x] Status tracking (draft → pending)
- [x] Edit hanya untuk draft/pending
- [x] Role-based access (hanya orang_tua)

---

## 🐛 Troubleshooting

### Issue: 404 Page Not Found
**Cause:** URL format is wrong  
**Solution:** Use the correct URL with pendaftaran ID: 
```
/user/pendaftaran/form/{ID}/step/{STEP}
```
Always start from dashboard instead of manually typing URL

### Issue: Not Logged In
**Cause:** Session expired  
**Solution:** 
1. Go to `http://localhost:8080/auth/login`
2. Login again with correct credentials

### Issue: "Belum Ada Tahun Ajaran Aktif"
**Cause:** No active academic year  
**Solution:** Admin harus create tahun ajaran dan activate terlebih dahulu

### Issue: Cannot Upload File
**Cause:** File type or size doesn't match requirements  
**Solution:**
- KK & Akta: PDF/JPG/PNG, max 2MB
- Foto: JPG/PNG, max 1MB

---

## 🎯 Expected Behavior

**After Step 1 (Data Siswa) - POST /user/pendaftaran/store-data-siswa:**
```json
{
  "success": true,
  "message": "Data siswa berhasil disimpan",
  "next_step": "user/pendaftaran/form/1/step/2"
}
```

**After Step 2 (Data Orang Tua) - POST /user/pendaftaran/store-data-orangtua/1:**
```json
{
  "success": true,
  "message": "Data orang tua berhasil disimpan",
  "next_step": "user/pendaftaran/form/1/step/3"
}
```

**After Step 3 (Upload Dokumen) - POST /user/pendaftaran/upload-dokumen/1:**
```json
{
  "success": true,
  "message": "Dokumen berhasil diupload",
  "dokumen_id": 1
}
```

**After Step 4 (Submit) - POST /user/pendaftaran/submit/1:**
- Redirects to: `/user/dashboard`
- Status changes from `draft` → `pending`
- Shows success message

---

## 📞 Support

Jika ada error, check:
1. `/writable/logs/` untuk error details
2. Browser console (F12) untuk AJAX errors
3. Database untuk data persistence

