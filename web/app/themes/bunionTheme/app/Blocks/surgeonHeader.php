<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use Log1x\SageSvg\SageSvg;

use WP_Query;

class surgeonHeader extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Surgeon Header';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Surgeon Header block.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'formatting';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
  </svg>';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = [];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = ['wpsl_stores'];

    /**
     * The parent block type allow list.
     *
     * @var array
     */
    public $parent = [];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = '';

    /**
     * The default block text alignment.
     *
     * @var string
     */
    public $align_text = '';

    /**
     * The default block content alignment.
     *
     * @var string
     */
    public $align_content = '';

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => true,
        'align_text' => false,
        'align_content' => false,
        'full_height' => false,
        'anchor' => false,
        'mode' => false,
        'multiple' => true,
        'jsx' => true,
    ];

    /**
     * The block styles.
     *
     * @var array
     */
    public $styles = [
        [
            'name' => 'light',
            'label' => 'Light',
            'isDefault' => true,
        ],
        [
            'name' => 'dark',
            'label' => 'Dark',
        ]
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'surgeons' => $this->getSurgeons(),
            'surgeonName' => $this->getSurgeonName(),
            'surgeonPhone' => $this->getSurgeonPhone(),
            'surgeonURL' => $this->getSurgeonURL(),
            'surgeonImage' => $this->getSurgeonImage(),
            'surgeonCategory' => $this->getSurgeonCategory(),
            'formDescription' => $this->getFormDescription(),
            'formIcon' => $this->getFormSVG(),
            'surgeonAddress1' => $this->getSurgeonAddress1(),
            'surgeonAddress2' => $this->getSurgeonAddress2(),
            'surgeonState' => $this->getSurgeonState(),
            'surgeonCountry' => $this->getSurgeonCountry(),
            'surgeonCity' => $this->getSurgeonCity(),
            'surgeonZip' => $this->getSurgeonZip(),
            'surgeonDescription' => $this->getSurgeonDescription(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $surgeonHeader = new FieldsBuilder('surgeon_header', ['position' => 'bottom']);

        $surgeonHeader
            ->addTextarea('surgeon_description', [
                'label' => 'Surgeon Description',
                'instructions' => '',
                'required' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => 'br', // Possible values are 'wpautop', 'br', or ''.
            ]);

        return $surgeonHeader->build();
    }

    /**
     * Assets to be enqueued when rendering the block.
     *
     * @return void
     */
    public function enqueue()
    {
        //
    }

    public function getSurgeons()
    {
        return get_field('surgeons');
    }
    public function getSurgeonPhone()
    {

        $surgeonPhone = get_post_meta(get_the_ID(), 'wpsl_phone', true);

        return isset($surgeonPhone) ? $surgeonPhone : null;
    }

    public function getSurgeonURL()
    {

        $surgeonURL = get_post_meta(get_the_ID(), 'wpsl_url', true);

        return isset($surgeonURL) ? $surgeonURL : null;
    }

    public function getFormDescription()
    {

        $formDescription = get_field('form_description', 'option');

        return isset($formDescription) ? $formDescription : null;
    }

    public function getFormSVG()
    {
        $formIcon = \App(SageSvg::class)->render('images.human-form', 'w-75');

        return $formIcon;
    }

    public function getSurgeonImage()
    {
        $surgeonImage = get_the_post_thumbnail(null, 'surgeon-image', ['class' => 'object-fit-cover']);

        return $surgeonImage;
    }

    public function getSurgeonName()
    {
        $surgeonName = get_the_title();

        return $surgeonName;
    }

    public function getSurgeonCategory()
    {
        $surgeonCategory = get_the_terms(get_the_ID(), 'wpsl_store_category');

        return $surgeonCategory;
    }

    public function getSurgeonAddress1()
    {
        return (get_post_meta(get_the_ID(), 'wpsl_address', true));
    }
    public function getSurgeonAddress2()
    {
        return (get_post_meta(get_the_ID(), 'wpsl_address2', true));
    }
    public function getSurgeonState()
    {
        return (get_post_meta(get_the_ID(), 'wpsl_state', true));
    }

    public function getSurgeonCountry()
    {
        return (get_post_meta(get_the_ID(), 'wpsl_country', true));
    }

    public function getSurgeonCity()
    {
        return (get_post_meta(get_the_ID(), 'wpsl_city', true));
    }
    public function getSurgeonZip()
    {
        return (get_post_meta(get_the_ID(), 'wpsl_zip', true));
    }
    public function getSurgeonDescription() {
        return get_field('surgeon_description');
    }
}
