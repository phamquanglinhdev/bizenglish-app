<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LogRequest;
use App\Models\Grade;
use App\Models\Log;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use http\Env\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class LogCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LogCrudController extends CrudController
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

        CRUD::setModel(Log::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/log');
        CRUD::setEntityNameStrings("Nhật ký","Nhật ký");
        $this->crud->addButtonFromModelFunction("line", "detail", "detail", "line");
        if(backpack_user()->type==3){
            $this->crud->addButtonFromModelFunction("line", "pushExercise", "pushExercise", "line");
        }
        $this->crud->denyAccess(["show"]);
        if (backpack_user()->type != 1) {
            $this->crud->denyAccess(["update", "create", "delete"]);
        }

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        if(isset($_REQUEST["grade_id"])){
            $grade = Grade::find(($_REQUEST["grade_id"]));
            CRUD::setEntityNameStrings("Nhật ký","Lớp ".$grade->name);
            Widget::add([
                'type'     => 'view',
                'view'     => 'test',
                'grade' => $grade,
            ]);
            $this->crud->addClause("where","grade_id",$grade->id);
        }

        if (backpack_user()->type == 3) {
            $this->crud->addClause('rep');
        }
        CRUD::addColumn([
            'name' => 'grade_id',
            'type' => 'select',
            'entity' => 'Grade',
            'model' => "App\Model\Grade",
            'attribute' => 'name',
            'label' => "Lớp",
        ]);

        CRUD::addColumn([
            'name' => 'teacher_id',
            'type' => 'select',
            'entity' => 'Teacher',
            'model' => "App\Model\Teacher",
            'attribute' => 'name',
            'label' => "Giáo viên dạy",
        ]);
        CRUD::column('duration')->label("Thời gian dạy");
        CRUD::column('lesson')->label("Bài học");
        CRUD::column('hour_salary')->label("Lương theo giờ (đ)")->type("number");
        CRUD::column('teacher_video')->label("Video bài giảng")->type("open");

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
        CRUD::setValidation(LogRequest::class);

        CRUD::addField([
            'name' => 'grade_id',
            'type' => 'select2',
            'entity' => 'Grade',
            'model' => "App\Models\Grade",
            'attribute' => 'name',
            'label' => "Lớp",
        ]);
        CRUD::addField([
            'name' => 'teacher_id',
            'value' => backpack_user()->id,
            'type' => 'hidden',
        ]);
        CRUD::field('time')->label("Thời gian")->type("datetime");
        CRUD::field('duration')->label("Thời gian dạy(Phút)");
        CRUD::field('lesson')->label("Bài học");
        CRUD::field('information')->label("Nội dung")->type("tinymce");
        CRUD::field('hour_salary')->label("Lương theo giờ");
        CRUD::addField(
            [
                'name' => 'teacher_video',
                'label' => 'Video bài giảng',
                'type' => 'upload',
                'upload' => true,
                'disk' => 'uploads_video',
            ]);
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
        if (Log::find($id)) {
            return view("log-detail", ['log' => Log::find($id)]);
        }
        return view("errors.404");
    }

    public function acceptByStudent(\Illuminate\Http\Request $request)
    {
        $id = backpack_user()->id;
        $isExist = DB::table("student_log")->where("log_id", $request->log_id)->where("student_id", $id)->count();
        if ($isExist == 0) {
            DB::table("student_log")->insert([
                'student_id' => $id,
                'log_id' => $request->log_id,
                'accept' => $request->accept??0,
                'comment' => $request->comment,
            ]);
        } else {
            return redirect()->back()->with("message","Đã xác nhận rồi");
        }
        return redirect()->back()->with("message","Xác nhận thành công");
    }
}
