<?php

namespace App\Orchid\Screens;

use App\Models\Arjob;
use App\Models\Category;
use App\Models\Company;
use App\Models\Event;
use App\Orchid\Layouts\Category\CategoryEditLayout;
use App\Orchid\Layouts\Category\CategoryEditTranslationEnglishLayout;
use App\Orchid\Layouts\Category\CategoryEditTranslationGeorgianLayout;
use App\Orchid\Layouts\JobEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
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

//            Layout::block([
//                CategoryEditLayout::class,
//            ])
//                ->title(__('Other Category Info'))
//                ->description(__('These section is common between all languages and does not require translation.'))

        ];
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
            'job.width_aspect' => 'required',
            'job.height_aspect' => 'required',
            'job.photo' => 'required|max:255',
//            'job.video' => 'required|max:255',
        ]);

        $data = $request->get('job');
        $data['video'] = isset($data['video']) ? $data['video'][0] : "";
        $data['mind_file'] = isset($data['mind_file']) ? $data['mind_file'][0] : "";
        $data['user_id'] = auth()->user()->id;

        $this->job->fill($data)->save();
        $this->job->attachment()->syncWithoutDetaching(
            $request->input('job.video', [])
        );
        $this->job->attachment()->syncWithoutDetaching(
            $request->input('job.mind_file', [])
        );

        if(!isset($this->job->generated_id)){
            $this->job->generated_id = $this->job::generateId();
            $this->job->save();
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
