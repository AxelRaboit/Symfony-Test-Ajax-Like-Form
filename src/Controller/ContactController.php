<?php

namespace App\Controller;

use App\Service\ContactService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact', methods: ['POST'])]
    public function index(Request $request, ContactService $contactService): Response
    {
        $formData = [
            'subject' => $request->request->get('contact-subject'),
            'body' => $request->request->get('contact-body')
        ];

        if (empty($formData['subject']) || empty($formData['body'])) {
            return $this->json(['status' => 'error', 'message' => 'Formulaire incomplet'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $contactService->manageContact($formData);
            return $this->json(['status' => 'success', 'message' => 'Formulaire reçu avec succès', 'formData' => $formData]);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Erreur lors du traitement du formulaire'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}