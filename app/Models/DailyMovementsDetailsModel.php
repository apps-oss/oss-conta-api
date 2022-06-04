<?php

namespace App\Models;

use App\Entities\DailyMovementDetail;
use CodeIgniter\Model;

class DailyMovementsDetailsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'daily_movement_details';
    protected $primaryKey       = 'id_detail';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = DailyMovementDetail::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_movement',
        'account_code',
        'description',
        'specific_concept',
        'quantity',
        'document',
        'reference',
        'value',
        'movement_type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
