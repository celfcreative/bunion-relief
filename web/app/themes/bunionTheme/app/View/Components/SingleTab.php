<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SingleTab extends Component
{
    public $tabDescription;
    public $tabImage;
    public $tabTitle;
    public $tabButton;
    public $hasButton;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->tabDescription = get_field('tab_description', $id);
        $this->tabImage = get_the_post_thumbnail_url($id, 'blog-image');
        $this->tabTitle = get_the_title($id);
        $this->tabButton = get_field('available_pages', $id);
        $this->hasButton = get_field('button_true_false', $id);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.single-tab');
    }
}
