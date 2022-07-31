<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LessonCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LessonCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Lesson::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lesson');
        CRUD::setEntityNameStrings('Giáo trình', 'Những giáo trình');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name')->label("Tên giáo trình");
        CRUD::column('pdf')->label("Đường dẫn PDF");
        CRUD::column('updated_at')->label("Cập nhật lần cuối");;

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
        CRUD::setValidation(LessonRequest::class);

        CRUD::field('user_id')->type("hidden")->value(backpack_user()->id);
        CRUD::field('name')->type("text")->label("Tên");
        CRUD::addField(
            [
                'name' => 'pdf',
                'label' => 'Upload File Tài liệu',
                'type' => 'browse',
//                'upload'    => true,
//                'disk'      => 'uploads_document',
            ]);
        CRUD::addField(
            [
                'name' => 'audios',
                'label' => 'File Audio',
                'type' => 'repeatable',
                'new_item_label' => 'Thêm trang', // customize the text of the button
                'fields' => [
                    [
                        'name' => 'page',
                        'type' => 'text',
                        'label' => 'Trang',
                        'wrapper' => ['class' => 'form-group col-md-6'],
                    ],
                    [
                        'name' => 'audio',
                        'label' => 'File âm thanh',
                        'type' => 'browse',
//                        'upload'    => true,
//                        'disk'      => 'uploads_audio',
                        'wrapper' => ['class' => 'form-group col-md-6'],
                    ],
                ],
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
        return view("lesson-show", ['lesson' => Lesson::find($id)->first()]);
    }
}
