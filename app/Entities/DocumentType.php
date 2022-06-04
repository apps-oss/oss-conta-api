<?php
namespace App\Entities;

use CodeIgniter\Entity\Entity;

class DocumentType extends Entity
{
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    protected function getNumeroDocumento()
    {
        /*
        $db = db_connect();
        $builder = $db->table('master_documents')
                      ->select('MAX(document_number)')
                      ->get()->getResult();
        return $builder[0]->document_number;
        */
        //return "hola";
    }
}
?>