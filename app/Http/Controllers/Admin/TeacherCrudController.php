<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TeacherRequest;
use App\Models\Skill;
use App\Models\Teacher;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

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
        if (backpack_user()->type == 1 || backpack_user()->type >= 3) {
            $this->crud->denyAccess(["list"]);
        }
        if (backpack_user()->type >= 1) {
            $this->crud->denyAccess(["create"]);
        }
        $this->crud->denyAccess(["show"]);
        $this->crud->addFilter([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Tên giáo viên'
        ], false, function ($value) {
            $this->crud->query->where("name", "like", "%$value%");
        });
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
            if (!is_array($values))
                if (is_array(json_decode($values))) {
                    $this->crud->query->whereHas('skills', function (Builder $builder) use ($values) {
                        $builder->whereIn("id", json_decode($values));
                    });
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
        if (backpack_user()->type == 5) {
            $this->crud->query->where("partner_id", backpack_user()->id);
        }
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
        CRUD::addColumn([
            'name' => 'partner_id',

            'label' => 'Đối tác cung cấp',
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
        CRUD::column("video")->type("link_youtube")->label("Video");
        CRUD::column("cv")->type("link")->label("Hồ sơ giáo viên");
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
        CRUD::addField([
            'name' => 'partner_id',
            'type' => 'select2',
            'model' => 'App\Models\Partner',
            'entity' => 'Partner',
            'attribute' => 'name',
            'label' => 'Đối tác cung cấp',
            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->where('type', 5)->where("disable", 0)->get();
            }),
        ]);
        CRUD::field('email')->label("Email giáo viên");
        CRUD::field("facebook")->label("Link Facebook");
        CRUD::field("address")->label("Địa chỉ");
        CRUD::field('avatar')->type("image")->crop(true)->aspect_ratio(1);
        CRUD::field('type')->type("hidden")->value(1);
        CRUD::field("video")->type("text")->label("Video")->prefix("https://");
        CRUD::field("cv")->type("text")->label("Hồ sơ giáo viên")->prefix("https://");
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

    public function destroy($id)
    {
        try {
            User::find($id)->update([
                'email' => Str::random(12) . "@gmail.com",
                'disable' => 1,
                'phone' => null,
            ]);
        } catch (\Exception $exception) {
            return redirect()->back();
        }
    }
}
