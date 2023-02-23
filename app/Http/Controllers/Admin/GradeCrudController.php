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
use Illuminate\Database\Eloquent\Builder;

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

//        $this->crud->addButtonFromModelFunction("line", "meeting", "meeting", "line");
        if (backpack_user()->type >= 1) {
            $this->crud->addButtonFromModelFunction("top", "redirectToIndex", "toIndex", "top");
        }
        CRUD::setModel(Grade::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/grade');
        CRUD::setEntityNameStrings('Lớp học', 'Các lớp học');
        if (backpack_user()->type == 0) {
            $this->crud->query->where(function (Builder $query) {
                $query->whereHas("staff", function (Builder $builder) {
                    $builder->where("id", backpack_user()->id);
                })->orWhereHas("supporter", function (Builder $supporter) {
                    $supporter->where("id", backpack_user()->id);
                });
            });
        }
        if (backpack_user()->type == 1) {
            $this->crud->query->whereHas("teacher", function (Builder $builder) {
                $builder->where("id", backpack_user()->id);
            });
        }
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'grade_filter',
            'label' => 'Tên lớp'
        ],
            false,
            function ($value) {
                $this->crud->query->where("name", "like", "%$value%");
            }
        );
        if (backpack_user()->type == -1) {
            $this->crud->addFilter([
                'type' => 'text',
                'name' => 'staff_filter',
                'label' => 'Nhân viên'
            ],
                false,
                function ($value) {
                    $this->crud->query->whereHas("staff", function (Builder $builder) use ($value) {
                        $builder->where("name", "like", "%$value%");
                    });
                }
            );
            $this->crud->addFilter([
                'type' => 'text',
                'name' => 'sp_filter',
                'label' => 'Nhân viên hỗ trợ'
            ],
                false,
                function ($value) {
                    $this->crud->query->whereHas("supporter", function (Builder $builder) use ($value) {
                        $builder->where("name", "like", "%$value%");
                    });
                }
            );
        }
        if (backpack_user()->type <= 0) {
            $this->crud->addFilter([
                'type' => 'text',
                'name' => 'teacher_filter',
                'label' => 'Giáo viên'
            ],
                false,
                function ($value) {
                    $this->crud->query->whereHas("teacher", function (Builder $builder) use ($value) {
                        $builder->where("name", "like", "%$value%");
                    });
                }
            );
        }
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'student_filter',
            'label' => 'Học sinh'
        ],
            false,
            function ($value) {
                $this->crud->query->whereHas("student", function (Builder $builder) use ($value) {
                    $builder->where("name", "like", "%$value%");
                });
            }
        );

        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'client_filter',
            'label' => 'Đối tác'
        ],
            false,
            function ($value) {
                $this->crud->query->whereHas("client", function (Builder $builder) use ($value) {
                    $builder->where("name", "like", "%$value%");
                });
            }
        );
        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2_multiple',
            'label' => 'Trạng thái lớp'
        ], function () {
            return [
                0 => 'Đang học',
                1 => 'Đã kết thúc',
                2 => 'Đang bảo lưu',
            ];
        }, function ($values) { // if the filter is active
            if (is_countable(json_decode($values)) && count(json_decode($values)) > 0) {
                $this->crud->query->whereIn('status', json_decode($values));
            }


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
        CRUD::column('supporter_id')->type("select")->label("Nhân viên hỗ trợ");
        CRUD::column('client_id')->type("select")->label("Đối tác");
        CRUD::column('zoom')->type("link")->label("Link lớp");
        if (backpack_user()->type != 1) {
            CRUD::column('pricing')->label("Gói học phí")->type("number");
        }
        CRUD::column('minutes')->label("Số phút");
        CRUD::column('')->label("Số phút còn lại")->type("model_function")->function_name("getRs");
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
    protected function setupCreateOperation($editing = false)
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
                        return $query->orderBy('name', 'ASC')->where('type', 3)->where("disable", 0)->get();
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
                        return $query->orderBy('name', 'ASC')->where('type', 1)->where("disable", 0)->get();
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
                        return $query->orderBy('name', 'ASC')->where('type', 2)->where("disable", 0)->get();
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
                            return $query->orderBy('name', 'ASC')->where('type', 0)->where("disable", 0)->get();
                        }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                    ],
                );
                CRUD::addField(
                    [    // Select2Multiple = n-n relationship (with pivot table)
                        'label' => "Nhân viên hỗ trợ",
                        'type' => 'select2_multiple',
                        'name' => 'supporter', // the method that defines the relationship in your Model

                        // optional
                        'entity' => 'Supporter', // the method that defines the relationship in your Model
                        'model' => "App\Models\Staff", // foreign key model
                        'attribute' => 'name', // foreign key attribute that is shown to user
                        'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                        // 'select_all' => true, // show Select All and Clear buttons?

                        // optional
                        'options' => (function ($query) {
                            return $query->orderBy('name', 'ASC')->where('type', 0)->where("disable", 0)->get();
                        }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                    ],
                );
            } else {
                if ($editing) {
                    $gradeId = explode("/", $this->crud->getRequest()->path())[2];
                    if ($this->crud->model->isNotSupporter($gradeId)) {
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
                                'attributes' => [
                                    'required' => true,
                                ],
                                'options' => (function ($query) {
                                    return $query->orderBy('name', 'ASC')->where("id", backpack_user()->id)->get();
                                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                            ],
                        );
                        CRUD::addField(
                            [    // Select2Multiple = n-n relationship (with pivot table)
                                'label' => "Nhân viên hỗ trợ",
                                'type' => 'select2_multiple',
                                'name' => 'supporter', // the method that defines the relationship in your Model

                                // optional
                                'entity' => 'Supporter', // the method that defines the relationship in your Model
                                'model' => "App\Models\Staff", // foreign key model
                                'attribute' => 'name', // foreign key attribute that is shown to user
                                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                                // 'select_all' => true, // show Select All and Clear buttons?

                                // optional
                                'options' => (function ($query) {
                                    return $query->orderBy('name', 'ASC')->where('type', 0)->where("id", "!=", backpack_user()->id)->where("disable", 0)->get();
                                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                            ],
                        );
                    }
                } else {
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
                            'attributes' => [
                                'required' => true,
                            ],
                            'options' => (function ($query) {
                                return $query->orderBy('name', 'ASC')->where("id", backpack_user()->id)->get();
                            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                        ],
                    );
                    CRUD::addField(
                        [    // Select2Multiple = n-n relationship (with pivot table)
                            'label' => "Nhân viên hỗ trợ",
                            'type' => 'select2_multiple',
                            'name' => 'supporter', // the method that defines the relationship in your Model

                            // optional
                            'entity' => 'Supporter', // the method that defines the relationship in your Model
                            'model' => "App\Models\Staff", // foreign key model
                            'attribute' => 'name', // foreign key attribute that is shown to user
                            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                            // 'select_all' => true, // show Select All and Clear buttons?

                            // optional
                            'options' => (function ($query) {
                                return $query->orderBy('name', 'ASC')->where('type', 0)->where("id", "!=", backpack_user()->id)->where("disable", 0)->get();
                            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                        ],
                    );
                }

            }
            CRUD::addField([
                'name' => 'menus',
                'label' => 'Bộ sách được sử dụng',
                'type' => 'relationship',
                'model' => 'App\Models\Menu',
                'entity' => 'Menus',
                'attribute' => 'name',
                'pivot' => true,
                'options' => (function ($query) {
                    return $query->where("parent_id", "!=", null)->get();
                }),
            ]);
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

        $this->setupCreateOperation(true);
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

    public function meeting($id)
    {
        return view("meeting", ["grade" => Grade::find($id)]);
    }
}
