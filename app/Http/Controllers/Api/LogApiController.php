<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Teacher;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class LogApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?? 30;
        $data = [];
        $user = $request->user();
        $logs = Log::where("disable", 0)->orderBy("created_at", "DESC")->limit($limit)->get();
        foreach ($logs as $log) {
            $item = new \stdClass();
            $item->id = $log->id;
            $item->date = $log->date;
            $item->start = $log->start;
            $item->end = $log->end;
            $item->grade = $log->Grade()->first(["id", "name"]);
            $item->students = $log->Grade()->first()->Student()->get(["id", "name"]);
            $item->teachers = Teacher::where("id", $log->teacher_id)->first(["id", "name"]);
            $item->clients = $log->Grade()->first()->Client()->get(["id", "name"]);
            $item->lesson = $log->lesson;
            $item->video = $log->teacher_video;
            $item->duration = $log->duration;
            $item->logSalary = $log->log_salary;
            $item->hourSalary = $log->hour_salary;
            $item->status = $log->StatusShow();
            $item->assessment = $log->assessment;
            $item->attachments = $log->attachments;
            $data[] = $item;
        }
        return \response()->json($data, 200);
    }

    public function show(Request $request)
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        //
    }

    public function upload(Request $request)
    {
        $data = [];
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            foreach ($attachments as $attachment) {
                $name = $attachment->getClientOriginalName();
                $ext = $attachment->getClientOriginalExtension();
                $orName = substr(str_replace(".$ext", "", $name), 0, 15) . ".$ext";
//                dd($orName);
                $newFile = Storage::disk('uploads_document')->put("", $attachment);
                Storage::disk("uploads_document")->move($newFile, $orName);
                $data[] = $orName;
            }
            return redirect("/app/upload")->with("data", $data);
        }
        return redirect("/app/upload")->with("data", null);
    }
}
