<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class SiteSettingsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('site_settings');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

    public function getAll(): array
    {
        $settings = [];
        foreach ($this->find()->all() as $row) {
            $settings[$row->setting_key] = $row->setting_value;
        }
        return $settings;
    }
}
