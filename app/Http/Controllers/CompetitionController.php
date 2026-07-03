<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompetitionController extends Controller
{
    public function index(): View
    {
        $competitions = Competition::query()
            ->publiclyVisible()
            ->with(['status'])
            ->newestFirst()
            ->paginate(10);

        return view('competitions.index', [
            'competitions' => $competitions,
            'title' => 'Competitions',
            'description' => 'Explore open competitions, categories, and registration deadlines.',
        ]);
    }

    public function show(Competition $competition): View
    {
        $competition->load('status');

        $plainDescription = trim(strip_tags((string) $competition->description));

        return view('competitions.show', [
            'competition' => $competition,
            'otherCompetitions' => Competition::recentForSidebar($competition->id),
            'title' => $competition->title,
            'description' => filled($plainDescription)
                ? Str::limit($plainDescription, 160)
                : 'Competition details and registration information.',
        ]);
    }
}
