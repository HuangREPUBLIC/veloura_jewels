<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class PageContent extends Entity
{
    protected array $_accessible = [
        'page_id' => true,
        'content_key' => true,
        'content_value' => true,
        'label' => true,
        'content_type' => true,
        'sort_order' => true,
    ];
}
