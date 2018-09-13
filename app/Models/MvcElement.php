<?php

namespace App\Models;

use App\Models\Common\CrudModel;

class MvcElement extends CrudModel {

    protected $table = 'mvc_elements';
    protected $fillable = [
        'name',
        'controller',
        'model',
        'view'
    ];

}
