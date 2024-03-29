<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DemoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class DemoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DemoCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Demo::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/demo');
        CRUD::setEntityNameStrings('Buổi học DEMO', 'Các buổi học DEMO');
        if (backpack_user()->type > 0) {
            $this->crud->denyAccess(["create", "delete"]);
        }
        $this->crud->setOperationSetting('exportButtons', true);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        if (backpack_user()->type == 0) {
            $this->crud->query->where(function (Builder $query) {
                $query->whereHas("supporter", function (Builder $builder) {
                    $builder->where("id", backpack_user()->id);
                })->orWhereHas("staff", function (Builder $builder) {
                    $builder->where("id", backpack_user()->id);
                });
            });
        }
        if (backpack_user()->type == 1) {
            $this->crud->query->whereHas("teacher", function (Builder $builder) {
                $builder->where("id", backpack_user()->id);
            });
        }
        if (backpack_user()->type == 2) {
            $this->crud->query->whereHas("client", function (Builder $builder) {
                $builder->where("id", backpack_user()->id);
            });
        }
        if (backpack_user()->type == 1) {
            $this->crud->query->where("teacher_id", backpack_user()->id);
        }
        if (backpack_user()->type == 2) {
            $this->crud->query->where("client_id", backpack_user()->id);
        }
        if (backpack_user()->type == -1) {

            $this->crud->addFilter([
                'type' => 'text',
                'name' => 'staff',
                'label' => 'Nhân viên'
            ],
                false,
                function ($value) { // if the filter is active
                    $this->crud->query->whereHas("staff", function (Builder $builder) use ($value) {
                        $builder->where("name", "like", "%$value%");
                    });
                });
            $this->crud->addFilter([
                'type' => 'text',
                'name' => 'supporter',
                'label' => 'Nhân viên hỗ trợ'
            ],
                false,
                function ($value) { // if the filter is active
                    $this->crud->query->whereHas("supporter", function (Builder $builder) use ($value) {
                        $builder->where("name", "like", "%$value%");
                    });
                });
        }
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'grade',
            'label' => 'Lớp'
        ],
            false,
            function ($value) { // if the filter is active
                $this->crud->query->where('grade', 'LIKE', "%$value%");
            });
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'students',
            'label' => 'Học viên'
        ],
            false,
            function ($value) { // if the filter is active
                $this->crud->query->where('students', 'LIKE', "%$value%");
            });
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'customer',
            'label' => 'Khách hàng'
        ],
            false,
            function ($value) { // if the filter is active
                $this->crud->query->whereHas("customers", function (Builder $builder) use ($value) {
                    $builder->where("name", "like", "%$value%");
                });
            });
        if (backpack_user()->type != 1) {
            $this->crud->addFilter([
                'type' => 'text',
                'name' => 'teacher',
                'label' => 'Giáo viên'
            ],
                false,
                function ($value) { // if the filter is active
                    $this->crud->query->whereHas("teacher", function (Builder $builder) use ($value) {
                        $builder->where("name", "like", "%$value%");
                    });
                });
        }
        if (backpack_user()->type != 2) {
            $this->crud->addFilter([
                'type' => 'text',
                'name' => 'client',
                'label' => 'Đối tác'
            ],
                false,
                function ($value) { // if the filter is active
                    $this->crud->query->join("users as clients", "clients.id", "=", "demos.client_id")
                        ->where("clients.name", "like", "%$value%");
                });
        }
        if (backpack_user()->type != 1) {
            $this->crud->addFilter([
                'type' => 'date_range',
                'name' => 'from_to',
                'label' => 'Ngày'
            ],
                false,
                function ($value) { // if the filter is active
                    $dates = json_decode($value);
                    $this->crud->addClause('where', 'date', '>=', $dates->from);
                    $this->crud->addClause('where', 'date', '<=', $dates->to . ' 23:59:59');
                });
        }
        CRUD::column('date')->label(trans("backpack::crud.date"))->type("date");
        CRUD::column('start')->label(trans("backpack::crud.start"))->type("time");
        CRUD::column('end')->label(trans("backpack::crud.end"))->type("time");
        CRUD::column('grade')->label(trans("backpack::crud.grade_name"));
        CRUD::column('student_quantity')->label(trans("backpack::crud.student_quantity"));
        CRUD::addColumn([
            'label' => trans("backpack::crud.student_name"),
            'name' => 'students',
            'searchLogic' => 'text',
        ]);
        CRUD::addColumn([
            'name' => 'teacher',
            'type' => 'select',
            'label' => trans("backpack::crud.teacher_name"),
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url("/teacher/detail/$related_key");
                },
            ],
        ]);
        CRUD::addColumn([
            'name' => 'staff',
            'type' => 'select',
            'label' => "Nhân viên",
        ]);
        CRUD::addColumn([
            'name' => 'supporter',
            'type' => 'select',
            'label' => 'NV Hỗ trợ /Supporter',
        ]);
        CRUD::addColumn([
            'name' => 'client',
            'type' => 'select',
            'label' => trans("backpack::crud.client_name"),
        ]);
        CRUD::addColumn([
            'name' => 'customers',
            'type' => 'select',
            'label' => 'Khách hàng',
        ]);
        CRUD::addColumn([
            'label' => 'Số điện thoại',
            'name' => 'student_phone',
//            'type' => 'select2_create',
        ]);
        CRUD::addColumn([
            'label' => 'Faceboook',
            'name' => 'student_facebook',
            'type' => 'link',
        ]);
        CRUD::column('lesson')->label(trans("backpack::crud.lesson_name"));

        CRUD::column('teacher_video')->label(trans("backpack::crud.teacher_video"))->type("video");
        CRUD::addColumn(
            [
                'name' => 'drive',
                'label' => "Drive Video",
                'type' => 'link',

            ]);
        CRUD::column('duration')->label(trans("backpack::crud.duration"))->type("number");

        if (backpack_user()->type != 2) {
            CRUD::addColumn([
                'label' => trans("backpack::crud.hour_salary"),
                'name' => 'hour_salary',
                'type' => 'number',
//            'type' => 'model_function',
//            'function_name' => 'getHourSalary'
            ]);
            CRUD::addColumn([
                'label' => trans("backpack::crud.log_salary"),
                'name' => 'log_salary',
                'type' => 'number',
//            'type' => 'model_function',
//            'function_name' => 'getLogSalary'
            ]);
        }
        CRUD::column('assessment')->label(trans("backpack::crud.assessment"))->type("textarea");
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

            'type' => 'model_function',
            'function_name' => 'StatusShow',
            'label' => trans("backpack::crud.grade_status"),
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
        CRUD::setValidation(DemoRequest::class);
        CRUD::field('grade')->label(trans("backpack::crud.grade_name"));
        CRUD::field('student_quantity')->label(trans("backpack::crud.student_quantity"));
        CRUD::addField([
            'label' => "Khách hàng",
            'name' => 'Customers',
            'type' => 'relationship',
            'pivot' => true,
            'entity' => 'Customers',
            'model' => 'App\Models\Customer',
            'options' => (function ($query) {
                return $query->where("type", 4)->where("disable", 0)->get();
            }),
        ]);
        CRUD::addField([
            'label' => 'Số điện thoại',
            'name' => 'student_phone',
//            'type' => 'select2_create',
        ]);
        CRUD::addField([
            'label' => 'Faceboook',
            'name' => 'student_facebook',
//            'type' => 'select2_create',
        ]);
        CRUD::addField([
            'label' => trans("backpack::crud.student_name"),
            'name' => 'students',
//            'type' => 'select2_create',
        ]);
        CRUD::addField([
            'name' => 'teacher_id',
            'label' => 'Giáo viên',
            'type' => 'select2',
            'options' => (function ($query) {
                return $query->where("type", 1)->where("disable", 0)->get();
            }),
        ]);
        CRUD::addField([
            'name' => 'client_id',
            'label' => 'Đối tác',
            'options' => (function ($query) {
                return $query->where("type", 2)->where("disable", 0)->get();
            }),
        ]);
        if (backpack_user()->type != 0) {
            CRUD::addField([
                'name' => 'staff_id',
                'type' => 'select2',
                'label' => "Nhân viên",
                'model' => 'App\Models\Staff',
                'entity' => 'Staff',
                'options' => (function ($query) {
                    return $query->where("type", 0)->where("disable", 0)->get();
                }),
            ]);
        } else {
            CRUD::addField([
                'name' => 'staff_id',
                'value' => backpack_user()->id,
                'type' => 'hidden'
            ]);
        }
        CRUD::addField([
            'model' => 'App\Models\Staff',
            'entity' => 'Supporter',
            'name' => 'supporter_id',
            'type' => 'select2',
            'label' => 'NV Hỗ trợ /Supporter',
            'options' => (function ($query) {
                return $query->where("type", 0)->where("disable", 0)->get();
            }),
        ]);
        CRUD::field('date')->label(trans("backpack::crud.date"))->type("date")->wrapper([
            "class" => "col-md-4 col-12 mb-2"
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
        CRUD::field('information')->label(trans("backpack::crud.content"))->type("summernote");
        CRUD::addField(
            [
                'name' => 'teacher_video',
                'label' => trans("backpack::crud.teacher_video"),
                'type' => 'video',
                'youtube_api_key' => 'AIzaSyDc3MoGdCrqbCHq6XRbulelTPy5oWsLwIE',
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
        $this->setupCreateOperation();
    }
}
