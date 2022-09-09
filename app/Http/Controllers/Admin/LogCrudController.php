<?php

namespace App\Http\Controllers\Admin;

use App\Events\ReportFromStudent;
use App\Http\Requests\LogRequest;
use App\Models\Grade;
use App\Models\Log;
use App\Models\Student;
use App\Models\User;
use App\Notifications\SlackNotification;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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


        CRUD::column('lesson')->label("Bài học");
        CRUD::column('teacher_video')->label("Video bài giảng")->type("open");
        CRUD::column('date')->label("Ngày")->type("date");
        CRUD::column('start')->label("Bắt đầu")->type("time");
        CRUD::column('end')->label("Kết thúc")->type("time");
        CRUD::column('duration')->label("Thời gian dạy (Phút)")->type("number");

        if (backpack_user()->type <= 1) {
            CRUD::column('hour_salary')->label("Lương theo giờ (đ)")->type("number")->wrapper(["class" => "text-center"]);
            CRUD::column('log_salary')->label("Lương của buổi học (đ)")->type("number");
        }
        CRUD::addColumn([

            'type' => 'model_function',
            'function_name' => 'StatusShow',
            'label' => "Tình trạng lớp học",
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
        CRUD::field('hour_salary')->label("Lương theo giờ")->suffix("đ")->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('log_salary')->attributes(["readonly" => true])->suffix("đ")->label("Lương của buổi học")->wrapper([
            "class" => "col-md-4 col-12 mb-2"
        ]);
        CRUD::field('lesson')->label("Bài học");
        CRUD::field('information')->label("Nội dung")->type("tinymce");
        CRUD::addField(
            [
                'name' => 'teacher_video',
                'label' => 'Video bài giảng',
                'type' => 'upload',
                'upload' => true,
                'disk' => 'uploads_video',
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
