<?php
/**
 * Created by PhpStorm.
 * User: kamil
 * Date: 06.10.16
 * Time: 22:42
 */

namespace Message\Form;

use Zend\Form\Form;

class MessageForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('message');

        $this->add([
            'name' => 'content',
            'type' => 'text',
            'attributes' => [
                'id' => 'content',
                'class' => 'form-control',
                'placeholder' =>'Let\'s type something here!'
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Send',
                'id'    => 'submitbutton',
                'class' => 'btn btn-success'
            ],
        ]);
    }
}