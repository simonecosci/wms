
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
                    if (!empty($field->autoincrement))
                        echo '->autoIncrement()';
                    echo ';' . PHP_EOL;
                }
                if ($element->model->timestamps)
                    echo $t . '$table->timestamps();' . PHP_EOL;
                
                foreach($element->model->relations as $field){
                    echo $t . '$table->foreign(\'' . $field->foreign . '\')'
                            . '->references(\'' . $field->references . '\')'
                            . '->on(\'' . $field->on . '\')'
                            . '->onDelete(\'' . $field->onDelete . '\')'
                            . ';' . PHP_EOL;
                }
            ?>
            
        });
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
