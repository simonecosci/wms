
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create{{ ucfirst(camel_case($element->model->table)) }}Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('{{ $element->model->table }}', function (Blueprint $table) {
            $table->engine = 'InnoDB';

<?php
                $t = "            ";
                foreach($element->model->fields as $field){
                    if ($field->primary && $field->autoincrement) {
                        echo $t . '$table->increments(\'' . $field->name . '\');' . PHP_EOL;
                        break;
                    }
                }
                $indexes = [];
                foreach($element->model->fields as $field){
                    if ($field->primary && $field->autoincrement) {
                        continue;
                    }
                    echo $t . '$table->' . $field->dbtype . '(\'' . $field->name . '\'';
                    if (!empty($field->length))
                        echo ', ' . $field->length;
                    if (!empty($field->decimals))
                        echo ', ' . $field->decimals;
                    echo ')';
                    if (!empty($field->nullable))
                        echo '->nullable()';
                    if (!empty($field->unique))
                        echo '->unique()';
                    if (!empty($field->default))
                        echo '->default(' . $field->default . ')';
                    if (!$field->primary && $field->autoincrement) 
                        echo '->autoIncrement()';
                    echo ';' . PHP_EOL;
                    if ($field->index) {
                        $indexes[] = $field->name;
                    }
                }
                if ($element->model->timestamps)
                    echo $t . '$table->timestamps();' . PHP_EOL;
                if (count($indexes) > 0) {
                    echo $t . '$table->index([\'' . implode("', '", $indexes) . '\']);';
                }
            ?>
            
        });
        
<?php if (!empty($element->model->relations)) : ?>
        Schema::table('{{ $element->model->table }}', function($table) {
<?php foreach($element->model->relations as $field){
        echo $t . '$table->foreign(\'' . $field->foreign . '\')'
                . '->references(\'' . $field->references . '\')'
                . '->on(\'' . $field->on . '\')'
                . '->onDelete(\'' . $field->onDelete . '\')'
                . ';' . PHP_EOL;
    }
?>
        });
<?php endif; ?>
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{{ $element->model->table }}');
    }
}
