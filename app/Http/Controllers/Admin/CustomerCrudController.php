<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Student;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Str;
use function MongoDB\BSON\toJSON;
use function Symfony\Component\Translation\t;

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
        $this->crud->addButtonFromModelFunction("line", "Detail", "Detail", "line");
        $this->crud->addButtonFromModelFunction("line", "Switch", "Switch", "line");

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause("where", "disable", 0);
        $this->crud->addClause("where", "type", "4");
        CRUD::addColumn(['name' => 'code', 'type' => 'text', 'label' => "Mã khách hàng"]);
        CRUD::addColumn(['name' => 'name', 'type' => 'text', 'label' => "Tên khách hàng"]);
        CRUD::addColumn([
            'name' => "staff",
            'type' => 'model_function',
            "function_name" => "staffs",
            "label" => "Nhân viên quản lý",
//            'searchLogic' => function ($query, $column, $searchTerm) {
//                $query->orWhere('staff', 'like', '%' . $searchTerm . '%');
//            }
            "searchLogic" => "text",
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
//                    if (backpack_user()->type < 0) {
//                        return backpack_url("/staff/detail/$entry->id");
//                    }
                },
            ]
        ]);
        CRUD::addColumn(['name' => 'email', 'type' => 'text', "label" => "Email của khách hàng"]);
        CRUD::addColumn(['name' => 'phone', 'type' => 'text', 'label' => "Số điện thoại"]);
        CRUD::column("student_type")->label("Phân loại khách hàng")->type("select_from_array")->options(["Tiềm năng", "Không tiềm năng", "Đã học thử"]);
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
        CRUD::addField(['name' => 'phone', 'type' => 'text', 'label' => "Số điện thoại"]);
        CRUD::addField(
            [
                'label' => "Bài test cho KH",
                'name' => 'contests',
                'type' => 'relationship',
                'pivot' => true,
                'model' => 'App\Models\Contest',
                'entity' => 'contests',
                'attribute' => 'title'
            ]
        );
        CRUD::field('type')->type("hidden")->value(4);
        CRUD::field('avatar')->type("image")->crop(true)->aspect_ratio(1);
        CRUD::field("facebook")->label("Link Facebook");
        CRUD::field("address")->label("Địa chỉ");

        if (backpack_user()->type == -1) {
            CRUD::addField([
                'name' => 'staff_id',
                'type' => 'select2',
                'model' => 'App\Models\Customer',
                'attribute' => 'name',
                "label" => "Nhân viên quản lý",
                'options' => (function ($query) {
                    return $query->where("type", "=", 0)->where("disable", 0)->get();
                }),
            ]);
        }
        if (backpack_user()->type < 1) {
            CRUD::addField([
                'name' => 'code',
                'type' => 'text',
                "label" => "Mã khách hàng",
            ]);
        }
        CRUD::field("student_type")->label("Phân loại khách hàng")->type("select_from_array")->options(["Tiềm năng", "Không tiềm năng", "Đã học thử"]);
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
        CRUD::addField(
            [   // Password
                'name' => 'private_key',
                'type' => 'hidden'
            ],
        );

        if (backpack_user()->type <= 0) {
            CRUD::addField(
                [
                    'name' => 'files',
                    'label' => 'Văn bản',
                    'type' => 'repeatable',
                    'new_item_label' => 'Thêm văn bản', // customize the text of the button
                    'fields' => [
                        [
                            'name' => 'name',
                            'type' => 'text',
                            'label' => 'Tên',
                            'wrapper' => ['class' => 'form-group col-md-6'],
                        ],
                        [
                            'name' => 'link',
                            'type' => 'browse',
                            'label' => 'File',
                            'wrapper' => ['class' => 'form-group col-md-6'],
                        ],
                    ],
                ],
            );
        }
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
        return view("customer-detail", ['data' => Customer::find($id)]);
    }

    protected function switcher($id)
    {
        Customer::find($id)->update([
            'type' => 3,
            'code' => str_replace("KH", "HS", Customer::find($id)->first()->code),
        ]);
        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            User::find($id)->update([
                'email' => Str::random(12) . "@gmail.com",
                'disable' => 1,
                'phone' => null,
            ]);
            return 1;
        } catch (\Exception $exception) {
            return redirect()->back();
        }
    }
}
