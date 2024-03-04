<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class JobEditLayout extends Rows
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
            Input::make('job.title')
                ->required()
                ->title(__('Title'))
                ->placeholder(__('Job Title'))
                ->help(__('Specify a short descriptive title for this job.')),


            Cropper::make('job.photo')
                ->required()
                ->title(__('Main Image'))
//                ->help(__('The main category image that is shown in homepage'))
//                ->width(844)
//                ->height(443)
                ->targetRelativeUrl(),

            Upload::make('job.mind_file')
                ->title(__('Mind File'))
                ->maxFiles(1)
                ->horizontal(),

            Upload::make('job.video')
                ->title('Video File')
                ->multiple(false)
                ->maxFiles(1)
                ->horizontal(),

            Input::make('job.width_aspect')
                ->required()
                ->title(__('Width aspect ratio'))
                ->value(1)
                ->placeholder(__('Job Title')),

            Input::make('job.height_aspect')
                ->required()
                ->title(__('Height aspect ratio'))
                ->value(1)
                ->placeholder(__('Job Title')),


        ];
    }
}
