<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Stepper extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Stepper';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Stepper block.';

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
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'allowedBlocks' => json_encode(['acf/step']),
            'template' => json_encode([['acf/step'], ['acf/step'], ['acf/step']]),
            'step' => $this->getStepContent(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $stepper = new FieldsBuilder('stepper');

        $stepper
        ->addRepeater('step_content', [
            'label' => 'Step Content'
        ])
            ->addText('step_title', [
                'label' => 'Title'
            ])
            ->addTextarea('step_description', [
                'label' => 'Description'
            ])
            ->addImage('step_image', [
                'label' => 'Image'
            ])
        ->endRepeater();
        return $stepper->build();
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

    public function getStepContent() { 
        return get_field('step_content');
    }
}
