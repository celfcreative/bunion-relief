<?php

namespace App\View\Components;

use Illuminate\View\Component;
use WP_Query;

class Card extends Component
{
    public $title;
    public $description;
    public $image;
    public $link;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $description, $image, $link)
    {
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card');
    }
}
