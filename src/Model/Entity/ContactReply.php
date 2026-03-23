<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class ContactReply extends Entity
{
    protected array $_accessible = [
        'contact_submission_id' => true,
        'subject' => true,
        'message' => true,
        'sent_at' => true,
        'created' => true,
        'modified' => true,
        'contact_submission' => true,
    ];
}
