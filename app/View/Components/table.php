<?php

namespace App\View\Components;

use App\Models\Student;
use Illuminate\View\Component;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
class table extends Component
{
    /**
     * Create a new component instance.
     *Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
     * @return void
     */
    public function __construct()
    {
        $this->crud = CRUD::setModel(Student::class);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table',$this->crud);
    }
}
