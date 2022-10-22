<?php

namespace App\Http\Controllers\Admin;

use App\Events\ReportFromStudent;
use App\Http\Requests\LogRequest;
use App\Models\Client;
use App\Models\Grade;
use App\Models\Log;
use App\Models\Staff;
use App\Models\Teacher;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\DB;

/**
 * Class LogCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LogCrudController extends CrudController
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
        $_SESSION["filtered"] = false;
        $load = 0;
        $logs_id = [];
        if (isset($_REQUEST["grade_filter"])) {
            $value = $_REQUEST["grade_filter"];
            $grades = Grade::where("name", "like", "%$value%")->get();
            foreach ($grades as $grade) {
                $logs = $grade->Logs()->get();
                foreach ($logs as $log) {
                    $logs_id[] = $log->id;
                }
            }
            $load = 1;
        }
        if (backpack_user()->type == 0) {
            $staff_id = [];
            $staff = Staff::where("id", "=", backpack_user()->id)->first();
            $grades = $staff->Grades()->get();
            foreach ($grades as $grade) {
                $logs = $grade->Logs()->get();
                foreach ($logs as $log) {
                    $staff_id[] = $log->id;
                }
            }

            if ($load == 0) {
                $logs_id = $staff_id;
                $load = 1;
            } else {
                $logs_id = array_intersect($logs_id, $staff_id);
            }

        }
        if (isset($_REQUEST["teacher_filter"]) || backpack_user()->type == 1) {
            $teachers_id = [];
            $value = $_REQUEST["teacher_filter"] ?? "";
            $teachers = Teacher::where("name", "like", "%$value%")->get();
            if (backpack_user()->type == 1) {
                $teacher = Teacher::find(backpack_user()->id);
                $logs = $teacher->Logs()->get();
                foreach ($logs as $log) {
                    $teachers_id[] = $log->id;
                }
            } else {
                foreach ($teachers as $teacher) {
                    $logs = $teacher->Logs()->get();
                    foreach ($logs as $log) {
                        $teachers_id[] = $log->id;
                    }
                }
            }

            if ($load == 0) {
                $logs_id = $teachers_id;
                $load = 1;
            } else {
                $logs_id = array_intersect($logs_id, $teachers_id);
            }
        }
        if (isset($_REQUEST["client_filter"])) {
            $client_id = [];
            $value = $_REQUEST["client_filter"];
            $clients = Client::where("name", "like", "%$value%")->get();
            foreach ($clients as $client) {
                $grades = $client->Grades()->get();
                foreach ($grades as $grade) {
                    $logs = $grade->Logs()->get();
                    foreach ($logs as $log) {
                        $client_id[] = $log->id;
                    }
                }
            }
            if ($load == 0) {
                $logs_id = $client_id;
                $load = 1;
            } else {
                $logs_id = array_intersect($logs_id, $client_id);
            }
        }

        CRUD::setModel(Log::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/log');
        CRUD::setEntityNameStrings("Nhật ký học", "Nhật ký học");
        if (backpack_user()->type == 3) {
            $this->crud->addButtonFromModelFunction("line", "setAcceptLog", "setAcceptLog", "line");
            $this->crud->addButtonFromModelFunction("line", "pushExercise", "pushExercise", "line");
        }
        $this->crud->addButtonFromModelFunction("line", "detail", "detail", "line");

        $this->crud->denyAccess(["show"]);
        if (backpack_user()->type > 1) {
            $this->crud->denyAccess(["update", "create", "delete"]);
        }
        $this->crud->setResponsiveTable(true);
        $this->crud->setOperationSetting('exportButtons', true);
        $this->crud->setOperationSetting('detailsRow', true);
        $this->crud->addFilter([
            'type' => "text",
            'name' => 'grade_filter',
            'label' => 'Lớp',
        ],
            false,
            function ($value) use ($logs_id) {
                $first = true;
                if ($logs_id != []) {
                    foreach ($logs_id as $id) {
                        if ($first) {
                            $this->crud->query->where("id", "=", $id);
                            $first = false;
                        } else {
                            $this->crud->query->orWhere("id", "=", $id);
                        }
                    }
                } else {
                    $this->crud->query->where("id", "=", "-9999");
                }
                $_SESSION["filtered"] = true;
            }
        );
        if (backpack_user()->type == 0) {
            if (!$_SESSION["filtered"]) {
                $first = true;
                if ($logs_id != []) {
                    foreach ($logs_id as $id) {
                        if ($first) {
                            $this->crud->query->where("id", "=", $id);
                            $first = false;
                        } else {
                            $this->crud->query->orWhere("id", "=", $id);
                        }
                    }
                } else {
                    $this->crud->query->where("id", "=", "-9999");
                }
                $_SESSION["filtered"] = true;
            }
        }
        $this->crud->addFilter([
            'type' => "text",
            'name' => 'teacher_filter',
            'label' => 'Giáo viên',
        ],
            false,
            function ($value) use ($logs_id) {
                if (!$_SESSION["filtered"]) {
                    $first = true;
                    if ($logs_id != []) {
                        foreach ($logs_id as $id) {
                            if ($first) {
                                $this->crud->query->where("id", "=", $id);
                                $first = false;
                            } else {
                                $this->crud->query->orWhere("id", "=", $id);
                            }
                        }
                    } else {
                        $this->crud->query->where("id", "=", "-9999");
                    }
                    $_SESSION["filtered"] = true;
                }
            }
        );
        $this->crud->addFilter([
            'type' => "text",
            'name' => 'client_filter',
            'label' => 'Đối tác',
        ],
            false,
            function ($value) use ($logs_id) {
                if (!$_SESSION["filtered"]) {
                    $first = true;
                    if ($logs_id != []) {
                        foreach ($logs_id as $id) {
                            if ($first) {
                                $this->crud->query->where("id", "=", $id);
                                $first = false;
                            } else {
                                $this->crud->query->orWhere("id", "=", $id);
                            }
                        }
                    } else {
                        $this->crud->query->where("id", "=", "-9999");
                    }
                    $_SESSION["filtered"] = true;
                }
            }
        );
        // dropdown filter
        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'dropdown',
            'label' => 'Tình trạng lớp học'
        ], [
            0 => 'Học viên và giáo viên vào đúng giờ',
            1 => 'Học viên vào muộn',
            2 => 'Giáo viên vào muộn',
            3 => 'Học viên hủy buổi học',
            4 => 'Giáo viên hủy buổi học',
        ], function ($value) { // if the filter is active
            $this->crud->addClause('where', 'status', "like", "%\"name\":\"$value\"%");
        });

        // daterange filter
        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'from_to',
            'label' => 'Lọc theo ngày'
        ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'date', '>=', $dates->from);
                $this->crud->addClause('where', 'date', '<=', $dates->to . ' 23:59:59');
            });
        if (backpack_user()->type == 0) {
            $this->crud->denyAccess(["create"]);
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
        if (backpack_user()->type == 1) {
            $this->crud->addClause("where", "teacher_id", backpack_user()->id);
        }
        $this->crud->addClause("where", "disable", 0);
//        $this->crud->addClause('where', 'date', '>=', '2022-08-23 23:59:59');
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

        if (backpack_user()->type == 3) {
            $this->crud->addClause('rep');
        }
        CRUD::addColumn([
            'name' => 'grade_id',
            'type' => 'select',
            'entity' => 'Grade',
            'model' => "App\Model\Grade",
            'attribute' => 'name',
            'label' => "Lớp",
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
            'label' => "Giáo viên dạy",
            'wrapper' => [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url("/teacher/detail/$related_key");
                },
                // 'target' => '_blank',
                // 'class' => 'some-class',
            ],
        ]);

        CRUD::column("clients")->label("Đối tác")->type("model_function")->function_name("client");
        CRUD::column('lesson')->label("Bài học");
        CRUD::column('teacher_video')->label("Video bài giảng")->type("video");
        CRUD::column('date')->label("Ngày")->type("date");
        CRUD::column('start')->label("Bắt đầu")->type("time");
        CRUD::column('end')->label("Kết thúc")->type("time");
        CRUD::column('duration')->label("Thời gian dạy (Phút)")->type("number");

        if (backpack_user()->type <= 1) {
//            CRUD::column('hour_salary')->label("Lương theo giờ (đ)")->type("number")->wrapper(["class" => "text-center"]);
//            CRUD::column('log_salary')->label("Lương của buổi học (đ)")->type("number");
            CRUD::addColumn([
                'label' => "Lương theo giờ",
                'type' => 'model_function',
                'function_name' => 'getHourSalary'
            ]);
            CRUD::addColumn([
                'label' => "Lương buổi học",
                'type' => 'model_function',
                'function_name' => 'getLogSalary'
            ]);
        }
        CRUD::addColumn([

            'type' => 'model_function',
            'function_name' => 'StatusShow',
            'label' => "Tình trạng lớp học",
        ]);
        CRUD::column('assessment')->label("Nhận xét của giáo viên")->type("textarea");
//        CRUD::column('attachment')->label("Đính kèm")->type("model_function")->function_name("showAttachments");
        CRUD::addColumn([
            "name" => "attachments",
            "label" => "Đính kèm",
            "type" => "upload_multiple",
            "wrapper" => [
                'href' => function ($crud, $column, $entry) {
                    return url('uploads/document/' . $crud);
                },
            ]
        ]);
        CRUD::addColumn([
//            'name' => 'StudentAccept',
            'type' => 'model_function',
            'function_name' => 'StudentAccept',
            'label' => "Học sinh xác nhận",
//            'wrapper' => [
//                // 'element' => 'a', // the element will default to "a" so you can skip it here
//                'href' => function ($crud, $column, $entry, $related_key) {
//                    return backpack_url("/student/detail/$related_key");
//                },
//
//                // 'target' => '_blank',
//                // 'class' => 'some-class',
//            ],
//            'searchLogic' => function ($query, $column, $searchTerm) {
//                $query->orWhereHas('Students', function ($q) use ($column, $searchTerm) {
//                    $q->where('name', 'like', '%' . $searchTerm . '%');
//                });
//            }
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

        CRUD::setValidation(LogRequest::class);

        CRUD::addField([
            'name' => 'grade_id',
            'type' => 'select2',
            'entity' => 'Grade',
            'model' => "App\Models\Grade",
            'attribute' => 'name',
            'label' => "Lớp",
            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->leftJoin("teacher_grade", "teacher_grade.grade_id", "=", "grades.id")->where("teacher_grade.teacher_id", backpack_user()->id)->where("disable", 0)->get();
            })
        ]);
        CRUD::addField([
            'name' => 'teacher_id',
            'value' => backpack_user()->id,
            'type' => 'hidden',
        ]);
        CRUD::field('date')->label("Ngày")->type("date")->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('start')->label("Bắt đầu")->type("time")->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('end')->label("Ksết thúc")->type("time")->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('duration')->label("Thời gian dạy thực tế")->suffix("phút")->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('hour_salary')->label("Lương theo giờ")->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('log_salary')->attributes(["readonly" => true])->label("Lương của buổi học")->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('lesson')->label("Bài học");
        CRUD::field('information')->label("Nội dung")->type("tinymce");
        CRUD::addField(
            [
                'name' => 'teacher_video',
                'label' => 'Video bài giảng',
                'type' => 'video',
                'youtube_api_key' => 'AIzaSyDc3MoGdCrqbCHq6XRbulelTPy5oWsLwIE',
            ]);
        CRUD::addField(
            [   // repeatable
                'name' => 'status',
                'label' => 'Tình trạng lớp học',
                'type' => 'repeatable',
                'fields' => [ // also works as: "fields"
                    [
                        'name' => 'name',
                        'label' => 'Trạng thái',
                        'type' => "select_from_array",
                        'options' => [
                            "Học viên và giáo viên vào đúng giờ.",
                            "Học viên vào muộn ... phút",
                            "Giáo viên vào muộn ... phút",
                            "Học viên hủy buổi học trước … giờ",
                            "Giáo viên hủy buổi học trước … giờ",
                            "Khác...",
                        ],
                        "value" => 0,
                        "attributes" => ["id" => "status_name"],
                        'wrapper' => ['class' => 'form-group col-md-6 '],
                    ],
                    [
                        'name' => 'time',
                        'type' => 'number',
                        'label' => 'Thời gian',
                        'suffix' => "Phút",
                        'wrapper' => ['class' => 'form-group col-md-6', "id" => "status_time"],
                    ],
                    [
                        'name' => 'message',
                        'type' => 'textarea',
                        'label' => 'Tình trạng khác',
                        'wrapper' => ["id" => "status_message"],
                    ],
                ],

                // optional
                'new_item_label' => 'Add Group', // customize the text of the button
                'init_rows' => 1, // number of empty rows to be initialized, by default 1
                'min_rows' => 1, // minimum rows allowed, when reached the "delete" buttons will be hidden
                'max_rows' => 1, // maximum rows allowed, when reached the "new item" button will be hidden
                // allow reordering?
                // 'reorder' => false, // hide up&down arrows next to each row (no reordering)
                // 'reorder' => true, // show up&down arrows next to each row
                //  'reorder' => 'order', // show arrows AND add a hidden subfield with that name (value gets updated when rows move)
                // 'reorder' => ['name' => 'order', 'type' => 'number', 'attributes' => ['data-reorder-input' => true]], // show arrows AND add a visible number subfield
            ],
        );
        CRUD::field('assessment')->label("Nhận xét của giáo viên")->type("textarea");
        CRUD::field('question')->label("Bài tập cho học sinh")->type("tinymce");
        CRUD::addField(
            [   // Upload
                'name' => 'attachments',
                'label' => 'Đính kèm',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'uploads_document', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
            ],
        );
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
        Widget::add()->type('script')->content(asset('assets/js/admin/forms/log.js'));
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
        if (Log::find($id)) {
            return view("log-detail", ['log' => Log::find($id)]);
        }
        return view("errors.404");
    }

    public function acceptByStudent(\Illuminate\Http\Request $request)
    {
        $id = backpack_user()->id;
        $isExist = DB::table("student_log")->where("log_id", $request->log_id)->where("student_id", $id)->count();
        if ($isExist == 0) {
            DB::table("student_log")->insert([
                'student_id' => $id,
                'log_id' => $request->log_id,
                'accept' => $request->accept,
                'comment' => $request->comment,
            ]);
            $code = backpack_user()->code;
            $name = backpack_user()->name;
            if ($request->accept == 1) {
                $accept = "Sai";
            } else {
                $accept = "Đúng";
            }
            $grade = Log::where("id", "=", $request->log_id)->first()->Grade()->first()->name;
            $message = $request->comment;
            $messages = "Mã $code : $name đã xác nhận buổi học của lớp $grade là $accept với lời nhắn : $message";
            $title = "Thông báo từ lớp học";
            $link = route("admin.log.detail", $request->log_id);
            ReportFromStudent::dispatch($title, $messages, $link);
            $users = User::where("type", "<=", 0)->get();
            foreach ($users as $user) {
                \App\Models\Notification::create([
                    "title" => "Phản hồi của học sinh",
                    "user_id" => $user->id,
                    "message" => $messages,
                    "link" => $link,
                    "read" => 0,
                ]);
            }
        } else {
            return redirect()->back()->with("message", "Đã xác nhận rồi");
        }
        return redirect()->back()->with("message", "Xác nhận thành công");
    }
}
