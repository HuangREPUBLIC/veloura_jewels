<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class SchedulesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('schedules');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->date('week_start')
            ->requirePresence('week_start', 'create')
            ->notEmptyDate('week_start');

        $validator
            ->integer('day_of_week')
            ->inList('day_of_week', [0, 1, 2, 3, 4, 5, 6])
            ->requirePresence('day_of_week', 'create')
            ->notEmptyString('day_of_week');

        $validator
            ->time('start_time')
            ->requirePresence('start_time', 'create')
            ->notEmptyTime('start_time');

        $validator
            ->time('end_time')
            ->requirePresence('end_time', 'create')
            ->notEmptyTime('end_time')
            ->add('end_time', 'afterStart', [
                'rule' => function ($value, $context) {
                    return $value > $context['data']['start_time'];
                },
                'message' => 'End time must be after start time.',
            ]);

        return $validator;
    }
}
