<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SocialIcons extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $link;
    public $ariaLabel;
    public $title;
    public $path;

    public function __construct($link, $ariaLabel, $title, $path)
    {
        $this->link = $link;
        $this->ariaLabel = $ariaLabel;
        $this->title = $title;
        $this->path = $path;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.social-icons');
    }
}
