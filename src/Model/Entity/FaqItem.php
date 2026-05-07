<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class FaqItem extends Entity
{
    protected array $_accessible = [
        'question' => true,
        'answer' => true,
        'sort_order' => true,
        'is_active' => true,
    ];
}
