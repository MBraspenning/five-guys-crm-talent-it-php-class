<?php

namespace Contact\Controller\Factory;


use Contact\Model\ContactModelInterface;
use Contact\Model\CountryModelInterface;
use Contact\Model\EmailAddressModelInterface;
use Contact\Model\AddressModelInterface;
use Contact\Service\ContactFormServiceInterface;
use Contact\Controller\ContactController;
/*use Contact\Form\EditForm;
*/use Dashboard\Form\ContactForm;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class ContactControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ContactController(
            $container->get(AuthenticationService::class),
            $container->get(ContactModelInterface::class),
            $container->get(EmailAddressModelInterface::class),
            $container->get(AddressModelInterface::class),
            $container->get(CountryModelInterface::class),
            $container->get(ContactFormServiceInterface::class),
            $container->get('FormElementManager')->get(ContactForm::class)
/*            $container->get('FormElementManager')->get(EditForm::class)
*/            
        );
    }

}