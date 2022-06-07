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

class AuthController extends ResourceController
{
    public function __construct()
    {
        $this->enforcer = \Config\Services::enforcer();
        $this->model = model('UsersModel');
        helper('restful');
    }

    public function index()
    {
        // validate the form data
        $validation = service('validation');
        $validation->setRules([
            'user'     => [
                'label' => 'usuario',
                'rules' => 'required|max_length[50]'
            ],
            'password'  => [
                'label' => 'password',
                'rules' => 'required|max_length[50]'
            ],
        ]);

        //! if validation fails
        if (!$validation->withRequest($this->request)->run()) {
            $firstKey = array_key_first($validation->getErrors());

            $data = data(BAD_REQUEST, $validation->getError($firstKey));
            return $this->respond($data, BAD_REQUEST);
        }

        $user = $this->model->login($this->request->getPost('user'), $this->request->getPost('password'));

        if ($user != null) {
            // add token to $user
            $user->token = Authorization::generateToken($user->id_user);
            $this->enforcer->addPermissionForUser($user->id_user, 'daily_movements', 'index');

            $user_array = [
                'id_user' => $user->id_user,
                'user' => $user->user_name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'token' => $user->token,
                'email' => $user->email,
            ];

            $data = data(OK, 'Bienvenido al sistema', $user_array);
            return $this->respond($data, OK);
        }

        // ! No results found
        $data = data(BAD_REQUEST, 'Usuario o contraseña incorrectos');
        return $this->respond($data, BAD_REQUEST);

        
    }

    public function verify()
    {
        $auth = Authorization::verifyToken();

        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }

        // Accedemos al id del usuario dentro del token
        $user_id = $auth['data'];

        // Find user
        $user = $this->model->getUserBy('id_user', $user_id);

        if (!empty($user)) {

            $data = data(OK, "Información del usuario", $user);
            return $this->respond($data);
        }

        // ! No results found
        $data = data(BAD_REQUEST, 'Usuario o contraseña incorrectos');
        return $this->respond($data, BAD_REQUEST);
    }
}
