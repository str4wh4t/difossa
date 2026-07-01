<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
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
            ->paginate(12);

        return view('posts.index', [
            'stickyPosts' => $stickyPosts,
            'posts' => $posts,
            'title' => 'Blog',
            'description' => 'Latest articles and updates.',
        ]);
    }

    public function show(Post $post): View
    {
        return view('posts.show', [
            'post' => $post,
            'otherPosts' => Post::recentForSidebar($post->id),
            'title' => $post->meta_title,
            'description' => $post->meta_description ?? $post->excerpt,
        ]);
    }
}
