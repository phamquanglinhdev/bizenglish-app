<?php

namespace App\Http\Controllers\Admin;

use App\Events\ReportFromStudent;
use App\Http\Requests\LogRequest;
use App\Models\Client;
use App\Models\Grade;
use App\Models\Log;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
        CRUD::setModel(Log::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/log');
        CRUD::setEntityNameStrings(trans("backpack::crud.history"), trans("backpack::crud.history"));
        $this->crud->query->where("disable", 0);
        if (isset($_REQUEST["grade_id"])) {
            $this->crud->query->where("grade_id", $_REQUEST["grade_id"]);
        }
//        filter by role
        if (backpack_user()->type == 0) {
            $this->crud->query->where(function (Builder $query) {
                $query->whereHas("grade", function (Builder $builder) {
                    $builder->where(function (Builder $child) {
                        $child->whereHas("staff", function (Builder $staff) {
                            $staff->where("id", backpack_user()->id);
                        })->orWhereHas("supporter", function (Builder $sp) {
                            $sp->where("id", backpack_user()->id);
                        });
                    });
                });
            });
        }
        if (backpack_user()->type == 1) {
            $this->crud->query->where("teacher_id", backpack_user()->id);
        }
        if (backpack_user()->type == 2) {
            $this->crud->query->where(function (Builder $query) {
                $query->whereHas("grade", function (Builder $builder) {
                    $builder->whereHas("client", function (Builder $student) {
                        $student->where("id", backpack_user()->id);
                    });
                });
            });
        }
        if (backpack_user()->type == 3) {
            $this->crud->query->where(function (Builder $query) {
                $query->whereHas("grade", function (Builder $builder) {
                    $builder->whereHas("student", function (Builder $student) {
                        $student->where("id", backpack_user()->id);
                    });
                });
            });
        }
        if (backpack_user()->type == 3) {
            $this->crud->addButtonFromModelFunction("line", "setAcceptLog", "setAcceptLog", "line");
            $this->crud->addButtonFromModelFunction("line", "pushExercise", "pushExercise", "line");
        }
        $this->crud->addButtonFromModelFunction("line", "detail", "detail", "line");
        $this->crud->denyAccess(["show"]);
        if (backpack_user()->type > 1) {
            $this->crud->denyAccess(["update", "create", "delete"]);
        }
        $this->crud->setResponsiveTable(false);
        $this->crud->setOperationSetting('exportButtons', true);
//        $this->crud->setOperationSetting('detailsRow', true);
        if (!isset($_REQUEST["grade_id"])) {
            $this->crud->addFilter([
                'type' => "text",
                'name' => 'grade_filters',
                'label' => trans("backpack::crud.grade_name"),
            ],
                false,
                function ($value) {
                    $this->crud->query->whereHas("grade", function (Builder $builder) use ($value) {
                        $builder->where("name", "like", "%$value%");
                    });
                }
            );
        }
        $this->crud->addFilter([
            'type' => "text",
            'name' => 'student_filter',
            'label' => trans("backpack::crud.student_name"),
        ],
            false,
            function ($value) {
                $this->crud->query->whereHas("grade", function (Builder $builder) use ($value) {
                    $builder->whereHas("student", function (Builder $student) use ($value) {
                        $student->where("name", "like", "%$value%");
                    });
                });
            }
        );
        $this->crud->addFilter([
            'type' => "text",
            'name' => 'teacher_filter',
            'label' => trans("backpack::crud.teacher_name"),
        ],
            false,
            function ($value) {
                $this->crud->query->whereHas("teacher", function (Builder $builder) use ($value) {
                    $builder->where("name", "like", "%$value%");
                });
            }
        );

        $this->crud->addFilter([
            'type' => "text",
            'name' => 'client_filter',
            'label' => trans("backpack::crud.client_name"),
        ], false,
            function ($value) {
                $this->crud->query->whereHas("grade", function (Builder $builder) use ($value) {
                    $builder->whereHas("client", function (Builder $student) use ($value) {
                        $student->where("name", "like", "%$value%");
                    });
                });
            }
        );
        $this->crud->addFilter([
            'type' => "text",
            'name' => 'partner_filter',
            'label' => trans("backpack::crud.partner_name"),
        ], false,
            function ($value) {
                $this->crud->query->whereHas("teacher", function (Builder $teacher) use ($value) {
                    $teacher->whereHas("partner", function (Builder $partner) use ($value) {
                        $partner->where("name", "like", "%$value%");
                    });
                });
            }
        );
        // dropdown filter
        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'dropdown',
            'label' => trans("backpack::crud.grade_status")
        ], [
            0 => 'Học viên và giáo viên vào đúng giờ',
            1 => 'Học viên vào muộn',
            2 => 'Giáo viên vào muộn',
            3 => 'Học viên hủy buổi học',
            4 => 'Giáo viên hủy buổi học',
        ], function ($value) { // if the filter is active
            $this->crud->query->where("status", "like", "%\"name\":\"$value\"%");
        });

        // daterange filter
        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'from_to',
            'label' => trans("backpack::crud.date_filter")
        ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->query->where("date", ">=", $dates->from);
                $this->crud->query->where("date", "<=", $dates->to);
//                $this->crud->addClause('where', 'date', '>=', $dates->from);
//                $this->crud->addClause('where', 'date', '<=', $dates->to . ' 23:59:59');
            });
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected
    function setupListOperation()
    {
//        CRUD::setOperationSetting('showEntryCount', false);
        if (backpack_user()->type == 5) {
            $this->crud->query->whereHas("teacher", function (Builder $teacher) {
                $teacher->whereHas("partner", function (Builder $partner) {
                    $partner->where("id", backpack_user()->id);
                });
            });
        }
        $this->crud->query->orderBy("date", "DESC");
        if (isset($_REQUEST["grade_id"])) {
            $grade = Grade::find(($_REQUEST["grade_id"]));
            $trans = trans('backpack::crud.history');
            $tran = trans('backpack::crud.history') . ": " . $grade->name;
            CRUD::setEntityNameStrings($trans, $tran);
            Widget::add([
                'type' => 'view',
                'view' => 'test',
                'grade' => $grade,
            ]);
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
        CRUD::column('date')->label(trans("backpack::crud.date"))->type("date");
        CRUD::column('start')->label(trans("backpack::crud.start"))->type("time");
        CRUD::column('end')->label(trans("backpack::crud.end"))->type("time");



        if (backpack_user()->type != 3) {
            CRUD::addColumn([
                'name' => trans("backpack::crud.student_name"),
                'type' => 'model_function',
                'function_name' => 'getStudentList',
                'searchLogic' => 'text',
            ]);
        }
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

        if (backpack_user()->type != 3) {
            CRUD::column("clients")->label(trans("backpack::crud.client_name"))->type("model_function")->function_name("client");
            if (backpack_user()->type != 2) {
                CRUD::column("partners")->label(trans("backpack::crud.partner_name"))->type("model_function")->function_name("partner");
            }
        }
        CRUD::column('lesson')->label(trans("backpack::crud.lesson_name"));
        CRUD::column('teacher_video')->label(trans("backpack::crud.teacher_video"))->type("video");
        CRUD::addColumn(
            [
                'name' => 'drive',
                'label' => "Drive Video",
                'type' => 'link',

            ]);

        CRUD::column('duration')->label(trans("backpack::crud.duration"))->type("number");

        if (backpack_user()->type <= 1) {
//            CRUD::column('hour_salary')->label("Lương theo giờ (đ)")->type("number")->wrapper(["class" => "text-center"]);
//            CRUD::column('log_salary')->label("Lương của buổi học (đ)")->type("number");
            CRUD::addColumn([
                'label' => trans("backpack::crud.hour_salary"),
                'type' => 'model_function',
                'function_name' => 'getHourSalary'
            ]);
            CRUD::addColumn([
                'label' => trans("backpack::crud.log_salary"),
                'type' => 'model_function',
                'function_name' => 'getLogSalary'
            ]);
        }
        CRUD::addColumn([

            'type' => 'model_function',
            'function_name' => 'StatusShow',
            'label' => trans("backpack::crud.grade_status"),
        ]);
        CRUD::column('assessment')->label(trans("backpack::crud.assessment"))->type("textarea");
//        CRUD::column('attachment')->label(trans("backpack::crud.attachment"))->type("model_function")->function_name("showAttachments");
        CRUD::addColumn([
            "name" => "attachments",
            "label" => trans("backpack::crud.attachment"),
            "type" => "upload_multiple",
            "wrapper" => [
                'href' => function ($crud) {
                    return url('uploads/document/' . $crud);
                },
            ]
        ]);
        CRUD::addColumn([
//            'name' => 'StudentAccept',
            'type' => 'model_function',
            'function_name' => 'StudentAccept',
            'label' => trans("backpack::crud.accepted_student"),
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
    protected
    function setupCreateOperation($edit = false)
    {
        $this->crud->setTitle(1);
        CRUD::setValidation(LogRequest::class);
        if (!$edit) {
            if (backpack_user()->type == 1) {
                CRUD::addField([
                    'name' => 'grade_id',
                    'type' => 'select2',
                    'entity' => 'Grade',
                    'model' => "App\Models\Grade",
                    'attribute' => 'name',
                    'label' => trans("backpack::crud.grade_name"),
                    'options' => (function ($query) {
                        return $query->orderBy('name', 'ASC')->leftJoin("teacher_grade", "teacher_grade.grade_id", "=", "grades.id")->where("teacher_grade.teacher_id", backpack_user()->id)->where("grades.status", 0)->where("disable", 0)->get();
                    })
                ]);
            }
            if (backpack_user()->type == 0) {
                CRUD::addField([
                    'name' => 'grade_id',
                    'type' => 'select2',
                    'entity' => 'Grade',
                    'model' => "App\Models\Grade",
                    'attribute' => 'name',
                    'label' => trans("backpack::crud.grade_name"),
                    'options' => (function ($query) {
                        return $query->orderBy('name', 'ASC')->
                        leftJoin("staff_grade", "staff_grade.grade_id", "=", "grades.id")
                            ->leftJoin("supporter_grade", "supporter_grade.grade_id", "=", "grades.id")
                            ->where("staff_grade.staff_id", backpack_user()->id)->where("disable", 0)
                            ->orWhere("supporter_grade.supporter_id", backpack_user()->id)
                            ->where("disable", 0)->get()
                            ->where("grades.status", 0);
                    })
                ]);
            }
            if (backpack_user()->type == -1) {
                CRUD::addField([
                    'name' => 'grade_id',
                    'type' => 'select2',
                    'entity' => 'Grade',
                    'model' => "App\Models\Grade",
                    'attribute' => 'name',
                    'label' => trans("backpack::crud.grade_name"),
                    'options' => (function ($query) {
                        return $query->where("disable", 0)->where("grades.status", 0)->get();
                    })
                ]);
            }

            if (backpack_user()->type != 1) {
                CRUD::addField([
                    'name' => 'teacher_id',
                    'label' => 'Điểm danh hộ giáo viên',
                    'type' => 'select2',
                    'attribute' => 'fullName',
                    'options' => (function ($query) {
                        return $query->where("type", 1)->where("disable", 0)->get();
                    })
                ]);
            } else {
                if (backpack_user()->type == 1) {
                    CRUD::addField([
                        'name' => 'teacher_id',
                        'value' => backpack_user()->id,
                        'type' => 'hidden',
                    ]);
                }
            }
        }
        CRUD::field('date')->label(trans("backpack::crud.date"))->type("date")->wrapper([
            "class" => "col-md-4 col-12 mb-2",
        ])->attributes([
            'min' => backpack_user()->type > 0 ? Carbon::now()->subDays(2)->format("Y-m-d") : "",
            'max' => backpack_user()->type > 0 ? Carbon::now()->format("Y-m-d") : "",
            'required' => true,
        ]);
        CRUD::field('start')->label(trans("backpack::crud.start"))->type("time")->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('end')->label(trans("backpack::crud.end"))->type("time")->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('duration')->label(trans("backpack::crud.duration"))->suffix(trans("backpack::crud.minutes"))->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('hour_salary')->label(trans("backpack::crud.hour_salary"))->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('log_salary')->attributes(["readonly" => true])->label(trans("backpack::crud.log_salary"))->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('lesson')->label(trans("backpack::crud.lesson_name"));
        CRUD::field('information')->label(trans("backpack::crud.content"))->type("tinymce");
        CRUD::addField(
            [
                'name' => 'teacher_video',
                'label' => trans("backpack::crud.teacher_video"),
                'type' => 'video',
//                'youtube_api_key' => 'AIzaSyDc3MoGdCrqbCHq6XRbulelTPy5oWsLwIE',
                'tab' => 'Video from youtube',
            ]);
        CRUD::addField(
            [
                'name' => 'drive',
                'label' => "Drive Video",
                'tab' => 'Video from Drive',
            ]);
        CRUD::addField(
            [   // repeatable
                'name' => 'status',
                'label' => trans("backpack::crud.grade_status"),
                'type' => 'repeatable',
                'fields' => [ // also works as: "fields"
                    [
//                        dd($_SERVER),
                        'name' => 'name',
                        'label' => trans("backpack::crud.status"),
                        'type' => "select_from_array",
                        'options' => [
                            trans("backpack::crud.student") . " " . trans("backpack::crud.and") . " " . trans("backpack::crud.teacher") . " " . trans("backpack::crud.on_time"),
                            trans("backpack::crud.student") . " " . trans("backpack::crud.late"),
                            trans("backpack::crud.teacher") . " " . trans("backpack::crud.late"),
                            trans("backpack::crud.student") . " " . trans("backpack::crud.drop"),
                            trans("backpack::crud.teacher") . " " . trans("backpack::crud.drop"),
                            trans('backpack::crud.other'),
                        ],
                        "value" => 0,
                        "attributes" => ["id" => "status_name"],
                        'wrapper' => ['class' => 'form-group col-md-6 '],
                    ],
                    [
                        'name' => 'time',
                        'type' => 'number',
                        'label' => trans("backpack::crud.time"),
                        'suffix' => trans("backpack::crud.minutes"),
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
        CRUD::field('assessment')->label(trans("backpack::crud.assessment"))->type("textarea");
        CRUD::field('question')->label(trans("backpack::crud.exercise"))->type("tinymce");
        CRUD::addField(
            [   // Upload
                'name' => 'attachments',
                'label' => trans("backpack::crud.attachment"),
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
        $this->setupCreateOperation(true);
    }

    protected
    function detail($id)
    {
        if (Log::find($id)) {
            return view("log", [
                'log' => Log::find($id),
                'logs' => Log::find($id)->Grade()->first()->Logs()->get()
            ]);
        }
        return view("errors.404");
    }

    public
    function acceptByStudent(\Illuminate\Http\Request $request)
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
