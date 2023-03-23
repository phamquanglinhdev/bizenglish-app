<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CaringRequest;
use App\Models\Caring;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;

/**
 * Class CaringCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CaringCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
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
        CRUD::setModel(\App\Models\Caring::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/caring');
        CRUD::setEntityNameStrings('caring', 'carings');
        $this->crud->denyAccess(["list"]);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('staff_id');
        CRUD::column('student_id');
        CRUD::column('note');
        CRUD::column('origin');
        CRUD::column('date');
        CRUD::column('created_at');
        CRUD::column('updated_at');

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
        CRUD::setValidation(CaringRequest::class);

        CRUD::field('id');
        CRUD::field('staff_id');
        CRUD::field('student_id');
        CRUD::field('note');
        CRUD::field('origin');
        CRUD::field('date');
        CRUD::field('created_at');
        CRUD::field('updated_at');

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

    protected function store()
    {
        $data = $this->crud->getRequest()->request;
        $item = [
            'staff_id' => $data->get("staff_id"),
            'student_id' => $data->get("student_id"),
            'note' => $data->get("note"),
            'origin' => $data->get("origin"),
            'date' => Carbon::parse($data->get('date')),
        ];
        if ($item["note"] != null) {
            Caring::create($item);
        }
        return redirect()->back();
    }
}
