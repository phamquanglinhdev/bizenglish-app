<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Exception;

class EditableController extends Controller
{

    public function update(Request $request)
    {
        $id = $request->id ?? null;
        $name = $request->name ?? null;
        $value = $request->value ?? null;
        $db = $request->model ?? null;
        if ($id != null && $name != null && $value != null && $db != null) {
            try {
                DB::table($db)->where("id", $id)->update([
                    $name => $value
                ]);
                return response()->json(['message' => "Thành công"], 200);
            } catch (Exception $exception) {
                return response()->json(['message' => $exception->getMessage()], 200);
            }
        }

    }
}
