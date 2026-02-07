# 🧪 PPDB-TK ONLINE - TESTING CHECKLIST & GUIDE

**Status:** ✅ SETUP COMPLETE - Ready for Testing
**Date:** 7 February 2026
**Environment:** Laragon (Local Development)

---

## ✅ PRE-TESTING VERIFICATION

### Completed Setup:

- ✅ Composer packages installed (PhpSpreadsheet v5.4.0, Dompdf v3.1.4)
- ✅ Database migrations executed (7 migrations)
- ✅ User seeder data loaded (admin@ppdb.test, user@ppdb.test)
- ✅ Upload directories created (dokumen/, pembayaran/)
- ✅ All PHP syntax verified (0 errors)
- ✅ Routes configured
- ✅ Models enhanced with new methods
- ✅ Controllers implemented (5 new + 2 updated)
- ✅ Views created (7 new admin templates)
- ✅ Sidebar notifications badges integrated

---

## 🔐 LOGIN CREDENTIALS FOR TESTING

### Admin Account:

```
Email: admin@ppdb.test
Password: admin123
Role: admin
```

### User (Orang Tua) Account:

```
Email: user@ppdb.test
Password: user123
Role: orang_tua
```

---

## 📋 COMPREHENSIVE TESTING CHECKLIST

### PHASE 1: AUTHENTICATION & LOGIN

#### 1.1 Login as Admin

- [ ] Navigate to `http://ppdb-tk.test/auth/login`
- [ ] Enter email: `admin@ppdb.test`
- [ ] Enter password: `admin123`
- [ ] Click "Login"
- [ ] Verify redirect to `/admin/dashboard`
- [ ] Verify sidebar shows ADMIN MENU (Tahun Ajaran, Users, etc)

#### 1.2 Login as Orang Tua

- [ ] Open new tab/private window
- [ ] Navigate to `http://ppdb-tk.test/auth/login`
- [ ] Enter email: `user@ppdb.test`
- [ ] Enter password: `user123`
- [ ] Click "Login"
- [ ] Verify redirect to `/user/dashboard`
- [ ] Verify sidebar shows USER MENU (Daftar Baru, Status Pendaftaran, Pembayaran)

#### 1.3 Logout

- [ ] Click "Logout" di sidebar
- [ ] Verify redirect to login page
- [ ] Verify session cleared (cannot access /admin/dashboard)

---

### PHASE 2: SIDEBAR NOTIFICATION BADGES

#### 2.1 Admin Sidebar Badges

- [ ] Login as admin
- [ ] Go to `/admin/dashboard`
- [ ] Check sidebar items:
  - [ ] "Verifikasi Pembayaran" - should show badge if pending pembayaran
  - [ ] "Verifikasi Berkas" - should show badge if pending dokumen
  - [ ] "Penerimaan Siswa" - should show badge if pending penerimaan
- [ ] Verify badge colors:
  - [ ] Pembayaran = RED (bg-danger)
  - [ ] Dokumen = YELLOW (bg-warning)
  - [ ] Penerimaan = BLUE (bg-info)

#### 2.2 Badge Count Accuracy

- [ ] Create test data with multiple pending items
- [ ] Verify badge count matches actual pending count
- [ ] After processing item, verify badge count decreases

---

### PHASE 3: PAYMENT VERIFICATION (ADMIN)

#### 3.1 List Pembayaran Page

- [ ] Login as admin
- [ ] Navigate to `/admin/pembayaran`
- [ ] Verify page displays:
  - [ ] Filter by Status (pending, verified, rejected)
  - [ ] Filter by Tahun Ajaran dropdown
  - [ ] Search field (nomor pendaftaran/nama)
  - [ ] Data table with columns:
    - No, Nomor Pendaftaran, Nama, Email, Jumlah, Status, Tanggal Upload, Aksi

#### 3.2 Filter Pembayaran

- [ ] Filter by "Pending" status
- [ ] Filter by specific tahun ajaran
- [ ] Search by nomor pendaftaran
- [ ] Search by nama lengkap
- [ ] Verify results update correctly
- [ ] Reset filter works

#### 3.3 Verify Pembayaran Detail

- [ ] Click "Lihat" button on a payment
- [ ] Verify shows:
  - [ ] Nomor Pendaftaran
  - [ ] Nama Lengkap
  - [ ] Email
  - [ ] Jumlah Pembayaran (formatted Rp XX.XXX)
  - [ ] Tanggal Transfer
  - [ ] Preview Bukti Bayar (image/PDF)
  - [ ] Download link for bukti bayar

#### 3.4 Approve Pembayaran

- [ ] On verify page, select "Setujui (Approved)"
- [ ] Leave keterangan empty
- [ ] Click "Simpan Verifikasi"
- [ ] Verify:
  - [ ] Redirect to `/admin/pembayaran`
  - [ ] Success message appears
  - [ ] Status in table changed to "Verified"
  - [ ] Status pendaftaran auto-updated to "pembayaran_verified"

#### 3.5 Reject Pembayaran

- [ ] Click "Lihat" for another payment
- [ ] Select "Tolak (Rejected)"
- [ ] Enter keterangan: "Bukti bayar tidak jelas"
- [ ] Click "Simpan Verifikasi"
- [ ] Verify:
  - [ ] Success message shown
  - [ ] Status changed to "Rejected"
  - [ ] Keterangan saved

---

### PHASE 4: DOCUMENT VERIFICATION (ADMIN)

#### 4.1 List Verifikasi Berkas

- [ ] Login as admin
- [ ] Navigate to `/admin/verifikasi`
- [ ] Verify displays list of "pembayaran_verified" pendaftaran
- [ ] Filter options:
  - [ ] Tahun Ajaran
  - [ ] Search (nomor/nama)
- [ ] Verify data table columns are correct

#### 4.2 Verify Dokumen Detail

- [ ] Click "Verifikasi" on any pendaftaran
- [ ] Verify page shows:
  - [ ] Nomor Pendaftaran
  - [ ] Nama Lengkap
  - [ ] Email
  - [ ] Tahun Ajaran
  - [ ] 3 dokumen sections (KK, Akta, Foto)

#### 4.3 Verify Individual Dokumen

- [ ] For each dokumen:
  - [ ] Preview/view dokumen image correctly
  - [ ] Select status: Lolos/Ditolak/Pending
  - [ ] can add keterangan jika ditolak
- [ ] Verify:
  - [ ] Summary shows: Total (3), Lolos (_), Ditolak (_), Pending (\_)

#### 4.4 Process Verifikasi (All Approve)

- [ ] Select "Lolos" for semua dokumen (KK, Akta, Foto)
- [ ] Click "Simpan Verifikasi"
- [ ] Verify:
  - [ ] Success message: "semua dokumen LOLOS"
  - [ ] Status pendaftaran auto-updated to "diverifikasi"
  - [ ] Pendaftaran removed from verifikasi list

#### 4.5 Process Verifikasi (With Rejection)

- [ ] On another pendaftaran, select "Lolos" untuk 2 dokumen
- [ ] Select "Ditolak" untuk 1 dokumen (e.g., KK)
- [ ] Add keterangan: "Foto tidak jelas, silakan upload ulang"
- [ ] Click "Simpan Verifikasi"
- [ ] Verify:
  - [ ] Status pendaftaran auto-updated to "dokumen_ditolak"
  - [ ] Success message shown

---

### PHASE 5: ACCEPTANCE DECISION (ADMIN PENERIMAAN)

#### 5.1 List Penerimaan

- [ ] Login as admin
- [ ] Navigate to `/admin/penerimaan`
- [ ] Verify page shows:
  - [ ] Tahun Ajaran Aktif info
  - [ ] Sisa Kuota count
  - [ ] Stats cards (Menunggu, Diterima, Ditolak)
  - [ ] Filter options
  - [ ] Data table with checkboxes

#### 5.2 View Stats

- [ ] Verify stat cards show:
  - [ ] Menunggu Keputusan = count of "diverifikasi"
  - [ ] Diterima = count of "diterima"
  - [ ] Ditolak = count of "ditolak"
  - [ ] Sisa Kuota = calculated remaining slots

#### 5.3 Single Selection

- [ ] Click checkbox untuk 1 pendaftaran
- [ ] Select status "Diterima" dari dropdown
- [ ] Click "Terapkan Status"
- [ ] Verify:
  - [ ] Selected count updated
  - [ ] Status updated in table
  - [ ] Badge counts updated

#### 5.4 Multi Selection (Batch)

- [ ] Click "Pilih Semua" checkbox (top left)
- [ ] Verify all rows checked
- [ ] Select "Ditolak" status
- [ ] Click "Terapkan Status"
- [ ] Verify:
  - [ ] All checked items status updated
  - [ ] "Diterima" count = 0 or decreased
  - [ ] "Ditolak" count increased

#### 5.5 Kuota Validation

- [ ] Note current sisa kuota (e.g., 10)
- [ ] Try to approve more than sisa kuota (e.g., select 15)
- [ ] Select "Diterima"
- [ ] Click "Terapkan Status"
- [ ] Verify:
  - [ ] Error message: "Kuota tidak mencukupi"
  - [ ] Status NOT updated

---

### PHASE 6: REPORTING & EXPORT

#### 6.1 Laporan Filter Page

- [ ] Login as admin
- [ ] Navigate to `/admin/laporan`
- [ ] Verify shows:
  - [ ] Form filters (Tahun Ajaran, Status, JK, Agama, Date range, Search)
  - [ ] Preview, Export PDF, Export Excel buttons

#### 6.2 Filter & Preview

- [ ] Select Tahun Ajaran: (pilih yang ada)
- [ ] Select Status: "diterima"
- [ ] Select JK: "Laki-laki"
- [ ] Click "Preview"
- [ ] Verify data displays correctly di table

#### 6.3 Export PDF

- [ ] On laporan page, select filters
- [ ] Click "Export PDF"
- [ ] Verify:
  - [ ] PDF file downloads
  - [ ] Opening PDF shows:
    - Header dengan nama sekolah
    - Filter info
    - Data table lengkap
    - Summary (total pendaftar)
    - Formatted date/time

#### 6.4 Export Excel

- [ ] Select filters
- [ ] Click "Export Excel"
- [ ] Verify:
  - [ ] Excel file downloads
  - [ ] Opening Excel shows:
    - [ ] Header row (No, Nomor Pendaftaran, Nama, etc)
    - [ ] All data rows
    - [ ] Formatting (colors, borders)
    - [ ] Proper column widths
    - [ ] Frozen header row

---

### PHASE 7: FILE ACCESS & DOWNLOAD

#### 7.1 Download Dokumen (As Owner)

- [ ] Login as user (orang tua)
- [ ] Go to `/user/dashboard`
- [ ] View pendaftaran dengan dokumen
- [ ] Click download link untuk dokumen
- [ ] Verify:
  - [ ] File downloads correctly
  - [ ] File content valid (image/PDF)

#### 7.2 Download Bukti Bayar (As Owner)

- [ ] On user dashboard, go to pembayaran section
- [ ] Click download bukti bayar
- [ ] Verify:
  - [ ] File downloads
  - [ ] File is correct

#### 7.3 Security - No Cross-Access

- [ ] Open 2 browser tabs
  - Tab 1: Login as user (misalnya ID=1)
  - Tab 2: Login as user (misalnya ID=2)
- [ ] On Tab 1, try access dokumen dari user 2:
  - `http://ppdb-tk.test/files/download/dokumen/{dokumen_id_dari_user2}`
- [ ] Verify:
  - [ ] Error 403 (Forbidden) atau access denied
  - [ ] Cannot download file milik orang lain

#### 7.4 Security - Admin Access

- [ ] Login as admin
- [ ] Try access `/files/download/dokumen/{any_id}`
- [ ] Verify:
  - [ ] Admin CAN download semua dokumen
  - [ ] No restrictions

#### 7.5 Preview Dokumen

- [ ] Go back to user dashboard dengan dokumen
- [ ] Click preview/view button
- [ ] Verify:
  - [ ] Image displays inline di modal/page
  - [ ] PDF opens di browser
  - [ ] Can still download dari preview page

---

### PHASE 8: FORM VALIDATIONS

#### 8.1 Pembayaran Verification Form

- [ ] Fill form tanpa select status
- [ ] Click submit
- [ ] Verify:
  - [ ] Error message: "Status harus dipilih"

#### 8.2 Dokumen Verification Form

- [ ] On verifikasi dokumen page, tidak select status apapun
- [ ] Click "Simpan Verifikasi"
- [ ] Verify:
  - [ ] JavaScript warning atau error message
  - [ ] Form not submitted

#### 8.3 Penerimaan Batch Form

- [ ] Don't select any checkbox
- [ ] Click "Terapkan Status" tanpa select status
- [ ] Verify:
  - [ ] Alert message: "Silakan pilih..."
  - [ ] Form not submitted

---

### PHASE 9: RESPONSIVENESS

#### 9.1 Desktop View (1920x1080)

- [ ] All pages render correctly
- [ ] Tables readable
- [ ] Buttons properly aligned
- [ ] Forms easy to fill

#### 9.2 Tablet View (768x1024)

- [ ] All pages responsive
- [ ] Table scrollable if needed
- [ ] Sidebar collapsible
- [ ] Touch-friendly buttons

#### 9.3 Mobile View (375x667)

- [ ] All pages mobile-friendly
- [ ] Menu hamburger functional
- [ ] Buttons large enough to tap
- [ ] Forms stack vertically

---

## � ERRORS FIXED (7 Feb 2026)

### Error 1: Missing Orang Tua Form Fields ✅ FIXED

**Issue:** Form in `user/pendaftaran/orangtua.php` was missing pekerjaan_ayah, pekerjaan_ibu fields
**Error:** `Column 'pekerjaan_ayah' cannot be null`
**Fix Applied:**

- ✅ Added missing form fields to orangtua.php: pekerjaan_ayah, pekerjaan_ibu, penghasilan_ayah, penghasilan_ibu
- ✅ Made pekerjaan fields more lenient (optional in form, nullable in DB via new migration)
- ✅ Enhanced form with section headers for Data Ayah, Data Ibu, Data Wali
- ✅ Added dropdown selects for penghasilan with predefined ranges

### Error 2: Missing Edit View File ✅ FIXED

**Issue:** Controller `User\Pendaftaran::edit()` referenced missing view `user/pendaftaran/edit.php`
**Error:** `Invalid file: "user/pendaftaran/edit.php"`
**Fix Applied:**

- ✅ Created `app/Views/user/pendaftaran/edit.php` with form for editing student data
- ✅ Form pre-fills all fields from pendaftaran object
- ✅ Form POSTs to `user/pendaftaran/update/{id}` endpoint
- ✅ Added Batal/Cancel button returning to preview

### Error 3: UserSeeder Duplicate Entry ⚠️ EXPECTED

**Issue:** `Duplicate entry 'admin' for key 'users.username'`
**Status:** EXPECTED BEHAVIOR
**Reason:** UserSeeder was already run during initial setup, admin@ppdb.test already exists
**Solution:** Not a blocking issue - just indicates seeder only needs to run once

### Database Migration Applied ✅

**New Migration:** `AlterOrangTuaMakeJobsNullable.php`

- Made `pekerjaan_ayah` nullable
- Made `pekerjaan_ibu` nullable
- Allows users to omit job info if needed (more flexible form submission)

---

## �🔍 ADVANCED TESTING SCENARIOS

### Scenario 1: Complete Workflow

1. Admin create tahun ajaran 2025/2026, kuota 60, biaya 500rb
2. User (orang tua) register akun
3. User daftar dengan lengkap (data siswa, orang tua, dokumen)
4. User upload bukti bayar
5. Admin verify pembayaran → approve
6. Admin verify dokumen → approve semua
7. Admin announce terima siswa
8. User lihat status "diterima" di dashboard
9. User download bukti pendaftaran PDF

### Scenario 2: Rejection Flow

1. User upload pendaftaran
2. Admin tolak pembayaran dengan keterangan
3. Verify status back to "pending"
4. User re-upload bukti bayar
5. Admin approve → verify dokumen
6. Admin reject 1 dokumen
7. Verify status → "dokumen_ditolak"
8. User re-upload dokumen
9. Admin approve → auto update to "diverifikasi"

### Scenario 3: Batch Actions

1. Multiple pendaftaran in "diverifikasi" status
2. Admin select 30 sekaligus
3. Decide terima/tolak/cadangan
4. Check kuota validation
5. Verify batch update successful

---

## 📊 DATABASE VERIFICATION

### Check User Data:

```sql
SELECT * FROM users WHERE role = 'admin' LIMIT 1;
-- Should show: admin@ppdb.test, role=admin
```

### Check Tahun Ajaran:

```sql
SELECT * FROM tahun_ajaran WHERE status='aktif';
-- Must have at least 1 active tahun ajaran for testing
```

### Check Pendaftaran Status Flow:

```sql
SELECT status_pendaftaran, COUNT(*)
FROM pendaftaran
GROUP BY status_pendaftaran;
-- Should show progression: draft → pending → pembayaran_verified → diverifikasi → diterima/ditolak
```

---

## 🐛 COMMON ISSUES & FIXES

### Issue 1: Packages Not Found (DomPDF/PhpSpreadsheet)

**Error:** `Class 'Dompdf\Dompdf' not found`
**Fix:**

```bash
cd c:\laragon\www\ppdb-tk
composer dump-autoload
composer install --no-dev
```

### Issue 2: Files Not Downloading

**Error:** `File not found` saat download
**Fix:**

1. Check folder permissions: `public/uploads/dokumen/` dan `public/uploads/pembayaran/`
2. Ensure files ada di correct path
3. Check file path di database vs actual path

### Issue 3: Badge Not Showing

**Error:** No badge di sidebar menu
**Fix:**

1. Check database connection
2. Verify models sudah ada methods: `countPending()`
3. Check sidebar.php instantiate models correctly
4. Clear browser cache

### Issue 4: Status Not Auto-Updating

**Error:** Status tetap "pending" setelah approve pembayaran
**Fix:**

1. Check controller logic (Admin\Pembayaran::processVerify)
2. Verify database update query executed
3. Check no exception thrown silently
4. Enable query logging untuk debug

---

## ✅ SIGN-OFF CHECKLIST

- [ ] All PHP syntax errors verified (0 errors)
- [ ] Composer packages installed & verified
- [ ] Database migrations complete
- [ ] Seeder data loaded
- [ ] Upload directories created
- [ ] All authentication tests passing
- [ ] All admin features working
- [ ] All verifications processing correctly
- [ ] Reports exporting successfully
- [ ] File download security validated
- [ ] Sidebar badges displaying correctly
- [ ] Form validations working
- [ ] Responsive design verified
- [ ] No console errors in browser

---

## 🚀 READY FOR PRODUCTION CHECKLIST

When all testing complete:

- [ ] Fix any bugs found
- [ ] Update .env to production settings
- [ ] Submit to stakeholder for UAT
- [ ] Get sign-off from client
- [ ] Final security audit
- [ ] Deploy to production server

---

## 📞 SUPPORT & CONTACT

If issues found during testing:

1. Check this document for solutions
2. Review error logs: `writable/logs/`
3. Enable debug mode: `CI_ENVIRONMENT = development` in `.env`
4. Check browser console (F12) untuk JavaScript errors
5. Document the issue dan steps to reproduce

---

**Last Updated:** 7 February 2026
**Version:** 1.0 - PPDB-TK Setup Complete
