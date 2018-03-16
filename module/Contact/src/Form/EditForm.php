<?php

namespace Contact\Form;

use Contact\Entity\Contact;
use Contact\Entity\ContactHydrator;
use Contact\Entity\Factory\ContactHydratorFactory;
use Zend\Form\Form;

class EditForm extends Form
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ContactHydrator());
        $this->bind(new Contact());

        $this->add([
            'name' => 'contact-fieldset',
            'type' => ContactFieldset::class,
        ]);

        $this->add([
            'name' => 'contact-email-collection',
            'type' => ContactEmailCollection::class,
        ]);

        $this->add([
            'name' => 'contact-address-collection',
            'type' => ContactAddressCollection::class,
        ]);

        $this->add([
            'name' => 'csrf',
            'type' => 'csrf',
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Save contact',
                'class' => 'btn btn-success',
            ],
        ]);
    }
}