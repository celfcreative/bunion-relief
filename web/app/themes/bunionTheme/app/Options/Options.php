<?php

namespace App\Options;

use Log1x\AcfComposer\Options as Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Options extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Options';

    /**
     * The option page document title.
     *
     * @var string
     */
    public $title = 'Options';

    /**
     * The option page field group.
     *
     * @return array
     */
    public function fields()
    {
        $options = new FieldsBuilder('options');

        // social
        $options
        ->addTab('site_socials', [
            'label' => 'Social Medias'
        ])
        // Facebook
        ->addUrl('facebook_link', ['label' => 'Facebook'])
        // Instagram
        ->addUrl('instagram_link', ['label' => 'Instagram'])
        // Twitter
        ->addUrl('twitter_link', ['label' => 'Twitter']);


        // address
        $options
        ->addTab('site_contact', ['label' => 'Contact Details'])
        ->addTextArea('contact_office_address', ['label' => 'Address', 'new_lines' => 'br'])
        ->addText('contact_office_number', ['label' => 'Number'])
        ->addText('contact_office_email', ['label' => 'Email']);
        
        // buttons
        $options
        ->addTab('site_button', ['label'=>'Footer Buttons'])
        ->addRepeater('footer_buttons', ['label' => 'Buttons', 'layout' => 'block'])
        ->addText('footer_button', ['label' => 'Button'])
        ->addUrl('footer_button_link', ['label' => 'Link']);

        $options
        ->addTab('site_citation', ['label'=>'Citation Button'])
        ->addText('citation_button', ['label' => 'Button'])
        ->addUrl('citation_button_link', ['label' => 'Link']);

        $options
        ->addTab('site_health_provider', ['label'=>'Health Provider Button'])
        ->addText('provider_button', ['label' => 'Button Name'])
        ->addUrl('provider_button_link', ['label' => 'Link']);

        $options
        ->addTab('site_form', ['label'=>'Contact Form Description'])
        ->addText('form_description', ['label'=>'Form Description']);

        $options
        ->addTab('site_tracking', ['label'=>'Tracking'])
        ->addTextarea('header_tracker', ['label'=>'Header'])
        ->addTextarea('body_tracker', ['label'=>'Body'])
        ->addTextarea('footer_tracker', ['label'=>'Footer']);

        $options
        ->addTab('site_api', ['label'=>'Constant Contact API'])
        ->addTextArea('constant_contact_token', ['label'=>'Constant Contact Access Token'])
        ->addTextArea('constant_contact_refresh_token', ['label'=>'Constant Contact API Refresh Token']);

        $options
        ->addTab('authorization_constant', ['label'=>'Constant Contact Authorization Code'])
        ->addTextArea('constant_contact_authorization_code', ['label'=>'Constant Contact Authorization Code']);

        // scripts **later
        return $options->build();
    }
}
