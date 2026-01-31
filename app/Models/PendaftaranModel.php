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
}
