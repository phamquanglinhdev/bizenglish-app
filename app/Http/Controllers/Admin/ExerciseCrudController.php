<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExerciseRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ExerciseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ExerciseCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Exercise::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/exercise');
        CRUD::setEntityNameStrings('Bài tập', 'Quản lý bài tập');
        $this->crud->denyAccess(["delete", "show"]);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        if (backpack_user()->type >= 3) {
            $this->crud->addClause("where", "student_id", "=", backpack_user()->id);
        } else {
            CRUD::addColumn([
                'name' => 'student_id',
                'type' => 'number'
            ]);
        }
        CRUD::addColumn([
            'name' => 'log_id',
            'type' => 'select',
            'model'=>"App\Models\Log",
            'entity'=>"Log",
            'attribute'=>'lesson',
            'label'=>'Bài'
        ]);
        CRUD::addColumn([
            'name'=>"grades",
            'label'=>'Lớp',
            'type'  => 'model_function',
            'function_name' => 'Grade'
        ]);
        CRUD::column('video')->type("open");
        CRUD::column('document')->type("read")->label("Tài liệu");
        CRUD::column('updated_at')->label("Lần cập nhật cuối");

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
        CRUD::setValidation(ExerciseRequest::class);
        CRUD::field('student_id')->type("hidden")->value(backpack_user()->id);
        CRUD::field('log_id');
        CRUD::addField(
            [
                'name' => 'video',
                'label' => 'Nộp video',
                'type' => 'upload',
                'upload' => true,
                'disk' => 'uploads_video',
            ]);
        CRUD::addField(
            [
                'name'      => 'document',
                'label'     => 'Nộp tài liệu',
                'type'      => 'upload',
                'upload'    => true,
                'disk'      => 'uploads_document',
            ]);

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
}
