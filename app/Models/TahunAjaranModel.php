<?php

namespace App\Models;

use CodeIgniter\Model;

class TahunAjaranModel extends Model
{
    protected $table            = 'tahun_ajaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_tahun',
        'status',
        'kuota',
        'biaya_pendaftaran',
        'tanggal_buka',
        'tanggal_tutup'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nama_tahun'        => 'required|regex_match[/^\d{4}\/\d{4}$/]|is_unique[tahun_ajaran.nama_tahun,id,{id}]',
        'status'            => 'required|in_list[aktif,nonaktif]',
        'kuota'             => 'required|integer|greater_than[0]',
        'biaya_pendaftaran' => 'required|decimal|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'nama_tahun' => [
            'required'    => 'Nama tahun ajaran harus diisi',
            'regex_match' => 'Format tahun ajaran harus YYYY/YYYY (contoh: 2025/2026)',
            'is_unique'   => 'Tahun ajaran sudah ada',
        ],
        'kuota' => [
            'required'     => 'Kuota harus diisi',
            'integer'      => 'Kuota harus berupa angka',
            'greater_than' => 'Kuota minimal 1',
        ],
        'biaya_pendaftaran' => [
            'required'             => 'Biaya pendaftaran harus diisi',
            'greater_than_equal_to' => 'Biaya pendaftaran minimal 0',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = ['deactivateOthers'];

    /**
     * Nonaktifkan tahun ajaran lain jika status aktif
     */
    protected function deactivateOthers(array $data)
    {
        if (isset($data['data']['status']) && $data['data']['status'] === 'aktif') {
            $this->where('status', 'aktif')
                ->set(['status' => 'nonaktif'])
                ->update();
        }
        return $data;
    }

    /**
     * Get active tahun ajaran
     */
    public function getActive()
    {
        return $this->where('status', 'aktif')->first();
    }

    /**
     * Activate tahun ajaran
     */
    public function activate($id)
    {
        // Nonaktifkan semua tahun ajaran
        $this->set(['status' => 'nonaktif'])->update();

        // Aktifkan tahun ajaran yang dipilih
        return $this->update($id, ['status' => 'aktif']);
    }

    /**
     * Get sisa kuota
     */
    public function getSisaKuota($tahunAjaranId)
    {
        $tahunAjaran = $this->find($tahunAjaranId);

        if (!$tahunAjaran) {
            return 0;
        }

        $pendaftaranModel = new \App\Models\PendaftaranModel();
        $terpakai = $pendaftaranModel->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status_pendaftaran', 'diterima')
            ->countAllResults();

        return $tahunAjaran->kuota - $terpakai;
    }

    /**
     * Check if pendaftaran is open
     */
    public function isPendaftaranOpen($tahunAjaranId = null)
    {
        $tahunAjaran = $tahunAjaranId
            ? $this->find($tahunAjaranId)
            : $this->getActive();

        if (!$tahunAjaran || $tahunAjaran->status !== 'aktif') {
            return false;
        }

        $today = date('Y-m-d');
        return $today >= $tahunAjaran->tanggal_buka && $today <= $tahunAjaran->tanggal_tutup;
    }
}
