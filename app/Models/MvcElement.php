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
        foreach(['model', 'view', 'controller'] as $element) {
            $attributes[$element] = json_encode($attributes[$element]);
        }
        return parent::update($attributes, $options);
    }
    
    public function create(array $attributes = array(), array $options = array()) {
        foreach(['model', 'view', 'controller'] as $element) {
            $attributes[$element] = json_encode($attributes[$element]);
        }
        return parent::create($attributes, $options);
    }
    
    public function read($options = null) {
        $data = parent::read($options);
        foreach ($data as $key => $item) {
            foreach(['model', 'view', 'controller'] as $element) {
                $data[$key][$element] = json_decode($item[$element]);
            }
        }
        return $data;
    }
}
