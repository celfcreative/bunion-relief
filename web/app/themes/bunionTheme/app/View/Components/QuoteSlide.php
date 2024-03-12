<?php

namespace App\View\Components;

use Illuminate\View\Component;

class QuoteSlide extends Component
{

    public $location;
    public $locationline2;
    public $quote;
    public $name;
    public $image;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->location = get_field('location', $id);
        $this->locationline2 = get_field('location-line-2', $id);
        $this->quote = get_field('quote', $id);
        $this->name = get_the_title($id);
        $this->image = get_the_post_thumbnail_url($id, '500-image');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.quote-slide');
    }
}
