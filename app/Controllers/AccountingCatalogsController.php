<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Authorization;
use App\Entities\AccountingCatalogs;
use App\Entities\User;

class AccountingCatalogsController extends ResourceController
{
    /**
     * Instance of the Document Type Model.
     *
     * @var App\Models\AccountingCatalogsModel
     */
    protected $accountingCatalogs;

    /**
     * Instance of the Level Model.
     *
     * @var App\Models\LevelModel
     */
    protected $levelModel;

    public function __construct()
    {
        $this->levelModel = model('LevelModel');
        $this->accountingCatalogsModel = model('AccountingCatalogsModel');
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

        // Authorization logic
        $auth = Authorization::verifyToken();
        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }
        // id user
        $user_id = $auth['data'];

        if (!$this->enforcer->enforce($user_id, "accounting_catalog", "index")) {
            return $this->respond(data(FORBIDDEN, "No tiene permisos para realizar esta acción"), FORBIDDEN);
        }

        // Controller Logic
        $accounting = $this->accountingCatalogsModel
            ->orderBy('code', 'ASC')
            ->paginate(config("GlobalSettings")->regPerPage);

        $data = data(OK, 'Datos Devueltos', $accounting);
        return $this->respond($data, OK);

    }

    public function getCatalog()
    {
        //get accounting catalog
        $accounting = $this->accountingCatalogsModel->get_catalog();

        function buildTree($data, $rootId = "00")
        {
            $tree = array(
                'code' => '',
                'description' => "CATALOGO",
                'arbol' => array(),
                'children' => array()
            );
            foreach ($data as $ndx => $node) {
                $id = $node['code'];
                /**
                 * Puede que exista el children creado si los hijos 
                 * entran antes que el padre 
                 */
                $node['children'] = (isset($tree['arbol'][$id])) ?
                    array($tree['arbol'][$id]['children']) : array();
                $tree['arbol'][$id] = $node;

                if ($node['parent_id'] == $rootId)
                    $tree['children'][] = &$tree['arbol'][$id];
                else {
                    $tree['arbol'][$node['parent_id']]['children'][] = &$tree['arbol'][$id];
                }
            }
            return $tree;
        }
        $arbol = buildTree($accounting);

        //get levels accounting catalog
        $levels = $this->levelModel->findAll();

        $arrData = array("catalog" => $arbol, "levels" => $levels);
        $data = data(OK, 'Datos Devueltos', $arrData);
        return $this->respond($data, OK);
        //return $this->respond($arbol, OK);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function getAccountCode()
    {
        $parentId = $this->request->getVar('parent_id');
        $parentLevel = $this->request->getVar('parent_level');

        //get the account level 
        $level = $this->levelModel->where("id_level", ($parentLevel + 1))->first();

        //get max code
        $documentNumber = ($this->accountingCatalogsModel->getMaxCode($parentId)) + 1;

        if (empty($level)) {
            // ! No results found
            $data = data(NOT_FOUND, 'Debe crear un nivel para la subcuenta');
            return $this->respond($data, NOT_FOUND);
        } else {
            $codigo = $this->generate_code($documentNumber, $level->number_places);
            $codigo = array("codigo" => $parentId . $codigo);

            $data = data(OK, 'Datos Devueltos', $codigo);
            return $this->respond($data, OK);
        }
    }


    public function generate_code($ult_doc, $limit)
    {
        $ult_doc = trim($ult_doc);
        $len_ult_valor = strlen($ult_doc);
        $long_num_fact = $limit;
        $long_increment = $long_num_fact - $len_ult_valor;
        $valor_txt = "";
        if ($len_ult_valor < $long_num_fact) {
            for ($j = 0; $j < $long_increment; $j++) {
                $valor_txt .= "0";
            }
        } else {
            $valor_txt = "";
        }
        $valor_txt = $valor_txt . $ult_doc;
        return $valor_txt;
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
            'code'     => [
                'label' => 'codigo',
                'rules' => 'required'
            ],
            'description'     => [
                'label' => 'descripcion',
                'rules' => 'required|max_length[100]'
            ],
            'level'     => [
                'label' => 'nivel',
                'rules' => 'required'
            ],
            'type'     => [
                'label' => 'tipo',
                'rules' => 'required'
            ],
            'exercivse_settlement'     => [
                'label' => 'liquidacion',
                'rules' => 'required'
            ],
            'charge_credit'     => [
                'label' => 'cargo',
                'rules' => 'required'
            ],
            'sign'     => [
                'label' => 'signo',
                'rules' => 'required'
            ],
            'letter'     => [
                'label' => 'letra',
                'rules' => 'required'
            ],
            'chargeable'     => [
                'label' => 'agrupacion',
                'rules' => 'required'
            ],
            'reference'     => [
                'label' => 'referencia',
                'rules' => 'required'
            ],
            'budget'     => [
                'label' => 'presupuesto',
                'rules' => 'required'
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

        //Validamos que no exista una cuenta con el mismo nombre;
        $accountingExist = $this->accountingCatalogsModel->where("description", $this->request->getVar('description'))->first();
        if(!empty($accountingExist)){
            // ! results found
            $data = data(BAD_REQUEST, 'Nombre de cuenta ya existe');
            return $this->respond($data, BAD_REQUEST);
        }

        $accounting = array(
            "code" => $this->request->getVar('code'),
            "description" => $this->request->getVar('description'),
            "level" => $this->request->getVar('level'),
            "exercivse_settlement" => $this->request->getVar('exercivse_settlement'),
            "charge_credit" => $this->request->getVar('charge_credit'),
            "sign" => $this->request->getVar('sign'),
            "letter" => $this->request->getVar('letter'),
            "chargeable" => $this->request->getVar('chargeable'),
            "reference" => $this->request->getVar('reference'),
            "budget" => $this->request->getVar('budget'),
            "type" => $this->request->getVar('type'),
            "parent_id" => $this->request->getVar('parent_id'),
            "parent_level" => $this->request->getVar('parent_level'),
            "created_by" => $user_id,
            "updated_by" => $user_id
        );

        $accounting = new AccountingCatalogs($accounting);

        if ($this->accountingCatalogsModel->save($accounting)) {
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

        $document = $this->accountingCatalogsModel->find($id);
        $dataE = array("encabezado" => $document);
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

        $accounting = array(
            "description" => $this->request->getVar('description'),
            "exercivse_settlement" => $this->request->getVar('exercivse_settlement'),
            "charge_credit" => $this->request->getVar('charge_credit'),
            "sign" => $this->request->getVar('sign'),
            "letter" => $this->request->getVar('letter'),
            "chargeable" => $this->request->getVar('chargeable'),
            "reference" => $this->request->getVar('reference'),
            "budget" => $this->request->getVar('budget'),
            "type" => $this->request->getVar('type'),
            "parent_id" => $this->request->getVar('parent_id'),
            "parent_level" => $this->request->getVar('parent_level'),
            "id_catalog" => $this->request->getVar('id_catalog'),
            "created_by" => $user_id,
            "updated_by" => $user_id
        );

        $accounting = new AccountingCatalogs($accounting);

        if ($this->accountingCatalogsModel->save($accounting)) {
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
}
