<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContestRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

/**
 * Class ContestCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContestCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Contest::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contest');
        CRUD::setEntityNameStrings('Bài test', 'Danh sách bài test');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('title')->label("Tiêu đề");
        CRUD::column('created_at')->label("Ngày tạo");
        CRUD::column('limit_time')->label("Thời gian giới hạn");

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
        CRUD::setValidation(ContestRequest::class);
        CRUD::field('title')->label("Tiêu đề bài Test");
        CRUD::field("next_contest")->label("Bài test cấp tiếp theo")->type("select2")->model("App\Models\Contest")->entity("nextContest");
        CRUD::field("min_point")->label("Điểm tối thiểu cần đạt (thang điểm 100)")->type("number");
        CRUD::field('limit_time')->suffix("phút")->label("Giới hạn thời gian");
        CRUD::addField([
            'name' => 'body',
            'type' => 'repeatable',
            'new_item_label' => 'Thêm câu hỏi mới',
            'init_rows' => 1,
            'fields' => [
                [
                    'name' => 'question',
                    'label' => 'Câu hỏi',
                    'type' => 'textarea'
                ],
                [
                    'name' => 'image',
                    'label' => 'Ảnh',
                    'type' => 'browse',
                    'wrapper' => [
                        'class' => 'col-md-6 mb-3'
                    ]
                ],
                [
                    'name' => 'audio',
                    'label' => 'Audio',
                    'type' => 'browse',
                    'wrapper' => [
                        'class' => 'col-md-6 mb-3'
                    ]
                ],
                [
                    'name' => 'delay',
                    'label' => 'Phát âm thanh sau',
                    'type' => 'number',
                    'suffix' => 'Giây',
                    'default' => 3,
                    'wrapper' => [
                        'class' => 'col-md-6 mb-3'
                    ]
                ],
                [
                    'name' => 'video',
                    'label' => 'Link video Youtube',
                    'type' => 'text',
                    'wrapper' => [
                        'class' => 'col-md-6 mb-3'
                    ]
                ],
                [
                    'name' => 'a',
                    'label' => 'Đáp án A',
                    'wrapper' => [
                        'class' => 'col-md-6 mb-3'
                    ]
                ],
                [
                    'name' => 'b',
                    'label' => 'Đáp án B',
                    'wrapper' => [
                        'class' => 'col-md-6 mb-3'
                    ]
                ],
                [
                    'name' => 'c',
                    'label' => 'Đáp án C',
                    'wrapper' => [
                        'class' => 'col-md-6 mb-3'
                    ]
                ],
                [
                    'name' => 'd',
                    'label' => 'Đáp án D',
                    'wrapper' => [
                        'class' => 'col-md-6 mb-3'
                    ]
                ],
                [
                    'name' => 'correct',
                    'label' => 'Đáp án đúng',
                    'wrapper' => [
                        'class' => 'col-md-6 mb-3'
                    ],
                    'type' => 'select2_from_array',
                    'options' => ['a' => 'Đáp án A', 'b' => 'Đáp án B', 'c' => 'Đáp án C', 'd' => 'Đáp án D'],
                    'value' => 'a'
                ],
                [
                    'name' => 'text_correct',
                    'label' => 'Đáp án điền từ',
                    'wrapper' => [
                        'class' => 'col-md-6 mb-3'
                    ]
                ]
            ]
        ]);


        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - );
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

    protected function correctContest($id)
    {
        $case = DB::table("customer_contest")->where("id", $id)->first();
        if ($case) {
            return view("contest-correct", ["case" => $case]);
        }
        abort(404);
    }
}
