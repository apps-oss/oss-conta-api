<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Level;

class LevelModel extends Model
{
    /**
     * --------------------------------------------------------------------
     * Config model parameters
     * 
     */
    protected $DBGroup          = 'default';
    protected $table            = 'level';
    protected $primaryKey       = 'id_level';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = Level::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'number_places',
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
}
