<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

trait UploadTrait {
    
    protected function getBasePath() {
        return config('filesystems.disks.public')['root'] . DIRECTORY_SEPARATOR;
    }

    public function upload(Request $request) {
        if (!$request->hasFile('file')) {
            return abort(400, "Missing file");
        }
        if (!$request->file('file')->isValid()) {
            return abort(400, "File not valid");
        }
        $id = $request->input('id');
        if (empty($id)) {
            return abort(400, "Missing id");
        }
        $request->file->storeAs('public/' . $this->getModel()
                ->getUploadFolder(), $this->mapName($id));
        return [];
    }

    public function remove(Request $request) {
        $id = $request->input('id');
        if (empty($id)) {
            return abort(400, "Missing id");
        }
        $file = $this->getBasePath() . 
                $this->getModel()->getUploadFolder() . DIRECTORY_SEPARATOR . 
                $this->mapName($id);
        File::delete($file);
        return [];
    }

    public function mapName() {
        $args = func_get_args();
        return $args[0] . '.jpg';
    }
}
