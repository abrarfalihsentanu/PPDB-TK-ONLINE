<?php

namespace App\Models;

use CodeIgniter\Model;

class OrangTuaModel extends Model
{
    protected $table            = 'orang_tua';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pendaftaran_id',
        'nama_ayah',
        'nik_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'telepon_ayah',
        'nama_ibu',
        'nik_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'telepon_ibu',
        'nama_wali',
        'nik_wali',
        'pekerjaan_wali',
        'hubungan_wali',
        'telepon_wali',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'pendaftaran_id' => 'required|numeric',
        'nama_ayah'      => 'required|min_length[3]|max_length[200]',
        'nik_ayah'       => 'numeric|exact_length[16]',
        'pekerjaan_ayah' => 'required|min_length[3]|max_length[100]',
        'penghasilan_ayah' => 'required|in_list[< 1 juta,1-2 juta,2-5 juta,5-10 juta,> 10 juta]',
        'telepon_ayah'   => 'required|regex_match[/^08[0-9]{8,10}$/]',
        'nama_ibu'       => 'required|min_length[3]|max_length[200]',
        'nik_ibu'        => 'numeric|exact_length[16]',
        'pekerjaan_ibu'  => 'required|min_length[3]|max_length[100]',
        'penghasilan_ibu' => 'required|in_list[< 1 juta,1-2 juta,2-5 juta,5-10 juta,> 10 juta]',
        'telepon_ibu'    => 'required|regex_match[/^08[0-9]{8,10}$/]',
        'nama_wali'      => 'permit_empty|min_length[3]|max_length[200]',
        'nik_wali'       => 'permit_empty|numeric|exact_length[16]',
        'pekerjaan_wali' => 'permit_empty|min_length[3]|max_length[100]',
        'hubungan_wali'  => 'permit_empty|in_list[kakek,nenek,paman,tante,keluarga lain]',
        'telepon_wali'   => 'permit_empty|regex_match[/^08[0-9]{8,10}$/]',
    ];

    protected $validationMessages = [
        'nama_ayah' => [
            'required'    => 'Nama ayah harus diisi',
            'min_length'  => 'Nama ayah minimal 3 karakter',
        ],
        'nik_ayah' => [
            'numeric'      => 'NIK ayah harus berupa angka',
            'exact_length' => 'NIK ayah harus 16 digit',
        ],
        'pekerjaan_ayah' => [
            'required'   => 'Pekerjaan ayah harus diisi',
            'min_length' => 'Pekerjaan ayah minimal 3 karakter',
        ],
        'penghasilan_ayah' => [
            'required' => 'Penghasilan ayah harus dipilih',
            'in_list'  => 'Penghasilan ayah tidak valid',
        ],
        'telepon_ayah' => [
            'required'   => 'Nomor telepon ayah harus diisi',
            'regex_match' => 'Format nomor telepon ayah harus 08xxx dengan minimal 10 digit',
        ],
        'nama_ibu' => [
            'required'    => 'Nama ibu harus diisi',
            'min_length'  => 'Nama ibu minimal 3 karakter',
        ],
        'nik_ibu' => [
            'numeric'      => 'NIK ibu harus berupa angka',
            'exact_length' => 'NIK ibu harus 16 digit',
        ],
        'pekerjaan_ibu' => [
            'required'   => 'Pekerjaan ibu harus diisi',
            'min_length' => 'Pekerjaan ibu minimal 3 karakter',
        ],
        'penghasilan_ibu' => [
            'required' => 'Penghasilan ibu harus dipilih',
            'in_list'  => 'Penghasilan ibu tidak valid',
        ],
        'telepon_ibu' => [
            'required'   => 'Nomor telepon ibu harus diisi',
            'regex_match' => 'Format nomor telepon ibu harus 08xxx dengan minimal 10 digit',
        ],
        'telepon_wali' => [
            'regex_match' => 'Format nomor telepon wali harus 08xxx dengan minimal 10 digit',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get by pendaftaran id
     */
    public function getByPendaftaran($pendaftaranId)
    {
        return $this->where('pendaftaran_id', $pendaftaranId)->first();
    }

    /**
     * Check if parent data exists for pendaftaran
     */
    public function exists($pendaftaranId)
    {
        return $this->where('pendaftaran_id', $pendaftaranId)->countAllResults() > 0;
    }

    /**
     * Delete by pendaftaran id
     */
    public function deleteByPendaftaran($pendaftaranId)
    {
        return $this->where('pendaftaran_id', $pendaftaranId)->delete();
    }
}
