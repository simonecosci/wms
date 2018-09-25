<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use App;

abstract class CrudController extends Controller {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     *
     * @var string
     */
    protected $view;

    /**
     *
     * @var App\Models\Common\CrudModel
     */
    protected $model;
    
    /**
     *
     * @var boolean
     */
    protected $reopenOnSave = false;

    /**
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View 
     */
    public function index(Request $request) {
        return view($this->getView())
                ->with('controllerName', $request->controllerName);
    }

    /**
     * return an entry
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request) {
        $key = $this->getModel()->getKeyName();
        if (!$request->has($key)) {
            return abort(400, "Missing $key");
        }
        return $this->getModel()->find($request->input($key));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request) {
        return $this->getModel()->read($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if ($request->input("models") === null) {
            return abort(400, "Missing models");
        }
        $models = json_decode($request->input("models"), JSON_OBJECT_AS_ARRAY);
        foreach ($models as $k => $data) {
            if (!Validator::make($data, $this->rules())->fails()) {
                $models[$k] = $this->getModel()->create($data);
            }
        }
        if ($this->reopenOnSave){
            return response("id=" . array_pop($models)->id)
                    ->header("Content-Type", 'text/plain');
        }
        return $models;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        if ($request->input("models") === null) {
            return abort(400, "Missing models");
        }
        $models = json_decode($request->input("models"), JSON_OBJECT_AS_ARRAY);
        foreach ($models as $k => $data) {
            if (!Validator::make($data, $this->rules())->fails()) {
                $model      = $this->getModel();
                $pk         = $model->getPrimaryKey();
                $models[$k] = $model->find($data[$pk]);
                $models[$k]->update($data);
            }
        }
        if ($this->reopenOnSave && isset($data['id'])){
            return response("id=" . $data['id'])
                    ->header("Content-Type", 'text/html');
        }
        return $models;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        if ($request->input("models") === null) {
            return abort(400, "Missing models");
        }
        $models = json_decode($request->input("models"), JSON_OBJECT_AS_ARRAY);
        $key = $this->getModel()->getKeyName();
        foreach ($models as $data) {
            $entry = $this->getModel()->find($data[$key]);
            if (!empty($entry)) {
                $entry->delete();
            }
        }
        return $models;
    }

    /**
     * if not specified use the class name snaked as view name
     * @return string
     */
    public function getView() {
        if (is_null($this->view)) {
            $cls = explode('\\', static::class);
            $name = array_pop($cls);
            $module = array_pop($cls);
            $this->view = snake_case($module) . '.' . 
                    snake_case(substr($name, 0, -(strlen('Controller'))));
        }
        return $this->view;
    }

    /**
     * Return a model filled with given data
     * @param array $data
     * @return \App\Models\Common\CrudModel
     */
    public function getModel(array $data = array()) {
        return App::make(get_class($this->model))->fill($data);
    }
    
    
    public function rules() {
        return [];
    }
}
