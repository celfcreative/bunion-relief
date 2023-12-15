<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class TabInfo extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $tabInfo = new FieldsBuilder('tab_info');

        $tabInfo
            ->setLocation('post_type', '==', 'tab');

        $tabInfo
            ->addTextarea('tab_description', [
                'label' => 'Tab Description',
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
                'new_lines' => 'wpautop', // Possible values are 'wpautop', 'br', or ''.
            ])
            ->addTrueFalse('button_true_false', [
                'label' => 'Add Button',
                'instructions' => 'Would you like to add a button for direct pages',
                'required' => 0,
                'conditional_logic' => [],
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ])
            ->addPostObject('available_pages', [
                'label' => 'Direct Page to',
                'instructions' => 'Choose which page to direct',
                'required' => 0,
                'conditional_logic' => [
                    [
                        [
                            'field' => 'button_true_false',
                            'operator' => '==',
                            'value' => 1,
                        ]
                    ]
                ],
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'post_type' => ['page'],
                'return_format' => 'object',
            ]);

        return $tabInfo->build();
    }
}
