<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class JobRelatedVideo extends Model
{
    use HasFactory,AsSource, Attachable;
    public $timestamps = false;
    protected $fillable = [
        'arjob_id',
        'video_file',
        'status'
    ];

}
