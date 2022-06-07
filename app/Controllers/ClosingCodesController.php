<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Authorization;
use App\Entities\ClosingCodes;
use App\Entities\User;
use App\Entities\UserType;

class ClosingCodesController extends ResourceController
{
    /**
     * Instance of the Closing Codes Type Model.
     *
     * @var App\Models\ClosingCodesModel
     */
    protected $closingCodes;

    /**
     * Instance of the User Codes Type Model.
     *
     * @var App\Models\UsersModel
     */
    protected $user;

    public function __construct()
    {
        //session();
        $this->closingCodes = model('ClosingCodesModel');
        $this->user = model('UsersModel');

        helper('restful');
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $auth = Authorization::verifyToken();
        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }
        // id user
        $user_id = $auth['data'];

        if (!$this->enforcer->enforce($user_id, "accounting_period", "index")) {
            return $this->respond(data(FORBIDDEN, "No tiene permisos para realizar esta acción"), FORBIDDEN);
        }

        $closing = $this->closingCodes->findAll();

        $data = data(OK, 'Datos Devueltos', $closing);
        return $this->respond($data, OK);
        
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function store()
    {
        // validate the form data
        $validation = service('validation');
        $validation->setRules([
            'close_type'     => [
                'label' => 'cerrar tipo',
                'rules' => 'required|max_length[10]'
            ],
            'accounting_code'     => [
                'label' => 'codigo de contabilidad',
                'rules' => 'required|max_length[50]'
            ],
            'description'     => [
                'label' => 'descripcion',
                'rules' => 'required|max_length[255]'
            ]
        ]);

        //! if validation fails
        if (!$validation->withRequest($this->request)->run()) {
            $firstKey = array_key_first($validation->getErrors());

            $data = data(BAD_REQUEST, $validation->getError($firstKey));
            return $this->respond($data, BAD_REQUEST);
        }

        $auth = Authorization::verifyToken();
        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }
        // Accedemos al id del usuario dentro del token
        $user_id = $auth['data'];

        $closing = (array) ($this->request->getVar());
        $closing['created_by'] = $user_id;
        $closing['updated_by'] = $user_id;
        $closing = new ClosingCodes($closing);

        // register document type in the database
        if ($this->closingCodes->save($closing)) {
            $data = data(CREATED, "Registro insertado correctamente", array());
            return $this->respond($data);
        }

        // ! No results found
        $data = data(BAD_REQUEST, 'Registro no pudo ser insertado');
        return $this->respond($data, BAD_REQUEST);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
        // Verify token
        $auth = Authorization::verifyToken();

        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }

        $closing = $this->closingCodes->find($id);
        $dataE = array("encabezado" => $closing);
        $data = data(OK, 'Datos Devueltos', $dataE);
        return $this->respond($data, OK);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
        $auth = Authorization::verifyToken();
        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }
        // Accedemos al id del usuario dentro del token
        $user_id = $auth['data'];

        $closing = (array) ($this->request->getVar());
        $closing['updated_by'] = $user_id;

        $closing = new ClosingCodes($closing);

        if ($this->closingCodes->save($closing)) {
            $data = data(OK, "Registro actualizado correctamente");
            return $this->respond($data);
        }

        // ! No results found
        $data = data(BAD_REQUEST, 'Registro no pudo ser actualizado');
        return $this->respond($data, BAD_REQUEST);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function destroy($id = null)
    {
        //
        $auth = Authorization::verifyToken();
        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }
        if (!$closing = $this->closingCodes->find($id)) {
            $data = data(NOT_FOUND, 'Registro no pudo ser eliminado');
            return $this->respond($data, NOT_FOUND);
        }

        // Accedemos al id del usuario dentro del token
        $user_id = $auth['data'];
        $closing->deleted_by = $user_id;
        $this->closingCodes->save($closing);

        // deleted closing type in the database
        if ($this->closingCodes->delete($id)) {
            $data = data(OK, "Registro eliminado correctamente", array());
            return $this->respond($data);
        }

        // ! No results found
        $data = data(BAD_REQUEST, 'Registro no pudo ser eliminado');
        return $this->respond($data, BAD_REQUEST);
    }
}
