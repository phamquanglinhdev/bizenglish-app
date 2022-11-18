<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TeacherRequest;
use App\Models\Skill;
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
        $this->crud->addButtonFromModelFunction("line", "Detail", "Detail", "line");
        $this->crud->denyAccess(["show"]);
        $this->crud->addFilter([
            'name' => 'skills',
            'type' => 'select2_multiple',
            'label' => 'Kỹ năng'
        ], function () {
            $skills = Skill::all();
            $skills_arr = [];
            foreach ($skills as $skill) {
                $skills_arr[$skill->id] = $skill->name;
            }
            return $skills_arr;
        }, function ($values) {

            $teacher_id = [];
            if (is_array($values)) {
                $skills = $values;
            } else {
                $skills = json_decode($values);
            }
            $first = true;
            foreach ($skills as $id) {
                if(Skill::where("id", $id)->first() !== null) {
                    $teachers = Skill::where("id", $id)->first()->Teachers()->get();
                    foreach ($teachers as $teacher) {
                        if (!array_search($teacher->id, $teacher_id)) {
                            $teacher_id[] = $teacher->id;
                        }
                    }
                }

            }
//            foreach ($teacher_id as $id) {
//                if ($first) {
//                    $first = false;
//
//                }
//            }
            // if the filter is active
            $this->crud->addClause('whereIn', 'id', $teacher_id);
        });
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
        $this->crud->addClause("where", "type", "1");
        CRUD::addColumn(['name' => 'code', 'type' => 'text', 'label' => "Mã giáo viên"]);
        CRUD::addColumn([
            'name' => 'name', 'type' => 'text', 'label' => "Tên giáo viên",
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url("/teacher/detail/$entry->id");
                },
            ]

        ]);
        CRUD::addColumn(['name' => 'email', 'type' => 'text', "label" => "Email của giáo viên"]);
        CRUD::addColumn(['name' => 'phone', 'type' => 'text', 'label' => "Số điện thoại"]);
        CRUD::addColumn([
            'name' => 'skills',
            'entity' => 'Skills',
            'model' => "App\Models\Skill",
            'label' => 'Tag',
            'type' => 'relationship',
            'attribute' => 'name'
        ]);
        CRUD::addColumn([
            'name' => 'grades',
            'entity' => 'Grades',
            'model' => "App\Models\Grade",
            'label' => 'Lớp',
            'type' => 'relationship',
            'attribute' => 'name',
            'wrapper' => [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url("/log?grade_id=$related_key");
                },
                // 'target' => '_blank',
                // 'class' => 'some-class',
            ],
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('grades', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
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
        CRUD::setValidation(TeacherRequest::class);
        CRUD::field('name')->label("Tên giáo viên")->wrapper([]);
        CRUD::field('email')->label("Email giáo viên");
        CRUD::field("facebook")->label("Link Facebook");
        CRUD::field("address")->label("Địa chỉ");
        CRUD::field('avatar')->type("image")->crop(true)->aspect_ratio(1);
        CRUD::field('type')->type("hidden")->value(1);
        if (backpack_user()->type < 1) {
            CRUD::addField([
                'name' => 'code',
                'type' => 'text',
                "label" => "Mã giáo viên"
            ]);
        }
        CRUD::addField(['name' => 'phone', 'type' => 'text', 'label' => "Số điện thoại"]);
        CRUD::addField([
            'name' => 'skills',
            'entity' => 'Skills',
            'model' => "App\Models\Skill",
            'label' => 'Tag',
            'type' => 'relationship',
            'attribute' => 'name'
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
//        return view("teacher-detail",['data'=>Teacher::find($id)]);
        return redirect(url("admin/teaching?teacher_id=$id"));
    }
}
