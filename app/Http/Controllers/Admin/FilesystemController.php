<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Common\Controller;

class FilesystemController extends Controller {

    protected $view = 'admin.filesystem';
    
    protected function getBasePath() {
        return config('filesystems.disks.public')['root'] . DIRECTORY_SEPARATOR;
    }

    public function read(Request $request) {
        $path = $request->input('path', '');
        $files = glob($this->getBasePath() . $path . DIRECTORY_SEPARATOR . '*');
        $items = [];
        foreach ($files as $file) {
            $type = File::type($file);
            $items[] = [
                'name' => basename($file),
                'size' => File::size($file),
                'type' => substr($type, 0, 1)
            ];
        }
        return $items;
    }

    public function thumbnail(Request $request) {
        $path = $this->getBasePath() . $request->input('path');
        if (empty($path)) {
            return abort(400, "Missing path");
        }
        $extension = strtolower(File::extension($path));
        if (in_array($extension, ['jpg', 'png', 'gif'])) {
            $img = Image::make($path)->resize(80, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            switch ($extension) {
                
                case 'doc':
                case 'docx':
                case 'rtf':
                    $img = Image::make(public_path("/images/upload/doc.png"));
                    break;
                
                case 'pdf':
                    $img = Image::make(public_path("/images/upload/pdf.png"));
                    break;
                
                case 'xls':
                case 'xlsx':
                    $img = Image::make(public_path("/images/upload/xls.png"));
                    break;
                
                case 'zip':
                    $img = Image::make(public_path("/images/upload/zip.png"));
                    break;
                
                default:
                    $img = Image::make(public_path("/images/upload/default.png"));
                    break;
            }
        }
        return $img->response('jpg');
    }

    public function create(Request $request) {
        $name = $request->input('name');
        if (empty($name)) {
            return abort(400, "Mieesing name");
        }
        $type = $request->input('type');
        if (empty($type)) {
            return abort(400, "Mieesing type");
        }
        $path = $request->input('path');
        $file = $this->getBasePath() . trim($path) . trim($name);
        switch ($type) {
            case 'd':
                File::makeDirectory($file);
                break;

            case 'f':
                File::put($file, '');
                break;

            default:
                break;
        }
        return [
            'name' => basename($file),
            'size' => File::size($file),
            'type' => substr($type, 0, 1)
        ];
    }

    public function upload(Request $request) {
        if(!$request->hasFile('file')) {
            return abort(400, "Mieesing file");
        }
        if (!$request->file('file')->isValid()) {
            return abort(400, "File not valid");
        }
        $name = $request->file->getClientOriginalName();
        $size = $request->file->getClientSize();
        $request->file->storeAs('public' . DIRECTORY_SEPARATOR . $request->input('path', ''), $name);
        return [
            'name' => $name,
            'size' => $size,
            'type' => 'f'
        ];
    }
    
    public function destroy(Request $request) {
        $name = $request->input('name');
        if (empty($name)) {
            return abort(400, "Mieesing file name");
        }
        $path = $request->input('path');
        $file = $this->getBasePath() . $path . $name;
        $type = substr(File::type($file), 0, 1);
        $return = [
            'name' => $name,
            'size' => File::size($file),
            'type' => 'f'
        ];
        switch ($type) {
            case 'd':
                File::deleteDirectory($file);
                break;

            case 'f':
                File::delete($file);
                break;

            default:
                break;
        }
        return $return;
    }
    
}
