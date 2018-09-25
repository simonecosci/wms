<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Common\CrudController;
use App\Models\MvcElement;

class MvcElementsController extends CrudController {

    protected $view = 'admin.mvc-elements';

    public function __construct(MvcElement $model) {
        $this->model = $model;
    }

    public function createMigration(Request $request) {
        if (!$request->has('model'))
            return abort(400, "Missing aparameters");
        $model = json_decode($request->model);
        $code = view('admin.templates.migration', ['element' => $model])->render();
        $path = base_path('database/migrations') . DIRECTORY_SEPARATOR .
                date('Y_m_d_his_') . 'create_' . $model->model->table . '_table.php';
        File::put($path, '<?php' . PHP_EOL . $code);
        return $code;
    }

    public function createModel(Request $request) {
        if (!$request->has('model'))
            return abort(400, "Missing aparameters");
        $model = json_decode($request->model);
        $hasMany = [];
        $belongsTo = [];
        $elements = MvcElement::where('name', '!=', $model->name)->get();
        foreach ($elements as $dependent) {
            $depententModel = json_decode($dependent->model);
            if (!isset($depententModel->relations) || !is_array($depententModel->relations)) {
                continue;
            }
            foreach ($depententModel->relations as $relation) {
                if ($relation->on == $model->model->table) {
                    $hasMany[] = [
                        'name' => snake_case($depententModel->name),
                        'model' => $depententModel->name,
                        'on' => $relation->foreign
                    ];
                }
            }
        }
        foreach ($model->model->relations as $relation) {
            foreach ($elements as $related) {
                $relatedModel = json_decode($related->model);
                if ($relation->on !== $relatedModel->table) {
                    continue;
                }
                $belongsTo[] = [
                    'name' => snake_case($relatedModel->name),
                    'model' => $relatedModel->name,
                    'on' => $relation->foreign,
                ];
            }
        }
        $code = view('admin.templates.model', [
            'element' => $model,
            'hasMany' => $hasMany,
            'belongsTo' => $belongsTo,
                ])->render();
        $path = app_path('Models') . DIRECTORY_SEPARATOR .
                $model->model->name . '.php';
        File::put($path, '<?php' . PHP_EOL . $code);
        return $code;
    }

    public function createController(Request $request) {
        if (!$request->has('model'))
            return abort(400, "Missing aparameters");
        $model = json_decode($request->model);
        $validators = [];
        foreach ($model->model->fields as $field) {
            if (empty($field->validator)) {
                continue;
            }
            $validators[$field] = $field->validator;
        }
        $code = view('admin.templates.controller', [
            'element' => $model,
            'validators' => $validators
                ])->render();
        $path = app_path('Http/Controllers/Admin') . DIRECTORY_SEPARATOR .
                $model->controller->name . '.php';
        File::put($path, '<?php' . PHP_EOL . $code);
        return $code;
    }

    public function createView(Request $request) {
        if (!$request->has('model'))
            return abort(400, "Missing aparameters");
        $model = json_decode($request->model);
        $elements = MvcElement::where('name', '!=', $model->name)->get();
        $belongsTo = [];
        foreach ($model->model->relations as $relation) {
            foreach ($elements as $related) {
                $relatedModel = json_decode($related->model);
                if ($relation->on !== $relatedModel->table) {
                    continue;
                }
                $belongsTo[] = snake_case($relatedModel->name);
            }
        }
        $code = view('admin.templates.view', [
            'element' => $model,
            'belongsTo' => $belongsTo
                ])->render();
        $path = resource_path('views/admin') . DIRECTORY_SEPARATOR .
                $model->view->name . '.blade.php';
        File::put($path, $code);
        return $code;
    }

}
