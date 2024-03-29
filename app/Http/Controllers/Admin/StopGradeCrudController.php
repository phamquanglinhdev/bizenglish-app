<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StopGradeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StopGradeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StopGradeCrudController extends GradeCrudController
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
        CRUD::setModel(\App\Models\StopGrade::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/stop-grade');
        CRUD::setEntityNameStrings('Lớp đã kết thúc', 'Lớp đã kết thúc');
        CRUD::denyAccess(["create"]);

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        parent::setupListOperation();
        $this->crud->removeFilter("status");
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
        parent::setupCreateOperation($editing);
        CRUD::setValidation(StopGradeRequest::class);

        // CRUD::field('attachment');
        // CRUD::field('created_at');
        // CRUD::field('disable');
        // CRUD::field('id');
        // // CRUD::field('information');
        // CRUD::field('minutes');
        // CRUD::field('name');
        // CRUD::field('pricing');
        // CRUD::field('status');
        // CRUD::field('time');
        // CRUD::field('updated_at');
        // CRUD::field('zoom');

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
}
