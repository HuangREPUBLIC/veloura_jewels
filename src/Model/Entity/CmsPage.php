<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class CmsPage extends Entity
{
    protected array $_accessible = ['slug' => true, 'title' => true, 'sort_order' => true];
}
