<?php

/**
 * This file is part of the OSS
 *
 * (c) Open Solution Systems <operaciones@tumundolaboral.com.sv>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

// TODO: el sistema carece de autenticacion, y de creacion de usuarios
// al momento solo contiene un usuario de prueba, sin embargo se intenta
// siempre buscar permisos del usuario, por lo que es necesario contar siempre
// con un usuario de prueba

//  TODO: el sistema tiene una administracion defectuosa de los permisos, asi que integraremos spatie

// 

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Authorization;

class PoliciesController extends ResourceController
{
    public function __construct()
    {
        $this->enforcer = \Config\Services::enforcer();
        $this->model = model('UsersModel');
        helper('restful');
    }

    protected function formatPolicies(array $policies){

        $result = [];

        foreach ($policies as $policy) {
            // we will add an action in each of the modules array
            $result[$policy[1]][] = $policy[2];
        }

        return ["role" => $policies[0][0], "policies" => $result];
    }

    public function show($role = null)
    {
        // $this->enforcer->addPolicy('admin', 'policies', 'store');
        // $this->enforcer->addPolicy('admin', 'policies', 'show');

        // Authorization logic
        $auth = Authorization::verifyToken();
        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }
        // id user
        $user_id = $auth['data'];

        // el permiso es el nombre de la tabla y la funcion a la que se desea acceder
        if (!$this->enforcer->enforce($user_id, "policies", "show")) {
            return $this->respond(data(FORBIDDEN, "No tiene permisos para realizar esta acción"), FORBIDDEN);
        }

        $policies = $this->enforcer->getPermissionsForUser($role);

        $policies = $this->formatPolicies($policies);

        $data = data(OK, "Permisos obtenidos", $policies);
        return $this->respond($data);
    }

    public function store()
    {

        // Authorization logic
        $auth = Authorization::verifyToken();
        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }
        // id user
        $user_id = $auth['data'];

        // el permiso es el nombre de la tabla y la funcion a la que se desea acceder
        if (!$this->enforcer->enforce($user_id, "policies", "store")) {
            return $this->respond(data(FORBIDDEN, "No tiene permisos para realizar esta acción"), FORBIDDEN);
        }

        // validate the form data
        $validation = service('validation');
        $validation->setRules([
            'role'     => [
                'label' => 'nombre rol',
                'rules' => 'required|max_length[50]'
            ],
            'modules'  => [
                'label' => 'modulos',
                'rules' => 'required'
            ],
        ]);
        
        //! if validation fails
        if (!$validation->withRequest($this->request)->run()) {
            $firstKey = array_key_first($validation->getErrors());

            $data = data(BAD_REQUEST, $validation->getError($firstKey));
            return $this->respond($data, BAD_REQUEST);
        }

        $body = $this->request->getJSON();
        $role = $body->role;
        $modules = $body->modules;


        // if modules doesn't has a first value with a name property
        // or, it doesn't has a single action in actions array
        if (!isset($modules[0]->name) || !isset($modules[0]->actions[0])) {
            // ! No results found
            $data = data(BAD_REQUEST, 'Formato de peticion incorrecto');
            return $this->respond($data, BAD_REQUEST);
        }

        // drop current policies for role
        $policies = $this->enforcer->getPermissionsForUser($role);
        foreach ($policies as $policy) {
            $this->enforcer->deletePermissionForUser($policy[0], $policy[1], $policy[2]);
        }

        // make new policies for role
        foreach ($modules as $module) {
            foreach ($module->actions as $action) {
                $this->enforcer->addPolicy($role, $module->name, $action);
            }
        }

        $policies = $this->enforcer->getPermissionsForUser($role);
        $policies = $this->formatPolicies($policies);

        $data = data(OK, "Permisos editados con exito", $policies);
        return $this->respond($data);
    }

    
}
