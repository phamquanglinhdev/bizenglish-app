<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LogRequest;
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
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'grade_id',
            'type' => 'select',
            'entity'=>'Log',
            'model'=>"App\Model\Grade",
            'attribute'=>'name',
            'label'=>"Lớp",
        ]);
        CRUD::column('day')->label("Ngày");
        CRUD::column('month')->label("Tháng");
        CRUD::column('year')->label("Năm");
        CRUD::column('hour')->label("Giờ");
        CRUD::column('minutes')->label("Phút");
        CRUD::column('duration')->label("Thời gian dạy");
        CRUD::column('lesson')->label("Bài học");
        CRUD::column('information')->label("Nội dung");
        CRUD::column('hour_salary')->label("Lương theo giờ");
        CRUD::column('teacher_video')->label("Video bài giảng");

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
            'entity'=>'Log',
            'model'=>"App\Models\Grade",
            'attribute'=>'name',
            'label'=>"Lớp",
        ]);
        CRUD::field('day')->label("Ngày");
        CRUD::field('month')->label("Tháng");
        CRUD::field('year')->label("Năm");
        CRUD::field('hour')->label("Giờ");
        CRUD::field('minutes')->label("Phút");
        CRUD::field('duration')->label("Thời gian dạy(Phút)");
        CRUD::field('lesson')->label("Bài học");
        CRUD::field('information')->label("Nội dung");
        CRUD::field('hour_salary')->label("Lương theo giờ");
        CRUD::field('teacher_video')->label("Video bài giảng");

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
