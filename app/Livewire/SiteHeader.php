<?php

namespace App\Livewire;

use App\Models\Menu;
use Livewire\Component;

class SiteHeader extends Component
{
    public function render()
    {
        return view('livewire.site-header', [
            'headerMenu' => Menu::findBySlugWithItems('header'),
        ]);
    }
}
