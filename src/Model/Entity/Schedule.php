<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Schedule extends Entity
{
    protected array $_accessible = [
        'user_id'     => true,
        'week_start'  => true,
        'day_of_week' => true,
        'start_time'  => true,
        'end_time'    => true,
        'created'     => true,
        'modified'    => true,
        'user'        => true,
    ];
}
