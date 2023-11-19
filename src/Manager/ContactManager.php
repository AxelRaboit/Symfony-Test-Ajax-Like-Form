<?php

namespace App\Manager;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;

class ContactManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function persistContact(
        array $data,
    ): void
    {
        $contact = new Contact();
        $contact->setSubject($data['subject']);
        $contact->setBody($data['body']);

        $this->persistAndFlush($contact);
    }

    // base

    private function persistAndFlush(Contact $contact): void
    {
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
    }

    private function removeAndFlush(Contact $contact): void
    {
        $this->entityManager->remove($contact);
        $this->entityManager->flush();
    }
}