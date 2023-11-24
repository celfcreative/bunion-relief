<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Tab extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Tab';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Tab block.';

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
    public $icon = 'editor-ul';

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
    public $post_types = [];

    /**
     * The parent block type allow list.
     *
     * @var array
     */
    public $parent = ['acf/tabs'];

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
            'tabTitle' => $this->getTabTitle(),
            'tabContent' => $this->getTabContent(),
            'tabImage' => $this->getTabImage(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $tab = new FieldsBuilder('tab');

        $tab
            ->addText('tab_title', [
                'label' => 'Tab Title',
                'required' => 0,
            ])

            ->addTextarea('tab_content', [
                'label' => 'Tab Content',
                'required' => 1,
            ])

            ->addImage('tab_image', [
                'label' => 'Tab Image',
                'required' => 1,
                'preview_size' => 'blog'
            ]);

        return $tab->build();
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

    public function getTabTitle()
    {

        $tabTitle = get_field('tab_title');

        return isset($tabTitle) ? $tabTitle : null;
    }

    public function getTabContent()
    {

        $tabContent = get_field('tab_content');

        return isset($tabContent) ? $tabContent : null;
    }

    public function getTabImage()
    {
        $image = get_field('tab_image');

        if ($image && isset($image['sizes']['blog-image'])) {
            return $image['sizes']['blog-image'];
        } else {
            return null;
        }
    }
}
