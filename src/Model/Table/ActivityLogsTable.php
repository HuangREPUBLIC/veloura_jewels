<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ActivityLogsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('activity_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    public function getSchema(): \Cake\Database\Schema\TableSchemaInterface
    {
        $schema = parent::getSchema();
        $schema->setColumnType('changes', 'json');
        return $schema;
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('action')
            ->notEmptyString('model');

        return $validator;
    }
}
