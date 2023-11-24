<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Quoter extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $quoter = new FieldsBuilder('quoter');

        $quoter
            ->setLocation('post_type', '==', 'quote');

        $quoter
            ->addText('location', [
                'label' => 'Location',
                'required' => 0,
            ])
            ->addTextarea('quote', [
                'label' => 'Quote',
                'required' => 0,
            ]);

        return $quoter->build();
    }
}
