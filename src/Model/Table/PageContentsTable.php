<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class PageContentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('page_content');
        $this->setPrimaryKey('id');
        $this->belongsTo('CmsPages', ['foreignKey' => 'page_id']);
    }

    public function getForPage(string $slug): array
    {
        $result = [];
        foreach ($this->find()
            ->matching('CmsPages', fn($q) => $q->where(['CmsPages.slug' => $slug]))
            ->orderBy(['PageContents.sort_order' => 'ASC'])
            ->all() as $row) {
            $result[$row->content_key] = $row->content_value;
        }
        return $result;
    }
}
