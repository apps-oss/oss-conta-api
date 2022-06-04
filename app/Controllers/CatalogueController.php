<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class CatalogueController extends BaseController
{
    public function __construct()
    {
        helper('restful');
    }
    public function index()
    {
        //
    }

    public function store()
    {
        $validation = service('validation');
        $fields = [
            'annotation_date' => [
                'label' => 'fecha de anotacion',
                'rules' => 'required'
            ],
            'amount' => [
                'label' => 'monto',
                'rules' => 'required'
            ],
        ];
    }
}
