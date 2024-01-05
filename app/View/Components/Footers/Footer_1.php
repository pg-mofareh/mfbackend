<?php

namespace App\View\Components\Footers;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer_1 extends Component
{
    public $bgColor;
    public function __construct($bgColor="#ededed")
    {
        $this->bgColor = $bgColor;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.footers.footer_1');
    }
}
