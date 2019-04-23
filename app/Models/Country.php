<?php
namespace App\Models;

use App\Models\Common\CrudModel;

class Country extends CrudModel {

    protected $table = 'countries';
    protected $fillable = ['name'];

    
    public function customer() {
        return $this->hasMany(Customer::class, 'country_id');
    }
    
}
