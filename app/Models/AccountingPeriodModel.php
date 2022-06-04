<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\AccountingPeriod;

class AccountingPeriodModel extends Model
{
    /**
     * --------------------------------------------------------------------
     * Config model parameters
     * 
     */
    protected $table                = 'accounting_period';
    protected $primaryKey           = 'id_period';

    protected $returnType           = AccountingPeriod::class;
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [
        'begin_date',
        'end_date',
        'correlative',
        'status',
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