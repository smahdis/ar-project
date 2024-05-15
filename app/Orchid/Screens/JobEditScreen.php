<?php

namespace App\Orchid\Screens;

use App\Models\Arjob;
use App\Models\Category;
use App\Models\Company;
use App\Models\Event;
use App\Models\EventRelatedGroup;
use App\Models\JobRelatedVideo;
use App\Orchid\Layouts\Category\CategoryEditLayout;
use App\Orchid\Layouts\Category\CategoryEditTranslationEnglishLayout;
use App\Orchid\Layouts\Category\CategoryEditTranslationGeorgianLayout;
use App\Orchid\Layouts\JobEditLayout;
use App\Orchid\Layouts\JobEditVideoLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class JobEditScreen extends Screen
{
    /**
     * @var Arjob
     */
    public $job;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Arjob $job): iterable
    {
        $job->load('attachment');
        return [
            'job' => $job
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->job->exists ? __('Edit job') : __('Creating a new job');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->job->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->job->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->job->exists),
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
            Layout::block([
                JobEditLayout::class
            ])
                ->title(__('Ar Jobs'))
                ->description(__('These info are shown in job\'s details page and they require translation.')),
            JobEditVideoLayout::class,
        ];
    }

    /**
     * Remove the first and last quote from a quoted string of text
     *
     * @param mixed $text
     */
    function stripQuotes($text) {
        return preg_replace('/^(\'[^\']*\'|"[^"]*")$/', '$2$3', $text);
    }


    /**
     * @param Arjob $job
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function createOrUpdate(Request $request): RedirectResponse
    {

        $request->validate([
            'job.title' => 'required|max:255',
//            'job.width_aspect' => 'required',
//            'job.height_aspect' => 'required',
            'job.photo' => 'required|max:255',
//            'job.video' => 'required|max:255',
        ]);

        $data = $request->get('job');
        $data['video'] = isset($data['video']) ? $data['video'][0] : "";
        $data['mind_file'] = isset($data['mind_file']) ? $data['mind_file'][0] : "";
        $data['user_id'] = auth()->user()->id;


        $data['related_videos'] = array_values($data['related_videos']);
//        $data['related_videos'] = substr($dataList, 1, -1);

//        var_dump($data['related_videos']);
//        var_dump(json_encode($data['related_videos']), true);
//        die();

//        var_dump(trim($data['related_videos'],'"'));
//        die();


        $this->job->fill($data)->save();
//        $this->job->attachment()->syncWithoutDetaching(
//            $request->input('job.video', [])
//        );
        $this->job->attachment()->syncWithoutDetaching(
            $request->input('job.mind_file', [])
        );

        if(!isset($this->job->generated_id)){
            $this->job->generated_id = $this->job::generateId();
            $this->job->save();
        }



        if($this->job->wasChanged('related_videos')) {
//        if(!empty($data['related_videos'])) {
            JobRelatedVideo::where('arjob_id', $this->job->id)
                ->update([
                    'status' => -1
                ]);
            foreach ($data['related_videos'] as $related_video) {
//                var_dump($related_video['Width']);
//                foreach ($related_video['To'] as $to_group) {
                    $jrv = JobRelatedVideo::create([
                        'arjob_id' => $this->job->id,
                        'video_file' => isset($related_video['Video']) ? $related_video['Video'][0] : "",
                        'width_aspect' => isset($related_video['Width']) ? $related_video['Width'] : "",
                        'height_aspect' => isset($related_video['Height']) ? $related_video['Height'] : "",
                    ]);
                    if(isset($related_video['Video'])) {
                        $jrv->attachment()->syncWithoutDetaching(
                            $related_video['Video'][0]
                        );
                    }
//                }
            }
//            die();

        }

        Alert::info('You have successfully updated/created the job.');

        return redirect()->route('platform.job.list');

    }

    /**
     * @return RedirectResponse
     */
    public function remove(): RedirectResponse
    {
        $this->job->delete();

        Alert::info('You have successfully deleted the job.');

        return redirect()->route('platform.job.list');
    }
}
