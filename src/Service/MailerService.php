<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class MailerService{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $to, string $subject, array $content): void
    {
        // Générer le contenu HTML
        $htmlContent = '';
        foreach ($content as $element) {
            $htmlContent .= "<p>$element</p>"; // Construire le contenu HTML
        }

        // Créer l'email
        $email = (new Email())
            ->from('no-reply@gmail.com')
            ->to($to)
            ->subject($subject)
            ->text(implode("\n", $content))
            ->html($htmlContent);

        // Envoyer l'email
        $this->mailer->send($email);
    }

}