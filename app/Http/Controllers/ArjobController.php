<?php

namespace App\Http\Controllers;

use App\Models\Arjob;
use App\Models\JobRelatedVideo;
use Illuminate\Http\Request;

class ArjobController extends Controller
{
    public function index(Request $request, $code){
        $job = Arjob::where('generated_id', $code)->where('status', 1)->firstOrFail();
        $job_related_videos = JobRelatedVideo::where('arjob_id', $job->id)->where('status', 1)->get();
        $job['videos'] = $job_related_videos;
        $job->load('attachment');
        $job_related_videos->load('attachment');
//        var_dump($job_related_videos[0]['video_file']);
//        var_dump(json_encode($job));
//        var_dump(json_encode($job_related_videos[0]->attachment()->where('attachments.id', $job_related_videos[0]['video_file'])->first()));
//        die();
        return view('index',compact('job'))->with([
//                "search_text" => $search_text
            ]
        );
    }
}
