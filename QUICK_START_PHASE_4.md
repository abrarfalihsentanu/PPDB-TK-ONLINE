# Phase 4 - Quick Start Testing Guide

## Prerequisites

- Laragon running with PHP 8.x
- MySQL dengan database `ppdb_tk`
- CodeIgniter 4.6.4
- Server running on `http://localhost:8080`

## Database Setup

Pastikan database sudah di-setup dengan migration atau import `reset_db.sql`:

```bash
# If using migrations
php spark migrate

# Or import the SQL file
mysql -u root ppdb_tk < reset_db.sql
```

## Login Credentials

**Admin User:**
- Email: `admin@ppdb.test`
- Password: `admin123`
- Role: `admin`

**Parent/Guardian User:**
- Email: `user@ppdb.test`
- Password: `user123`
- Role: `orang_tua`

## User Flow - Complete 4-Step Registration

### Step 1: Login as Parent
1. Go to `http://localhost:8080/auth`
2. Login with parent credentials
3. Should redirect to `/user/dashboard`

### Step 2: Access Registration Form
1. From dashboard, click "Daftar Sekarang" or go to `http://localhost:8080/user/pendaftaran/create`
2. New draft registration created
3. Redirected to step 1 form

### Step 3: Fill Data Siswa (Step 1)
Complete the student data form:
```
Nama Lengkap:      [Nama Lengkap Siswa]
NIK:               1234567890123456  (16 digits)
Tempat Lahir:      Jakarta
Tanggal Lahir:     15/01/2020         (Age should be 3-7 years)
Jenis Kelamin:     Laki-laki
Agama:             Islam
Alamat:            Jl. Merdeka No. 123
RT/RW:             001/002
Kelurahan:         Merdeka
Kecamatan:         Jakarta Pusat
Kota/Kabupaten:    Jakarta
Provinsi:          DKI Jakarta
Kode Pos:          12180
```

Click "Lanjut ke Step 2" or validate with AJAX

### Step 4: Fill Data Orang Tua (Step 2)
Complete the parent data form:

**Ayah:**
```
Nama Ayah:         [Nama Ayah]
Pekerjaan Ayah:    Karyawan Swasta
Penghasilan Ayah:  1jt-3jt
Telepon Ayah:      081234567890
NIK Ayah:          1234567890123456 (optional)
```

**Ibu:**
```
Nama Ibu:          [Nama Ibu - MUST BE DIFFERENT from Ayah]
Pekerjaan Ibu:     Ibu Rumah Tangga
Penghasilan Ibu:   <500rb
Telepon Ibu:       082345678901
NIK Ibu:           6543210987654321 (optional)
```

Click "Lanjut ke Step 3"

### Step 5: Upload Documents (Step 3)
Upload 3 required documents:

1. **Kartu Keluarga (KK)**
   - Max size: 2 MB
   - Allowed formats: PDF, JPG, PNG
   - Can drag-drop or click to select

2. **Akta Lahir**
   - Max size: 2 MB
   - Allowed formats: PDF, JPG, PNG
   - Can drag-drop or click to select

3. **Foto 3x4**
   - Max size: 1 MB
   - Allowed formats: JPG, PNG
   - Should show live preview

Click "Lanjut ke Step 4" when all documents uploaded

### Step 6: Review & Submit (Step 4)
1. Review all data from steps 1-3
2. Check "Saya setuju dengan ketentuan" confirmation
3. Click "Selesaikan Pendaftaran" to submit
4. Status changes from `draft` to `pending`
5. Email notification sent (if configured)
6. Redirected to `/user/pendaftaran/view/{id}`

## Admin Flow - Manage Registrations

### Step 1: Login as Admin
1. Go to `http://localhost:8080/auth`
2. Login with admin credentials
3. Should redirect to `/admin/dashboard`

### Step 2: View All Registrations
1. Go to `http://localhost:8080/admin/pendaftaran`
2. See table with all registrations
3. Can filter by:
   - **Tahun Ajaran** (dropdown)
   - **Status** (draft, pending, diterima, ditolak, etc.)

### Step 3: View Registration Detail
1. Click on registration in list
2. Goes to `/admin/pendaftaran/view/{id}`
3. Shows:
   - Student data (read-only)
   - Address info (read-only)
   - Parent data (read-only)
   - Uploaded documents with preview
   - Current status badge

### Step 4: Accept Registration
1. On detail page, if status is `pending`:
2. Click green "Terima" button
3. Status changes to `diterima`
4. Success message displays

### Step 5: Reject Registration
1. On detail page, if status is `pending`:
2. Click red "Tolak" button
3. Modal opens with textarea for reason
4. Enter rejection reason/keterangan
5. Click "Tolak Pendaftaran"
6. Status changes to `ditolak`
7. Reason saved in keterangan field

## File Structure & Uploads

### Document Storage
- Location: `public/uploads/dokumen/`
- Naming: `TAHUN_NOMOR_JENIS_TIMESTAMP.ext`
- Example: `2025_PPDB-2025-001_kk_1705864432.pdf`

### Supported Formats
- **KK & Akta:** `.pdf`, `.jpg`, `.jpeg`, `.png`
- **Foto:** `.jpg`, `.jpeg`, `.png`

## Testing Validations

### Client-Side Validations
- Required fields show red border if empty
- Invalid NIK format shows error
- Invalid phone format shows error
- Age calculation validates (must be 3-7 years)

### Server-Side Validations
- NIK must be 16 digits
- Phone must start with 08 and be 10-12 digits
- File size exceeds limit → error
- File type not allowed → error
- Penghasilan must be from dropdown options

## URL Reference

### User Routes
```
GET  /user/pendaftaran/create                    - Create new
GET  /user/pendaftaran/form/{id}/step/{step}    - Show form
GET  /user/pendaftaran/edit/{id}                - Edit form
GET  /user/pendaftaran/preview/{id}             - Preview
GET  /user/pendaftaran/view/{id}                - View submitted
POST /user/pendaftaran/store-data-siswa         - Save step 1
POST /user/pendaftaran/store-data-orangtua/{id} - Save step 2
POST /user/pendaftaran/upload-dokumen/{id}      - Upload files
POST /user/pendaftaran/submit/{id}              - Final submit
```

### Admin Routes
```
GET  /admin/pendaftaran                         - List all
GET  /admin/pendaftaran/view/{id}              - View detail
GET  /admin/pendaftaran/accept/{id}            - Accept registration
POST /admin/pendaftaran/reject/{id}            - Reject registration
POST /admin/pendaftaran/{id}/status            - Update status (AJAX)
```

## Troubleshooting

### 404 Page Not Found
- Ensure `php spark serve` is running
- Check URL format (must include all parameters)
- Clear browser cache (Ctrl+F5)

### Form won't submit / AJAX error
- Check browser console for JavaScript errors
- Verify CSRF token in form
- Check server logs: `writable/logs/`

### Upload fails
- Check file size (KK/Akta max 2MB, Foto max 1MB)
- Check file format (only pdf/jpg/jpeg/png allowed)
- Ensure `public/uploads/dokumen/` directory exists
- Check file permissions

### Cannot login
- Verify email & password correct
- Check user exists in database: `select * from users;`
- Check user role: should be 'admin' or 'orang_tua'

### Status not updating
- Verify you're logged in as admin
- Check registration status is 'pending' (only pending can change)
- Check server logs for errors

## Sample Test Data

### Student Data
```
NIK: 1234567890123456
Nama: Budi Santoso
TTL: Jakarta, 15/01/2020 (5 years old)
JK: Laki-laki
Agama: Islam
Alamat: Jl. Merdeka No. 123, Jakarta
```

### Parent Data
```
Ayah:
  Nama: Santoso Wijaya
  Pekerjaan: Karyawan Bank
  Penghasilan: 1jt-3jt
  Telepon: 081234567890

Ibu:
  Nama: Siti Nurhaliza
  Pekerjaan: Guru
  Penghasilan: 1jt-3jt
  Telepon: 082345678901
```

### Document Files
- Use actual files from your computer
- Or create test files:
  - Small PDF (< 2MB)
  - Small JPG photo (< 1MB)

## Expected Results

✅ **User Can:**
- Create draft registration
- Fill 4-step form with validation
- Upload documents with preview
- Submit registration
- View own registration
- Edit registration (if draft/pending)

✅ **Admin Can:**
- View all registrations
- Filter by tahun_ajaran & status
- View registration detail
- Accept registration (status → diterima)
- Reject registration with reason (status → ditolak)
- See document previews

✅ **System Should:**
- Generate unique nomor_pendaftaran
- Save all data to database
- Create orang_tua record
- Create dokumen records
- Store files in public/uploads/dokumen/
- Send email notifications (if configured)
- Maintain role-based access control

## Next Phase

After successful Phase 4 testing:
- Phase 5: Modul Pembayaran
  - Payment gateway integration
  - Receipt generation
  - Payment verification workflow

---

**Last Updated:** 2025-01-24
**Status:** Phase 4 ✅ Complete
