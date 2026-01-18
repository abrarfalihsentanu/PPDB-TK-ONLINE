# 🧪 PPDB-TK-ONLINE - Feature Testing Checklist

## ✅ Authentication Features Testing

### 📝 Login Page

- **URL:** `http://localhost:8080/auth/login`
- **Test Cases:**
  - [ ] Page loads correctly with modern UI
  - [ ] Form fields: Email, Password, Remember Me checkbox
  - [ ] Password toggle functionality works
  - [ ] Client-side validation works
  - [ ] Test with invalid email format
  - [ ] Test with empty fields

### 🔐 Login Validation

**Test Credentials:**

- Admin: `admin@ppdb.test` / `admin123`
- User: `user@ppdb.test` / `user123`

**Test Cases:**

- [ ] Login dengan email tidak terdaftar → Error message
- [ ] Login dengan password salah → Error message
- [ ] Login dengan email & password benar → Dashboard
- [ ] Admin login → Redirect ke `/admin/dashboard`
- [ ] User (Orang Tua) login → Redirect ke `/user/dashboard`
- [ ] Flash message success ditampilkan
- [ ] Session data tersimpan (user_id, username, email, role)

### ✅ Remember Me

- [ ] Jika checkbox dicentang → Cookie `remember_token` disimpan
- [ ] Cookie disimpan selama 30 hari
- [ ] Token disimpan di database (`remember_token` field)

### 📋 Register Page

- **URL:** `http://localhost:8080/auth/register`
- **Test Cases:**
  - [ ] Page loads correctly
  - [ ] Form fields: Username, Email, Password, Confirm Password
  - [ ] Password strength indicator (jika ada)
  - [ ] Client-side validation works

### 📝 Register Validation

- [ ] Username wajib diisi
- [ ] Username minimal 3 karakter
- [ ] Username tidak boleh duplikat
- [ ] Email wajib diisi
- [ ] Email harus format valid
- [ ] Email tidak boleh duplikat
- [ ] Password wajib diisi
- [ ] Password minimal 8 karakter
- [ ] Confirm password harus match dengan password
- [ ] Success register → Flash message & redirect ke login
- [ ] Data tersimpan di database dengan password ter-hash

### 🔑 Lupa Password

- **URL:** `http://localhost:8080/auth/forgot-password`
- **Test Cases:**
  - [ ] Page loads correctly
  - [ ] Email field validasi
  - [ ] Input email yang tidak terdaftar → Error message
  - [ ] Input email terdaftar → Generate reset token
  - [ ] Flash message dengan reset link ditampilkan
  - [ ] Reset link valid (token ada di database)
  - [ ] Token expiry set 1 jam dari sekarang

### 🔄 Reset Password

- **URL:** `http://localhost:8080/auth/reset-password/{token}`
- **Test Cases:**
  - [ ] Token tidak valid → Error message
  - [ ] Token expired → Error message
  - [ ] Token valid → Show reset form
  - [ ] Password baru wajib diisi
  - [ ] Password minimal 8 karakter
  - [ ] Confirm password harus match
  - [ ] Success reset → Flash message & redirect ke login
  - [ ] Password di database ter-update dengan hash baru
  - [ ] Reset token & expiry dihapus setelah berhasil
  - [ ] Bisa login dengan password baru

### 🚪 Logout

- **Test Cases:**
  - [ ] Logout button/link berfungsi
  - [ ] Session dihapus setelah logout
  - [ ] Redirect ke login page
  - [ ] Flash message logout ditampilkan
  - [ ] Cannot access protected pages setelah logout

### 🔐 Auth Filter (Protection)

- **Test Cases:**
  - [ ] Access `/admin/*` tanpa login → Redirect ke login
  - [ ] Access `/user/*` tanpa login → Redirect ke login
  - [ ] Access `/auth/*` tanpa login → Allowed (public)
  - [ ] Session check berfungsi dengan benar

### 👥 Role-Based Access

- **Admin Role:**
  - [ ] Admin dapat akses `/admin/*`
  - [ ] Admin tidak dapat akses `/user/*` (jika ada restriction)
- **Orang Tua Role:**
  - [ ] Orang Tua dapat akses `/user/*`
  - [ ] Orang Tua tidak dapat akses `/admin/*`

## 🎨 UI/UX Features

### Modern Design

- [ ] Responsive di mobile, tablet, desktop
- [ ] Password toggle eye icon berfungsi
- [ ] Error messages ditampilkan dengan warna merah
- [ ] Success messages ditampilkan dengan warna hijau
- [ ] Form validation feedback langsung

### Validation Messages

- [ ] Client-side validation messages tampil
- [ ] Server-side validation messages tampil
- [ ] Indonesian error messages correct
- [ ] Fields dikembalikan ke form setelah error (withInput)

## 🗄️ Database Integration

### Users Table

- [ ] Field: id, username, email, password, role, status
- [ ] Fields baru: reset_token, reset_token_expiry, remember_token
- [ ] Timestamps: created_at, updated_at, deleted_at
- [ ] Unique constraints: username, email

### Seed Data

- [ ] Admin user tersimpan
- [ ] Demo user tersimpan
- [ ] Password ter-hash dengan PASSWORD_DEFAULT

## 📊 Helper Functions

### Auth Helper

- [ ] `is_logged_in()` - Cek user sudah login
- [ ] `get_user_id()` - Get user ID
- [ ] `get_user_role()` - Get user role
- [ ] `is_admin()` - Cek admin
- [ ] `is_orang_tua()` - Cek orang tua
- [ ] `set_message()` - Set flash message
- [ ] `get_message()` - Get flash message
- [ ] `hash_password()` - Hash password
- [ ] `verify_password()` - Verify password
- [ ] `generate_token()` - Generate random token

## 🔍 Security Features

- [ ] Password ter-hash dengan PASSWORD_DEFAULT (bcrypt)
- [ ] SQL Injection prevention (menggunakan prepared statements)
- [ ] XSS prevention (output escaping)
- [ ] CSRF protection (jika enabled)
- [ ] Session security configured
- [ ] Reset token random & secure
- [ ] Token expiry implemented

## 📝 Routes

- [ ] GET `/` → Redirect ke login
- [ ] GET `/auth/login` → Login page
- [ ] POST `/auth/attempt-login` → Process login
- [ ] GET `/auth/register` → Register page
- [ ] POST `/auth/attempt-register` → Process register
- [ ] GET `/auth/forgot-password` → Forgot password page
- [ ] POST `/auth/process-forgot-password` → Process forgot password
- [ ] GET `/auth/reset-password/:token` → Reset password page
- [ ] POST `/auth/process-reset-password` → Process reset password
- [ ] GET `/auth/logout` → Logout

## 🐛 Known Issues & Notes

- SMTP email configuration not set up yet (reset link shown in flash message)
- Virtual host `ppdb-tk.test` not configured - using `localhost:8080`
- Remember Me feature stores token in database but auto-login on cookie check not implemented yet

## ✅ Overall Status

**Total Tests:** **_ / _**
**Passed:** **_ ✅
**Failed:** _** ❌
**Not Tested:** \_\_\_ ⏳

**Overall Status:** 🔄 In Progress

---

**Last Updated:** 2026-01-18
**Tested By:** Developer
**Environment:** Development (localhost:8080)
