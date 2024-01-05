<?php

namespace App\View\Components\Navbars;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar_1 extends Component
{


    public function __construct()
    {
       
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.navbars.navbar-1');
    }
}
