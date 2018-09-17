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

    public function update(array $attributes = array(), array $options = array()) {
        $attributes['model'] = json_encode($attributes['model']);
        return parent::update($attributes, $options);
    }
    
    public function create(array $attributes = array(), array $options = array()) {
        $attributes['model'] = json_encode($attributes['model']);
        return parent::create($attributes, $options);
    }
    
    public function read($options = null) {
        $data = parent::read($options);
        foreach ($data as $key => $item) {
            $data[$key]['model'] = json_decode($item['model']);
        }
        return $data;
    }
}
