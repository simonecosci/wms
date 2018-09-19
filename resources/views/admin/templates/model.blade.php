<?php
$fillables = [];
foreach ($element->model->fields as $field) {
    if ($field->fillable)
        $fillables[] = $field->name;
}
$relations = [];
foreach ($element->model->relations as $relation) {
    $relations[] = $relation->on;
}
?>
namespace App\Models;

use App\Models\Common\CrudModel;

class {{ $element->model->name }} extends CrudModel {

    protected $table = '{{ $element->model->table }}';
    protected $fillable = ['<?php echo implode("', '", $fillables) ?>'];

@if (!empty($relations))
    public static function query() {
        return parent::query()->with([<?php echo sprintf("'%s'", implode("', '", $relations)) ?>]);
    }
@endif
    
@foreach($belongsTo as $relation)
    public function {{ $relation['name'] }}() {
        return $this->belongsTo({{ $relation['model'] }}::class, '{{ $relation['on'] }}');
    }
    
@endforeach
@foreach($hasMany as $relation)
    public function {{ $relation['name'] }}() {
        return $this->hasMany({{ $relation['model'] }}::class, '{{ $relation['on'] }}');
    }
    
@endforeach
}
