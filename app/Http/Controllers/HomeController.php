<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $stickyPosts = Post::query()
            ->published()
            ->sticky()
            ->latest('published_at')
            ->get();

        $latestPosts = Post::query()
            ->published()
            ->where('is_sticky', false)
            ->latest('published_at')
            ->limit(4)
            ->get();

        $competitions = Competition::query()
            ->openForRegistration()
            ->with(['status'])
            ->newestFirst()
            ->limit(1)
            ->get();

        return view('home', [
            'stickyPosts' => $stickyPosts,
            'featuredPost' => $latestPosts->first(),
            'recentPosts' => $latestPosts->skip(1)->values(),
            'competitions' => $competitions,
            'title' => config('app.name'),
            'description' => null,
        ]);
    }
}
