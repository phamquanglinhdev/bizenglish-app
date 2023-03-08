<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PartnerRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Str;

/**
 * Class PartnerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PartnerCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Partner::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/partner');
        CRUD::setEntityNameStrings('Đối tác cung cấp', 'Đối tác cung cấp');
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
        $this->crud->addClause("where", "type", "5");
        CRUD::addColumn(['name' => 'code', 'type' => 'text', 'label' => "Mã đối tác"]);
        CRUD::addColumn(['name' => 'name', 'type' => 'text', 'label' => "Tên đối tác"]);
        CRUD::addColumn(['name' => 'email', 'type' => 'text', "label" => "Email của đối tác"]);
        CRUD::addColumn(['name' => 'phone', 'type' => 'text', 'label' => "Số điện thoại"]);
        CRUD::addColumn([
            'name' => 'client_status',
            'type' => 'select_from_array',
            'label' => 'Tình trạng hợp tác',
            'options' => ["Đang hợp tác", "Hợp tác ít", "Ngừng hợp tác"],
            'searchLogic' => function ($query, $column, $searchTerm) {
                $term = [];
                if (preg_match("/$searchTerm/i", "Đang hợp tác")) {
                    $term[] = 0;
                }
                if (preg_match("/$searchTerm/i", "Hợp tác ít")) {
                    $term[] = 1;
                }
                if (preg_match("/$searchTerm/i", "Ngừng hợp tác")) {
                    $term[] = 2;
                }
                foreach ($term as $item) {
                    $query->orWhere('client_status', '=', $item);
                }

            }

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
        CRUD::setValidation(PartnerRequest::class);
        CRUD::field('name')->label("Tên đối tác");
        CRUD::addField(['name' => 'code', 'type' => 'text', 'label' => "Mã đối tác"]);
        CRUD::field('email')->label("Email đối tác");
        CRUD::addField(['name' => 'phone', 'type' => 'text', 'label' => "Số điện thoại"]);
        CRUD::field('type')->type("hidden")->value(5);
        CRUD::field('avatar')->type("image")->crop(true)->aspect_ratio(1);
        CRUD::field("facebook")->label("Link Facebook");
        CRUD::field("address")->label("Địa chỉ");
        if (backpack_user()->type <= 0) {
            CRUD::field("client_status")->label("Tình trạng đối tác")->type("select_from_array")->options(["Đang hợp tác", "Hợp tác ít", "Ngừng hợp tác"]);
        }
        CRUD::addField(
            [
                'name' => 'extra',
                'label' => 'Thông tin thêm của đối tác',
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
    public function destroy($id)
    {
        try {
            User::find($id)->update([
                'email' => Str::random(12) . "@gmail.com",
                'disable' => 1,
                'phone' => null,
            ]);
        } catch (\Exception $exception) {
            return redirect()->back();
        }
    }
}
