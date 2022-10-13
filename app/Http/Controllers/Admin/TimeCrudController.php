<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TimeRequest;
use App\Models\Teacher;
use App\Models\Time;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TimeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TimeCrudController extends CrudController
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
        CRUD::setModel(Time::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/time');
        CRUD::setEntityNameStrings('Thời gian rảnh', 'Thời gian rảnh');
        $this->crud->denyAccess(["create", "delete"]);
        $teachers = Teacher::where("type", "=", 1)->get();
        foreach ($teachers as $teacher) {
            if ($teacher->genesis()) {
                Time::create([
                    "teacher_id" => $teacher->id
                ]);
            }
        }
        $this->crud->setOperationSetting('detailsRow', true);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        if (backpack_user()->type > 0) {
            $this->crud->addClause("where", "teacher_id", backpack_user()->id);
        }
        CRUD::column('teacher_id')->label("Tên giáo viên");
        CRUD::column('comment')->label("Comment");

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
        CRUD::setValidation(TimeRequest::class);
        CRUD::addField(
            [
                'name' => 'data',
                'label' => 'Dữ liệu',
                'type' => 'repeatable',
                'fields' => [
                    [
                        'name' => 'time',
                        'type' => 'text',
                        'label' => 'Time',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'monday',
                        'type' => 'checkbox',
                        'label' => 'Monday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'tuesday',
                        'type' => 'checkbox',
                        'label' => 'Tuesday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'wednesday',
                        'type' => 'checkbox',
                        'label' => 'Wednesday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'thursday',
                        'type' => 'checkbox',
                        'label' => 'Thursday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'friday',
                        'type' => 'checkbox',
                        'label' => 'Friday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'saturday',
                        'type' => 'checkbox',
                        'label' => 'Saturday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'sunday',
                        'type' => 'checkbox',
                        'label' => 'Sunday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],

                ],
                'init_rows' => 3,
                'max_rows' => 3,
                'min_rows' => 3,
                'new_item_label' => 'Thêm buổi',
            ]
        );
        CRUD::addField(['name' => 'comment', 'type' => 'textarea']);
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

    protected function showDetailsRow($id)
    {
        $table = Time::find($id)->data;
        return view("components.time-detail", ["table" => $table]);
    }
}
