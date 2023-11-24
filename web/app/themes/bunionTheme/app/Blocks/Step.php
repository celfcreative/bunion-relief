<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Step extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Step';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Step block.';

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
    public $parent = ['acf/stepper'];

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
            'stepTitle' => $this->getStepTitle(),
            'stepContent' => $this->getStepContent(),
            'stepImage' => $this->getStepImage(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $step = new FieldsBuilder('step');

        $step
            ->addText('step_title', [
                'label' => 'Step Title',
                'required' => 1,
            ])
            ->addTextarea('step_content', [
                'label' => 'Step Content',
                'required' => 1,
            ])
            ->addImage('step_image', [
                'label' => 'Step Image',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => [],
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'preview_size' => 'thumbnail',
                'library' => 'all',
            ]);

        return $step->build();
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

    /**
     * get Custom Field step_title
     */
    public function getStepTitle() {

        $stepTitle = get_field('step_title');

        return isset($stepTitle) ? $stepTitle : null;
    }

    /**
     * get Custom Field step_content
     */
    public function getStepContent() {

        $stepContent = get_field('step_content');

        return isset($stepContent) ? $stepContent : null;
    }

    public function getStepImage() {

        $stepImage = get_field('step_image');

        return isset($stepImage) ? $stepImage : null;
    }
}
