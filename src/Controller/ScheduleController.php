<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

class ScheduleController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $identity = $this->Authentication->getIdentity();

        if (!$identity || $identity->get('role') !== 'admin') {
            $this->Flash->error('You do not have permission to manage schedules.');
            return $this->redirect('/');
        }
    }

    public function add($id = null)
    {
        $usersTable = $this->fetchTable('Users');

        // Compute this week's Monday (ISO: week starts Monday)
        $today      = new \DateTime('today');
        $dow        = (int)$today->format('N'); // 1=Mon … 7=Sun
        $thisMonday = (clone $today)->modify('-' . ($dow - 1) . ' days');

        // Resolve requested week from query string; reject past weeks
        $weekParam = $this->request->getQuery('week'); // expects YYYY-MM-DD
        $weekStart = clone $thisMonday;
        if ($weekParam && preg_match('/^\d{4}-\d{2}-\d{2}$/', $weekParam)) {
            $requested = new \DateTime($weekParam);
            $reqDow    = (int)$requested->format('N');
            $requested->modify('-' . ($reqDow - 1) . ' days'); // snap to Monday
            if ($requested >= $thisMonday) {
                $weekStart = $requested;
            }
        }

        $weekStartStr     = $weekStart->format('Y-m-d');
        $weekEnd          = (clone $weekStart)->modify('+6 days');
        $weekRange        = $weekStart->format('j M') . ' – ' . $weekEnd->format('j M Y');
        $minWeekInput     = $thisMonday->format('Y-\WW');   // e.g. 2026-W18
        $currentWeekInput = $weekStart->format('Y-\WW');

        $staffList = $usersTable->find()
            ->where(['role' => 'staff'])
            ->orderByAsc('first_name')
            ->all();

        $staff    = null;
        $schedule = [];
        $dayNames = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Sunday'];

        if ($id !== null) {
            $staff = $usersTable->get($id);
            if ($staff->role !== 'staff') {
                $this->Flash->error('Schedules can only be assigned to staff members.');
                return $this->redirect(['action' => 'add']);
            }

            $schedulesTable = $this->fetchTable('Schedules');

            if ($this->request->is(['post', 'put'])) {
                $days         = $this->request->getData('days') ?? [];
                $postedWeek   = $this->request->getData('week_start');
                // Validate: only allow current or future weeks
                $postedMonday = new \DateTime($postedWeek ?: $weekStartStr);
                $postedDow    = (int)$postedMonday->format('N');
                $postedMonday->modify('-' . ($postedDow - 1) . ' days');
                if ($postedMonday < $thisMonday) {
                    $postedMonday = clone $thisMonday;
                }
                $saveWeek = $postedMonday->format('Y-m-d');

                $schedulesTable->deleteAll(['user_id' => $id, 'week_start' => $saveWeek]);

                foreach ($days as $day => $times) {
                    if (empty($times['active']) || empty($times['start_time']) || empty($times['end_time'])) {
                        continue;
                    }
                    $schedulesTable->save($schedulesTable->newEntity([
                        'user_id'     => (int)$id,
                        'week_start'  => $saveWeek,
                        'day_of_week' => (int)$day,
                        'start_time'  => $times['start_time'],
                        'end_time'    => $times['end_time'],
                    ]));
                }

                $this->Flash->success('Schedule saved successfully.');
                return $this->redirect(['action' => 'add', $id, '?' => ['week' => $saveWeek]]);
            }

            $rows = $schedulesTable->find()
                ->where(['user_id' => $id, 'week_start' => $weekStartStr])
                ->all();
            foreach ($rows as $row) {
                $schedule[$row->day_of_week] = $row;
            }
        }

        $this->set(compact('staffList', 'staff', 'schedule', 'dayNames', 'weekStartStr', 'weekRange', 'minWeekInput', 'currentWeekInput'));
    }
}
