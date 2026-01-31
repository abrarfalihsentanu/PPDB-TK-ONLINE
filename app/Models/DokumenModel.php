<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    protected $table            = 'dokumen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pendaftaran_id',
        'jenis_dokumen',
        'nama_file_asli',
        'nama_file',
        'path_file',
        'ukuran_file',
        'tipe_file',
        'status_verifikasi',
        'keterangan_verifikasi',
        'verifikasi_at',
        'verified_by',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'pendaftaran_id'   => 'required|numeric',
        'jenis_dokumen'    => 'required|in_list[kk,akta,foto]',
        'nama_file'        => 'required|min_length[3]',
        'path_file'        => 'required|min_length[3]',
        'ukuran_file'      => 'required|numeric',
        'tipe_file'        => 'required',
        'status_verifikasi' => 'required|in_list[pending,verified,rejected]',
    ];

    protected $validationMessages = [
        'pendaftaran_id' => [
            'required' => 'ID pendaftaran harus diisi',
        ],
        'jenis_dokumen' => [
            'required' => 'Jenis dokumen harus dipilih',
            'in_list'  => 'Jenis dokumen tidak valid',
        ],
        'status_verifikasi' => [
            'required' => 'Status verifikasi harus diisi',
            'in_list'  => 'Status verifikasi tidak valid',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get dokumen by pendaftaran
     */
    public function getByPendaftaran($pendaftaranId)
    {
        return $this->where('pendaftaran_id', $pendaftaranId)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

    /**
     * Get single dokumen by pendaftaran and jenis
     */
    public function getByJenis($pendaftaranId, $jenisDokumen)
    {
        return $this->where('pendaftaran_id', $pendaftaranId)
            ->where('jenis_dokumen', $jenisDokumen)
            ->first();
    }

    /**
     * Check if dokumen exists for jenis
     */
    public function existsByJenis($pendaftaranId, $jenisDokumen)
    {
        return $this->where('pendaftaran_id', $pendaftaranId)
            ->where('jenis_dokumen', $jenisDokumen)
            ->countAllResults() > 0;
    }

    /**
     * Get total dokumen count for pendaftaran
     */
    public function countByPendaftaran($pendaftaranId)
    {
        return $this->where('pendaftaran_id', $pendaftaranId)->countAllResults();
    }

    /**
     * Check if all required dokumen uploaded (kk, akta, foto)
     */
    public function isAllDocumentsUploaded($pendaftaranId)
    {
        $requiredDocs = ['kk', 'akta', 'foto'];
        
        foreach ($requiredDocs as $jenis) {
            if (!$this->existsByJenis($pendaftaranId, $jenis)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get dokumen statistics by pendaftaran
     */
    public function getStats($pendaftaranId)
    {
        $builder = $this->db->table($this->table)
            ->where('pendaftaran_id', $pendaftaranId);

        return [
            'total'           => $builder->countAllResults(false),
            'pending'         => $builder->where('status_verifikasi', 'pending')->countAllResults(false),
            'verified'        => $builder->where('status_verifikasi', 'verified')->countAllResults(false),
            'rejected'        => $builder->where('status_verifikasi', 'rejected')->countAllResults(false),
            'all_uploaded'    => $this->isAllDocumentsUploaded($pendaftaranId),
        ];
    }

    /**
     * Delete dokumen by pendaftaran and jenis
     */
    public function deleteByJenis($pendaftaranId, $jenisDokumen)
    {
        return $this->where('pendaftaran_id', $pendaftaranId)
            ->where('jenis_dokumen', $jenisDokumen)
            ->delete();
    }

    /**
     * Delete all dokumen for pendaftaran
     */
    public function deleteByPendaftaran($pendaftaranId)
    {
        return $this->where('pendaftaran_id', $pendaftaranId)->delete();
    }
}
