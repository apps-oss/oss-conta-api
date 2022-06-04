<?php

namespace App\Models;

use CodeIgniter\Model;

class UserTypePermissionsModel extends Model
{
    /**
     * --------------------------------------------------------------------
     * Config model parameters
     * 
     */
    protected $table                = 'user_type_permission';
    protected $primaryKey           = 'id_user_type_permission';

    protected $returnType           = 'object';
    protected $useSoftDeletes       = true;

    protected $allowedFields        = [
        'id_user_type',
        'id_permission',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
