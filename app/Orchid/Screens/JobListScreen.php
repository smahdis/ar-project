<?php

namespace App\Orchid\Screens;

use App\Models\Arjob;
use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class JobListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $jobs = Arjob::where('user_id', '=',auth()->user()->id)->latest()->paginate();

        return [
            'jobs' => $jobs
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Jobs';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
//            ModalToggle::make('Add')
//                ->modal('categoryModal')
//                ->method('create')
//                ->icon('plus'),
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.job.edit')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [


            Layout::table('jobs', [

                TD::make('image', __("Image"))
                    ->width('100')
                    ->render(fn (Arjob $model) => // Please use view('path')
                    $model->photo ? "<img src='{$model->photo}'
                              alt='sample'
                              style='object-fit: cover;width: 50px!important; height: 50px!important;'
                              class='mw-100 d-block img-fluid rounded-circle w-100 '>" : ""),

                TD::make('title')
                    ->render(function (Arjob $job) {
                        return Link::make($job->title)
                            ->route('platform.job.edit', $job);
                    }),

                TD::make('generated_id', 'Url')
                    ->render(function (Arjob $job) {
                        return Link::make(strtoupper($job->generated_id))
                            ->icon('link')
                            ->href('/ar/job/' . $job->generated_id);
                    }),

                TD::make('created_at', __('Created'))
                    ->render(fn (Arjob $model) => $model->created_at->toDateString()),

                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(fn (Arjob $model) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm(__('Once the Job is deleted, all of its resources and data will be permanently deleted.'))
                                ->method('delete', [
                                    'id' => $model->id,
                                ]),
                        ])),
            ]),



        ];
    }

    /**
     * @param Arjob $job
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete($id)
    {
        Arjob::destroy($id);
        Alert::info('You have successfully deleted the item.');
        return redirect()->route('platform.job.list');
    }

}
