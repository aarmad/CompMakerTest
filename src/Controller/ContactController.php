<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $form->getData();

            $email = (new Email())
                ->from($data['email'])
                ->to('aaron.madjri@efrei.net')
                ->subject('Nouveau message du formulaire de contact')
                ->text(
                    "Nom : {$data['nom']}\n" .
                    "Email : {$data['email']}\n\n" .
                    "Message :\n{$data['message']}"
                );

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé ');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
