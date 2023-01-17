<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookRequest;
use App\Models\Book;
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
        $this->crud->denyAccess(["delete", "show"]);
        if (backpack_user()->type != -1) {
            $this->crud->denyAccess(["create", "update", "delete"]);
        }
        $this->crud->addButtonFromModelFunction("line", "copy", "Copy", "line");
//        $this->crud->enableDetailsRow();
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function index()
    {
        $bag = [];
        $name = [
            'Không xác định',
            'Metarial for kid 4-10 year old',
            'Metarial for kid 11-18 year old',
            'Metarial for Aldult',
            'IELTS',
            'TOEIC',

        ];
        for ($i = 0; $i <= 5; $i++) {
            $bag[] = Book::where("type", $i)->get();
        }
//        dd($bag);
        return view("books", ["bag" => $bag,'name'=>$name]);
    }

    protected function setupListOperation()
    {
        CRUD::column('name')->label(trans("backpack::crud.book_name"));
        CRUD::column('thumbnail')->label(trans("backpack::crud.thumbnail"))->type("image");
        CRUD::column('description')->label(trans("backpack::crud.description"))->type("text");


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
            'name' => 'type',
            'label' => 'Danh mục',
            'type' => 'select2_from_array',
            'options' => [
                'Không xác định',
                'Metarial for kid 4-10 year old',
                'Metarial for kid 11-18 year old',
                'Metarial for Aldult',
                'IELTS',
                'TOEIC',

            ]
        ]);
        CRUD::field('name')->label("Tên sách");
        CRUD::field('slug')->type("hidden");
        CRUD::field('description')->label("Mô tả");
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
