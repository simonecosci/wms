<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CrudController;
use App\Models\MvcElement;

class MvcElementsController extends CrudController {

    protected $view = 'admin.mvc-elements';
    
    public function __construct(MvcElement $model) {
        $this->model = $model;
    }
}
