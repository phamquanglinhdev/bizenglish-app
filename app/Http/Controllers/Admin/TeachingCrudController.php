<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TeachingRequest;
use App\Models\Client;
use App\Models\Grade;
use App\Models\Teacher;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class TeachingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TeachingCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Teaching::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/teaching');
        CRUD::setEntityNameStrings(trans("backpack::crud.information"), trans("backpack::crud.information"));
        $this->crud->denyAccess(["create", "show", "delete"]);
        $teacher_id = $_REQUEST["teacher_id"] ?? backpack_user()->id;
        $daily = Teacher::where("id", $teacher_id)->first()->getOwnTime();
        if (isset($_REQUEST["teacher_id"]) || isset($_REQUEST["client_id"])) {
            $this->crud->addButtonFromModelFunction("line", "detail", "detail", "line");
            $this->crud->setOperationSetting('exportButtons', true);
            $this->crud->setOperationSetting('detailsRow', true);
            if (isset($_REQUEST["teacher_id"])) {
                if ($_REQUEST["teacher_id"] != backpack_user()->id) {
                    $this->crud->denyAccess(["update"]);
                }
                $this->crud->addClause("where", "teacher_id", $_REQUEST["teacher_id"]);
                $data = Teacher::where("id", "=", $_REQUEST["teacher_id"])->first();
                $grades = $data->Grades()->where("disable", "=", 0)->get();
            } else {
                $this->crud->denyAccess(["update"]);
//                $this->crud->addClause("where", "teacher_id", $_REQUEST["teacher_id"]);
                $data = Client::where("id", "=", $_REQUEST["client_id"])->first();
                $grades = $data->Grades()->where("disable", "=", 0)->get();
            }
            Widget::add([
                'type' => 'view',
                'view' => 'teacher-detail',
                "data" => $data,
            ]);

            Widget::add([
                'type' => 'view',
                'view' => 'teacher-time',
                'daily' => $daily,
            ]);
            if (backpack_user()->type <= 1) {
                Widget::add([
                    'type' => 'view',
                    'view' => 'components.sub-table',
                    'data' => [
                        'columns' => [
                            "name" => ["label" => trans("backpack::crud.grade_name")],
                            "status" => ["label" => trans("backpack::crud.status")],
                            "student" => ["label" => trans("backpack::crud.student_name")],
                            "teacher" => ["label" => trans("backpack::crud.teacher_name")],
                            "client" => ["label" => trans("backpack::crud.client_name")],
                            "timeRS" => ["label" => trans("backpack::crud.has_time")],
                        ],
                        'grades' => $grades,
                    ]
                ]);
            }
            $this->crud->addFilter([
                'type' => 'date_range',
                'name' => 'from_to',
                'label' => trans("backpack::crud.date_filter")
            ],
                false,
                function ($value) { // if the filter is active, apply these constraints
                    $dates = json_decode($value);
                    $this->crud->addClause('where', 'date', '>=', $dates->from);
                    $this->crud->addClause('where', 'date', '<=', $dates->to . ' 23:59:59');
                });
            if (isset($_REQUEST["teacher_id"])) {
                $this->crud->addClause("where", "teacher_id", $_REQUEST["teacher_id"]);
            }
        }

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        if (isset($_REQUEST["client_id"])) {
            $this->crud->addClause("client");
        }
        $this->crud->addClause("where", "disable", 0);

        $this->crud->addClause("orderBy", "date", "DESC");
        if (isset($_REQUEST["grade_id"])) {
            $grade = Grade::find(($_REQUEST["grade_id"]));
            CRUD::setEntityNameStrings("Nhật ký học", "Lớp " . $grade->name);
            Widget::add([
                'type' => 'view',
                'view' => 'test',
                'grade' => $grade,
            ]);
            $this->crud->addClause("where", "grade_id", $grade->id);
        }
        if (backpack_user()->type == 1) {
            $this->crud->addClause("where", "teacher_id", backpack_user()->id);
        }
        if (backpack_user()->type == 3) {
            $this->crud->addClause('rep');
        }
        CRUD::addColumn([
            'name' => 'grade_id',
            'type' => 'select',
            'entity' => 'Grade',
            'model' => "App\Model\Grade",
            'attribute' => 'name',
            'label' => trans("backpack::crud.grade_name"),
            'wrapper' => [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url("/log?grade_id=$related_key");
                },
                // 'target' => '_blank',
                // 'class' => 'some-class',
            ],

        ]);

        CRUD::addColumn([
            'name' => 'teacher_id',
            'type' => 'select',
            'entity' => 'Teacher',
            'model' => "App\Model\Teacher",
            'attribute' => 'name',
            'label' => trans("backpack::crud.teacher_name"),
            'wrapper' => [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url("/teacher/detail/$related_key");
                },
                // 'target' => '_blank',
                // 'class' => 'some-class',
            ],
        ]);
        CRUD::column("clients")->label(trans("backpack::crud.client_name"))->type("model_function")->function_name("client");
        CRUD::addColumn([
            'name' => 'Students',
            'type' => 'select',
            'entity' => 'Students',
            'model' => "App\Model\Student",
            'attribute' => 'name',
            'label' => trans("backpack::crud.accepted_student"),
            'wrapper' => [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url("/student/detail/$related_key");
                },

                // 'target' => '_blank',
                // 'class' => 'some-class',
            ],
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('Students', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            }
        ]);

        CRUD::column('lesson')->label(trans("backpack::crud.lesson_name"));
        CRUD::column('teacher_video')->label(trans("backpack::crud.grade_name"))->type("video");
        CRUD::column('date')->label(trans("backpack::crud.date"))->type("date");
        CRUD::column('start')->label(trans("backpack::crud.start"))->type("time");
        CRUD::column('end')->label(trans("backpack::crud.end"))->type("time");
        CRUD::column('duration')->label(trans("backpack::crud.duration"))->type("number");
        if (backpack_user()->type <= 1) {
            CRUD::column('hour_salary')->label(trans("backpack::crud.hour_salary"))->type("number");
            CRUD::column('log_salary')->label(trans("backpack::crud.log_salary"))->type("number");
        }

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
        CRUD::setValidation(TeachingRequest::class);

        CRUD::field('id');
        CRUD::field('grade_id');
        CRUD::field('time');
        CRUD::field('duration');
        CRUD::field('lesson');
        CRUD::field('information');
        CRUD::field('hour_salary');
        CRUD::field('teacher_video');
        CRUD::field('disable');
        CRUD::field('created_at');
        CRUD::field('updated_at');
        CRUD::field('teacher_id');

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
