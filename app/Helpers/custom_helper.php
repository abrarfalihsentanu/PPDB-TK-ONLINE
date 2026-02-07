<?php

/**
 * Custom Helper Functions
 * File ini berisi fungsi-fungsi helper custom untuk aplikasi PPDB
 */

if (!function_exists('formatRupiah')) {
    /**
     * Format angka menjadi format Rupiah
     *
     * @param int|float $angka
     * @return string
     */
    function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('parseRupiah')) {
    /**
     * Parse format Rupiah menjadi angka
     *
     * @param string $rupiah
     * @return int
     */
    function parseRupiah($rupiah)
    {
        return (int) preg_replace('/[^0-9]/', '', $rupiah);
    }
}

if (!function_exists('getStatusBadge')) {
    /**
     * Get badge class based on status
     *
     * @param string $status
     * @return string
     */
    function getStatusBadge($status)
    {
        $badges = [
            'draft' => 'secondary',
            'pending' => 'warning',
            'pembayaran_verified' => 'info',
            'dokumen_ditolak' => 'danger',
            'diverifikasi' => 'primary',
            'diterima' => 'success',
            'ditolak' => 'danger'
        ];

        return $badges[$status] ?? 'secondary';
    }
}

if (!function_exists('getStatusLabel')) {
    /**
     * Get human-readable label for status
     *
     * @param string $status
     * @return string
     */
    function getStatusLabel($status)
    {
        $labels = [
            'draft' => 'Draft',
            'pending' => 'Menunggu Verifikasi',
            'pembayaran_verified' => 'Pembayaran Terverifikasi',
            'dokumen_ditolak' => 'Dokumen Ditolak',
            'diverifikasi' => 'Diverifikasi',
            'diterima' => 'DITERIMA',
            'ditolak' => 'Ditolak'
        ];

        return $labels[$status] ?? ucfirst($status);
    }
}

if (!function_exists('hitungUmur')) {
    /**
     * Hitung umur berdasarkan tanggal lahir
     *
     * @param string $tanggalLahir
     * @return int
     */
    function hitungUmur($tanggalLahir)
    {
        $birthDate = new DateTime($tanggalLahir);
        $today = new DateTime('today');
        $umur = $birthDate->diff($today)->y;

        return $umur;
    }
}

if (!function_exists('indonesianDate')) {
    /**
     * Format tanggal ke format Indonesia
     *
     * @param string $date
     * @param bool $withTime
     * @return string
     */
    function indonesianDate($date, $withTime = false)
    {
        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $timestamp = strtotime($date);
        $tanggal = date('d', $timestamp);
        $bulanNum = date('n', $timestamp);
        $tahun = date('Y', $timestamp);

        $result = $tanggal . ' ' . $bulan[$bulanNum] . ' ' . $tahun;

        if ($withTime) {
            $result .= ' ' . date('H:i', $timestamp);
        }

        return $result;
    }
}

if (!function_exists('timeAgo')) {
    /**
     * Get time ago format (contoh: 5 menit yang lalu)
     *
     * @param string $datetime
     * @return string
     */
    function timeAgo($datetime)
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return $mins . ' menit yang lalu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' jam yang lalu';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' hari yang lalu';
        } else {
            return indonesianDate($datetime);
        }
    }
}

if (!function_exists('generateNomorPendaftaran')) {
    /**
     * Generate nomor pendaftaran otomatis
     *
     * @param int $tahunAjaranId
     * @param string $tahun
     * @param int $urutan
     * @return string
     */
    function generateNomorPendaftaran($tahunAjaranId, $tahun, $urutan)
    {
        return sprintf('PPDB/%s/%03d', $tahun, $urutan);
    }
}

if (!function_exists('sanitizeFilename')) {
    /**
     * Sanitize filename untuk upload
     *
     * @param string $filename
     * @return string
     */
    function sanitizeFilename($filename)
    {
        // Remove any non-alphanumeric characters except dots and dashes
        $filename = preg_replace('/[^A-Za-z0-9\.\-]/', '_', $filename);

        // Remove multiple consecutive underscores
        $filename = preg_replace('/_+/', '_', $filename);

        return $filename;
    }
}

if (!function_exists('getFileIcon')) {
    /**
     * Get icon class based on file extension
     *
     * @param string $filename
     * @return string
     */
    function getFileIcon($filename)
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $icons = [
            'pdf' => 'ri-file-pdf-line text-danger',
            'doc' => 'ri-file-word-line text-primary',
            'docx' => 'ri-file-word-line text-primary',
            'xls' => 'ri-file-excel-line text-success',
            'xlsx' => 'ri-file-excel-line text-success',
            'jpg' => 'ri-image-line text-warning',
            'jpeg' => 'ri-image-line text-warning',
            'png' => 'ri-image-line text-warning',
            'gif' => 'ri-image-line text-warning',
            'zip' => 'ri-file-zip-line text-secondary',
            'rar' => 'ri-file-zip-line text-secondary'
        ];

        return $icons[$ext] ?? 'ri-file-line text-muted';
    }
}

if (!function_exists('formatFileSize')) {
    /**
     * Format file size to human readable format
     *
     * @param int $bytes
     * @return string
     */
    function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }
}
