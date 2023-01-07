<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PostCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('Bài viết ', 'Những bài viết');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('title')->label('Tiêu đề');
        CRUD::column('updated_at')->label('Ngày chỉnh sửa')->type("date");

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
        CRUD::setValidation(PostRequest::class);

        CRUD::field('user_id')->type("hidden")->value(backpack_user()->id);
        CRUD::addField([
            'name' => 'title',
            'label' => 'Tiêu đề',
        ]);
        CRUD::addField([
            'name' => 'document',
            'label' => 'Nội dung',
            'type' => 'ckeditor',
        ]);
        CRUD::addField([
            'name' => 'pin',
            'label' => false,
            'type' => 'radio',
            'default' => 0,
            'options' => [
                1 => 'Ghim lên đầu',
                0 => 'Không ghim',
            ]
        ]);
        CRUD::addField([
            'name' => 'type',
            'label' => 'Đối tượng cụ thể',
            'type' => 'select_from_array',
            'options' => [
                5 => 'Tất cả',
                0 => 'Chỉ dành cho nhân viên',
                1 => 'Chỉ dành cho giáo viên , nhân viên',
                2 => 'Chỉ dành cho đối tác , nhân viên',
                3 => 'Chỉ dành cho học viên, nhân viên',
                4 => 'Chỉ dành cho khách hàng, nhân viên',
            ]
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
