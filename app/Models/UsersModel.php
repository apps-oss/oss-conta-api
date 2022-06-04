<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\User;

/**
 * Users Model
 * 
 * @package		FUPAPP
 * @subpackage	Models
 * @category	Models
 * @author		FUPAPP Dev Team
 */
class UsersModel extends Model
{

    /**
     * --------------------------------------------------------------------
     * Config model parameters
     * 
     */
    protected $table                = 'user';
    protected $primaryKey           = 'id_user';

    protected $returnType           = User::class;
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [
        'id_user_type',
        'first_name',
        'last_name',
        'user_name',
        'email',
        'password',
        'picture',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';


    /**
     * 
     * Search a user for the information in their columns
     * 
     * @param string $column name of the column to search
     * @param string $value search value
     * 
     * @return App\Entities\User
     */
    public function getUserBy(string $column, string $value)
    {
        return $this->where($column, $value)->first();
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
            $this->like('first_name', $search, 'both')
            ->orLike('last_name', $search, 'both')
            ->orLike('user_name', $search, 'both');
        }

        // get pagination
        $result = $this->paginate($length, 'default', $page);
        
        return $result;
    }
}
