<?php
use App\Model\Entity\Schedule;

$this->assign('title', $isAdminView ? 'Manage Schedule' : 'Team Schedule');
$this->Html->css('schedule', ['block' => true]);

$todayStr = (new DateTime('today'))->format('Y-m-d');
?>

<div class="sched-page">

    <div class="sched-header">

        <div class="sched-header-left">
            <?= $this->Html->link(__('← Back'), ['controller' => 'Users', 'action' => 'dashboard'], ['class' => 'back-link']) ?>
            <h3 class="page-title"><?= $this->fetch('title') ?></h3>
        </div>

        <div class="sched-header-right">

            <?php
            $currentMonday = new DateTime($weekStartStr);
            $prevWeek = (clone $currentMonday)->modify('-7 days')->format('Y-m-d');
            $nextWeek = (clone $currentMonday)->modify('+7 days')->format('Y-m-d');
            ?>

            <div class="sched-week-nav">
                <?= $this->Html->link('Prev', ['action' => 'index', '?' => ['week' => $prevWeek]], ['class' => 'sched-nav-btn']) ?>

                <div class="sched-week-picker-wrap">
                    <input type="week"
                           value="<?= h($currentWeekInput) ?>"
                           onchange="switchWeek(this.value)"
                           class="sched-week-input">
                    <span class="sched-week-label"><?= h($weekRange) ?></span>
                </div>

                <?= $this->Html->link('Next', ['action' => 'index', '?' => ['week' => $nextWeek]], ['class' => 'sched-nav-btn']) ?>
            </div>

        </div>
    </div>

    <div class="sched-grid-wrap">

        <?php if (empty($staffOrder)): ?>

            <div class="sched-empty">
                <p>No shifts scheduled.</p>
                <?php if ($isAdminView): ?>
                    <?= $this->Html->link('Add shifts', ['action' => 'shifts'], ['class' => 'btn-primary-sched']) ?>
                <?php endif; ?>
            </div>

        <?php else: ?>

            <div class="sched-grid">

                <div class="sched-grid-header">
                    <div class="sched-staff-col-head">Staff Member</div>

                    <?php foreach ($dayDates as $dayNum => $date):
                        $dateStr = $date->format('Y-m-d');
                        $isToday = $dateStr === $todayStr;
                        ?>
                        <div class="sched-day-head <?= $isToday ? 'sched-day-head--today' : '' ?>">
                            <span class="sched-day-name"><?= Schedule::DAY_NAMES_SHORT[$dayNum] ?></span>
                            <span class="sched-day-num"><?= $date->format('j') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($staffOrder as $userId => $user): ?>

                    <?php
                    $fullName = trim($user->first_name . ' ' . $user->last_name) ?: $user->email;
                    $initial  = strtoupper(substr($fullName, 0, 1));
                    ?>

                    <div class="sched-grid-row">

                        <div class="sched-staff-cell">
                            <div class="sched-staff-avatar"><?= h($initial) ?></div>
                            <span class="sched-staff-name"><?= h($fullName) ?></span>
                        </div>

                        <?php foreach ($dayDates as $dayNum => $date):
                            $shift   = $shiftsByUserDay[$userId][$dayNum] ?? null;
                            $isToday = $date->format('Y-m-d') === $todayStr;
                            ?>
                            <div class="sched-day-cell <?= $isToday ? 'sched-day-cell--today' : '' ?>">

                                <?php if ($shift): ?>

                                    <div class="sched-shift-card">
                                        <span class="sched-shift-times"><?= h($shift->time_range) ?></span>
                                        <span class="sched-shift-hours"><?= number_format($shift->hours, 1) ?>h</span>

                                        <?php if ($isAdminView): ?>
                                            <div class="sched-shift-actions">
                                                <?= $this->Html->link('Edit', ['action' => 'shifts', $userId, '?' => ['week' => $weekStartStr]], ['class' => 'sched-shift-edit']) ?>
                                                <?= $this->Form->postLink('Delete', ['action' => 'delete', $shift->id], ['class' => 'sched-shift-delete', 'confirm' => 'Delete this shift?']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                <?php else: ?>

                                    <div class="sched-day-off">
                                        <?php if ($isAdminView): ?>
                                            <?= $this->Html->link('Add', ['action' => 'shifts', $userId, '?' => ['week' => $weekStartStr]], ['class' => 'sched-add-btn']) ?>
                                        <?php else: ?>
                                            <span class="sched-day-off-dash">-</span>
                                        <?php endif; ?>
                                    </div>

                                <?php endif; ?>

                            </div>
                        <?php endforeach; ?>

                    </div>

                <?php endforeach; ?>

            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($staffOrder)): ?>

        <div class="sched-summary">

            <?php foreach ($staffOrder as $userId => $user): ?>

                <?php
                $totalHours = 0;
                $shiftCount = 0;

                foreach ($dayDates as $d => $_) {
                    if (!empty($shiftsByUserDay[$userId][$d])) {
                        $totalHours += $shiftsByUserDay[$userId][$d]->hours;
                        $shiftCount++;
                    }
                }

                if ($shiftCount === 0) continue;
                ?>

                <div class="sched-summary-chip">
                    <strong><?= h($user->first_name ?: $user->email) ?></strong>
                    <span class="sched-summary-stat"><?= $shiftCount ?> shifts / <?= number_format($totalHours, 1) ?>h</span>
                </div>

            <?php endforeach; ?>

        </div>
    <?php endif; ?>

</div>


<script>
    function switchWeek(weekValue) {
        const match = weekValue.match(/^(\d{4})-W(\d{2})$/);
        if (!match) return;

        const year = parseInt(match[1]);
        const week = parseInt(match[2]);

        const jan4 = new Date(Date.UTC(year, 0, 4));
        const monday = new Date(jan4 - ((jan4.getUTCDay()+6)%7)*86400000 + (week-1)*7*86400000);

        const dateStr = monday.toISOString().slice(0,10);

        window.location.href = `<?= $this->Url->build(['controller'=>'Schedule','action'=>'index']) ?>?week=${dateStr}`;
    }
</script>
