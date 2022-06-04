<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\AccountingCatalogs;

class AccountingCatalogsModel extends Model
{
    /**
     * --------------------------------------------------------------------
     * Config model parameters
     * 
     */
    protected $table                = 'accounting_catalogs';
    protected $primaryKey           = 'id_catalog';

    protected $returnType           = AccountingCatalogs::class;
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [
        'id_catalog',
        'code',
        'description',
        'level',
        'exercivse_settlement',
        'charge_credit',
        'sign',
        'letter',
        'chargeable',
        'reference',
        'budget',
        'type',
        'parent_id',
        'parent_level',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    function get_catalog(){
        $db = \Config\Database::connect();

        $q = "SELECT * FROM accounting_catalogs WHERE deleted_at IS NULL";
        $query = $db->query($q);
        return $query->getResultArray();
    }

    public function getMaxCode($parentId)
    {
        $db     = \Config\Database::connect();
        $catalog = $db->table($this->table);
        $catalog->where('parent_id', $parentId);

        return $catalog->countAllResults();
    }
}
?>