<?php

namespace App\Models;

use CodeIgniter\Model;

class OrangTuaModel extends Model
{
    protected $table = 'orang_tua';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
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
        'telepon_wali'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
