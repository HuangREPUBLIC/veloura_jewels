<?php
/**
 * @var \App\View\AppView $this
 * @var iterable $staffList
 * @var \App\Model\Entity\User|null $staff
 * @var array $schedule  keyed by day_of_week
 * @var array $dayNames
 * @var string $weekStartStr   Monday date YYYY-MM-DD
 * @var string $weekRange      Human-readable range label
 * @var string $minWeekInput   Min value for <input type="week">
 * @var string $currentWeekInput Current value for <input type="week">
 */
$this->assign('title', 'Manage Schedule');
$this->Html->css('login', ['block' => true]);
?>

<div class="login-page">
    <div class="users form content login-card--wide">

        <?= $this->Html->link(__('← Back'), ['controller' => 'Users', 'action' => 'index']) ?>

        <legend>Schedule</legend>

        <!-- Week picker -->
        <div class="schedule-week-picker">
            <input
                type="week"
                id="weekPicker"
                value="<?= h($currentWeekInput) ?>"
                min="<?= h($minWeekInput) ?>"
                onchange="switchWeek(this.value)"
                class="schedule-time-input"
            >
            <span class="schedule-week-range"><?= h($weekRange) ?></span>
        </div>

        <!-- Staff selector -->
        <div class="schedule-selector">
            <label for="staffSelect">Select Staff Member</label>
            <select id="staffSelect" class="schedule-time-input" style="width:100%;" onchange="switchStaff(this.value)">
                <option value="">— Choose a staff member —</option>
                <?php foreach ($staffList as $s): ?>
                    <option value="<?= $s->id ?>" <?= ($staff && $staff->id === $s->id) ? 'selected' : '' ?>>
                        <?= h(trim($s->first_name . ' ' . $s->last_name) ?: $s->email) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if ($staff): ?>
        <!-- Weekly time picker form -->
        <?= $this->Form->create(null, ['url' => ['action' => 'add', $staff->id], 'method' => 'post']) ?>
        <input type="hidden" name="week_start" value="<?= h($weekStartStr) ?>">

        <p class="form-hint" style="margin-top:1.4rem;">
            Tick the days <strong><?= h($staff->first_name ?: $staff->email) ?></strong> works and set the shift times.
        </p>

        <div class="schedule-grid">
            <?php foreach ($dayNames as $dayNum => $dayLabel): ?>
                <?php $existing = $schedule[$dayNum] ?? null; ?>
                <?php $checked  = $existing !== null; ?>
                <div class="schedule-row <?= $checked ? 'schedule-row--active' : '' ?>" id="row-<?= $dayNum ?>">
                    <label class="schedule-day-toggle">
                        <input type="hidden" name="days[<?= $dayNum ?>][active]" value="0">
                        <input
                            type="checkbox"
                            name="days[<?= $dayNum ?>][active]"
                            value="1"
                            class="schedule-checkbox"
                            data-day="<?= $dayNum ?>"
                            <?= $checked ? 'checked' : '' ?>
                            onchange="toggleDay(<?= $dayNum ?>)"
                        >
                        <span class="schedule-day-label"><?= $dayLabel ?></span>
                    </label>

                    <div class="schedule-times" id="times-<?= $dayNum ?>" style="<?= $checked ? '' : 'display:none' ?>">
                        <div class="schedule-time-group">
                            <label>Start</label>
                            <input
                                type="time"
                                name="days[<?= $dayNum ?>][start_time]"
                                class="schedule-time-input"
                                value="<?= $existing ? $existing->start_time->format('H:i') : '09:00' ?>"
                            >
                        </div>
                        <span class="schedule-time-sep">–</span>
                        <div class="schedule-time-group">
                            <label>End</label>
                            <input
                                type="time"
                                name="days[<?= $dayNum ?>][end_time]"
                                class="schedule-time-input"
                                value="<?= $existing ? $existing->end_time->format('H:i') : '17:00' ?>"
                            >
                        </div>
                    </div>

                    <span class="schedule-day-off" id="off-<?= $dayNum ?>" style="<?= $checked ? 'display:none' : '' ?>">Day off</span>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="login-button" style="margin-top:2rem;">Save</button>

        <?= $this->Form->end() ?>
        <?php endif; ?>

    </div>
</div>

<script>
var scheduleIndexUrl = '<?= $this->Url->build(['controller' => 'Schedule', 'action' => 'add']) ?>';
var scheduleStaffUrlTemplate = '<?= $this->Url->build(['controller' => 'Schedule', 'action' => 'add', '__STAFF_ID__']) ?>';
var currentStaff = <?= $staff ? $staff->id : 'null' ?>;
var currentWeek  = '<?= h($weekStartStr) ?>';

function scheduleUrlFor(staffId, weekStart) {
    var url = staffId
        ? scheduleStaffUrlTemplate.replace('__STAFF_ID__', encodeURIComponent(staffId))
        : scheduleIndexUrl;

    return url + '?week=' + encodeURIComponent(weekStart);
}

function switchStaff(id) {
    window.location.href = id ? scheduleUrlFor(id, currentWeek) : scheduleIndexUrl;
}

function switchWeek(weekValue) {
    // weekValue is "YYYY-Wnn" — use UTC to avoid DST shifts
    var m = weekValue.match(/^(\d{4})-W(\d{2})$/);
    if (!m) return;
    var year = parseInt(m[1], 10);
    var week = parseInt(m[2], 10);
    var jan4ms  = Date.UTC(year, 0, 4);
    var jan4dow = (new Date(jan4ms).getUTCDay() + 6) % 7;
    var monday  = new Date(jan4ms - jan4dow * 86400000 + (week - 1) * 7 * 86400000);
    var dateStr = monday.getUTCFullYear() + '-'
        + String(monday.getUTCMonth() + 1).padStart(2, '0') + '-'
        + String(monday.getUTCDate()).padStart(2, '0');
    window.location.href = scheduleUrlFor(currentStaff, dateStr);
}

function toggleDay(day) {
    var times = document.getElementById('times-' + day);
    var off   = document.getElementById('off-'   + day);
    var row   = document.getElementById('row-'   + day);
    var cb    = document.querySelector('[data-day="' + day + '"]');

    if (cb.checked) {
        times.style.display = 'flex';
        off.style.display   = 'none';
        row.classList.add('schedule-row--active');
    } else {
        times.style.display = 'none';
        off.style.display   = '';
        row.classList.remove('schedule-row--active');
    }
}
</script>
