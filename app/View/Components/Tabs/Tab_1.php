<?php

namespace App\View\Components\Tabs;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tab_1 extends Component
{

    public $categories;
    
    public function __construct($categories=[])
    {
        $this->categories = $categories;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tabs.tab_1');
    }
}
