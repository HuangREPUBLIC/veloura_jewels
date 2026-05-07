<?php
$this->assign('title', 'Manage Schedule');
$this->Html->css('schedule', ['block' => true]);

// Helper functions
$selectedStaffId = $staff->id ?? null;
$selectedName = $staff
    ? trim($staff->first_name . ' ' . $staff->last_name) ?: $staff->email
    : null;
?>

<div class="sched-editor-page">
    <div class="sched-form-panel">

        <?= $this->Html->link(__('← Back'), ['action' => 'index', '?' => ['week' => $weekStartStr]], ['class' => 'back-link']) ?>

        <div class="sched-form-header">
            <div>
                <h3>Manage Schedule</h3>
            </div>
            <?php if ($staff): ?>
                <button type="submit" form="shifts-form" class="btn-primary-sched schedule-save-btn">Save</button>
            <?php endif; ?>
        </div>

        <div class="schedule-controls-row">
            <div class="schedule-week-picker">
                <input type="week"
                       value="<?= h($currentWeekInput) ?>"
                       min="<?= h($minWeekInput) ?>"
                       onchange="switchWeek(this.value)"
                       class="schedule-time-input">
                <span class="schedule-week-range"><?= h($weekRange) ?></span>
            </div>

            <div class="schedule-selector">
                <label for="staffSelect">Staff</label>
                <select id="staffSelect" onchange="switchStaff(this.value)" class="schedule-time-input schedule-staff-input">
                    <option value="">Choose staff</option>
                    <?php foreach ($staffList as $s):
                        $name = trim($s->first_name . ' ' . $s->last_name) ?: $s->email;
                        ?>
                        <option value="<?= $s->id ?>" <?= $selectedStaffId === $s->id ? 'selected' : '' ?>>
                            <?= h($name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?php if ($staff): ?>

            <?= $this->Form->create(null, ['url' => ['action' => 'shifts', $staff->id], 'id' => 'shifts-form']) ?>
            <input type="hidden" name="week_start" value="<?= h($weekStartStr) ?>">

            <div class="schedule-grid">

                <?php foreach ($dayNames as $dayNum => $label):
                    $shift   = $schedule[$dayNum] ?? null;
                    $active  = $shift !== null;
                    $start   = $shift ? $shift->start_time->format('H:i') : '09:00';
                    $end     = $shift ? $shift->end_time->format('H:i') : '17:00';
                    ?>

                    <div class="schedule-row <?= $active ? 'schedule-row--active' : '' ?>">

                        <label class="schedule-day-toggle">
                            <input type="hidden" name="days[<?= $dayNum ?>][active]" value="0">

                            <input type="checkbox"
                                   class="schedule-checkbox"
                                   name="days[<?= $dayNum ?>][active]"
                                   value="1"
                                   data-day="<?= $dayNum ?>"
                                <?= $active ? 'checked' : '' ?>
                                   onchange="toggleDay(<?= $dayNum ?>)">

                            <span class="schedule-day-label"><?= h($label) ?></span>
                        </label>

                        <div class="schedule-times" id="times-<?= $dayNum ?>" style="<?= $active ? '' : 'display:none' ?>">
                            <div class="schedule-time-group">
                                <label for="start-<?= $dayNum ?>">Start</label>
                                <input id="start-<?= $dayNum ?>" class="schedule-time-input" type="time" name="days[<?= $dayNum ?>][start_time]" value="<?= h($start) ?>">
                            </div>
                            <span class="schedule-time-sep">-</span>
                            <div class="schedule-time-group">
                                <label for="end-<?= $dayNum ?>">End</label>
                                <input id="end-<?= $dayNum ?>" class="schedule-time-input" type="time" name="days[<?= $dayNum ?>][end_time]" value="<?= h($end) ?>">
                            </div>
                        </div>

                        <span class="schedule-day-off" id="off-<?= $dayNum ?>" style="<?= $active ? 'display:none' : '' ?>">Day off</span>

                    </div>

                <?php endforeach; ?>

            </div>

            <?= $this->Form->end() ?>
        <?php endif; ?>

    </div>
</div>

<script>
    const BASE_URL = '<?= $this->Url->build(['controller'=>'Schedule','action'=>'shifts']) ?>';
    const STAFF_URL = '<?= $this->Url->build(['controller'=>'Schedule','action'=>'shifts','__ID__']) ?>';

    const currentStaff = <?= $selectedStaffId ?? 'null' ?>;
    const currentWeek  = '<?= $weekStartStr ?>';

    function buildUrl(staffId, week) {
        let url = staffId
            ? STAFF_URL.replace('__ID__', staffId)
            : BASE_URL;

        return url + '?week=' + week;
    }

    function switchStaff(id) {
        window.location.href = buildUrl(id, currentWeek);
    }

    function switchWeek(weekValue) {
        const match = weekValue.match(/^(\d{4})-W(\d{2})$/);
        if (!match) return;

        const year = +match[1];
        const week = +match[2];

        const jan4 = new Date(Date.UTC(year, 0, 4));
        const monday = new Date(jan4 - ((jan4.getUTCDay()+6)%7)*86400000 + (week-1)*7*86400000);

        const dateStr = monday.toISOString().slice(0,10);

        window.location.href = buildUrl(currentStaff, dateStr);
    }

    function toggleDay(day) {
        const times = document.getElementById('times-' + day);
        const off   = document.getElementById('off-' + day);
        const row   = document.querySelector(`[data-day="${day}"]`).closest('.schedule-row');
        const checked = document.querySelector(`[data-day="${day}"]`).checked;

        times.style.display = checked ? 'block' : 'none';
        off.style.display   = checked ? 'none'  : 'block';
        row.classList.toggle('schedule-row--active', checked);
    }
</script>
