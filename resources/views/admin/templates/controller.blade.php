
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Common\CrudController;
use App\Models\{{ $element->model->name }};

class {{ $element->controller->name }} extends CrudController {

    protected $view = 'admin.{{ $element->view->name }}';
    
    public function __construct({{ $element->model->name }} $model) {
        $this->model = $model;
    }
@if (!empty($validators))   
    
    public function rules() {
        return [
@foreach($validators as $name => $validator)
            '{{ $name }}' => '{{ $validator }}',

@endforeach
        ];
    }
@endif
}
