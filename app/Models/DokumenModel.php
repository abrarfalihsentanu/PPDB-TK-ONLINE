<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    protected $table = 'dokumen';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'pendaftaran_id',
        'jenis_dokumen',
        'nama_file',
        'path_file',
        'status_verifikasi',
        'keterangan',
        'uploaded_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get dokumen dengan relasi pendaftaran
     */
    public function getWithRelations($id)
    {
        return $this->select('dokumen.*, pendaftaran.nomor_pendaftaran, pendaftaran.nama_lengkap, pendaftaran.status_pendaftaran')
            ->join('pendaftaran', 'pendaftaran.id = dokumen.pendaftaran_id', 'left')
            ->where('dokumen.id', $id)
            ->first();
    }

    /**
     * Get semua dokumen by pendaftaran
     */
    public function getByPendaftaran($pendaftaranId)
    {
        return $this->where('pendaftaran_id', $pendaftaranId)->findAll();
    }

    /**
     * Get dokumen pending verification
     */
    public function getPendingVerification()
    {
        return $this->select('dokumen.*, pendaftaran.nomor_pendaftaran, pendaftaran.nama_lengkap, pendaftaran.status_pendaftaran')
            ->join('pendaftaran', 'pendaftaran.id = dokumen.pendaftaran_id', 'left')
            ->where('dokumen.status_verifikasi', 'pending')
            ->orderBy('dokumen.created_at', 'ASC')
            ->findAll();
    }

    /**
     * Count dokumen pending verification
     */
    public function countPending()
    {
        return $this->where('status_verifikasi', 'pending')->countAllResults();
    }

    /**
     * Get dokumen pending dengan info pendaftaran
     */
    public function getPendingForNotification($limit = 5)
    {
        return $this->select('dokumen.*, pendaftaran.nomor_pendaftaran, pendaftaran.nama_lengkap')
            ->join('pendaftaran', 'pendaftaran.id = dokumen.pendaftaran_id', 'left')
            ->where('dokumen.status_verifikasi', 'pending')
            ->orderBy('dokumen.created_at', 'DESC')
            ->limit($limit)
            ->find();
    }

    /**
     * Get status verifikasi by pendaftaran
     */
    public function getStatusByPendaftaran($pendaftaranId)
    {
        $result = [
            'total' => 0,
            'approved' => 0,
            'rejected' => 0,
            'pending' => 0
        ];

        $documents = $this->where('pendaftaran_id', $pendaftaranId)->findAll();
        $result['total'] = count($documents);

        foreach ($documents as $doc) {
            if ($doc->status_verifikasi === 'approved') {
                $result['approved']++;
            } elseif ($doc->status_verifikasi === 'rejected') {
                $result['rejected']++;
            } else {
                $result['pending']++;
            }
        }

        return $result;
    }
}
