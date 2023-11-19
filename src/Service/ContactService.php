<?php

namespace App\Service;

use App\Manager\ContactManager;

class ContactService
{
    public function __construct(
        private readonly ContactManager $contactManager
    ) {
    }

    public function manageContact(
        array $data
    ): void
    {
        $this->contactManager->persistContact($data);
    }
}