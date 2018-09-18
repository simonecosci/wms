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
    protected $fillable = [{{ implode(', ', $fillables) }}];

}
