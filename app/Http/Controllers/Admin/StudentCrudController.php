<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StudentRequest;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class StudentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StudentCrudController extends CrudController
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
        CRUD::setModel(Student::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/student');
        CRUD::setEntityNameStrings('Học sinh', 'Học sinh');
        if (backpack_user()->type == 3) {
            $this->crud->denyAccess(["list"]);
        }
        if (backpack_user()->type >= 1) {
            $this->crud->denyAccess(["create", "update", "edit"]);
        }
        $this->crud->addButtonFromModelFunction("line", "Detail", "Detail", "line");
        $this->crud->denyAccess(["show"]);
        $this->crud->disableReorder();
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'student_filter',
            'label' => 'Học sinh'
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
                'label' => 'Nhân viên quản lý'
            ],
                false,
                function ($value) {
                    $this->crud->query->whereHas("grades", function (Builder $builder) use ($value) {
                        $builder->whereHas("staff", function (Builder $staff) use ($value) {
                            $staff->where("name", "like", "%$value%");
                        });
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
                    $this->crud->query->whereHas("grades", function (Builder $builder) use ($value) {
                        $builder->whereHas("supporter", function (Builder $staff) use ($value) {
                            $staff->where("name", "like", "%$value%");
                        });
                    });
                }
            );
        }

        if (backpack_user()->type == 0) {
            $this->crud->addFilter([
                'type' => 'text',
                'name' => 'name',
                'label' => 'Tên học sinh'
            ],
                false,
                function ($value) { // if the filter is active
                    $this->crud->query->where("name", "like", "%$value%");
                }
            );

        }
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'phone',
            'label' => 'Số điện thoại'
        ],
            false,
            function ($value) { // if the filter is active
                $this->crud->query->where("phone", "like", "%$value%");
            }
        );
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'parent',
            'label' => 'Người giám hộ'
        ],
            false,
            function ($value) { // if the filter is active
                $this->crud->query->where("student_parent", "like", "%$value%");
            }
        );
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'email',
            'label' => 'Email của học sinh'
        ],
            false,
            function ($value) { // if the filter is active
                $this->crud->query->where("email", "like", "%$value%");
            }
        );
        $this->crud->addFilter([
            'type' => 'select2',
            'name' => 'status',
            'label' => 'Tình trạng'
        ], function () {
            return [
                0 => 'Đang học',
                1 => 'Đã ngừng học',
                2 => 'Đã bảo lưu',
            ];
        },
            function ($value) { // if the filter is active
                $this->crud->query->where("student_status", $value);
            }
        );

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
            $this->crud->query->where(function (Builder $map) {
                $map->whereHas("grades", function (Builder $builder) {
                    $builder->whereHas("staff", function (Builder $staff) {
                        $staff->where("id", backpack_user()->id);
                    })->orWhereHas("supporter", function (Builder $supporter) {
                        $supporter->where("id", backpack_user()->id);
                    });
                })->orWhere("staff_id", backpack_user()->id);
            })->where("disable", 0)->where("type", 3);
        } else {
            $this->crud->query->where("users.disable", 0)->where("users.type", 3);
        }
        CRUD::column("student_status")->label("Tình trạng học sinh")->type("select_editable")->options(["Đang học", "Đã ngừng học", "Đang bảo lưu"]);
        CRUD::addColumn(['name' => 'code', 'type' => 'text', 'label' => "Mã học sinh"]);

        CRUD::addColumn(['name' => 'name', 'type' => 'text', 'label' => "Tên học sinh",
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url("/student/detail/$entry->id");
                },
            ]]);
        CRUD::addColumn([
            'name' => "staff",
            'type' => 'model_function',
            "function_name" => "staffs",
            "label" => "Nhân viên quản lý",
//            'searchLogic' => function ($query, $column, $searchTerm) {
//                $query->orWhere('staff', 'like', '%' . $searchTerm . '%');
//            }
            "searchLogic" => "text",
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
//                    if (backpack_user()->type < 0) {
//                        return backpack_url("/staff/detail/$entry->id");
//                    }
                },
            ]
        ]);
        CRUD::addColumn([
            'name' => "staff",
            'type' => 'model_function',
            "function_name" => "staffs",
            "label" => "Nhân viên quản lý",
//            'searchLogic' => function ($query, $column, $searchTerm) {
//                $query->orWhere('staff', 'like', '%' . $searchTerm . '%');
//            }
            "searchLogic" => "text",
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url("/staff/detail/$entry->id");
                },
            ]
        ]);
        CRUD::addColumn([
            'type' => 'model_function',
            "function_name" => "supporters",
            "label" => "Nhân viên hỗ trợ",
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url("/staff/detail/$entry->id");
                },
            ]
        ]);
        CRUD::addColumn(['name' => 'student_parent', 'type' => 'text', 'label' => "Người giám hộ"]);
        CRUD::addColumn(['name' => 'phone', 'type' => 'text', 'label' => "Số điện thoại"]);
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

        CRUD::addColumn(['name' => 'email', 'type' => 'text', "label" => "Email của học sinh"]);
//        CRUD::column("student_type")->label("Phân loại học sinh")->type("select_from_array")->options(["Tiềm năng", "Không tiềm năng", "Chưa học thử"]);
        CRUD::column("student_status")->label("Tình trạng học sinh")->type("select_editable")->options(["Đang học", "Đã ngừng học", "Đang bảo lưu"]);

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
    protected function setupCreateOperation($edit = false)
    {

        CRUD::setValidation(StudentRequest::class);

        CRUD::field('name')->label("Tên học sinh");
        CRUD::field('email')->label("Email học sinh");
        CRUD::addField(['name' => 'student_parent', 'type' => 'text', 'label' => "Tên người giám hộ (Để trống nếu HS tự đăng ký)"]);
        CRUD::addField(['name' => 'phone', 'type' => 'text', 'label' => "Số điện thoại"]);
        CRUD::field('type')->type("hidden")->value(3);
        CRUD::field('avatar')->type("image")->crop(true)->aspect_ratio(1);
        CRUD::field("facebook")->label("Link Facebook");

        CRUD::field("address")->label("Địa chỉ");
        if (backpack_user()->type <= 0) {
//            CRUD::field("student_type")->label("Phân loại học sinh")->type("select_from_array")->options(["Tiềm năng", "Không tiềm năng", "Chưa học thử"]);
            CRUD::field("student_status")->label("Tình trạng học sinh")->type("select_from_array")->options(["Đang học", "Đã ngừng học", "Đang bảo lưu"]);
        }
        if (backpack_user()->type < 1) {
            CRUD::addField([
                'name' => 'staff_id',
                'type' => 'select2',
                'entity' => "originStaff",
                'model' => "App\Models\Staff",
                'attribute' => 'name',
            ]);
            if ($edit) {
                CRUD::addField([
                    'name' => 'code',
                    'type' => 'text',
                    'label' => "Mã học sinh",

//            'value'=>'HS'.User::max("id")+1,
                ]);

            } else {
                CRUD::addField([
                    'name' => 'code',
                    'type' => 'text',
                    'label' => "Mã học sinh",
                    'value' => Student::getID()
                ]);
                if (backpack_user()->type == 0) {
                    CRUD::addField([
                        'name' => 'staff_id',
                        'type' => 'hidden',
                        'value' => backpack_user()->id,
                    ]);
                }
            }

        }
        CRUD::addField(
            [
                'name' => 'extra',
                'label' => 'Thông tin thêm của học sinh',
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
        CRUD::addField(
            [   // Password
                'name' => 'password',
                'label' => 'Mật khẩu',
                'type' => 'password'
            ],
        );
        CRUD::addField(
            [   // Password
                'name' => 'private_key',
                'type' => 'hidden',
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

    protected function detail($id)
    {

        return view("student-detail", ['data' => Student::find($id)]);
    }

    public function destroy($id)
    {
        try {
            User::find($id)->update([
                'email' => Str::random(12) . "@gmail.com",
                'disable' => 1,
                'phone' => null,
            ]);
            return "1";
        } catch (\Exception $exception) {
            return redirect()->back();
        }
    }

}
