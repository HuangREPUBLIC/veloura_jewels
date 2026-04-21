<?php
declare(strict_types=1);

namespace App\Mailer;

use App\Model\Entity\ContactSubmission;
use Cake\Mailer\Mailer;

class ContactMailer extends Mailer
{
    /**
     * Returns the mailer name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Contact';
    }

    /**
     * Sends a confirmation email to the customer after they submit the contact form.
     *
     * @param \App\Model\Entity\ContactSubmission $contactSubmission The contact submission entity.
     * @return void
     */
    public function confirmation(ContactSubmission $contactSubmission): void
    {
        $this
            ->setTo($contactSubmission->email)
            ->setSubject('We received your message - Veloura Jewels')
            ->setEmailFormat('text')
//            ->setLayout(false)
            ->setViewVars([
                'firstName' => $contactSubmission->first_name,
                'userMessage' => $contactSubmission->message,
            ]);
    }
}
