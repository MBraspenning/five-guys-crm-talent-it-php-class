<?php

namespace Contact\Controller;

use Contact\Model\ContactModelInterface;
use Contact\Model\EmailAddressModelInterface;
use Contact\Model\CountryModelInterface;
use Contact\Service\ContactFormServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Form\FormInterface;

class ContactController extends AbstractActionController
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var ContactModelInterface
     */
    private $contactModel;

    /**
     * @var EmailAddressModelInterface
     */
    private $emailAddressModel;

    /**
     * @var CountryModelInterface
     */
    protected $countryModel;

    /**
     * @var ContactFormServiceInterface
     */
    protected $contactFormService;

    /**
     * @var FormInterface
     */
    protected $contactForm;

    /**
     * ContactController constructor.
     *
     * @param AuthenticationService $authService
     * @param ContactModelInterface $contactModel
     * @param EmailAddressModelInterface $emailAddressModel
     * @param AddressModelInterface $addressModel
     * @param CountryModelInterface $countryModel
     * @param ContactFormServiceInterface $contactFormService
     * @param FormInterface $contactForm
     */
    public function __construct(
        AuthenticationService $authService,
        ContactModelInterface $contactModel,
        EmailAddressModelInterface $emailAddressModel,
        CountryModelInterface $countryModel,
        ContactFormServiceInterface $contactFormService,
        FormInterface $contactForm
    )
    {
        $this->authService = $authService;
        $this->contactModel = $contactModel;
        $this->emailAddressModel = $emailAddressModel;
        $this->countryModel = $countryModel;
        $this->contactFormService = $contactFormService;
        $this->contactForm = $contactForm;

    }

    public function indexAction()
    {
        return $this->redirect()->toRoute('contact/overview', ['page' => 1]);
    }

    public function overviewAction()
    {
        $page = $this->params()->fromRoute('page', 1);
        $memberId = $this->identity()->getMemberId();

        $contacts = $this->contactModel->fetchAllContacts($memberId);

        $viewModel = [
            'contacts' => $contacts,
            'page' => $page,
        ];

        return new ViewModel($viewModel);
    }
    public function editAction()
    {
        if (!$this->authService->hasIdentity()) {
            return $this->redirect()->toRoute('auth');
        }

        $contactId = $this->params()->fromRoute('contactId', 0);
        $memberId = $this->authService->getIdentity()->getMemberId();

        $contact = $this->contactModel->findContact($memberId, $contactId);
        $countries = $this->countryModel->fetchAllCountries();

        $this->contactForm->bind($contact);

        $viewModel = new ViewModel([
            'contactForm' => $this->contactForm,
            'contact' => $contact,
            'countries' => $countries,
        ]);

        if (!$this->request->isPost()) {
            return $viewModel;
        }

        $data = $this->request->getPost();
        $this->contactForm->setData($data);
        if (!$this->contactForm->isValid()) {
            return $viewModel;
        }

        $validData = $this->contactForm->getData();
        if (!$validData instanceof ContactInterface) {
            return $viewModel;
        }
        $this->contactModel->saveContact($memberId, $validData);
        foreach ($validData->getEmailAddresses() as $emailAddress) {
            $this->contactEmailModel->saveEmailAddress($contactId, $emailAddress);
        }
        foreach ($validData->getAddresses() as $address) {
            $this->contactAddressModel->saveAddress($contactId, $address);
        }

        return $this->redirect()->toRoute('dashboard/contacts/detail', ['contactId' => $contactId]);
    }
}