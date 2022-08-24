<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GradeRequest;
use App\Models\Grade;
use App\Models\Log;
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
        CRUD::setModel(Grade::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/grade');
        CRUD::setEntityNameStrings('Lớp học', 'Các lớp học');
        $this->crud->denyAccess(["show"]);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause("where","disable",0);
        if (backpack_user()->type != -1) {
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
        CRUD::column('staff_id')->type("select")->label("Nhân viên quản lý");
        CRUD::column('student_id')->type("select")->label("Học viên");
        CRUD::column('teacher_id')->type("select")->label("Giáo viên");
        CRUD::column('client_id')->type("select")->label("Đối tác");
        CRUD::column('pricing')->label("Gói học phí")->type("number");
        CRUD::column('minutes')->label("Số phút");
        CRUD::column('attachment')->label("Tài liệu")->type("link");
        CRUD::column('status')->label("Trạng thái")->type("select_from_array")->options(["Đang học", "Đã kết thúc", "Đã bảo lưu"]);
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

        CRUD::field('name')->label("Tên lớp");
        CRUD::field('pricing')->label("Gói học phí");
        CRUD::field('minutes')->label("Số phút")->type("number");
        CRUD::field('information')->label("Thông tin chi tiết")->type("tinymce");
        CRUD::field('status')->label("Trạng thái")->type("select_from_array")->options(["Đang học", "Đã kết thúc", "Đã bảo lưu"]);
        CRUD::addField(
            [
                'name' => 'attachment',
                'label' => 'Tài liệu',
                'type' => 'text',
//                'upload' => true,
//                'disk' => 'uploads_document',
                'prefix'=>"Link drive",
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
        Log::where("grade_id","=",$id)->update([
            'disable'=>1,
        ]);
        return Grade::find($id)->update([
            'disable'=>1,
        ]);
    }
}
