<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Magazine;

class MagazineSection extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $magazines = Magazine::orderBy('pub_date', 'desc')->take(6)->get();
        return view('components.magazine-section', compact('magazines'));
    }
}
