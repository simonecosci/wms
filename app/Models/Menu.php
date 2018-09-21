<?php

namespace App\Models;

use App\Models\Common\CrudModel;

class Menu extends CrudModel {

    protected $table = 'menus';
    protected $fillable = [
        'name',
        'menu_id',
        'controller',
        'index',
        'path',
        'callback',
        'icon'
    ];

    public static function query() {
        return parent::query()->with(['items']);
    }

    public function items() {
        return $this->hasMany(get_class($this), 'menu_id')
                ->with(['items'])
                ->orderBy('index', 'ASC');
    }

    public function remove() {
        foreach ($this->items as $entry) {
            static::with('items')->find($entry->id)->remove();
        }
        return $this->delete();
    }

    public function read($options = null) {
        $query = static::query();
        if (!isset($options['menu_id']) || empty($options['menu_id'])) {
            $query = $query->whereNull('menu_id');
        } else {
            $query = $query->where('menu_id', '=', $options['menu_id']);
        }
        return $query->orderBy('index', 'ASC')->get();
    }

}
