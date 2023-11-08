<?php
namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;


class SendMailService{
    private $mailer;

    public function __construct(MailerInterface $mailer){
        $this->mailer= $mailer;
    }


        /**
     * Envoie un e-mail
     *
     * @param string $from L'adresse e-mail de l'expéditeur
     * @param string $to L'adresse e-mail du destinataire
     * @param string $subject Le sujet de l'e-mail
     * @param string $template Le chemin du template Twig pour le contenu de l'e-mail
     * @param array $context Le contexte à transmettre au template Twig
     */
    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ): void
    {
          //on crée le mail
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        //on envoie le mail
        $this->mailer->send($email);
    }
}