<?php
namespace App\Entities;

use CodeIgniter\Entity;

class UserType extends Entity
{
	protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    /**
     * check if a user has a permission through their url
     * 
     * @param string url
     * @return bool
     */

    // TODO: Esto puede dar mal rendimiento al evaluar varias URL, debido a que se ejecuta
    // la consulta join para cada una de las url que se quieren evaluar.
    // considerar una manera de evaluar varias url a la vez por medio de querys

    public function verifyPermissionURL(string $url) : bool 
    {

        // clear url ------------------------------------------------------
        $url = str_replace('/(.*)', '', $url);
        $url = str_replace('/([0-9]+)', '', $url);
        $sub_url = substr($url, 0, 1);
        if ($sub_url == "/")  $url = substr($url, 1);
        // ----------------------------------------------------------------
        
        if (!empty($this->attributes['id_user_type'])) {

            $userPermissionsModel = model("UserTypePermissionsModel");
            $reult = $userPermissionsModel->db
            ->table('permission AS p')
            ->join("user_type_permission AS utp", "utp.id_permission = p.id_permission")
            ->where("utp.id_user_type", $this->attributes['id_user_type'])
            ->where("utp.deleted_at", null)
            ->get()->getResult();
        
            $routes_array = [];
            foreach ($reult as $item) {
                $routes_array[] = $item->route;
            }

            return in_array($url, $routes_array);
        }

        return false;
    }
}