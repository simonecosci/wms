<?php
$fillables = [];
foreach ($element->model->fields as $field) {
    if ($field->fillable)
        $fillables[] = $field->name;
}
?>
namespace App\Models;

use App\Models\Common\CrudModel;

class {{ $element->model->name }} extends CrudModel {

    protected $table = '{{ $element->model->table }}';
    protected $fillable = ['{{ implode("', '", $fillables) }}'];

@foreach($belongsTo as $relation)
    public function {{ $relation->name }}() {
        return $this->belongsTo({{ $relation->model }}::class, '{{ $relation->on }}');
    }
    
@endforeach
@foreach($hasMany as $relation)
    public function {{ $relation->name }}() {
        return $this->hasMany({{ $relation->model }}::class, '{{ $relation->on }}');
    }
    
@endforeach
}
