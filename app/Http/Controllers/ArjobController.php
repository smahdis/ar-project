<?php

namespace App\Http\Controllers;

use App\Models\Arjob;
use Illuminate\Http\Request;

class ArjobController extends Controller
{
    public function index(Request $request, $code){
        $job = Arjob::where('generated_id', $code)->where('status', 1)->firstOrFail();
        $job->load('attachment');
//        var_dump(json_encode($job->attachment()->where('attachments.id', $job->video)->first()));
//        die();
        return view('index',compact('job'))->with([
//                "search_text" => $search_text
            ]
        );
    }
}
