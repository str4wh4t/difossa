<?php

namespace App\Http\Controllers;

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

        $posts = Post::query()
            ->published()
            ->where('is_sticky', false)
            ->latest('published_at')
            ->limit(6)
            ->get();

        return view('home', [
            'stickyPosts' => $stickyPosts,
            'posts' => $posts,
            'title' => config('app.name'),
            'description' => null,
        ]);
    }
}
