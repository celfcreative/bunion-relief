<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use Log1x\Navi\Navi;

class Navigation extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'partials.navigation',
        'partials.footer',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'navigation' => $this->getNavigation(),
            'footer' => $this->getFooter(),
            'productPage' => $this->isProductPage(),
            'healthPage' => $this->isHealthProviderPage(),
            'currentPage' => $this->isCurrentPage(),
        ];
    }

    public function getNavigation() {
        $navigation = (new Navi())->build('primary_navigation');

        if ($navigation->isEmpty()) {
            return;
        }
        return $navigation->toArray();
    }
    public function getFooter() {
        $footer = (new Navi())->build('footer');

        if ($footer->isEmpty()) {
            return;
        } 
        return $footer->toArray();
    }

    public function isProductPage() {
        $parent_product_page_id = 22;
        $current_page_id = get_the_ID();
        $parent_page_id = wp_get_post_parent_id($current_page_id);

        return $parent_page_id == $parent_product_page_id;
    }

    public function isHealthProviderPage() {
        $health_page_id = 1714;
        $current_page_id = get_the_ID();

        return $health_page_id;
    }
    
    public function isCurrentPage() {
        return get_the_ID();
    }
}
