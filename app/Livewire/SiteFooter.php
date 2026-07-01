<?php

namespace App\Livewire;

use App\Models\Menu;
use Livewire\Component;

class SiteFooter extends Component
{
    public function render()
    {
        return view('livewire.site-footer', [
            'footerMenu' => Menu::findBySlugWithItems('footer'),
        ]);
    }
}
