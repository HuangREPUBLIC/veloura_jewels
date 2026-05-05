<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class SiteSetting extends Entity
{
    protected array $_accessible = [
        'setting_key'   => false,
        'setting_value' => true,
        'label'         => true,
        'group_name'    => true,
    ];
}
