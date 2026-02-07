<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftaranModel extends Model
{
    protected $table            = 'pendaftaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'tahun_ajaran_id',
        'nomor_pendaftaran',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kota_kabupaten',
        'provinsi',
        'kode_pos',
        'status_pendaftaran',
        'keterangan'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nama_lengkap'  => 'required|min_length[3]|max_length[200]',
        'nik'           => 'required|numeric|exact_length[16]',
        'tempat_lahir'  => 'required',
        'tanggal_lahir' => 'required|valid_date',
        'jenis_kelamin' => 'required|in_list[L,P]',
        'agama'         => 'required|in_list[Islam,Kristen,Katolik,Hindu,Buddha,Konghucu]',
        'alamat'        => 'required',
        'kelurahan'     => 'required',
        'kecamatan'     => 'required',
        'kota_kabupaten' => 'required',
        'provinsi'      => 'required',
    ];

    protected $validationMessages = [
        'nama_lengkap' => [
            'required' => 'Nama lengkap harus diisi',
        ],
        'nik' => [
            'required'     => 'NIK harus diisi',
            'numeric'      => 'NIK harus berupa angka',
            'exact_length' => 'NIK harus 16 digit',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateNomorPendaftaran'];

    /**
     * Generate nomor pendaftaran otomatis
     */
    protected function generateNomorPendaftaran(array $data)
    {
        if (!isset($data['data']['nomor_pendaftaran'])) {
            $tahunAjaranId = $data['data']['tahun_ajaran_id'] ?? null;

            if ($tahunAjaranId) {
                $tahunAjaranModel = new TahunAjaranModel();
                $tahunAjaran = $tahunAjaranModel->find($tahunAjaranId);

                if ($tahunAjaran) {
                    // Ambil tahun dari nama_tahun (misal: 2025/2026 -> 2025)
                    $tahun = explode('/', $tahunAjaran->nama_tahun)[0];

                    // Hitung urutan pendaftaran untuk tahun ajaran ini
                    $lastNumber = $this->where('tahun_ajaran_id', $tahunAjaranId)
                        ->countAllResults() + 1;

                    // Format: PPDB/TAHUN/URUTAN (contoh: PPDB/2025/001)
                    $data['data']['nomor_pendaftaran'] = sprintf(
                        'PPDB/%s/%03d',
                        $tahun,
                        $lastNumber
                    );
                }
            }
        }

        return $data;
    }

    /**
     * Get pendaftaran with relations
     * Returns QueryBuilder object for chaining, or single object if $id provided
     */
    public function getWithRelations($id = null)
    {
        $builder = $this->select('pendaftaran.*, users.email, users.username, tahun_ajaran.nama_tahun, tahun_ajaran.biaya_pendaftaran')
            ->join('users', 'users.id = pendaftaran.user_id')
            ->join('tahun_ajaran', 'tahun_ajaran.id = pendaftaran.tahun_ajaran_id');

        if ($id !== null) {
            return $builder->find($id);
        }

        // Return builder for method chaining
        return $builder;
    }

    /**
     * Get pendaftaran by user
     */
    public function getByUser($userId, $tahunAjaranId = null)
    {
        $builder = $this->where('user_id', $userId);

        if ($tahunAjaranId) {
            $builder->where('tahun_ajaran_id', $tahunAjaranId);
        }

        return $builder->first();
    }

    /**
     * Count by status
     */
    public function countByStatus($status, $tahunAjaranId = null)
    {
        $builder = $this->where('status_pendaftaran', $status);

        if ($tahunAjaranId) {
            $builder->where('tahun_ajaran_id', $tahunAjaranId);
        }

        return $builder->countAllResults();
    }

    /**
     * Get statistics
     */
    public function getStatistics($tahunAjaranId = null)
    {
        $builder = $this->db->table($this->table);

        if ($tahunAjaranId) {
            $builder->where('tahun_ajaran_id', $tahunAjaranId);
        }

        return [
            'total'             => $builder->countAllResults(false),
            'draft'             => $builder->where('status_pendaftaran', 'draft')->countAllResults(false),
            'pending'           => $builder->where('status_pendaftaran', 'pending')->countAllResults(false),
            'pembayaran_verified' => $builder->where('status_pendaftaran', 'pembayaran_verified')->countAllResults(false),
            'diverifikasi'      => $builder->where('status_pendaftaran', 'diverifikasi')->countAllResults(false),
            'diterima'          => $builder->where('status_pendaftaran', 'diterima')->countAllResults(false),
            'ditolak'           => $builder->where('status_pendaftaran', 'ditolak')->countAllResults(false),
        ];
    }

    /**
     * Check if user can edit
     */
    public function canEdit($id, $userId)
    {
        $pendaftaran = $this->find($id);

        if (!$pendaftaran) {
            return false;
        }

        // Hanya bisa edit jika milik user sendiri dan status masih draft atau pending
        return $pendaftaran->user_id == $userId
            && in_array($pendaftaran->status_pendaftaran, ['draft', 'pending']);
    }
    /**
     * Get pending pembayaran verification untuk notifikasi
     */
    public function getPendingPaymentVerification($limit = 5)
    {
        return $this->select('pendaftaran.*, users.email, pembayaran.id as pembayaran_id')
            ->join('pembayaran', 'pembayaran.pendaftaran_id = pendaftaran.id', 'left')
            ->join('users', 'users.id = pendaftaran.user_id', 'left')
            ->where('pembayaran.status_bayar', 'pending')
            ->where('pendaftaran.status_pendaftaran', 'pending')
            ->orderBy('pembayaran.created_at', 'DESC')
            ->limit($limit)
            ->find();
    }

    /**
     * Count pending pembayaran verification
     */
    public function countPendingPaymentVerification()
    {
        return $this->select('COUNT(DISTINCT pendaftaran.id) as count', false)
            ->join('pembayaran', 'pembayaran.pendaftaran_id = pendaftaran.id', 'left')
            ->where('pembayaran.status_bayar', 'pending')
            ->where('pendaftaran.status_pendaftaran', 'pending')
            ->get()
            ->getRow()
            ->count ?? 0;
    }

    /**
     * Get pembayaran terverifikasi yang menunggu verifikasi dokumen
     */
    public function getPendingDocumentVerification($limit = 5)
    {
        return $this->select('pendaftaran.*, users.email')
            ->join('users', 'users.id = pendaftaran.user_id', 'left')
            ->where('pendaftaran.status_pendaftaran', 'pembayaran_verified')
            ->orderBy('pendaftaran.updated_at', 'ASC')
            ->limit($limit)
            ->find();
    }

    /**
     * Count pending dokumen verification
     */
    public function countPendingDocumentVerification()
    {
        return $this->where('status_pendaftaran', 'pembayaran_verified')->countAllResults();
    }

    /**
     * Get pending penerimaan (siap untuk pengumuman)
     */
    public function getPendingAcceptanceAnnounce($limit = 5)
    {
        return $this->select('pendaftaran.*, users.email')
            ->join('users', 'users.id = pendaftaran.user_id', 'left')
            ->where('pendaftaran.status_pendaftaran', 'diverifikasi')
            ->orderBy('pendaftaran.created_at', 'ASC')
            ->limit($limit)
            ->find();
    }

    /**
     * Count pending penerimaan
     */
    public function countPendingAcceptanceAnnounce()
    {
        return $this->where('status_pendaftaran', 'diverifikasi')->countAllResults();
    }

    /**
     * Get dengan filter advanced
     */
    public function getFiltered($filters = [], $limit = 10, $offset = 0)
    {
        $builder = $this->select('pendaftaran.*, users.email, users.username, tahun_ajaran.nama_tahun')
            ->join('users', 'users.id = pendaftaran.user_id', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = pendaftaran.tahun_ajaran_id', 'left');

        // Filter by status
        if (!empty($filters['status_pendaftaran'])) {
            $builder->where('pendaftaran.status_pendaftaran', $filters['status_pendaftaran']);
        }

        // Filter by tahun ajaran
        if (!empty($filters['tahun_ajaran_id'])) {
            $builder->where('pendaftaran.tahun_ajaran_id', $filters['tahun_ajaran_id']);
        }

        // Filter by jenis kelamin
        if (!empty($filters['jenis_kelamin'])) {
            $builder->where('pendaftaran.jenis_kelamin', $filters['jenis_kelamin']);
        }

        // Filter by agama
        if (!empty($filters['agama'])) {
            $builder->where('pendaftaran.agama', $filters['agama']);
        }

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $builder->groupStart()
                ->like('pendaftaran.nomor_pendaftaran', $search)
                ->orLike('pendaftaran.nama_lengkap', $search)
                ->orLike('users.email', $search)
                ->orLike('users.username', $search)
                ->groupEnd();
        }

        // Date range
        if (!empty($filters['date_from'])) {
            $builder->where('DATE(pendaftaran.created_at) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $builder->where('DATE(pendaftaran.created_at) <=', $filters['date_to']);
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'DESC';
        $builder->orderBy("pendaftaran.$sortBy", $sortOrder);

        // Pagination
        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder;
    }

    /**
     * Get sisa kuota tahun ajaran
     */
    public function getSisaKuota($tahunAjaranId)
    {
        $tahunAjaranModel = new TahunAjaranModel();
        $tahunAjaran = $tahunAjaranModel->find($tahunAjaranId);

        if (!$tahunAjaran) {
            return 0;
        }

        $diterima = $this->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status_pendaftaran', 'diterima')
            ->countAllResults();

        return max(0, $tahunAjaran->kuota - $diterima);
    }
}
