<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TimeRequest;
use App\Models\Teacher;
use App\Models\Time;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class TimeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TimeCrudController extends CrudController
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
        CRUD::setModel(Time::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/time');
        CRUD::setEntityNameStrings('Thời gian rảnh', 'Thời gian rảnh');
        $this->crud->denyAccess(["create", "delete"]);

        $this->crud->setOperationSetting('detailsRow', true);
        if (backpack_user()->type == 1) {
            $this->crud->addButtonFromModelFunction("top", "editNow", "RedirectToEdit", "top");
        }
        $this->crud->addFilter([
            'name' => 'name',
            'label' => 'Tên giáo viên',
            'type' => 'text'
        ], false, function ($value) {
            $this->crud->query->whereHas("teacher", function (Builder $builder) use ($value){
                $builder->where("name", "like", "%$value%");
            });
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
        if (backpack_user()->type > 0) {
            $this->crud->addClause("where", "teacher_id", backpack_user()->id);
        }
        CRUD::column('teacher_id')->label("Tên giáo viên");
        CRUD::column('comment')->label("Comment");

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
        CRUD::setValidation(TimeRequest::class);
        CRUD::addField(
            [
                'name' => 'data',
                'label' => 'Dữ liệu',
                'type' => 'repeatable',
                'fields' => [
                    [
                        'name' => 'time',
                        'type' => 'text',
                        'label' => 'Time',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'monday',
                        'type' => 'text',
                        'label' => 'Monday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'tuesday',
                        'type' => 'text',
                        'label' => 'Tuesday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'wednesday',
                        'type' => 'text',
                        'label' => 'Wednesday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'thursday',
                        'type' => 'text',
                        'label' => 'Thursday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'friday',
                        'type' => 'text',
                        'label' => 'Friday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'saturday',
                        'type' => 'text',
                        'label' => 'Saturday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],
                    [
                        'name' => 'sunday',
                        'type' => 'text',
                        'label' => 'Sunday',
                        'wrapper' => ['class' => 'form-group col'],
                    ],

                ],
                'init_rows' => 9,
                'max_rows' => 9,
                'min_rows' => 9,
                'new_item_label' => 'Thêm buổi',
            ]
        );
        CRUD::addField(['name' => 'comment', 'type' => 'textarea']);
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

    protected function showDetailsRow($id)
    {
        $table = Time::find($id)->data;
        return view("components.time-detail", ["table" => $table]);
    }

    protected function show($id)
    {
        $time = Time::where("id", $id)->first();
        return view("time-show", ['time' => $time]);
    }

    protected function update(Request $request)
    {
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 7; $j++) {
                $morning[$i][$j] = $request->{"morning-$i-$j"};
                $evening[$i][$j] = $request->{"evening-$i-$j"};
                $afternoon[$i][$j] = $request->{"afternoon-$i-$j"};
            }
        }
        print_r($morning);
        $morning = Time::ArrToString($morning);
        $evening = Time::ArrToString($evening);
        $afternoon = Time::ArrToString($afternoon);
        Time::find($request->id)->update([
            'morning' => $morning,
            'afternoon' => $afternoon,
            'evening' => $evening
        ]);
        return redirect("admin/time");
    }

}
