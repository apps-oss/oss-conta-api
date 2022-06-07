<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Authorization;
use App\Entities\AccountingPeriod;
use App\Entities\User;

class AccountingPeriodController extends ResourceController
{
    /**
     * Instance of the Document Type Model.
     *
     * @var App\Models\AccountingPeriodModel
     */
    protected $accountingPeriod;

    /**
     * Instance of the Document Type Model.
     *
     * @var App\Models\DailyMovementsModel
     */
    protected $dailyMovement;

    public function __construct()
    {
        //session();
        $this->accountingPeriod = model('AccountingPeriodModel');
        $this->dailyMovement = model('DailyMovementsModel');

        $this->user = model('UsersModel');

        $this->enforcer = \Config\Services::enforcer();

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

        $accounting = $this->accountingPeriod
            ->paginate(config("GlobalSettings")->regPerPage);

        $data = data(OK, 'Datos Devueltos', $accounting);
        return $this->respond($data, OK);
        //creation of object status
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
        // Authorization logic
        $auth = Authorization::verifyToken();
        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }
        // id user
        $user_id = $auth['data'];

        // el permiso es el nombre de la tabla y la funcion a la que se desea acceder
        if (!$this->enforcer->enforce($user_id, "daily_movement", "index")) {
            return $this->respond(data(FORBIDDEN, "No tiene permisos para realizar esta acción"), FORBIDDEN);
        }

        // validate the form data
        $validation = service('validation');
        $validation->setRules([
            'begin_date'     => [
                'label' => 'fecha inicio',
                'rules' => 'required'
            ],
            'end_date'     => [
                'label' => 'fecha fin',
                'rules' => 'required'
            ]
        ]);

        //! if validation fails
        if (!$validation->withRequest($this->request)->run()) {
            $firstKey = array_key_first($validation->getErrors());

            $data = data(BAD_REQUEST, $validation->getError($firstKey));
            return $this->respond($data, BAD_REQUEST);
        }

        $accounting = (array) ($this->request->getVar());
        $accounting['created_by'] = $user_id;
        $accounting['updated_by'] = $user_id;
        $accounting['status'] = 0;

        $accounting['correlative'] =  0;

        $accounting = new AccountingPeriod($accounting);

        // register accounting type in the database
        if ($this->accountingPeriod->save($accounting)) {
            $data = data(
                CREATED,
                "Registro insertado correctamente",
                array()
            );
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
        // Verify token
        $auth = Authorization::verifyToken();

        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }

        $accounting = $this->accountingPeriod->find($id);
        $dataE = array("encabezado" => $accounting);
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
        $auth = Authorization::verifyToken();
        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }
        // Accedemos al id del usuario dentro del token
        $user_id = $auth['data'];

        $accounting = (array) ($this->request->getVar());
        $accounting['updated_by'] = $user_id;

        $accounting = new AccountingPeriod($accounting);

        if ($this->accountingPeriod->save($accounting)) {
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
    public function delete($id = null)
    {
        //
    }

    /**
     * Destroy the designated resource object from the model
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
        if (!$accounting = $this->accountingPeriod->find($id)) {
            $data = data(NOT_FOUND, 'Registro no pudo ser eliminado');
            return $this->respond($data, NOT_FOUND);
        }

        // Accedemos al id del usuario dentro del token
        $user_id = $auth['data'];

        //explore the object
        $dailyMovement = $this->dailyMovement->where('id_period', $id)->first();
        if (!$dailyMovement) {
            //not exists
            $accounting->deleted_by = $user_id;
            $this->accountingPeriod->save($accounting);

            // deleted accounting period in the database
            if ($this->accountingPeriod->delete($id)) {
                $data = data(OK, "Registro eliminado correctamente", array());
                return $this->respond($data);
            }
        } else {
            //exists

        }

        // ! No results found
        $data = data(BAD_REQUEST, 'Registro no pudo ser eliminado');
        return $this->respond($data, BAD_REQUEST);
    }
}
