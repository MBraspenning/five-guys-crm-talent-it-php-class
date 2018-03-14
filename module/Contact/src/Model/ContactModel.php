<?php

namespace Contact\Model;


use Contact\Entity\ContactInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Hydrator\HydratorInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ContactModel implements ContactModelInterface
{
    const TABLE_NAME = 'contact';

    /**
     * @var AdapterInterface
     */
    protected $db;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var ContactInterface
     */
    protected $contactPrototype;

    /**
     * ContactModel constructor.
     *
     * @param AdapterInterface $db
     * @param HydratorInterface $hydrator
     * @param ContactInterface $contactPrototype
     */
    public function __construct(AdapterInterface $db, HydratorInterface $hydrator, ContactInterface $contactPrototype)
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
        $this->contactPrototype = $contactPrototype;
    }

    /**
     * @inheritdoc
     */
    public function fetchAllContacts($memberId)
    {
        $sql = new Sql($this->db);
        $select = $sql->select(self::TABLE_NAME);
        $select->where(['member_id = ?' => $memberId]);
        $select->order(['last_name']);

        $resultSet = new HydratingResultSet($this->hydrator, $this->contactPrototype);

        $adapter = new DbSelect($select, $this->db, $resultSet);
        $paginator = new Paginator($adapter);

        return $paginator;
    }

    /**
     * @inheritDoc
     */
    public function findContact($memberId, $contactId)
    {
        $sql = new Sql($this->db);
        $select = $sql->select(self::TABLE_NAME);
        $select->where([
            'member_id = ?' => $memberId,
            'contact_id = ?' => $contactId,
        ]);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new \DomainException('Cannot find contac');
        }

        return $this->hydrator->hydrate($result->current(), $this->contactPrototype);
    }

    /**
     * @inheritDoc
     */
    public function saveContact($memberId, ContactInterface $contact)
    {
        if (0 < $contact->getContactId()) {
            return $this->updateContact($memberId, $contact);
        }
        return $this->insertContact($contact);
    }

    /**
     * @inheritDoc
     */
    public function deleteContact($memberId, ContactInterface $contact)
    {
        // TODO: Implement deleteContact() method.
    }

    private function insertContact(ContactInterface $contact)
    {
        $contactData = $this->hydrator->extract($contact);
        unset ($contactData['contact_id']);
        $insert = new Insert(self::TABLE_NAME);
        $insert->values($contactData);

        $sql = new Sql($this->db);
        $stmt = $sql->prepareStatementForSqlObject($insert);
        $result = $stmt->execute();

        $contactId = $result->getGeneratedValue();
        $newContactEntity = clone $this->contactPrototype;
        $contactData['contact_id'] = $contactId;
        return $this->hydrator->hydrate($contactData, $newContactEntity);
    }

    private function updateContact($memberId, ContactInterface $contact)
    {
        $contactData = $this->hydrator->extract($contact);
        unset ($contactData['member_id'], $contactData['contact_id']);
        $update = new Update(self::TABLE_NAME);
        $update->set($contactData);
        $update->where([
            'member_id = ?' => $memberId,
            'contact_id = ?' => $contact->getContactId(),
        ]);

        $sql = new Sql($this->db);
        $stmt = $sql->prepareStatementForSqlObject($update);
        $stmt->execute();
        return $contact;
    }
}