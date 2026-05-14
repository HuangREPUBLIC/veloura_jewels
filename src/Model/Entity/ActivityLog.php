<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class ActivityLog extends Entity
{
    protected array $_accessible = [
        'user_id'     => true,
        'user_name'   => true,
        'action'      => true,
        'model'       => true,
        'model_id'    => true,
        'model_label' => true,
        'changes'     => true,
        'is_archived' => true,
        'created'     => true,
    ];
}
