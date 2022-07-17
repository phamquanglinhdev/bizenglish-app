<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StaffRequest;
use App\Models\Staff;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class StaffCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StaffCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Staff::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/staff');
        CRUD::setEntityNameStrings('Nhân viên', 'Danh sách nhân viên');
        $this->crud->denyAccess(["show"]);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause("where","disable",0);
        $this->crud->addClause("where", "type", "0");
        CRUD::addColumn(['name' => 'name', 'type' => 'text', 'label' => "Tên nhân viên"]);
        CRUD::addColumn(['name' => 'code', 'type' => 'text', 'label' => "Mã nhân viên"]);
        CRUD::addColumn(['name' => 'phone', 'type' => 'text', 'label' => "Số điện thoại"]);
        CRUD::addColumn(['name' => 'email', 'type' => 'text', "label" => "Email của nhân viên"]);
        CRUD::addColumn([
            'name' => 'grades',
            'entity'=>'Grades',
            'model'=>"App\Models\Grade",
            'label'=>'Lớp',
            'type' => 'relationship',
            'attribute'=>'name',
            'wrapper'   => [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url("/log?grade_id=$related_key");
                },
                // 'target' => '_blank',
                // 'class' => 'some-class',
            ],
        ]);
        $this->crud->addButtonFromModelFunction("line", "Detail", "Detail", "line");

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
        CRUD::setValidation(StaffRequest::class);

        CRUD::field('name')->label("Tên nhân viên");
        CRUD::field('email')->label("Email nhân viên");
        CRUD::addField(['name' => 'phone', 'type' => 'text', 'label' => "Số điện thoại"]);
        CRUD::field('type')->type("hidden")->value(0);
        CRUD::field('avatar')->type("image")->crop(true)->aspect_ratio(1);
        CRUD::field("facebook")->label("Link Facebook");
        CRUD::field("address")->label("Địa chỉ");
        CRUD::field("code")->type("hidden");

        CRUD::addField(
            [    // Select2Multiple = n-n relationship (with pivot table)
                'label' => "Học sinh quản lý (Khi nhân viên tạo học sinh cũng sẽ tự động thêm vào)",
                'type' => 'select2_multiple',
                'name' => 'students', // the method that defines the relationship in your Model

                // optional
                'entity' => 'Students', // the method that defines the relationship in your Model
                'model' => "App\Models\User", // foreign key model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                // 'select_all' => true, // show Select All and Clear buttons?

                // optional
                'options' => (function ($query) {
                    return $query->orderBy('name', 'ASC')->where('type', 3)->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            ],
        );
        CRUD::addField(
            [
                'name' => 'extra',
                'label' => 'Thông tin thêm của nhân viên',
                'type' => 'repeatable',
                'new_item_label' => 'Thêm thông tin', // customize the text of the button
                'fields' => [
                    [
                        'name' => 'name',
                        'type' => 'text',
                        'label' => 'Tên',
                        'wrapper' => ['class' => 'form-group col-md-6'],
                    ],
                    [
                        'name' => 'info',
                        'type' => 'text',
                        'label' => 'Thông tin',
                        'wrapper' => ['class' => 'form-group col-md-6'],
                    ],
                ],
            ],
        );
        CRUD::addField(
            [   // Password
                'name' => 'password',
                'label' => 'Mật khẩu',
                'type' => 'password'
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
    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    protected function detail($id)
    {
        return view("staff-detail", ['data' => Staff::find($id)]);
    }
}
