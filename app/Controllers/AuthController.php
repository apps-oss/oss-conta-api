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

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Authorization;

class AuthController extends ResourceController
{
    public function __construct()
    {
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

        $user = [
            "user_id" => 1,
            "name" => "Juanita",
            "user" => "juanit@",
            "token" => Authorization::generateToken(1),
        ];

        if (!empty($user)) {
            $data = data(OK, 'Bienvenido al sistema', $user);
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
        $user = [
            "name" => "juanito",
            "user" => "adad",
        ];

        if (!empty($user)) {
            $data = data(OK, "Información del usuario", $user);
            return $this->respond($data);
        }

        // ! No results found
        $data = data(BAD_REQUEST, 'Usuario o contraseña incorrectos');
        return $this->respond($data, BAD_REQUEST);
    }
}
