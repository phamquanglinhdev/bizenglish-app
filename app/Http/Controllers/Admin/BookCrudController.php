<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\Menu;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BookCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BookCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     * @throws \Backpack\CRUD\app\Exceptions\BackpackProRequiredException
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Book::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/book');
        CRUD::setEntityNameStrings(trans("backpack::crud.book"), trans("backpack::crud.books"));
        $this->crud->denyAccess(["show"]);
        if (backpack_user()->type != -1) {
            $this->crud->denyAccess(["create", "update", "delete"]);
        }
//        $this->crud->enableDetailsRow();
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    public function index()
    {
        $menus = Menu::where("depth", 1)->get();
//        dd($menus);
        return view("books",['menus'=>$menus]);
    }

    protected function setupListOperation()
    {
        CRUD::column('menu')->label(trans("backpack::crud.books"));
        CRUD::column('name')->label(trans("backpack::crud.book"));
        CRUD::column('thumbnail')->label(trans("backpack::crud.thumbnail"))->type("image");
        CRUD::column('description')->label(trans("backpack::crud.description"))->type("text");
        CRUD::column('link')->label("Url")->type("link");


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(BookRequest::class);
        CRUD::addField([
            'name' => 'menu_id',
            'type' => 'select2_nested',
            'model' => 'App\Models\Menu',
            'entity' => 'Menu',
            'attribute' => 'name',
        ]);
        CRUD::field('name')->label("Tên sách");
        CRUD::field('description')->label("Mô tả");
        CRUD::field('link')->label("Link");
        CRUD::field('thumbnail')->label("Ảnh")->type("image")->crop(true)->aspect_ratio(1907 / 2560);


        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function showDetailsRow($id)
    {

    }

}
