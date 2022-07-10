<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LogRequest;
use App\Models\Log;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LogCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LogCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Log::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/log');
        CRUD::setEntityNameStrings('Nhật ký', 'Nhật ký chung');
        $this->crud->addButtonFromModelFunction("line","detail","detail","line");
        $this->crud->addButtonFromModelFunction("line","pushExercise","pushExercise","line");
        $this->crud->denyAccess(["show","delete"]);
        if(backpack_user()->type>1){
            $this->crud->denyAccess(["update"]);
        }
        if(backpack_user()->type>1){
            $this->crud->denyAccess(["create"]);
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
        if(backpack_user()->type==3){
            $this->crud->addClause('rep');
        }
        CRUD::addColumn([
            'name' => 'grade_id',
            'type' => 'select',
            'entity'=>'Grade',
            'model'=>"App\Model\Grade",
            'attribute'=>'name',
            'label'=>"Lớp",
        ]);

        CRUD::addColumn([
            'name' => 'teacher_id',
            'type' => 'select',
            'entity'=>'Teacher',
            'model'=>"App\Model\Teacher",
            'attribute'=>'name',
            'label'=>"Giáo viên dạy",
        ]);
        CRUD::column('duration')->label("Thời gian dạy");
        CRUD::column('lesson')->label("Bài học");
        CRUD::column('hour_salary')->label("Lương theo giờ (đ)")->type("number");
        CRUD::column('teacher_video')->label("Video bài giảng")->type("open");

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
        CRUD::setValidation(LogRequest::class);

        CRUD::addField([
            'name' => 'grade_id',
            'type' => 'select',
            'entity'=>'Grade',
            'model'=>"App\Models\Grade",
            'attribute'=>'name',
            'label'=>"Lớp",
        ]);
        CRUD::addField([
            'name' => 'teacher_id',
            'value'=>backpack_user()->id,
            'type'=>'hidden',
        ]);
        CRUD::field('time')->label("Thời gian")->type("datetime");
        CRUD::field('duration')->label("Thời gian dạy(Phút)");
        CRUD::field('lesson')->label("Bài học");
        CRUD::field('information')->label("Nội dung")->type("tinymce");
        CRUD::field('hour_salary')->label("Lương theo giờ");
        CRUD::addField(
            [
                'name'      => 'teacher_video',
                'label'     => 'Video bài giảng',
                'type'      => 'upload',
                'upload'    => true,
                'disk'      => 'google',
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
    protected function detail($id)
    {
        if(Log::find($id)){
            return view("log-detail",['log'=>Log::find($id)]);
        }
        return view("errors.404");

    }
}
