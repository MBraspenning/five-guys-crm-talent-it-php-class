<?php

namespace Contact\Model;


use Contact\Entity\AddressInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Hydrator\HydratorInterface;

class AddressModel implements AddressModelInterface
{
    const TABLE_NAME = 'contact_address';

    /**
     * @var AdapterInterface
     */
    private $db;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @var AddressInterface
     */
    private $addressPrototype;

    /**
     * AddressModel constructor.
     * @param AdapterInterface $db
     * @param HydratorInterface $hydrator
     * @param AddressInterface $addressPrototype
     */
    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        AddressInterface $addressPrototype
    )
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
        $this->addressPrototype = $addressPrototype;
    }

    /**
     * @inheritDoc
     */
    public function fetchAllAddresses($contactId)
    {
        $sql = new Sql($this->db);
        $select = $sql->select(self::TABLE_NAME);
        $select->where(['contact_id = ?' => $contactId]);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new \DomainException('Cannot find an address for given contact');
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->addressPrototype);
        $resultSet->initialize($result);

        if (!$resultSet) {
            throw new \RuntimeException('Cannot process the data for this contact');
        }

        return $resultSet;
    }

    /**
     * @inheritDoc
     */
    public function findAddressById($contactId, $contactAddressId)
    {
        // TODO: Implement findAddressById() method.
    }

    /**
     * @inheritDoc
     */
    public function saveAddress($contactId, AddressInterface $address)
    {
        if (0 < $address->getContactAddressId()) {
            return $this->updateAddress($contactId, $address);
        }
        return $this->insertAddress($contactId, $address);
    }

    /**
     * @inheritDoc
     */
    public function deleteAddress(AddressInterface $address)
    {
        // TODO: Implement deleteAddress() method.
    }

    private function updateAddress($contactId, AddressInterface $address)
    {
        $addressData = $this->hydrator->extract($address);
        unset($addressData['contact_id'], $addressData['contact_address_id']);

        $update = new Update(self::TABLE_NAME);
        $update->set($addressData);
        $update->where([
            'contact_id = ?' => $contactId,
            'contact_address_id = ?' => $address->getContactAddressId(),
        ]);

        $sql = new Sql($this->db);
        $stmt = $sql->prepareStatementForSqlObject($update);
        $stmt->execute();

        return $address;
    }

    private function insertAddress($contactId, AddressInterface $address)
    {
        $addressData = $this->hydrator->extract($address);
        unset($addressData['contact_address_id']);

        $insert = new Insert(self::TABLE_NAME);
        $insert->values($addressData);

        $sql = new Sql($this->db);
        $stmt = $sql->prepareStatementForSqlObject($insert);
        $result = $stmt->execute();

        $addressId = $result->getGeneratedValue();
        $addressData['contact_address_id'] = $addressId;
        $newAddressEntity = clone $this->addressPrototype;

        return $this->hydrator->hydrate($addressData, $newAddressEntity);
    }

}