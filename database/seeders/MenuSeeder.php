<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['name' => 'Header', 'slug' => 'header'],
            ['name' => 'Footer', 'slug' => 'footer'],
        ];

        foreach ($menus as $menu) {
            Menu::query()->updateOrCreate(
                ['slug' => $menu['slug']],
                $menu,
            );
        }
    }
}
