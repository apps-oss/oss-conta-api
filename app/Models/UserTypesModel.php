<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\UserType;


class UserTypesModel extends Model
{

	/**
     * --------------------------------------------------------------------
     * Config model parameters
     * 
     */
    protected $table                = 'user_type';
    protected $primaryKey           = 'id_user_type';

    protected $returnType           = UserType::class;
    protected $useSoftDeletes       = true;
	
	protected $allowedFields        = [
        'name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // The functions indicated in the following properties will be executed
    // just after inserting or updating records in the user_type table
    protected $afterInsert          = ['storePermissions'];
    protected $afterUpdate          = ['updatePermissions'];

    protected $permissions = [];

    /**
     * update permissions callback after insert
     * 
     * for more information about the callbaks see
     * 
     *  https://codeigniter4.github.io/userguide/models/model.html#defining-callbacks
     * 
     * runs after update into the user_type table, and inserts the 
     * permissions assigned to the role
     * 
     * @param array callback data
     * 
     * @return array|null
     */
    public function updatePermissions(array $data){

        // load model
        $userTypePermissionsModel = model('UserTypePermissionsModel');

        // delete old permissions
        $userTypePermissionsModel->where('id_user_type', $data['id'])->delete();
        
        // check if there is information in the property permissions
        if (!empty($this->permissions)) {
            $batch = [];

            // format data to insert
            foreach ($this->permissions as $p) {
                $batch[] = [
                    'id_user_type' => $data['id'],
                    'id_permission' => $p,
                    'created_by' => session()->id_user,
                    'updated_by'=> session()->id_user
                ];
            }
            $userTypePermissionsModel->insertBatch($batch);
        }
        return $data;
    }

    /**
     * store permissions callback after insert
     * 
     * for more information about the callbaks see
     * 
     *  https://codeigniter4.github.io/userguide/models/model.html#defining-callbacks
     * 
     * runs after inserting into the user_type table, and inserts the 
     * permissions assigned to the role
     * 
     * @param array callback data
     * 
     * @return array|null
     */
    public function storePermissions(array $data){

        // check if there is information in the property permissions
        if (!empty($this->permissions)) {

            // load model
            $userTypePermissionsModel = model('UserTypePermissionsModel');

            $batch = [];

            // format data to insert
            foreach ($this->permissions as $p) {
                $batch[] = [
                    'id_user_type' => $data['id'],
                    'id_permission' => $p,
                    'created_by' => session()->id_user,
                    'updated_by'=> session()->id_user
                ];
            }
            $userTypePermissionsModel->insertBatch($batch);
        }
        return $data;
    }

    /**
     * Assign the permissions of the role
     * 
     * The assigned permissions are automatically inserted into the 
     * database after inserting a new role, this thanks to a callback 
     * function that will be executed after inserting in uer_type
     * 
     * @param array array with the id of the permissions that the user will have
     * 
     * @return void
     */
    public function assignPermissions(array $permissions){
        $this->permissions = $permissions;
    }

    /**
     * Returns paginated data
     * 
     * @param int     $star    initial register
     * @param int     $length  number of records to obtain
     * @param string  $search  query
     * 
     * @return array|null
     */
    public function getPaginate(int $start, int $length, string $search)
    {

        // get page number
        $page = $start = 0 ? 1 : $start / $length + 1 ;

        // if a search parameter was sent
        if ($search != '') {
            $this->like('name', $search, 'both')
            ->orLike('description', $search, 'both');
        }

        // get pagination
        $result = $this->paginate($length, 'default', $page);
        
        return $result;
    }

    

}
