<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\DocumentType;

class DocumentTypeModel extends Model
{
    /**
     * --------------------------------------------------------------------
     * Config model parameters
     * 
     */
    protected $table                = 'master_documents';
    protected $primaryKey           = 'id_document';

    protected $returnType           = DocumentType::class;
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [
        'document_code',
        'document_name',
        'document_number',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    public function getLastRow()
    {
        $db     = \Config\Database::connect();
        $document = $db->table($this->table);
        $document->orderBy('document_number', 'DESC');
        $document->limit(1);

        $result = $document->get();
        if(isset($result->getRow()->document_number)){
            return $result->getRow()->document_number;
        }
        else{
            return 0;
        }
    }
}
?>