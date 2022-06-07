<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\User;

/**
 * Users Model
 * 
 * @package		CONTAPP
 * @subpackage	Models
 * @category	Models
 * @author      Ruben Mulato, OSS DEV
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
     * Search user and check if the password is correct.
     *
     * use hash_pbkdf2 to encrypt passwords, the salt data and the number
     * of iterations is in the constants file
     *
     * @param string $user user name
     * @param string password to compare
     *
     * @return void
     *
     */
    public function login(string $user, string $password)
    {
        $user = $this->getUserBy('user_name', $user);

        // if the user is not found, return null
        if (!$user) {
            return null;
        }

        // if the user is found, check if the password is correct
        $pass = hash_pbkdf2("sha256",$password,'.:6S@tz9M/PM%-*GebtM/PDM.bCfmg[D',20,128);

        // echo $pass;
        // echo "<br>";
        // echo $user->password;
        // exit;

        if ($user->password === $pass) {
            return $user;
        }

        // if the password is incorrect, return null
        return null;
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
        $page = $start = 0 ? 1 : $start / $length + 1;

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
