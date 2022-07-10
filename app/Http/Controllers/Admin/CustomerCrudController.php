<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Student;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerCrudController extends CrudController
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
        CRUD::setModel(Customer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customer');
        CRUD::setEntityNameStrings('Khách hàng', 'Những khách hàng');
        $this->crud->addButtonFromModelFunction("line","Detail","Detail","line");
        $this->crud->addButtonFromModelFunction("line","Switch","Switch","line");

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause("where", "type", "4");
        CRUD::addColumn(['name' => 'name', 'type' => 'text', 'label' => "Tên khách hàng"]);
        CRUD::addColumn(['name' => 'email', 'type' => 'text', "label" => "Email của khách hàng"]);
        CRUD::column("student_type")->label("Phân loại khách hàng")->type("select_from_array")->options(["Tiềm năng", "Không tiềm năng", "Chưa học thử"]);
        $this->crud->denyAccess(["show"]);

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
        CRUD::setValidation(CustomerRequest::class);

        CRUD::field('name')->label("Tên khách hàng");
        CRUD::field('email')->label("Email khách hàng");
        CRUD::field('type')->type("hidden")->value(4);
        CRUD::field('avatar')->type("image")->crop(true)->aspect_ratio(1);
        CRUD::field("facebook")->label("Link Facebook");
        CRUD::field("address")->label("Địa chỉ");
        CRUD::field("student_type")->label("Phân loại khách hàng")->type("select_from_array")->options(["Tiềm năng", "Không tiềm năng", "Chưa học thử"]);
        CRUD::addField(
            [
                'name' => 'extra',
                'label' => 'Thông tin thêm của khách hàng',
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
    protected function switcher($id)
    {
        Customer::find($id)->update([
            'type'=>3
        ]);
        return redirect()->back();
    }
}
