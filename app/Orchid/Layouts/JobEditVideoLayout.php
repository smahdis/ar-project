<?php

namespace App\Orchid\Layouts;

use App\Models\Group;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\TD;

class JobEditVideoLayout extends Rows
{

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function fields(): iterable
    {
//        $query_exits = $this->query->get('job')->exists;
        return [

            Matrix::make('job.related_videos')
                ->title('Related Videos')
                ->columns(['Video', 'Width', 'Height'])
                ->fields([

                    'Video' => Upload::make()
//                        ->title('Video File')
                        ->maxFiles(1)
//                        ->style(["border" => "1px solid #000"])
                        ->horizontal(),

                    'Width_Aspect' => Input::make()
                        ->required()
                        ->title(__('Width aspect ratio'))
                        ->value(1)
                        ->placeholder(__('Job Title')),

                    'Height_Aspect' => Input::make()
                        ->required()
                        ->title(__('Height aspect ratio'))
                        ->value(1)
                        ->placeholder(__('Job Title')),

                ]),


        ];
    }
}
