# Phase 4 - Modul Pendaftaran (Selesai)

**Duration:** Hari 29-42  
**Status:** ✅ COMPLETE  
**Last Updated:** 2025-01-24

## Overview

Phase 4 mengimplementasikan modul pendaftaran lengkap dengan 4-step form untuk wali murid. Sistem ini mencakup:
- Form pendaftaran multi-step dengan AJAX validation
- Upload dokumen (KK, Akta, Foto)
- Dashboard user dengan tracking status
- Dashboard admin dengan list & detail registrations
- Role-based access control (admin vs orang_tua)

## Architecture

```
┌─────────────────────────────────────────────────────────┐
│                  User Flow (4-Step)                     │
├─────────────────────────────────────────────────────────┤
│  Step 1: Data Siswa ──► Step 2: Data Orang Tua        │
│       ↓                        ↓                        │
│  Step 3: Upload Dokumen ──► Step 4: Review & Submit  │
│                                 ↓                      │
│                         Update DB + Email             │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                    Admin Flow                           │
├─────────────────────────────────────────────────────────┤
│  List Pendaftaran ──► View Detail ──► Accept/Reject   │
│       (Filter)           (View All)    (Update Status) │
│                            Data)                       │
└─────────────────────────────────────────────────────────┘
```

## Database Schema

### Tables Created/Modified

#### 1. pendaftaran
```sql
ALTER TABLE pendaftaran ADD COLUMN nomor_pendaftaran VARCHAR(50) UNIQUE AFTER id;
ALTER TABLE pendaftaran ADD COLUMN tahun_ajaran_id INT UNSIGNED NOT NULL;
ALTER TABLE pendaftaran ADD COLUMN status_pendaftaran ENUM('draft','pending','pembayaran_verified','diverifikasi','diterima','ditolak') DEFAULT 'draft';
ALTER TABLE pendaftaran ADD COLUMN keterangan TEXT;
ALTER TABLE pendaftaran ADD COLUMN tanggal_submit TIMESTAMP NULL;
ALTER TABLE pendaftaran ADD FOREIGN KEY(tahun_ajaran_id) REFERENCES tahun_ajaran(id);
```

#### 2. orang_tua (NEW)
```sql
CREATE TABLE orang_tua (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    pendaftaran_id INT UNSIGNED NOT NULL,
    nama_ayah VARCHAR(100),
    nik_ayah VARCHAR(16),
    pekerjaan_ayah VARCHAR(100),
    penghasilan_ayah ENUM(...),
    telepon_ayah VARCHAR(13),
    
    nama_ibu VARCHAR(100),
    nik_ibu VARCHAR(16),
    pekerjaan_ibu VARCHAR(100),
    penghasilan_ibu ENUM(...),
    telepon_ibu VARCHAR(13),
    
    nama_wali VARCHAR(100),
    hubungan_wali VARCHAR(50),
    pekerjaan_wali VARCHAR(100),
    telepon_wali VARCHAR(13),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY(pendaftaran_id) REFERENCES pendaftaran(id) ON DELETE CASCADE
);
```

#### 3. dokumen (NEW)
```sql
CREATE TABLE dokumen (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    pendaftaran_id INT UNSIGNED NOT NULL,
    jenis_dokumen ENUM('kk','akta','foto'),
    nama_file VARCHAR(255),
    path_file VARCHAR(255),
    ukuran_file INT UNSIGNED,
    tipe_file VARCHAR(50),
    status_verifikasi ENUM('belum','diverifikasi','ditolak') DEFAULT 'belum',
    keterangan_verifikasi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY(pendaftaran_id) REFERENCES pendaftaran(id) ON DELETE CASCADE
);
```

## Models

### PendaftaranModel
**File:** `app/Models/PendaftaranModel.php`

**Key Methods:**
- `generateNomorPendaftaran()` - Generate nomor in format PPDB/TAHUN/URUTAN
- `getWithRelations($id = null)` - Join with users & tahun_ajaran
- `getByUser($userId, $tahunAjaranId)` - Get user's registration
- `countByStatus($status, $tahunAjaranId)` - Count by status
- `getStatistics($tahunAjaranId)` - Get summary stats
- `canEdit($id, $userId)` - Check edit permission (draft/pending only)

**Validation Rules:**
```php
[
    'nama_lengkap' => 'required|string|max_length[100]',
    'nik' => 'required|regex_match[/^[0-9]{16}$/]|is_unique[pendaftaran.nik,id,{id}]',
    'tempat_lahir' => 'required|string|max_length[100]',
    'tanggal_lahir' => 'required|valid_date',
    'jenis_kelamin' => 'required|in_list[L,P]',
    'agama' => 'required|in_list[Islam,Kristen,Katolik,Hindu,Buddha,Konghucu]',
    'alamat' => 'required|string',
    'kelurahan' => 'required|string',
    'kecamatan' => 'required|string',
    'kota_kabupaten' => 'required|string',
    'provinsi' => 'required|string',
]
```

### OrangTuaModel
**File:** `app/Models/OrangTuaModel.php`

**Key Methods:**
- `getByPendaftaran($pendaftaranId)` - Get parent data
- `exists($pendaftaranId)` - Check if exists
- `deleteByPendaftaran($pendaftaranId)` - Cascade delete

**Validation Rules:**
```php
[
    'nama_ayah' => 'required|string|max_length[100]',
    'nik_ayah' => 'regex_match[/^[0-9]{16}$/]|permit_empty',
    'pekerjaan_ayah' => 'required|string|max_length[100]',
    'penghasilan_ayah' => 'required|in_list[<500rb,500rb-1jt,1jt-3jt,3jt-5jt,>5jt]',
    'telepon_ayah' => 'required|regex_match[/^08[0-9]{8,10}$/]',
    'nama_ibu' => 'required|string|max_length[100]',
    'nik_ibu' => 'regex_match[/^[0-9]{16}$/]|permit_empty',
    'pekerjaan_ibu' => 'required|string|max_length[100]',
    'penghasilan_ibu' => 'required|in_list[<500rb,500rb-1jt,1jt-3jt,3jt-5jt,>5jt]',
    'telepon_ibu' => 'required|regex_match[/^08[0-9]{8,10}$/]',
]
```

### DokumenModel
**File:** `app/Models/DokumenModel.php`

**Key Methods:**
- `getByPendaftaran($pendaftaranId)` - Get all docs for registration
- `getByJenis($pendaftaranId, $jenis)` - Get specific doc type
- `isAllDocumentsUploaded($pendaftaranId)` - Check all 3 required docs
- `getStats($pendaftaranId)` - Count by verification status

**File Upload Rules:**
- **KK & Akta:** Max 2MB, type: pdf, jpg, jpeg, png
- **Foto:** Max 1MB, type: jpg, jpeg, png
- **Storage:** `public/uploads/dokumen/`
- **Naming:** `TAHUN_NOMOR_JENIS_TIMESTAMP.ext`

## Controllers

### User\Pendaftaran Controller
**File:** `app/Controllers/User/Pendaftaran.php`

**Routes:**
- `GET /user/pendaftaran/create` - Create new registration
- `GET /user/pendaftaran/form/{id}/step/{step}` - Show form for step
- `POST /user/pendaftaran/store-data-siswa` - Save step 1 (AJAX)
- `POST /user/pendaftaran/store-data-orangtua/{id}` - Save step 2 (AJAX)
- `POST /user/pendaftaran/upload-dokumen/{id}` - Upload files (AJAX)
- `POST /user/pendaftaran/submit/{id}` - Final submit
- `GET /user/pendaftaran/preview/{id}` - Preview registration
- `GET /user/pendaftaran/view/{id}` - View submitted registration
- `GET /user/pendaftaran/edit/{id}` - Edit registration (if draft/pending)

**Methods Detail:**

#### create()
- Create draft pendaftaran
- Assign nomor_pendaftaran
- Redirect to form step 1

#### form($id, $step)
- Display form based on step (1-4)
- Load existing data if edit
- Server-side step validation

#### storeDataSiswa()
- POST /user/pendaftaran/store-data-siswa
- Validate age (3-7 years old)
- Save to pendaftaran table
- Return JSON response

#### storeDataOrangtua($id)
- POST /user/pendaftaran/store-data-orangtua/{id}
- Validate father != mother (different people)
- Save to orang_tua table
- Return JSON response

#### uploadDokumen($id)
- POST /user/pendaftaran/upload-dokumen/{id}
- Handle multi-file upload
- Validate file size & type
- Store in public/uploads/dokumen/
- Save metadata to dokumen table

#### submit($id)
- POST /user/pendaftaran/submit/{id}
- Verify all steps completed
- Check all documents uploaded
- Update status: draft → pending
- Send email notification

#### edit($id)
- GET /user/pendaftaran/edit/{id}
- Load existing data
- Check ownership & edit permission
- Allow modification for draft/pending

#### preview($id)
- GET /user/pendaftaran/preview/{id}
- Show all data before submit
- Display uploaded documents

#### view($id)
- GET /user/pendaftaran/view/{id}
- Show submitted registration
- Admin & owner can view

### Admin\Pendaftaran Controller
**File:** `app/Controllers/Admin/Pendaftaran.php`

**Routes:**
- `GET /admin/pendaftaran` - List all registrations
- `GET /admin/pendaftaran/view/{id}` - View detail
- `GET /admin/pendaftaran/accept/{id}` - Accept registration
- `POST /admin/pendaftaran/reject/{id}` - Reject registration
- `POST /admin/pendaftaran/{id}/status` - Update status
- `GET /admin/pendaftaran/export` - Export (placeholder)

**Methods Detail:**

#### index()
- Display all registrations with pagination
- Filters: tahun_ajaran_id, status
- Paginate 20 per page
- Show nomor, nama, tahun, status, tanggal

#### view($id)
- Show full registration detail
- Display student data
- Display parent data
- Display uploaded documents with preview
- Show status & keterangan

#### accept($id)
- Verify all documents uploaded
- Update status: pending → diterima
- Redirect to detail page

#### reject($id)
- POST with keterangan (reason)
- Update status: pending → ditolak
- Save rejection reason

#### updateStatus($id)
- AJAX endpoint for status update
- Return JSON response

#### export()
- Placeholder for Excel/PDF export
- Will implement in Phase 5

## Views

### User Views

#### user/pendaftaran/step1.php
**Form Fields:**
- Nama Lengkap (text, required)
- NIK (16 digits, required)
- Tempat Lahir (text, required)
- Tanggal Lahir (date, required)
- Jenis Kelamin (radio L/P, required)
- Agama (dropdown, required)
- Alamat Lengkap (textarea, required)
- RT/RW (text, optional)
- Kelurahan (text, required)
- Kecamatan (text, required)
- Kota/Kabupaten (text, required)
- Provinsi (text, required)
- Kode Pos (text, optional)

**Validation:**
- Age check: 3-7 years old
- NIK format: 16 digits
- Client-side Bootstrap validation
- Server-side CodeIgniter validation

**Features:**
- 25% progress indicator
- Auto-save with AJAX
- Error message display
- Loading state on submit
- Next button to step 2

#### user/pendaftaran/step2.php
**Form Sections:**
1. **Data Ayah**
   - Nama Ayah (required)
   - Pekerjaan Ayah (required)
   - Penghasilan Ayah (dropdown, required)
   - Telepon Ayah (08xxx format, required)
   - NIK Ayah (optional)

2. **Data Ibu**
   - Same fields as Ayah

3. **Data Wali** (optional)
   - Nama Wali
   - Hubungan Wali (Parent relationship)
   - Pekerjaan Wali
   - Telepon Wali

**Validation:**
- Telepon format: 08[0-9]{8,10}
- Penghasilan: dropdown selection
- Father ≠ Mother check
- NIK optional but if provided must be 16 digits

**Features:**
- 50% progress indicator
- Save with AJAX
- Previous/Next buttons
- Warning if father = mother

#### user/pendaftaran/step3.php
**File Uploads:**
- Kartu Keluarga (KK)
  - Max 2MB
  - Allowed: PDF, JPG, PNG
  - Status indicator
  - Drag-drop upload
  - Preview for images

- Akta Lahir
  - Max 2MB
  - Allowed: PDF, JPG, PNG
  - Status indicator
  - Drag-drop upload
  - Preview for images

- Foto 3x4
  - Max 1MB
  - Allowed: JPG, PNG
  - Status indicator
  - Drag-drop upload
  - Live preview

**Features:**
- 75% progress indicator
- Individual upload forms
- File preview with thumbnails
- Real-time file info (size, type)
- Status badges (Not Uploaded, Uploaded, Verified)
- Previous/Next buttons
- Upload progress bar

#### user/pendaftaran/step4.php
**Display Sections:**
- All step 1 data (read-only)
- All step 2 data (read-only)
- All step 3 documents with thumbnails
- Biaya & Estimasi timeline

**Features:**
- 100% progress indicator
- Readonly form fields
- Document image preview
- Confirmation checkbox
- Final submit button
- Terms & conditions
- Back to edit buttons

#### user/pendaftaran/view.php
**Display:**
- Full registration detail (same as step 4)
- Status history (if applicable)
- Keterangan (if rejected)
- Edit button (if draft/pending)
- Download documents button
- Print button

### Admin Views

#### admin/pendaftaran/index.php
**Table Columns:**
- No Pendaftaran
- Nama Siswa
- Tahun Ajaran
- Status
- Tanggal Submit
- Actions (View, Edit Status, Delete)

**Filters:**
- Tahun Ajaran (dropdown)
- Status (dropdown: draft, pending, accepted, rejected)
- Search (by nomor or nama)

**Features:**
- Bootstrap table styling
- Pagination (20 per page)
- Filter form with Apply button
- Export button (modal)
- Total count display
- Responsive design

#### admin/pendaftaran/view.php
**Display Sections:**
1. **Header**
   - Nomor Pendaftaran
   - Nama Siswa
   - Status badge
   - Back button

2. **Data Siswa**
   - All fields in read-only mode
   - 2-column layout

3. **Data Alamat**
   - All fields in read-only mode
   - 2-column layout

4. **Data Orang Tua**
   - Data Ayah (read-only)
   - Data Ibu (read-only)
   - Data Wali (if exists, read-only)

5. **Dokumen**
   - KK with image preview
   - Akta with image preview
   - Foto with image preview
   - Download links
   - View in modal

6. **Action Buttons** (if status pending)
   - Terima button
   - Tolak button (opens modal for reason)

7. **Reject Modal**
   - Textarea for keterangan
   - Batal & Tolak buttons

**Features:**
- Responsive grid layout
- Document image modals
- Status management buttons
- Bootstrap styling
- Print-friendly layout

## Routes Configuration

**File:** `app/Config/Routes.php`

```php
// User Routes (protected by auth + role:orang_tua)
$routes->group('user', ['filter' => ['auth', 'role:orang_tua']], function($routes) {
    $routes->get('pendaftaran/create', 'User\Pendaftaran::create');
    $routes->get('pendaftaran/form/(:num)/step/(:num)', 'User\Pendaftaran::form/$1/$2');
    $routes->get('pendaftaran/edit/(:num)', 'User\Pendaftaran::edit/$1');
    $routes->get('pendaftaran/preview/(:num)', 'User\Pendaftaran::preview/$1');
    $routes->get('pendaftaran/view/(:num)', 'User\Pendaftaran::view/$1');
    $routes->post('pendaftaran/store-data-siswa', 'User\Pendaftaran::storeDataSiswa');
    $routes->post('pendaftaran/store-data-orangtua/(:num)', 'User\Pendaftaran::storeDataOrangtua/$1');
    $routes->post('pendaftaran/upload-dokumen/(:num)', 'User\Pendaftaran::uploadDokumen/$1');
    $routes->post('pendaftaran/submit/(:num)', 'User\Pendaftaran::submit/$1');
});

// Admin Routes (protected by auth + role:admin)
$routes->group('admin', ['filter' => ['auth', 'role:admin']], function($routes) {
    $routes->get('pendaftaran', 'Admin\Pendaftaran::index');
    $routes->get('pendaftaran/view/(:num)', 'Admin\Pendaftaran::view/$1');
    $routes->get('pendaftaran/accept/(:num)', 'Admin\Pendaftaran::accept/$1');
    $routes->post('pendaftaran/reject/(:num)', 'Admin\Pendaftaran::reject/$1');
    $routes->post('pendaftaran/(:num)/status', 'Admin\Pendaftaran::updateStatus/$1');
});
```

## Testing Checklist

### User Flow Testing

- [ ] Create new registration (GET /user/pendaftaran/create)
  - Generates draft pendaftaran
  - Assigns nomor_pendaftaran
  - Redirects to step 1 form

- [ ] Step 1 - Data Siswa
  - Enter valid student data
  - Age validation works (3-7 years)
  - NIK validation works (16 digits)
  - Save with AJAX
  - Error messages display

- [ ] Step 2 - Data Orang Tua
  - Enter valid parent data
  - Telepon format validation (08xxx)
  - Penghasilan dropdown works
  - Father ≠ Mother validation
  - Save with AJAX
  - Error messages display

- [ ] Step 3 - Upload Dokumen
  - Upload KK (max 2MB)
  - Upload Akta (max 2MB)
  - Upload Foto (max 1MB)
  - File preview displays
  - File type validation works
  - File size validation works
  - Status badges update

- [ ] Step 4 - Review & Submit
  - All data displays correctly
  - Documents show with thumbnails
  - Confirmation checkbox required
  - Submit button works
  - Status updates to pending
  - Email notification sent

- [ ] Edit Registration
  - Can edit if status draft/pending
  - Changes save correctly
  - Cannot edit if status diterima/ditolak

- [ ] View Submitted Registration
  - Can view own registration
  - All data displays correctly
  - Documents display with preview

### Admin Flow Testing

- [ ] List Pendaftaran
  - All registrations display
  - Filter by tahun_ajaran works
  - Filter by status works
  - Pagination works (20 per page)
  - Search functionality works

- [ ] View Detail
  - All student data displays
  - All parent data displays
  - All documents display with preview
  - Status badge shows current status

- [ ] Accept Registration
  - Status changes to diterima
  - Redirect to detail view
  - Success message displays

- [ ] Reject Registration
  - Reject modal opens
  - Keterangan textarea works
  - Status changes to ditolak
  - Keterangan saves
  - Success message displays

## File Locations

```
app/
├── Controllers/
│   ├── Admin/
│   │   └── Pendaftaran.php          (NEW)
│   └── User/
│       ├── Pendaftaran.php          (NEW)
│       └── Dashboard.php            (updated)
├── Models/
│   ├── PendaftaranModel.php         (updated)
│   ├── OrangTuaModel.php            (NEW)
│   ├── DokumenModel.php             (NEW)
│   └── TahunAjaranModel.php         (exists)
└── Views/
    ├── admin/
    │   └── pendaftaran/
    │       ├── index.php            (NEW)
    │       └── view.php             (NEW)
    └── user/
        ├── pendaftaran/
        │   ├── step1.php            (NEW)
        │   ├── step2.php            (NEW)
        │   ├── step3.php            (NEW)
        │   ├── step4.php            (NEW)
        │   ├── view.php             (NEW)
        │   ├── edit.php             (NEW)
        │   └── preview.php          (NEW)
        └── dashboard.php            (updated)

public/
└── uploads/
    └── dokumen/                     (NEW - for file storage)
```

## Access Control

### Role-Based Filtering

**OrangTua (Parents)**
- ✅ Can create registration
- ✅ Can edit own registration (draft/pending status only)
- ✅ Can view own registration
- ✅ Can upload documents
- ❌ Cannot view other parent's registrations
- ❌ Cannot change status manually

**Admin**
- ✅ Can view all registrations
- ✅ Can view registration detail
- ✅ Can change status (accept/reject)
- ✅ Can verify documents
- ✅ Can export (Phase 5)
- ❌ Cannot create registration
- ❌ Cannot delete registration

## Email Notifications

### Templates (to be implemented)

1. **Registrasi Diterima** (when status = pending)
   - To: Parent email
   - Subject: Pendaftaran Diterima - {nomor_pendaftaran}
   - Content: Confirmation + next steps

2. **Registrasi Diverifikasi** (when status = diverifikasi)
   - To: Parent email
   - Subject: Pendaftaran Diverifikasi - {nomor_pendaftaran}
   - Content: Verification confirmation + timeline

3. **Registrasi Diterima** (when status = diterima)
   - To: Parent email
   - Subject: Selamat! Anak Anda Diterima - {nomor_pendaftaran}
   - Content: Acceptance notification + next steps

4. **Registrasi Ditolak** (when status = ditolak)
   - To: Parent email
   - Subject: Pemberitahuan Penolakan - {nomor_pendaftaran}
   - Content: Rejection reason + appeal process

## Known Issues & Limitations

1. **Export Feature** - Placeholder only, needs PHPExcel/TCPDF implementation
2. **Email Templates** - Not yet implemented, using hardcoded messages
3. **Document Verification** - Admin can only view, verification in Phase 6
4. **Payment Verification** - Integration with Phase 5 (Pembayaran)
5. **Appeal Process** - Not implemented, can be added in future phases

## Next Steps (Phase 5)

1. **Modul Pembayaran** (Days 43-56)
   - Payment gateway integration
   - Receipt generation
   - Payment verification workflow
   - Admin payment dashboard

2. **Modul Penerimaan** (Days 57-70)
   - Acceptance decision management
   - Document verification completion
   - Enrollment confirmation
   - Class assignment

3. **Modul Laporan** (Days 71-84)
   - Registration statistics
   - Payment summary
   - Enrollment reports
   - Export to Excel/PDF

## Completed Commits

```
8da88ec - Add admin pendaftaran detail view and routes
[previous commits for Phase 4 implementation...]
```

## Summary

Phase 4 berhasil mengimplementasikan modul pendaftaran lengkap dengan:
- ✅ 4-step registration form yang user-friendly
- ✅ Multi-file document upload dengan validation
- ✅ Role-based access control
- ✅ Admin registration management dashboard
- ✅ User dashboard dengan tracking status
- ✅ Complete CRUD operations untuk registrations
- ✅ Database schema dengan relationships
- ✅ Input validation & error handling
- ✅ Responsive UI dengan Bootstrap
- ✅ GitHub commits & version control

Ready untuk lanjut ke Phase 5 (Modul Pembayaran).
