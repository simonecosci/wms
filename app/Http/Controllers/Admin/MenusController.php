<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Common\CrudController;
use App\Models\Menu;

class MenusController extends CrudController {

    public function __construct(Menu $model) {
        $this->model = $model;
    }

    public function update(Request $request) {
        if ($request->input("models") === null) {
            return abort(400, "Missing models");
        }
        $models = json_decode($request->input("models"), JSON_OBJECT_AS_ARRAY);
        foreach ($models as $k => $model) {
            $model['index'] = $k;
            $model['menu_id'] = null;
            $this->persist($model);
        }
        return [];
    }

    protected function persist(array $data) {
        if (!isset($data['id'])) 
            $model = new Menu();
        else
            $model = Menu::find($data['id']);
        if (empty($model)) {
            return abort(401, "Element not found");
        }
        $model->fill($data)->save();
        foreach ($data['items'] as $k => $item) {
            $item['index'] = $k;
            $item['menu_id'] = $model->id;
            $this->persist($item);
        }
    }

    public function destroy(Request $request) {
        if ($request->input("models") === null) {
            return abort(400, "Missing models");
        }
        $models = json_decode($request->input("models"), JSON_OBJECT_AS_ARRAY);
        foreach ($models as $data) {
            Menu::with('items')->find($data['id'])->remove();
        }
        return [];
    }

    public function publish() {
        $items = $this->model->read()->toJson();
        $menu = public_path('app/Application.menu.js');
        File::put($menu, $items);
        return $items;
    }
    
}
