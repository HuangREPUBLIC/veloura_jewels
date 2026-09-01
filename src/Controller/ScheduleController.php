<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Schedule;
use Cake\Event\EventInterface;

class ScheduleController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Schedules = $this->fetchTable('Schedules');
    }

    /**
     * Role-based access. Customers are not allowed.
     * Admin-only actions are gated individually.
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            $this->Flash->error('Please log in to view the schedule.');
            return $this->redirect(['controller' => 'Auth', 'action' => 'login']);
        }

        $role = $identity->get('role');
        if (!in_array($role, ['admin', 'staff'], true)) {
            $this->Flash->error('You do not have permission to view the schedule.');
            return $this->redirect('/');
        }

        $adminOnly = ['shifts', 'delete'];
        if (in_array($this->request->getParam('action'), $adminOnly, true) && $role !== 'admin') {
            $this->Flash->error('You do not have permission to manage schedules.');
            return $this->redirect(['action' => 'index']);
        }

        $this->viewBuilder()->setLayout('admin');

        return null;
    }

    /**
     * Weekly schedule grid. Admins see all staff with edit controls; staff see read-only.
     */
    public function index(): void
    {
        $identity = $this->Authentication->getIdentity();
        $isAdmin  = $identity->get('role') === 'admin';
        $weekData = $this->resolveWeek($this->request->getQuery('week'));
        $shifts   = $this->Schedules->find('forWeek', weekStart: $weekData['weekStartStr'])->all();

        if ($isAdmin) {
            // Admins see all staff rows even if they have no shifts this week
            $staffList  = $this->Schedules->Users->find()
                ->where(['role' => 'staff'])
                ->orderByAsc('first_name')
                ->orderByAsc('last_name')
                ->all();
            $staffOrder = $this->indexUsersById($staffList);
        } else {
            // Staff see the same full week view, just without edit/delete buttons
            $staffOrder = $this->extractStaffOrder($shifts);
        }

        $this->set([
                'shiftsByUserDay' => $this->groupShiftsByUserAndDay($shifts),
                'staffOrder'      => $staffOrder,
                'isAdminView'     => $isAdmin,
            ] + $weekData);
    }

    /**
     * Admin: create or edit a whole week of shifts for one staff member.
     *
     * The form posts an `active` checkbox plus start/end times for each day. We delete the
     * staff member's existing rows for that week and re-insert whatever is currently ticked.
     * Past weeks are blocked from being edited.
     */
    public function shifts(?int $id = null)
    {
        $weekData = $this->resolveWeek($this->request->getQuery('week'));

        $usersTable = $this->Schedules->Users;
        $staffList  = $usersTable->find()
            ->where(['role' => 'staff'])
            ->orderByAsc('first_name')
            ->orderByAsc('last_name')
            ->all();

        $staff    = null;
        $schedule = [];

        if ($id !== null) {
            $staff = $usersTable->get($id);
            if ($staff->role !== 'staff') {
                $this->Flash->error('Schedules can only be assigned to staff members.');
                return $this->redirect(['action' => 'shifts']);
            }

            if ($this->request->is(['post', 'put'])) {
                $saveWeek = $this->snapToCurrentOrFutureMonday(
                    $this->request->getData('week_start') ?: $weekData['weekStartStr'],
                    $weekData['thisMonday']
                );

                $result = $this->saveWeekForStaff(
                    (int)$id,
                    $saveWeek,
                    $this->request->getData('days') ?? []
                );

                if ($result['errors'] === []) {
                    $this->Flash->success('Schedule saved successfully.');
                } else {
                    $this->Flash->error('Saved with issues: ' . implode(' ', $result['errors']));
                }

                return $this->redirect(['action' => 'shifts', $id, '?' => ['week' => $saveWeek]]);
            }

            $rows = $this->Schedules->find()
                ->where(['user_id' => $id, 'week_start' => $weekData['weekStartStr']])
                ->all();
            foreach ($rows as $row) {
                $schedule[$row->day_of_week] = $row;
            }
        }

        $this->set([
                'staffList' => $staffList,
                'staff'     => $staff,
                'schedule'  => $schedule,
                'dayNames'  => Schedule::DAY_NAMES,
            ] + $weekData);

        return null;
    }

    /**
     * Delete a single shift (admin). POST/DELETE only.
     */
    public function delete(int $id)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shift = $this->Schedules->get($id);

        if ($this->Schedules->delete($shift)) {
            $this->Flash->success('Shift deleted.');
        } else {
            $this->Flash->error('Could not delete shift.');
        }

        $weekStart = $shift->week_start->format('Y-m-d');
        return $this->redirect(['action' => 'index', '?' => ['week' => $weekStart]]);
    }

    // Helpers

    /**
     * Resolve the week being viewed. Defaults to the current week.
     * Returns the data the templates need to render the week picker.
     */
    private function resolveWeek(?string $weekParam): array
    {
        $today      = new \DateTime('today');
        $thisMonday = (clone $today)->modify('-' . ((int)$today->format('N') - 1) . ' days');

        $weekStart = clone $thisMonday;
        if ($weekParam && preg_match('/^\d{4}-\d{2}-\d{2}$/', $weekParam)) {
            $requested = new \DateTime($weekParam);
            $requested->modify('-' . ((int)$requested->format('N') - 1) . ' days');
            $weekStart = $requested;
        }

        $weekEnd = (clone $weekStart)->modify('+6 days');

        return [
            'weekStartStr'     => $weekStart->format('Y-m-d'),
            'weekEndStr'       => $weekEnd->format('Y-m-d'),
            'weekRange'        => $weekStart->format('j M') . ' - ' . $weekEnd->format('j M Y'),
            'minWeekInput'     => $thisMonday->format('Y-\WW'),
            'currentWeekInput' => $weekStart->format('Y-\WW'),
            'thisMonday'       => $thisMonday,
            'dayDates'         => $this->buildDayDates($weekStart),
        ];
    }

    /**
     * Build a [1 => DateTime, 2 => DateTime, ..., 7 => DateTime] map of dates for the week.
     * Templates use this to render "Mon 4", "Tue 5" headers without doing math.
     */
    private function buildDayDates(\DateTime $weekStart): array
    {
        $dates = [];
        for ($i = 1; $i <= 7; $i++) {
            $dates[$i] = (clone $weekStart)->modify('+' . ($i - 1) . ' days');
        }
        return $dates;
    }

    /**
     * Snap a posted week-start to the Monday of that week, and refuse past weeks.
     */
    private function snapToCurrentOrFutureMonday(string $posted, \DateTime $thisMonday): string
    {
        $monday = new \DateTime($posted);
        $monday->modify('-' . ((int)$monday->format('N') - 1) . ' days');

        if ($monday < $thisMonday) {
            $monday = clone $thisMonday;
        }

        return $monday->format('Y-m-d');
    }

    /**
     * Wipe a staff member's week and re-insert from posted data. Returns any errors.
     */
    private function saveWeekForStaff(int $userId, string $weekStart, array $days): array
    {
        $this->Schedules->deleteAll(['user_id' => $userId, 'week_start' => $weekStart]);

        $errors = [];
        foreach ($days as $day => $times) {
            if (empty($times['active']) || empty($times['start_time']) || empty($times['end_time'])) {
                continue;
            }

            $entity = $this->Schedules->newEntity([
                'user_id'     => $userId,
                'week_start'  => $weekStart,
                'day_of_week' => (int)$day,
                'start_time'  => $times['start_time'],
                'end_time'    => $times['end_time'],
            ]);

            if ($this->Schedules->save($entity) === false) {
                $dayName = Schedule::DAY_NAMES[(int)$day] ?? "Day $day";
                $errors[] = $dayName . ': ' . implode(', ', $this->flattenErrors($entity->getErrors()));
            }
        }

        return ['errors' => $errors];
    }

    /**
     * Group shifts by user and day for calendar rendering: $out[userId][dayOfWeek] = $shift.
     */
    private function groupShiftsByUserAndDay(iterable $shifts): array
    {
        $out = [];
        foreach ($shifts as $shift) {
            $out[$shift->user_id][$shift->day_of_week] = $shift;
        }
        return $out;
    }

    /**
     * Build an ordered [userId => User] map preserving the find()'s sort order
     * (which is alphabetical by first_name).
     */
    private function extractStaffOrder(iterable $shifts): array
    {
        $out = [];
        foreach ($shifts as $shift) {
            if (!isset($out[$shift->user_id])) {
                $out[$shift->user_id] = $shift->user;
            }
        }
        return $out;
    }

    /**
     * Build a [userId => User] map from an already ordered user result set.
     */
    private function indexUsersById(iterable $users): array
    {
        $out = [];
        foreach ($users as $user) {
            $out[$user->id] = $user;
        }
        return $out;
    }

    /**
     * Turn nested validation errors into a flat list of strings.
     */
    private function flattenErrors(array $errors): array
    {
        $flat = [];
        array_walk_recursive($errors, function ($message) use (&$flat) {
            $flat[] = (string)$message;
        });
        return $flat;
    }
}
