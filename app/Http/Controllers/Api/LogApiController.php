<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Log;
use App\Models\Teacher;
use Carbon\Carbon;
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
        $page = $request->page ?? 1;
        $data = [];
        $user = $request->user();
        $logs = Log::where("disable", 0)->orderBy("created_at", "DESC")->skip(($page - 1) * 20 + 1)->take(20)->get();
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

    public function create()
    {
        $grades = Grade::where("disable", 0)->orderBy("name", "ASC")->get(["id", "name"]);
        foreach ($grades as $grade) {
            $grade->teachers = $grade->Teacher()->get(["id", "name"]);
        }
        $teachers = Teacher::where("disable", 0)->where("type", 1)->orderBy("name", "ASC")->get(["id", "name"]);
        return \response()->json(["grades" => $grades], 200);
    }

    public function show(Request $request)
    {
        //
    }
//grade: grade,
//teacher: teacher,
//date: date,
//start: start,
//end: end,
//duration: duration,
//hourSalary: hourSalary,
//logSalary: logSalary,
//lesson: lesson,
//information: information,
//video: video,
//status: status,
//assessment: assessment,
//question: question,
//attachments: attachments,
    public function store(Request $request)
    {
        $data = [
            'grade_id' => $request->grade ?? null,
            'teacher_id' => $request->teacher ?? null,
            'date' => Carbon::make($request->date) ?? null,
            'start' => $request->start ?? null,
            'end' => $request->end ?? null,
            'duration' => $request->duration ?? null,
            'hour_salary' => $request->hourSalary ?? null,
            'log_salary' => $request->logSalary ?? null,
            'lesson' => $request->lesson ?? null,
            'information' => $request->information ?? null,
            'teacher_video' => json_encode($request->video) ?? null,
            'status' => $request->status ?? null,
            'assessment' => $request->assessment ?? null,
            'attachments' => json_encode($request->attachments) ?? null,
        ];
        try {
            return $data["attachments"][0];
//            Log::create($data);
//            return \response()->json(["message" => "Thành công"], 200);
        } catch (\Exception $exception) {
            return $exception;
        }

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
