<?php

namespace Edit\Form;


use Contact\Entity\Contact;
use Contact\Entity\ContactHydrator;
use Zend\Form\Fieldset;

class ContactFieldset extends Fieldset
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ContactHydrator());
        $this->setObject(new Contact());

        $this->add([
            'type' => 'hidden',
            'name' => 'contact_id',
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'first_name',
            'options' => [
                'label' => 'First name',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'last_name',
            'options' => [
                'label' => 'Last name',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
    }
}