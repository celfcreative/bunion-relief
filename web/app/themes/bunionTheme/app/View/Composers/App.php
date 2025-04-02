<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'siteName' => $this->siteName(),
            'logo' => $this->getSiteLogo(),
            'footerLogo' => $this->getFooterLogo(),
        ];
    }

    /**
     * Returns the site name.
     *
     * @return string
     */
    public function siteName()
    {
        return get_bloginfo('name', 'display');
    }

    public function getSiteLogo()
    {
        $hasLogo = get_theme_mod('custom_logo');

        if ($hasLogo) {
            $logo = wp_get_attachment_image_src($hasLogo, 'full');

            if ($logo) {
                return $logo[0];
            }
        }

        return null;
    }

    public function getFooterLogo()
    {
        $hasLogo = get_theme_mod('footer_logo');

        if ($hasLogo) {
            return $hasLogo;
        }

        return null;
    }
}
