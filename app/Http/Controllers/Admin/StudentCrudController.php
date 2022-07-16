<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StudentRequest;
use App\Http\Requests\UserRequest;
use App\Models\Student;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

/**
 * Class StudentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StudentCrudController extends CrudController
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
        CRUD::setModel(Student::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/student');
        CRUD::setEntityNameStrings('Học sinh', 'Học sinh');
        $this->crud->addButtonFromModelFunction("line", "Detail", "Detail", "line");

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause("where", "type", "3");
        if (backpack_user()->type == 0) {
            $this->crud->addClause("where", "staff_id", backpack_user()->id);
        } else {
            CRUD::addColumn(['name' => 'staff_id', 'type' => 'select', 'attribute' => "name", "entity" => "Staff", "label" => "Nhân viên quản lý"]);
        }
        CRUD::addColumn(['name' => 'name', 'type' => 'text', 'label' => "Tên học sinh"]);

        CRUD::addColumn(['name' => 'code', 'type' => 'text', 'label' => "Mã học sinh"]);
        CRUD::addColumn(['name' => 'email', 'type' => 'text', "label" => "Email của học sinh"]);
        CRUD::column("student_type")->label("Phân loại học sinh")->type("select_from_array")->options(["Tiềm năng", "Không tiềm năng", "Chưa học thử"]);
        CRUD::column("student_status")->label("Tình trạng học sinh")->type("select_from_array")->options(["Đang học", "Đã ngừng học", "Đang bảo lưu"]);

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
        CRUD::setValidation(StudentRequest::class);

        CRUD::field('name')->label("Tên học sinh");
        CRUD::field('email')->label("Email học sinh");
        CRUD::field('type')->type("hidden")->value(3);
        CRUD::field('avatar')->type("image")->crop(true)->aspect_ratio(1);
        CRUD::field("facebook")->label("Link Facebook");
        CRUD::field("address")->label("Địa chỉ");
        if (backpack_user()->type <= 0) {
            CRUD::field("student_type")->label("Phân loại học sinh")->type("select_from_array")->options(["Tiềm năng", "Không tiềm năng", "Chưa học thử"]);
            CRUD::field("student_status")->label("Tình trạng học sinh")->type("select_from_array")->options(["Đang học", "Đã ngừng học", "Đang bảo lưu"]);
        }
        CRUD::addField(
            [
                'name' => 'extra',
                'label' => 'Thông tin thêm của học sinh',
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

    protected function detail($id)
    {
        return view("student-detail", ['data' => Student::find($id)]);
    }
}
