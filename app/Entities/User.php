<?php 
namespace App\Entities;

use CodeIgniter\Entity;
use App\Entities\UserType;

class User extends Entity
{
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * gets an instance of the user type assigned to the user
     *
     * @return object UserType Entity
     */
    protected function getUserType()
    {
        if (!empty($this->attributes['id_user_type'])) {
            $userTypesModel = model("UserTypesModel");
            return $userTypesModel
                ->where("id_user_type", $this->attributes['id_user_type'])
                ->first();
        }
        return $this;
    }
    
    /**
     * check if the user has permissions to access a route
     * 
     * @param string route
     * 
     * @return bool
     */
    public function permissionUrl(string $url) : bool
    {   
        // devuelve un error de funcion no definida (verifyPermissionURL)
        // porque al no encontrar un id_user_type definido, devuelve siempre un objeto User
        return $this->userType->verifyPermissionURL($url);
    }
}