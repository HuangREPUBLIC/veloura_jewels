<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use Cake\ORM\Query\SelectQuery;


class SchedulesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('schedules');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType'   => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->date('week_start')
            ->notEmptyDate('week_start');

        $validator
            ->integer('day_of_week')
            ->range('day_of_week', [1, 7])
            ->notEmptyString('day_of_week');

        $validator
            ->time('start_time')
            ->notEmptyTime('start_time');

        $validator
            ->time('end_time')
            ->notEmptyTime('end_time')
            ->add('end_time', 'afterStart', [
                'rule' => function ($value, $context) {
                    if (empty($context['data']['start_time'])) {
                        return true;
                    }
                    return strtotime((string)$value) > strtotime((string)$context['data']['start_time']);
                },
                'message' => 'End time must be after start time.',
            ]);

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->isUnique(
            ['user_id', 'week_start', 'day_of_week'],
            'A shift already exists for this staff member on this day.'
        ));

        return $rules;
    }

    /**
     * Find all shifts in a given week, with the user attached, ordered for calendar display.
     */
    public function findForWeek(SelectQuery $query, string $weekStart): SelectQuery
    {
        return $query
            ->contain(['Users'])
            ->where(['Schedules.week_start' => $weekStart])
            ->orderByAsc('Users.first_name')
            ->orderByAsc('Schedules.day_of_week')
            ->orderByAsc('Schedules.start_time');
    }

    /**
     * Find a single staff member's upcoming shifts (today onwards), ordered chronologically.
     *
     *Each shift's actual calendar date = week_start (a Monday) + (day_of_week - 1) days.
     * e.g. day_of_week=1 (Mon) is +0 days; day_of_week=7 (Sun) is +6 days.
     */
    public function findUpcomingForUser(SelectQuery $query, int $userId): SelectQuery
    {
        $today = (new \DateTime('today'))->format('Y-m-d');

        $shiftDateExpr = "DATE_ADD(Schedules.week_start, INTERVAL Schedules.day_of_week - 1 DAY)";

        return $query
            ->contain(['Users'])
            ->where([
                'Schedules.user_id' => $userId,
                "$shiftDateExpr >=" => $today,
            ])
            ->orderByAsc('Schedules.week_start')
            ->orderByAsc('Schedules.day_of_week')
            ->orderByAsc('Schedules.start_time');
    }

}
