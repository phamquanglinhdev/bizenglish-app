<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TeacherCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TeacherCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Teacher::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/teacher');
        CRUD::setEntityNameStrings('Giáo viên', 'Giáo viên');
        $this->crud->addButtonFromModelFunction("line","Detail","Detail","line");
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
        $this->crud->addClause("where", "type", "1");
        CRUD::addColumn(['name' => 'code', 'type' => 'text', 'label' => "Mã giáo viên"]);
        CRUD::addColumn(['name' => 'name', 'type' => 'text', 'label' => "Tên giáo viên"]);
        CRUD::addColumn(['name' => 'email', 'type' => 'text', "label" => "Email của giáo viên"]);
        CRUD::addColumn([
            'name' => 'skills',
            'entity'=>'Skills',
            'model'=>"App\Models\Skill",
            'label'=>'Tag',
            'type' => 'relationship',
            'attribute'=>'name'
        ]);
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
        CRUD::setValidation(TeacherRequest::class);
        CRUD::field('name')->label("Tên giáo viên");
        CRUD::field('email')->label("Email giáo viên");
        CRUD::field("facebook")->label("Link Facebook");
        CRUD::field("address")->label("Địa chỉ");
        CRUD::field('avatar')->type("image")->crop(true)->aspect_ratio(1);
        CRUD::field('type')->type("hidden")->value(1);
        CRUD::field('code')->type("hidden");
        CRUD::addField([
            'name' => 'skills',
            'entity'=>'Skills',
            'model'=>"App\Models\Skill",
            'label'=>'Tag',
            'type' => 'relationship',
            'attribute'=>'name'
        ]);
        CRUD::addField(
            [
                'name' => 'extra',
                'label' => 'Thông tin thêm của giáo viên',
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
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
    protected function detail($id){
        return view("teacher-detail",['data'=>Teacher::find($id)]);
    }
}
