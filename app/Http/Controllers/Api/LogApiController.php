<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class LogApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //
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
