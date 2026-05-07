<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\ORM\Entity;

class Schedule extends Entity
{
    protected array $_accessible = [
        'user_id'     => true,
        'week_start'  => true,
        'day_of_week' => true,
        'start_time'  => true,
        'end_time'    => true,
        'created'     => true,
        'modified'    => true,
        'user'        => true,
    ];

    /**
     * Day-of-week index used in this app: Mon=1 ... Sun=7.
     * (Matches PHP's date('N').)
     */
    public const DAY_NAMES = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday',
    ];

    public const DAY_NAMES_SHORT = [
        1 => 'Mon',
        2 => 'Tue',
        3 => 'Wed',
        4 => 'Thu',
        5 => 'Fri',
        6 => 'Sat',
        7 => 'Sun',
    ];

    /**
     * Get the actual calendar date of this shift (week_start + offset).
     * day_of_week is 1=Mon ... 7=Sun, week_start is the Monday of that week,
     * so the offset is simply day_of_week - 1.
     */
    protected function _getShiftDate(): ?Date
    {
        if (!$this->week_start instanceof Date) {
            return null;
        }
        return $this->week_start->addDays($this->day_of_week - 1);
    }

    /**
     * "08:00 - 18:30" formatted range.
     */
    protected function _getTimeRange(): string
    {
        if (!$this->start_time instanceof Time || !$this->end_time instanceof Time) {
            return '';
        }
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    /**
     * Hours worked on this shift, as a float.
     */
    protected function _getHours(): float
    {
        if (!$this->start_time instanceof Time || !$this->end_time instanceof Time) {
            return 0.0;
        }
        $start = (int)$this->start_time->format('U');
        $end   = (int)$this->end_time->format('U');
        return max(0.0, ($end - $start) / 3600);
    }
}
