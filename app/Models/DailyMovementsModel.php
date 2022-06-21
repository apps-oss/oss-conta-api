<?php

namespace App\Models;

use App\Entities\DailyMovement;
use CodeIgniter\Model;

class DailyMovementsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'daily_movement';
    protected $primaryKey       = 'id_movement';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = DailyMovement::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'date',
        'id_period',
        'id_document_type',
        'correlative',
        'general_concept',
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
    protected $afterInsert    = ['storeDetail'];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * contains an arrangement with the detail information
     *
     * @var array
     */
    protected $detail = [];

    /**
     * contains user id
     *
     * @var int
     */
    protected $user_id = 0;

    public function assignDetail($detail, $user_id) 
    {
        $this->detail = $detail;
        $this->user_id = $user_id;
    }

    public function storeDetail(array $data)
    {
        // check if there is information in the property detail
        if (!empty($this->detail)) {

            // load model
            $dailyMovementsDetailsModel = model('DailyMovementsDetailsModel');

            $batch = [];

            // format data to insert
            foreach ($this->detail as $item) {
                $batch[] = [
                    'id_movement' => $data['id'],
                    'account_code' => $item->account_code,
                    'description' => $item->description,
                    'specific_concept' => $item->specific_concept,
                    'quantity' => $item->quantity,
                    'value' => $item->value,
                    'reference' => $item->reference,
                    'document' => $item->document,
                    'movement_type' => $item->movement_type,
                    'created_by' => $this->user_id,
                    'updated_by' => $this->user_id
                ];
            }

            $dailyMovementsDetailsModel->insertBatch($batch);
        }
        return $data;
    }

    public function getCorrelative($idPeriod)
    {
        $db     = \Config\Database::connect();
        $catalog = $db->table($this->table);
        $catalog->where('id_period', $idPeriod);

        return $catalog->countAllResults();
    }
}
