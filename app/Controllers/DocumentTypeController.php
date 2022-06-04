<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Authorization;
use App\Entities\DocumentType;

class DocumentTypeController extends ResourceController
{
    /**
     * Instance of the Document Type Model.
     *
     * @var App\Models\DocumentTypeModel
     */
    protected $documentModel;

    /**
     * Instance of the User Codes Type Model.
     *
     * @var App\Models\UsersModel
     */
    protected $user;

    public function __construct()
    {
        //session();
        $this->documentModel = model('DocumentTypeModel');
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
        //
        $document = $this->documentModel->findAll();

        $auth = Authorization::verifyToken();
        if ($auth['hasError']) {
            return $this->respond($auth['data'], $auth['code']);
        }
        // id user
        $user_id = $auth['data'];

        $users = $this->user->find($user_id);
        //creation of object status
        $statusArray = array(
            "view" => $users->permissionUrl('/accounting/document_type/info'),
            "edit" => $users->permissionUrl('/accounting/document_type/edit'),
            "delete" => $users->permissionUrl('/accounting/document_type/delete')
        );

        if(!empty($document)){
            foreach ($document as $key => $arrDocument) {
                $document[$key]->permissions = array(
                    "view" => array(
                        "status" => $statusArray['view'],
                        "url" => BASE_URL_API . route_to(
                            "info_document_type",
                            $arrDocument->id_document
                        ),
                    ),
                    "edit" => array(
                        "status" => $statusArray['edit'],
                        "url" => BASE_URL_API . route_to(
                            "edit_document_type",
                            $arrDocument->id_document
                        ),
                    ),
                    "delete" => array(
                        "status" => $statusArray['delete'],
                        "url" => base_url(route_to(
                            "delete_document_type",
                            $arrDocument->id_document
                        )),
                    )
                );
            }
            $data = data(OK, 'Datos Devueltos', $document);
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
            'document_code'     => [
                'label' => 'codigo de documento',
                'rules' => 'required|max_length[50]'
            ],
            'document_name'     => [
                'label' => 'nombre de documento',
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

        $document = (array) ($this->request->getVar());
        $document['created_by'] = $user_id;
        $document['updated_by'] = $user_id;

        // Procedemos a obtener el maximo numero del documento
        $documentNumber = $this->documentModel->getLastRow();
        $document['document_number'] = $documentNumber + 1;

        $document = new DocumentType($document);

        // register document type in the database
        if ($this->documentModel->save($document)) {
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

        $document = $this->documentModel->find($id);
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

        $document = (array) ($this->request->getVar());
        $document['updated_by'] = $user_id;

        $document = new DocumentType($document);
        
        if ($this->documentModel->save($document)) {
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
        if (!$document = $this->documentModel->find($id)) {
            $data = data(NOT_FOUND, 'Registro no pudo ser eliminado');
            return $this->respond($data, NOT_FOUND);
        }

        // Accedemos al id del usuario dentro del token
        $user_id = $auth['data'];
        $document->deleted_by = $user_id;
        $this->documentModel->save($document);

        // deleted document type in the database
        if ($this->documentModel->delete($id)) {
            $data = data(OK, "Registro eliminado correctamente", array());
            return $this->respond($data);
        }
        
        // ! No results found
        $data = data(BAD_REQUEST, 'Registro no pudo ser eliminado');
        return $this->respond($data, BAD_REQUEST);
    }
}
