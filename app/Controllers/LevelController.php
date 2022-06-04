<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Authorization;
use App\Entities\Level;
use App\Entities\DocumentType;
use App\Entities\AccountingCatalogs;

class LevelController extends ResourceController
{
    /**
     * Instance of the Document Type Model.
     *
     * @var App\Models\LevelModel
     */
    protected $levelModel;

    /**
     * Instance of the Document Type Model.
     *
     * @var App\Models\AccountingCatalogsModel
     */
    protected $accountingCatalogs;

    public function __construct()
    {
        $this->levelModel = model('LevelModel');
        $this->accountingCatalogs = model('AccountingCatalogsModel');
        helper('restful');
    }


    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
        $level = $this->levelModel->findAll();
        if(!empty($level)){
            foreach ($level as $key => $arrLevel) {
                //explore the object
                $levelData = $this->accountingCatalogs->where('level', $arrLevel->id_level)->first();
                if (!$levelData) {
                    //not exists
                    $level[$key]->status = 0;
                }
                else{
                    //exists
                    $level[$key]->status = 1;
                }
            }
            $data = data(OK, 'Datos Devueltos', $level);
            return $this->respond($data, OK);
        }
        else{
            // ! No results found
            $data = data(NOT_FOUND, 'No se encontraron registros');
            return $this->respond($data, NOT_FOUND);
        }
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
            'name'     => [
                'label' => 'nombre de nivel',
                'rules' => 'required|max_length[50]'
            ],
            'number_places'     => [
                'label' => 'numero de posiciones',
                'rules' => 'required|max_length[50]'
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

        $level = (array) ($this->request->getVar());
        $level['created_by'] = $user_id;
        $level['updated_by'] = $user_id;

        $level = new Level($level);

        // register level type in the database
        if ($this->levelModel->save($level)) {
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
        // Verify token
        $auth = Authorization::verifyToken();

        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }

        $level = $this->levelModel->find($id);
        $dataE = array("encabezado" => $level);
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
        // Verify token
        $auth = Authorization::verifyToken();
        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }
        // Accedemos al id del usuario dentro del token
        $user_id = $auth['data'];

        $level = (array) ($this->request->getVar());
        $level['updated_by'] = $user_id;

        $level = new Level($level);
        
        if ($this->levelModel->save($level)) {
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
        if (!$level = $this->levelModel->find($id)) {
            $data = data(NOT_FOUND, 'Registro no pudo ser eliminado');
            return $this->respond($data, NOT_FOUND);
        }

        // Accedemos al id del usuario dentro del token
        $user_id = $auth['data'];
        $level->deleted_by = $user_id;
        $this->levelModel->save($level);

        // deleted level type in the database
        if ($this->levelModel->delete($id)) {
            $data = data(OK, "Registro eliminado correctamente", array());
            return $this->respond($data);
        }
        
        // ! No results found
        $data = data(BAD_REQUEST, 'Registro no pudo ser eliminado');
        return $this->respond($data, BAD_REQUEST);
    }
}
