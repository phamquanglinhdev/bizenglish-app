<?php

namespace App\Http\Controllers\Admin;

use App\Http\Middleware\ModMiddleware;
use App\Http\Requests\GradeRequest;
use App\Models\Client;
use App\Models\Grade;
use App\Models\Log;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class GradeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class GradeCrudController extends CrudController
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
        if (backpack_user()->type >= 1) {
            $this->crud->addButtonFromModelFunction("top", "redirectToIndex", "toIndex", "top");
        }else{

        }
        if (backpack_user()->type == 1) {
            $_REQUEST["teacher_filter"] = backpack_user()->name;
        }
        $grades_id = [];
        $load = 0;
        $_SESSION["filtered"] = false;
        if (isset($_REQUEST["staff_filter"])) {

            $staff_id = [];
            $value = $_REQUEST["staff_filter"];
            $staff = Staff::where("name", "like", "%$value%")->first();
            $grades = $staff->Grades()->get();
            foreach ($grades as $grade) {
                $staff_id[] = $grade->id;
            }
            $grades_id = $staff_id;
            $load = 1;
        }
        if (isset($_REQUEST["student_filter"])) {

            $student_id = [];
            $value = $_REQUEST["student_filter"];
            $staff = Student::where("name", "like", "%$value%")->first();
            $grades = $staff->Grades()->get();
            foreach ($grades as $grade) {
                $student_id[] = $grade->id;
            }
            if ($load == 1) {
                $grades_id = array_intersect($grades_id, $student_id);
            } else {
                $grades_id = $student_id;
                $load = 1;
            }

//            print_r($student_id);
        }
        if (isset($_REQUEST["teacher_filter"]) || backpack_user()->type == 1) {
            $teacher_id = [];
            $value = $_REQUEST["teacher_filter"];
            if (backpack_user()->type == 1) {
                $value = backpack_user()->name;
            }
            $staff = Teacher::where("name", "like", "%$value%")->first();
            $grades = $staff->Grades()->get();
            foreach ($grades as $grade) {
                $teacher_id[] = $grade->id;
            }
            if ($load == 1) {
                $grades_id = array_intersect($grades_id, $teacher_id);
            } else {
                $grades_id = $teacher_id;
                $load = 1;
            }

//            print_r($student_id);
        }
        if (isset($_REQUEST["client_filter"])) {
            $client_id = [];
            $value = $_REQUEST["client_filter"];
            $staff = Client::where("name", "like", "%$value%")->first();
            if ($staff != null) {
                $grades = $staff->Grades()->get();
                foreach ($grades as $grade) {
                    $client_id[] = $grade->id;
                }
            }
            if ($load == 1) {
                $grades_id = array_intersect($grades_id, $client_id);
            } else {
                $grades_id = $client_id;
                $load = 1;
            }

//            print_r($student_id);
        }
        CRUD::setModel(Grade::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/grade');
        CRUD::setEntityNameStrings('Lớp học', 'Các lớp học');
        if (backpack_user()->type <= 0) {
            $this->crud->denyAccess(["show"]);
            $this->crud->addFilter([
                'type' => 'text',
                'name' => 'staff_filter',
                'label' => 'Nhân viên quản lý'
            ],
                false,
                function ($value) use ($grades_id) {
                    if (!$_SESSION["filtered"]) {
                        $_SESSION["filtered"] = true;
                        $query = $this->crud->query;
                        if ($grades_id != []) {
                            foreach ($grades_id as $value) {
                                $query->orWhere("id", "=", $value);
                            }
                        } else {
                            $query->where("id", "=", -9999);
                        }
                    }

                });
            $this->crud->addFilter([
                'type' => 'text',
                'name' => 'student_filter',
                'label' => 'Học viên'
            ],
                false,
                function ($value) use ($grades_id) {
                    if ($_SESSION["filtered"]) {
                        return;
                    }
                    $_SESSION["filtered"] = true;
                    $query = $this->crud->query;
                    if ($grades_id != []) {
                        foreach ($grades_id as $value) {
                            $query->orWhere("id", "=", $value);
                        }
                    } else {
                        $query->where("id", "=", -9999);
                    }
                });
            if (backpack_user()->type != 1) {
                $this->crud->addFilter([
                    'type' => 'text',
                    'name' => 'teacher_filter',
                    'label' => 'Giáo viên'
                ],
                    false,
                    function ($value) use ($grades_id) {
                        if (!$_SESSION["filtered"]) {
                            $_SESSION["filtered"] = true;
                            $query = $this->crud->query;
                            if ($grades_id != []) {
                                foreach ($grades_id as $value) {
                                    $query->orWhere("id", "=", $value);
                                }
                            } else {
                                $query->where("id", "=", -9999);
                            }
                        }
                    });
            }
            $this->crud->addFilter([
                'type' => 'text',
                'name' => 'client_filter',
                'label' => 'Đối tác'
            ],
                false,
                function ($value) use ($grades_id) {
                    if (!$_SESSION["filtered"]) {
                        $_SESSION["filtered"] = true;
                        $query = $this->crud->query;
                        if ($grades_id != []) {
                            foreach ($grades_id as $value) {
                                $query->orWhere("id", "=", $value);
                            }
                        } else {
                            $query->where("id", "=", -9999);
                        }
                    }
                });
            $this->crud->addFilter([
                'type' => 'simple',
                'name' => 'active',
                'label' => 'Đang học'
            ],
                false,
                function () { // if the filter is active
                    $this->crud->addClause("where", 'status', "=", 0); // apply the "active" eloquent scope
                });
            $this->crud->addFilter([
                'type' => 'simple',
                'name' => 'stop',
                'label' => 'Đã kết thúc'
            ],
                false,
                function () { // if the filter is active
                    $this->crud->addClause("where", 'status', "=", 1); // apply the "active" eloquent scope
                });
            $this->crud->addFilter([
                'type' => 'simple',
                'name' => 'saved',
                'label' => 'Đang bảo lưu'
            ],
                false,
                function () { // if the filter is active
                    $this->crud->addClause("where", 'status', "=", 2); // apply the "active" eloquent scope
                });

            if (backpack_user()->type == 1 && !$_SESSION["filtered"]) {
                $_SESSION["filtered"] = true;
                $query = $this->crud->query;
                if ($grades_id != []) {
                    foreach ($grades_id as $value) {
                        $query->orWhere("id", "=", $value);
                    }
                } else {
                    $query->where("id", "=", -9999);
                }
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

        $this->crud->addClause("where", "grades.disable", "=", 0);
        if (backpack_user()->type == 0) {
            $this->crud->addClause("owner");
        }
        CRUD::column('name')->label("Tên lớp")->wrapper(
            [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url("/log?grade_id=$entry->id");
                },
                // 'target' => '_blank',
                // 'class' => 'some-class',
            ]);
        CRUD::column('student_id')->type("select")->label("Học viên");
        CRUD::column('teacher_id')->type("select")->label("Giáo viên");
        CRUD::column('staff_id')->type("select")->label("Nhân viên quản lý");
        CRUD::column('client_id')->type("select")->label("Đối tác");
        CRUD::column('zoom')->type("link")->label("Link lớp");
        CRUD::column('pricing')->label("Gói học phí")->type("number");
        CRUD::column('minutes')->label("Số phút");
        CRUD::column('attachment')->label("Tài liệu")->type("link");
        CRUD::column('status')->label("Trạng thái")->type("select_from_array")->options(["Đang học", "Đã kết thúc", "Đang bảo lưu"]);
        CRUD::column('created_at')->label("Ngày tạo lớp");


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
        CRUD::setValidation(GradeRequest::class);

        if (backpack_user()->type <= 0) {
            CRUD::field('name')->label("Tên lớp");
            CRUD::field('zoom')->label("Link lớp");
            CRUD::field('pricing')->label("Gói học phí");
            CRUD::field('minutes')->label("Số phút")->type("number");
        }
        CRUD::addField([
            'name' => 'time',
            'type' => 'repeatable',
            'label' => 'Lịch học',
            'fields' => [
                [
                    'name' => 'day',
                    'label' => 'Thứ',
                    'type' => 'select_from_array',
                    'options' => [
                        'mon' => 'Thứ 2',
                        'tue' => 'Thứ 3',
                        'wed' => 'Thứ 4',
                        'thu' => 'Thứ 5',
                        'fri' => 'Thứ 6',
                        'sat' => 'Thứ 7',
                        'sun' => 'Chủ nhật',
                    ]
                ],
                [
                    'name' => 'value',
                    'label' => 'Khung thời gian',
                    'attributes' => [
                        'placeholder' => 'Ví dụ : 8pm-10pm',
                    ]
                ]
            ],
            'new_item_label' => 'Thêm lịch',
        ]);
        if (backpack_user()->type <= 0) {
            CRUD::field('information')->label("Thông tin chi tiết")->type("tinymce");
            CRUD::field('status')->label("Trạng thái")->type("select_from_array")->options(["Đang học", "Đã kết thúc", "Đã bảo lưu"]);
            CRUD::addField(
                [
                    'name' => 'attachment',
                    'label' => 'Tài liệu',
                    'type' => 'text',
//                'upload' => true,
//                'disk' => 'uploads_document',
                    'prefix' => "Link drive",
                ]);
            CRUD::addField(
                [    // Select2Multiple = n-n relationship (with pivot table)
                    'label' => "Học sinh",
                    'type' => 'select2_multiple',
                    'name' => 'student', // the method that defines the relationship in your Model

                    // optional
                    'entity' => 'Student', // the method that defines the relationship in your Model
                    'model' => "App\Models\User", // foreign key model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                    // 'select_all' => true, // show Select All and Clear buttons?

                    // optional
                    'options' => (function ($query) {
                        return $query->orderBy('name', 'ASC')->where('type', 3)->get();
                    }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                ],
            );
            CRUD::addField(
                [    // Select2Multiple = n-n relationship (with pivot table)
                    'label' => "Giáo viên",
                    'type' => 'select2_multiple',
                    'name' => 'teacher', // the method that defines the relationship in your Model

                    // optional
                    'entity' => 'Teacher', // the method that defines the relationship in your Model
                    'model' => "App\Models\User", // foreign key model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                    // 'select_all' => true, // show Select All and Clear buttons?

                    // optional
                    'options' => (function ($query) {
                        return $query->orderBy('name', 'ASC')->where('type', 1)->get();
                    }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                ],
            );
            CRUD::addField(
                [    // Select2Multiple = n-n relationship (with pivot table)
                    'label' => "Đối tác",
                    'type' => 'select2_multiple',
                    'name' => 'client', // the method that defines the relationship in your Model

                    // optional
                    'entity' => 'Client', // the method that defines the relationship in your Model
                    'model' => "App\Models\User", // foreign key model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                    // 'select_all' => true, // show Select All and Clear buttons?

                    // optional
                    'options' => (function ($query) {
                        return $query->orderBy('name', 'ASC')->where('type', 2)->get();
                    }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                ],
            );
            if (backpack_user()->type == -1) {
                CRUD::addField(
                    [    // Select2Multiple = n-n relationship (with pivot table)
                        'label' => "Nhân viên quản lý",
                        'type' => 'select2_multiple',
                        'name' => 'staff', // the method that defines the relationship in your Model

                        // optional
                        'entity' => 'Staff', // the method that defines the relationship in your Model
                        'model' => "App\Models\Staff", // foreign key model
                        'attribute' => 'name', // foreign key attribute that is shown to user
                        'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                        // 'select_all' => true, // show Select All and Clear buttons?

                        // optional
                        'options' => (function ($query) {
                            return $query->orderBy('name', 'ASC')->where('type', 0)->get();
                        }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                    ],
                );
            } else {
                CRUD::addField(
                    [    // Select2Multiple = n-n relationship (with pivot table)
                        'label' => "Nhân viên quản lý",
                        'type' => 'select2_multiple',
                        'name' => 'staff', // the method that defines the relationship in your Model

                        // optional
                        'entity' => 'Staff', // the method that defines the relationship in your Model
                        'model' => "App\Models\User", // foreign key model
                        'attribute' => 'name', // foreign key attribute that is shown to user
                        'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                        // 'select_all' => true, // show Select All and Clear buttons?

                        // optional
                        'options' => (function ($query) {
                            return $query->orderBy('name', 'ASC')->where('id', backpack_user()->id)->get();
                        }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                    ],
                );
            }
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

    public function destroy($id)
    {
        Log::where("grade_id", "=", $id)->update([
            'disable' => 1,
        ]);
        return Grade::find($id)->update([
            'disable' => 1,
        ]);
    }
}
