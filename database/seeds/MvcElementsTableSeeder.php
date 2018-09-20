<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Menu;

class MvcElementsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('mvc_elements')->insert([
            'name' => 'products',
            'model' => '{"name": "Product", "table": "products", "fields": [{"name": "price", "index": false, "input": "number", "label": "Price", "dbtype": "float", "length": 9, "unique": false, "primary": false, "vartype": "number", "decimals": 2, "fillable": true, "nullable": false, "required": true, "inputOptions": null, "autoincrement": false}, {"name": "code", "index": false, "input": "text", "label": "Code", "dbtype": "string", "length": 128, "unique": false, "primary": false, "vartype": "string", "decimals": 0, "fillable": true, "nullable": false, "required": false, "inputOptions": null, "autoincrement": false}, {"name": "id", "index": true, "input": null, "label": "Id", "dbtype": "unsignedInteger", "length": null, "unique": true, "primary": true, "vartype": "number", "decimals": null, "fillable": false, "nullable": true, "required": false, "inputOptions": null, "autoincrement": true}], "relations": [], "timestamps": true}',
            'view' => '{"grid": {"reopenOnSave": false, "destroyOnClose": true}, "name": "products", "window": {"title": "Products", "width": "", "height": ""}, "columns": [{"field": "price", "title": "Price", "width": "", "hidden": false, "sortable": true, "template": "", "groupable": true, "resizable": true, "columnMenu": true, "filterable": true}, {"field": "code", "title": "Code", "width": "", "hidden": false, "sortable": true, "template": "", "groupable": true, "resizable": true, "columnMenu": true, "filterable": true}, {"field": "id", "title": "Id", "width": null, "hidden": true, "sortable": true, "template": null, "groupable": false, "resizable": true, "columnMenu": true, "filterable": true}], "pageSize": 50, "pageable": true, "windowed": true}',
            'controller' => '{"name": "ProductsController", "nested": []}'
        ]);
        DB::table('mvc_elements')->insert([
            'name' => 'customers',
            'model' => '{"name": "Customer", "table": "customers", "fields": [{"name": "country_id", "index": false, "input": "select", "label": "Country", "dbtype": "unsignedInteger", "length": null, "unique": false, "primary": false, "vartype": "number", "decimals": null, "fillable": true, "nullable": false, "required": true, "inputOptions": {"path": "admin/countries", "textField": "name", "controller": "countries", "valueField": "id"}, "autoincrement": false}, {"name": "birthdate", "index": false, "input": "date", "label": "Birth Date", "dbtype": "date", "length": null, "unique": false, "primary": false, "vartype": "date", "decimals": null, "fillable": true, "nullable": true, "required": false, "inputOptions": null, "autoincrement": false}, {"name": "enabled", "index": false, "input": "checkbox", "label": "Enabled", "dbtype": "boolean", "length": null, "unique": false, "primary": false, "vartype": "boolean", "decimals": null, "fillable": true, "nullable": false, "required": false, "inputOptions": null, "autoincrement": false}, {"name": "email", "index": true, "input": "text", "label": "Email", "dbtype": "string", "length": 128, "unique": true, "primary": false, "vartype": "string", "decimals": 0, "fillable": true, "nullable": false, "required": true, "inputOptions": null, "autoincrement": false}, {"name": "name", "index": false, "input": "text", "label": "Name", "dbtype": "string", "length": 128, "unique": false, "primary": false, "vartype": "string", "decimals": 0, "fillable": true, "nullable": false, "required": true, "inputOptions": null, "autoincrement": false}, {"name": "id", "index": true, "input": null, "label": "Id", "dbtype": "unsignedInteger", "length": null, "unique": true, "primary": true, "vartype": "number", "decimals": null, "fillable": false, "nullable": true, "required": false, "inputOptions": null, "autoincrement": true}], "relations": [{"on": "countries", "foreign": "country_id", "onDelete": "cascade", "references": "id"}], "timestamps": true}',
            'view' => '{"grid": {"reopenOnSave": false, "destroyOnClose": true}, "name": "customers", "window": {"title": "Customers", "width": "", "height": ""}, "columns": [{"field": "country.name", "title": "Country", "width": "", "hidden": false, "sortable": true, "template": "#: data.country ? data.country.name : \'\' #", "groupable": true, "resizable": true, "columnMenu": true, "filterable": true}, {"field": "email", "title": "Email", "width": "", "hidden": false, "sortable": true, "template": "", "groupable": true, "resizable": true, "columnMenu": true, "filterable": true}, {"field": "name", "title": "Name", "width": "", "hidden": false, "sortable": true, "template": "", "groupable": true, "resizable": true, "columnMenu": true, "filterable": true}, {"field": "id", "title": "Id", "width": null, "hidden": true, "sortable": true, "template": null, "groupable": false, "resizable": true, "columnMenu": true, "filterable": true}], "pageSize": 50, "pageable": true, "windowed": true, "timestamps": true}',
            'controller' => '{"name": "CustomersController", "nested": []}'
        ]);
        DB::table('mvc_elements')->insert([
            'name' => 'orders',
            'model' => '{"name": "Order", "table": "orders", "fields": [{"name": "note", "index": false, "input": "textarea", "label": "Note", "dbtype": "text", "length": 128, "unique": false, "primary": false, "vartype": "string", "decimals": 0, "fillable": true, "nullable": false, "required": false, "inputOptions": null, "autoincrement": false}, {"name": "date", "index": false, "input": "datetime", "label": "Date", "dbtype": "dateTime", "length": null, "unique": false, "primary": false, "vartype": "date", "decimals": null, "fillable": true, "nullable": false, "required": true, "inputOptions": null, "autoincrement": false}, {"name": "number", "index": true, "input": "number", "label": "Number", "dbtype": "unsignedInteger", "length": null, "unique": false, "primary": false, "vartype": "number", "decimals": null, "fillable": true, "nullable": false, "required": true, "inputOptions": null, "autoincrement": false}, {"name": "customer_id", "index": true, "input": "chooser", "label": "Customer", "dbtype": "unsignedInteger", "length": null, "unique": false, "primary": false, "vartype": "number", "decimals": null, "fillable": true, "nullable": false, "required": true, "inputOptions": {"path": "admin/customers", "display": "name, email", "controller": "customers"}, "autoincrement": false}, {"name": "id", "index": true, "input": null, "label": "Id", "dbtype": "unsignedInteger", "length": null, "unique": true, "primary": true, "vartype": "number", "decimals": null, "fillable": false, "nullable": true, "required": false, "inputOptions": null, "autoincrement": true}], "relations": [{"on": "customers", "foreign": "customer_id", "onDelete": "cascade", "references": "id"}], "timestamps": true}',
            'view' => '{"grid": {"reopenOnSave": false, "destroyOnClose": true}, "name": "orders", "window": {"title": "Orders", "width": "", "height": ""}, "columns": [{"field": "date", "title": "Date", "width": "", "hidden": false, "sortable": true, "template": "", "groupable": true, "resizable": true, "columnMenu": true, "filterable": true}, {"field": "customer.name", "title": "Customer", "width": "", "hidden": false, "sortable": true, "template": "#: data.customer ? data.customer.name : \'\' #", "groupable": true, "resizable": true, "columnMenu": true, "filterable": true}, {"field": "number", "title": "Number", "width": "", "hidden": false, "sortable": true, "template": "", "groupable": true, "resizable": true, "columnMenu": true, "filterable": true}, {"field": "id", "title": "Id", "width": null, "hidden": true, "sortable": true, "template": null, "groupable": false, "resizable": true, "columnMenu": true, "filterable": true}], "pageSize": 50, "pageable": true, "windowed": true, "timestamps": true}',
            'controller' => '{"name": "OrdersController", "nested": [{"foreign": "order_id", "controller": "orders-products"}]}'
        ]);
        DB::table('mvc_elements')->insert([
            'name' => 'orders-products',
            'model' => '{"name": "OrderProduct", "table": "orders_products", "fields": [{"name": "quantity", "index": false, "input": "number", "label": "Quantity", "dbtype": "unsignedInteger", "length": null, "unique": false, "primary": false, "vartype": "number", "decimals": 0, "fillable": true, "nullable": false, "required": true, "inputOptions": null, "autoincrement": false}, {"name": "product_id", "index": true, "input": "chooser", "label": "Product", "dbtype": "unsignedInteger", "length": null, "unique": false, "primary": false, "vartype": "number", "decimals": null, "fillable": true, "nullable": false, "required": true, "inputOptions": {"path": "admin/products", "display": "code", "controller": "products"}, "autoincrement": false}, {"name": "order_id", "index": true, "input": "hidden", "label": "Order", "dbtype": "unsignedInteger", "length": null, "unique": false, "primary": false, "vartype": "number", "decimals": 0, "fillable": true, "nullable": false, "required": true, "inputOptions": null, "autoincrement": false}, {"name": "id", "index": true, "input": null, "label": "Id", "dbtype": "unsignedInteger", "length": null, "unique": true, "primary": true, "vartype": "number", "decimals": null, "fillable": false, "nullable": true, "required": false, "inputOptions": null, "autoincrement": true}], "relations": [{"on": "orders", "foreign": "order_id", "onDelete": "cascade", "references": "id"}, {"on": "products", "foreign": "product_id", "onDelete": "cascade", "references": "id"}], "timestamps": true}',
            'view' => '{"grid": {"reopenOnSave": false, "destroyOnClose": true}, "name": "orders-products", "window": {"title": "a", "width": "", "height": ""}, "columns": [{"field": "quantity", "title": "Quantity", "width": "", "hidden": false, "sortable": true, "template": "", "groupable": true, "resizable": true, "columnMenu": true, "filterable": true}, {"field": "product.code", "title": "Product", "width": "", "hidden": false, "sortable": true, "template": "#: data.product ? data.product.code : \'\' #", "groupable": true, "resizable": true, "columnMenu": true, "filterable": true}, {"field": "id", "title": "Id", "width": null, "hidden": true, "sortable": true, "template": null, "groupable": false, "resizable": true, "columnMenu": true, "filterable": true}], "pageSize": 50, "pageable": true, "windowed": false}',
            'controller' => '{"name": "OrdersProductsController", "nested": []}'
        ]);
        DB::table('mvc_elements')->insert([
            'name' => 'countries',
            'model' => '{"name": "Country", "table": "countries", "fields": [{"name": "name", "index": false, "input": "text", "label": "Name", "dbtype": "string", "length": 128, "unique": false, "primary": false, "vartype": "string", "decimals": 0, "fillable": true, "nullable": false, "required": true, "inputOptions": null, "autoincrement": false}, {"name": "id", "index": true, "input": null, "label": "Id", "dbtype": "unsignedInteger", "length": null, "unique": true, "primary": true, "vartype": "number", "decimals": null, "fillable": false, "nullable": true, "required": false, "inputOptions": null, "autoincrement": true}], "relations": [], "timestamps": true}',
            'view' => '{"grid": {"reopenOnSave": false, "destroyOnClose": true}, "name": "countries", "window": {"title": "Countries", "width": "", "height": ""}, "columns": [{"field": "name", "title": "Name", "width": "", "hidden": false, "sortable": true, "template": "", "groupable": true, "resizable": true, "columnMenu": true, "filterable": true}, {"field": "id", "title": "Id", "width": null, "hidden": true, "sortable": true, "template": null, "groupable": false, "resizable": true, "columnMenu": true, "filterable": true}], "pageSize": 50, "pageable": false, "windowed": true}',
            'controller' => '{"name": "CountriesController", "nested": []}'
        ]);

        $mvcExamples = DB::table('menus')->insertGetId([
            'name' => 'Mvc Example',
            'index' => 2,
            'icon' => 'share'
        ]);
        DB::table('menus')->insert([
            'menu_id' => $mvcExamples,
            'name' => 'Countries',
            'index' => 0,
            'controller' => 'countries',
            'path' => 'admin/countries',
            'callback' => '',
            'icon' => 'globe'
        ]);
        DB::table('menus')->insert([
            'menu_id' => $mvcExamples,
            'name' => 'Customers',
            'index' => 1,
            'controller' => 'customers',
            'path' => 'admin/customers',
            'callback' => '',
            'icon' => 'user'
        ]);
        DB::table('menus')->insert([
            'menu_id' => $mvcExamples,
            'name' => 'Products',
            'index' => 2,
            'controller' => 'products',
            'path' => 'admin/products',
            'callback' => '',
            'icon' => 'cart'
        ]);
        DB::table('menus')->insert([
            'menu_id' => $mvcExamples,
            'name' => 'Orders',
            'index' => 3,
            'controller' => 'orders',
            'path' => 'admin/orders',
            'callback' => '',
            'icon' => 'inbox'
        ]);
        $items = (new Menu)->read()->toJson();
        $menu = public_path('app/Application.menu.js');
        File::put($menu, $items);
        
    }

}
