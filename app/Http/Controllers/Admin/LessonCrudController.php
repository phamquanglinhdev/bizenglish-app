<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LessonCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LessonCrudController extends CrudController
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
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Lesson::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lesson');
        CRUD::setEntityNameStrings(trans("backpack::crud.curriculum"), trans("backpack::crud.curriculums"));
        $this->crud->denyAccess(["show"]);
        if (backpack_user()->type != -1) {
            $this->crud->denyAccess(["create", "update", "delete"]);
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name')->label(trans("backpack::crud.curriculum_name"));
        CRUD::column('pdf')->label(trans("backpack::crud.drive_link"))->type("pdf");
        CRUD::column('link')->label(trans("backpack::crud.pdf_link"))->type("pdf");
        CRUD::addColumn(
            [
                'name' => 'book_id',
                'label' => trans("backpack::crud.book"),
                'type' => 'select',
                'model' => 'App\Models\Book',
                'entity' => 'Book',
                'attribute' => 'name',
            ]);
        CRUD::column('updated_at')->label(trans("backpack::crud.updated_at"));;

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
        CRUD::setValidation(LessonRequest::class);
        CRUD::field('user_id')->type("hidden")->value(backpack_user()->id);
        CRUD::field('name')->type("text")->label("Tên");
        CRUD::addField(
            [
                'name' => 'book_id',
                'label' => 'Bộ sách',
                'type' => 'select2',
                'model' => 'App\Models\Book',
                'entity' => 'Book',
                'attribute' => 'name',
            ]);
        CRUD::addField(
            [
                'name' => 'pdf',
                'label' => 'Duyệt file',
                'type' => 'browse',
//                'upload'    => true,
//                'disk'      => 'uploads_document',
            ]);
        CRUD::addField(
            [
                'name' => 'link',
                'label' => 'Google Drive',
                'prefix' => 'https://'
            ]
        );

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

    protected function detail($id)
    {
        return view("lesson-show", ['lesson' => Lesson::find($id)->first()]);
    }
}
