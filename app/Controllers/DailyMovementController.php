<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Authorization;
use App\Entities\DailyMovement;

class DailyMovementController extends ResourceController
{
    /**
     * Instance of the Daily Movement Model.
     *
     * @var App\Models\DailyMovementModel
     */
    protected $dailyMovement;

    /**
     * Instance of the Accounting Period Model.
     *
     * @var App\Models\AccountingPeriodModel
     */
    protected $accountingPeriod;

    public function __construct()
    {
        //session();
        $this->dailyMovement = model('DailyMovementsModel');
        $this->accountingPeriod = model('AccountingPeriodModel');

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
        $accounting = $this->accountingPeriod->where("status", 0)->first();
        $dailyMovements = $accounting->dailyMovements;
        if(!empty($accounting)){
            $dataArray = array('period' => $accounting, 'movements' => $dailyMovements);
            $data = data(OK, 'Datos Devueltos', $dataArray);
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
        //
        // validate the form data
        $validation = service('validation');
        $validation->setRules([
            'date'     => [
                'label' => 'fecha',
                'rules' => 'required|max_length[50]'
            ],
            'id_document_type'     => [
                'label' => 'tipo de documento',
                'rules' => 'required|max_length[50]'
            ],
            'general_concept'     => [
                'label' => 'concepto general',
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

        $accounting = $this->accountingPeriod->where("status", 0)->first();
        if(empty($accounting)){
            $data = data(BAD_REQUEST, 'No existe un periodo contable activo');
            return $this->respond($data, BAD_REQUEST); 
        }

        $movement = array(
            "date" => $this->request->getVar('date'),
            "id_period" => $accounting->id_period,
            "id_document_type" => $this->request->getVar('id_document_type'),
            "correlative" => $this->request->getVar('correlative'),
            "general_concept" => $this->request->getVar('general_concept'),
            "created_by" => $user_id,
            "updated_by" => $user_id
        );

        $movement = new DailyMovement($movement);
        //var_dump($this->request->getVar('details'));
        $this->dailyMovement->assignDetail($this->request->getVar('details'), $user_id);
        // register document type in the database
        if ($this->dailyMovement->save($movement)) {
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
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
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
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function getCorrelative()
    {
        $idPeriod = $this->request->getVar('id_period');
        $correlative = ($this->dailyMovement->getCorrelative($idPeriod)) + 1;

        $dataArray = array("correlativo" => $correlative);
        $data = data(OK, 'Datos Devueltos', $dataArray);
        return $this->respond($data, OK);
    }
}
