<?php
$this->assign('title', 'Manage Schedule');
$this->Html->css('login', ['block' => true]);

// Helper functions
$selectedStaffId = $staff->id ?? null;
$selectedName = $staff
    ? trim($staff->first_name . ' ' . $staff->last_name) ?: $staff->email
    : null;
?>

<div class="login-page">
    <div class="users form content login-card--wide">

        <?= $this->Html->link(__('← Back'), ['action' => 'index'], ['class' => 'back-link']) ?>

        <h3>Schedule</h3>

        <!-- Week Picker -->
        <div class="schedule-week-picker">
            <input type="week"
                   value="<?= h($currentWeekInput) ?>"
                   min="<?= h($minWeekInput) ?>"
                   onchange="switchWeek(this.value)"
                   class="schedule-time-input">
            <span><?= h($weekRange) ?></span>
        </div>

        <!-- Staff Select -->
        <div class="schedule-selector">
            <label>Select Staff Member</label>

            <select onchange="switchStaff(this.value)" class="schedule-time-input">
                <option value="">— Choose staff —</option>

                <?php foreach ($staffList as $s):
                    $name = trim($s->first_name . ' ' . $s->last_name) ?: $s->email;
                    ?>
                    <option value="<?= $s->id ?>" <?= $selectedStaffId === $s->id ? 'selected' : '' ?>>
                        <?= h($name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if ($staff): ?>

            <!-- Form -->
            <?= $this->Form->create(null, ['url' => ['action' => 'add', $staff->id]]) ?>
            <input type="hidden" name="week_start" value="<?= h($weekStartStr) ?>">

            <p class="form-hint">
                Set shifts for <strong><?= h($selectedName) ?></strong>
            </p>

            <div class="schedule-grid">

                <?php foreach ($dayNames as $dayNum => $label):
                    $shift   = $schedule[$dayNum] ?? null;
                    $active  = $shift !== null;
                    $start   = $shift ? $shift->start_time->format('H:i') : '09:00';
                    $end     = $shift ? $shift->end_time->format('H:i') : '17:00';
                    ?>

                    <div class="schedule-row <?= $active ? 'Schedule-row--active' : '' ?>">

                        <!-- Toggle -->
                        <label>
                            <input type="hidden" name="days[<?= $dayNum ?>][active]" value="0">

                            <input type="checkbox"
                                   name="days[<?= $dayNum ?>][active]"
                                   value="1"
                                   data-day="<?= $dayNum ?>"
                                <?= $active ? 'checked' : '' ?>
                                   onchange="toggleDay(<?= $dayNum ?>)">

                            <?= $label ?>
                        </label>

                        <!-- Times -->
                        <div id="times-<?= $dayNum ?>" style="<?= $active ? '' : 'display:none' ?>">
                            <input type="time" name="days[<?= $dayNum ?>][start_time]" value="<?= $start ?>">
                            –
                            <input type="time" name="days[<?= $dayNum ?>][end_time]" value="<?= $end ?>">
                        </div>

                        <!-- Off label -->
                        <span id="off-<?= $dayNum ?>" style="<?= $active ? 'display:none' : '' ?>">
        Day off
    </span>

                    </div>

                <?php endforeach; ?>

            </div>

            <button type="submit" class="login-button">Save</button>

            <?= $this->Form->end() ?>
        <?php endif; ?>

    </div>
</div>

<!-- ================= JS ================= -->
<script>
    const BASE_URL = '<?= $this->Url->build(['controller'=>'Schedule','action'=>'add']) ?>';
    const STAFF_URL = '<?= $this->Url->build(['controller'=>'Schedule','action'=>'add','__ID__']) ?>';

    const currentStaff = <?= $selectedStaffId ?? 'null' ?>;
    const currentWeek  = '<?= $weekStartStr ?>';

    // Build URL
    function buildUrl(staffId, week) {
        let url = staffId
            ? STAFF_URL.replace('__ID__', staffId)
            : BASE_URL;

        return url + '?week=' + week;
    }

    // Change staff
    function switchStaff(id) {
        window.location.href = buildUrl(id, currentWeek);
    }

    // Change week
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

    // Toggle day UI
    function toggleDay(day) {
        const times = document.getElementById('times-' + day);
        const off   = document.getElementById('off-' + day);
        const row   = document.querySelector(`[data-day="${day}"]`).closest('.schedule-row');
        const checked = document.querySelector(`[data-day="${day}"]`).checked;

        times.style.display = checked ? 'block' : 'none';
        off.style.display   = checked ? 'none'  : 'block';
        row.classList.toggle('Schedule-row--active', checked);
    }
</script>
