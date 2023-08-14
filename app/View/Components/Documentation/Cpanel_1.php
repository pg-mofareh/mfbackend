<?php

namespace App\View\Components\Dashboard;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Cpanel_1 extends Component
{
    public $page;
    public function __construct($page = "")
    {
        $this->page = $page;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.documentation.cpanel-1');
    }
}
