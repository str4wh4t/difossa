<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(Page $page): View
    {
        return view('pages.show', [
            'page' => $page,
            'otherPosts' => Post::recentForSidebar(),
            'title' => $page->meta_title,
            'description' => $page->meta_description,
        ]);
    }
}
