<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class Arjob extends Model
{
    use HasFactory,AsSource, Attachable;

//    public mixed $generated_id;
    protected $fillable = [
        'title',
        'photo',
        'video',
        'width_aspect',
        'height_aspect',
        'user_id',
        'mind_file',
        'generated_id',
    ];

    public static function generateId(int $length = 8): string
    {
        $generated_id = Str::random($length);//Generate random string
        $generated_id = strtolower($generated_id);

        $exists = DB::table('arjobs')
            ->where('generated_id', '=', $generated_id)
            ->get(['id']);//Find matches for id = generated id

        if (isset($exists[0]->id)) {//id exists in users table
            return self::generateId();//Retry with another generated id
        }

        return $generated_id;//Return the generated id as it does not exist in the DB
    }
}
