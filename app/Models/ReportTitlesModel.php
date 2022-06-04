<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\ReportTitles;

class ReportTitlesModel extends Model
{
    /**
     * --------------------------------------------------------------------
     * Config model parameters
     * 
     */
    protected $table                = 'reports_titles';
    protected $primaryKey           = 'id_title';

    protected $returnType           = ReportTitles::class;
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [
        'title_code',
        'title_name',
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