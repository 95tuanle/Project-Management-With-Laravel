<?php

namespace App\Http\Controllers;

use App\Events\ProjectCreated;
use App\Services\Twitter;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use App\Project;

class ProjectsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // C
    public function create() {
        return view('projects.create');
    }

    public function store() {
        $project = Project::create(
            $this->validateProject() + ['owner_id' => auth()->id()]
        );
//        $project = new Project();
//        $project->title = request('title');
//        $project->description = request('description');
//        $project->save();

//        event(new ProjectCreated($project));
        return redirect('/projects');
    }

    // R
    public function index() {
//        $projects = Project::where('owner_id', auth()->id())->get();

//        $projects = auth()->user()-projects;
//
//        return view('projects.index', compact('projects'));

        return view('projects.index', [
           'projects' => auth()->user()->projects
        ]);
    }

//    public function show(Filesystem $filesystem) {
//        dd($filesystem);
//    }

    public function show(Project $project) {
        $this->authorize('update', $project);

//        auth()->user()->can('update', $project);

//        abort_if(\Gate::denies('update', $project), 403);

//        abort_if(!auth()->user()->owns($project), 403);

//        abort_if($project->owner_id !== auth()->id(), 403);
        return view('projects.show', compact('project'));
    }

    // U
    public function edit(Project $project) {
        return view('projects.edit', compact('project'));
    }

    public function update(Project $project) {

        $this->authorize('update', $project);
        $project->update($this->validateProject());
//        $project->title = request('title');
//        $project->description = request('description');
//        $project->save();
        return view('projects.show', compact('project'));
    }

    // D
    public function destroy(Project $project) {
        $project->delete();
        return redirect('/projects');
    }

    protected function validateProject() {
        return request()->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:3']
        ]);
    }
}
