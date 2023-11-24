<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Footer extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'sections.footer',
        'partials.navigation'
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'socialFB'=>$this->socialFB(),
            'socialIG'=>$this->socialIG(),
            'socialX'=>$this->socialX(),
            'address'=>$this->address(),
            'phoneNum'=>$this->phoneNum(),
            'email'=>$this->email(),
            'getRepeaterButtons'=>$this->getRepeaterButtons(),
            'productPage' => $this->isProductPage(),
            'healthPage' => $this->isHealthProviderPage(),
            'currentPage' => $this->isCurrentPage(),
        ];
    }

    public function address() {
        $address = get_field('contact_office_address','option');

        return $address;
    }

    public function phoneNum() {
        $phoneNum = get_field('contact_office_number','option');

        return $phoneNum;
    }

    public function email() {
        $email = get_field('contact_office_email','option');

        return $email;
    }

    public function getRepeaterButtons() {
        $getRepeaterButtons = get_field('footer_buttons', 'option');

        return $getRepeaterButtons;   
    }

    public function socialFB() {
        $socialFB = get_field('facebook_link', 'option');
        
        return $socialFB;
    }

    public function socialIG() {
        $socialIG = get_field('instagram_link', 'option');

        return $socialIG;
    }

    public function socialX() {
        $socialX = get_field('twitter_link', 'option');

        return $socialX;
    }

    public function isProductPage() {
        $parent_product_page_id = 22;
        $current_page_id = get_the_ID();
        $parent_page_id = wp_get_post_parent_id($current_page_id);

        return $parent_page_id == $parent_product_page_id;
    }

    public function isHealthProviderPage() {
        $health_page_id = 1714;

        return $health_page_id;
    }

    public function isCurrentPage() {
        return get_the_ID();
    }
}
