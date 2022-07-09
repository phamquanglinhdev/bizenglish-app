<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GradeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class GradeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class GradeCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Grade::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/grade');
        CRUD::setEntityNameStrings('Lớp học', 'Các lớp học');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name')->label("Tên lớp");
        CRUD::column('pricing')->label("Gói học phí");
        CRUD::column('minutes')->label("Số phút");
        CRUD::column('status')->label("Trạng thái")->type("select_from_array")->options(["Đang học","Đã kết thúc","Đã bảo lưu"]);
        CRUD::column('created_at')->label("Ngày tạo lớp");


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
        CRUD::setValidation(GradeRequest::class);

        CRUD::field('name')->label("Tên lớp");
        CRUD::field('pricing')->label("Gói học phí");
        CRUD::field('minutes')->label("Số phút")->type("number");
        CRUD::field('status')->label("Trạng thái")->type("select_from_array")->options(["Đang học","Đã kết thúc","Đã bảo lưu"]);
        CRUD::addField(
            [    // Select2Multiple = n-n relationship (with pivot table)
                'label'     => "Học sinh",
                'type'      => 'select2_multiple',
                'name'      => 'student', // the method that defines the relationship in your Model

                // optional
                'entity'    => 'Student', // the method that defines the relationship in your Model
                'model'     => "App\Models\User", // foreign key model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
                // 'select_all' => true, // show Select All and Clear buttons?

                // optional
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->where('type', 3)->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            ],
        );
        CRUD::addField(
            [    // Select2Multiple = n-n relationship (with pivot table)
                'label'     => "Giáo viên",
                'type'      => 'select2_multiple',
                'name'      => 'teacher', // the method that defines the relationship in your Model

                // optional
                'entity'    => 'Teacher', // the method that defines the relationship in your Model
                'model'     => "App\Models\User", // foreign key model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
                // 'select_all' => true, // show Select All and Clear buttons?

                // optional
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->where('type', 1)->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            ],
        );
        CRUD::addField(
            [    // Select2Multiple = n-n relationship (with pivot table)
                'label'     => "Đối tác",
                'type'      => 'select2_multiple',
                'name'      => 'client', // the method that defines the relationship in your Model

                // optional
                'entity'    => 'Client', // the method that defines the relationship in your Model
                'model'     => "App\Models\User", // foreign key model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
                // 'select_all' => true, // show Select All and Clear buttons?

                // optional
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->where('type', 2)->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            ],
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
}
