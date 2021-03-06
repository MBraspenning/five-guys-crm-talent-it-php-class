<?php

namespace Contact\Entity;


/**
 * Interface ContactEmailInterface
 *
 * @package Contact\Entity
 */
interface EmailAddressInterface extends ContactAwareInterface
{
    /**
     * Retrieve the sequence ID of an email address
     *
     * @return int
     */
    public function getContactEmailId();

    /**
     * Retrieve the email address
     *
     * @return string
     */
    public function getEmailAddress();

    /**
     * Check if the email address is primary
     *
     * @return bool
     */
    public function isPrimary();
}