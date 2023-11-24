<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class AdditionalInfo extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $additionalInfo = new FieldsBuilder('additional_info');

        $additionalInfo
            ->setLocation('post_type', '==', 'wpsl_stores');

        $additionalInfo
            ->addText('npi_field', [
                'label' => 'NPI Number',
                'instructions' => '',
                'required' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ]);

        return $additionalInfo->build();
    }
}
