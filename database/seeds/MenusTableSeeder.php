<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Menu;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = DB::table('menus')->insertGetId([
            'name' => 'Options',
            'index' => 0,
            'icon' => 'gear'
        ]);
        $application = DB::table('menus')->insertGetId([
            'name' => 'Application',
            'index' => 1,
            'icon' => 'window'
        ]);
        
        DB::table('menus')->insert([
            'menu_id' => $options,
            'name' => 'Logout',
            'index' => 0,
            'controller' => 'logout',
            'callback' => 'if (confirm("Do you really want to logout ?")) window.location ="/logout";',
            'icon' => 'logout'
        ]);
        DB::table('menus')->insert([
            'menu_id' => $options,
            'name' => 'Settings',
            'index' => 1,
            'controller' => 'settings',
            'path' => 'admin/settings',
            'icon' => 'gears'
        ]);
        DB::table('menus')->insert([
            'menu_id' => $options,
            'name' => 'About',
            'index' => 2,
            'controller' => 'about',
            'path' => 'admin/about',
            'icon' => 'info'
        ]);
        $windows = DB::table('menus')->insertGetId([
            'menu_id' => $options,
            'name' => 'Windows',
            'index' => 3,
            'icon' => 'window'
        ]);
        DB::table('menus')->insert([
            'menu_id' => $windows,
            'name' => 'Select one',
            'index' => 0,
            'controller' => 'windows-select-one',
            'callback' => 'var wins = app().ui.openedWindows();var dataSource = new kendo.data.DataSource();for (var i = 0; i < wins.length; i++) { dataSource.add({ title: wins[i].options.title, controller: wins[i].options.controllerName });}if ($("#wnd-manager").length === 0) $("<div id=\'wnd-manager\'/>").appendTo("#placeholder").kendoWindow({ title: "Opened windows", modal: true, width: "50%", heigth: "60%", close: function () { this.destroy(); }, open: function () { var listView = $("<div/>").kendoListView({ dataSource: dataSource, template: "<div title=\'#:title#\' class=\'wnd-icon k-block k-pane k-widget k-button\' onclick=\'app().controllers[\"#:controller#\"].window.toFront();$(this).closest(\"div.k-window\").find(\"[data-role=window]\").data(\"kendoWindow\").close();\'>#:title.substr(0, 32)#</div>"}).data("kendoListView");this.element.empty().append(listView.element);}});var wnd = $("#wnd-manager").data("kendoWindow");wnd.center().open();',
            'icon' => 'windows'
        ]);
        DB::table('menus')->insert([
            'menu_id' => $windows,
            'name' => 'Close all',
            'index' => 1,
            'controller' => 'windows-close-all',
            'callback' => 'var wins = app().ui.openedWindows();for (var i = 0; i < wins.length; i++) {wins[i].close();}',
            'icon' => 'close-outline'
        ]);
        
        DB::table('menus')->insert([
            'menu_id' => $application,
            'name' => 'Users',
            'index' => 0,
            'controller' => 'users',
            'path' => 'admin/users',
            'icon' => 'user'
        ]);
        DB::table('menus')->insert([
            'menu_id' => $application,
            'name' => 'Menus',
            'index' => 1,
            'controller' => 'menus',
            'path' => 'admin/menus',
            'icon' => 'menu'
        ]);
        DB::table('menus')->insert([
            'menu_id' => $application,
            'name' => 'Files',
            'index' => 2,
            'controller' => 'filesystem',
            'path' => 'admin/filesystem',
            'icon' => 'document-manager'
        ]);
        /*
        DB::table('menus')->insert([
            'menu_id' => $application,
            'name' => 'MVC Elements',
            'index' => 2,
            'controller' => 'mvc-elements',
            'path' => 'admin/mvc-elements',
            'icon' => 'page-properties'
        ]);
         * 
         */
        $items = (new Menu)->read()->toJson();
        $menu = public_path('app/Application.menu.js');
        File::put($menu, $items);
    }
}
