<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'pendaftaran_id',
        'jumlah',
        'bukti_bayar',
        'status_bayar',
        'tanggal_bayar',
        'verified_by',
        'verified_at',
        'keterangan'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get pembayaran dengan relasi pendaftaran dan user
     */
    public function getWithRelations($id)
    {
        return $this->select('pembayaran.*, pendaftaran.nomor_pendaftaran, pendaftaran.nama_lengkap, pendaftaran.user_id, users.email')
            ->join('pendaftaran', 'pendaftaran.id = pembayaran.pendaftaran_id', 'left')
            ->join('users', 'users.id = pendaftaran.user_id', 'left')
            ->where('pembayaran.id', $id)
            ->first();
    }

    /**
     * Get semua pembayaran dengan filter
     */
    public function getAllWithRelations($filters = [])
    {
        $builder = $this->select('pembayaran.*, pendaftaran.nomor_pendaftaran, pendaftaran.nama_lengkap, pendaftaran.status_pendaftaran, users.email, users.username')
            ->join('pendaftaran', 'pendaftaran.id = pembayaran.pendaftaran_id', 'left')
            ->join('users', 'users.id = pendaftaran.user_id', 'left');

        // Filter by status
        if (!empty($filters['status_bayar'])) {
            $builder->where('pembayaran.status_bayar', $filters['status_bayar']);
        }

        // Filter by tahun ajaran
        if (!empty($filters['tahun_ajaran_id'])) {
            $builder->where('pendaftaran.tahun_ajaran_id', $filters['tahun_ajaran_id']);
        }

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $builder->groupStart()
                ->like('pendaftaran.nomor_pendaftaran', $search)
                ->orLike('pendaftaran.nama_lengkap', $search)
                ->orLike('users.email', $search)
                ->groupEnd();
        }

        // Sort
        $builder->orderBy('pembayaran.created_at', 'DESC');

        return $builder;
    }

    /**
     * Count pembayaran pending
     */
    public function countPending()
    {
        return $this->where('status_bayar', 'pending')->countAllResults();
    }

    /**
     * Get pembayaran pending dengan info pendaftaran
     */
    public function getPendingForNotification($limit = 5)
    {
        return $this->select('pembayaran.*, pendaftaran.nomor_pendaftaran, pendaftaran.nama_lengkap')
            ->join('pendaftaran', 'pendaftaran.id = pembayaran.pendaftaran_id', 'left')
            ->where('pembayaran.status_bayar', 'pending')
            ->orderBy('pembayaran.created_at', 'DESC')
            ->limit($limit)
            ->find();
    }
}
