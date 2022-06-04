<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\ClosingCodes;

class ClosingCodesModel extends Model
{
    /**
     * --------------------------------------------------------------------
     * Config model parameters
     * 
     */
    protected $table                = 'closing_codes';
    protected $primaryKey           = 'id_code';

    protected $returnType           = ClosingCodes::class;
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [
        'close_type',
        'accounting_code',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';
}
?>