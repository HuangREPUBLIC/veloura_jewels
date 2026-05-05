<?php
use App\Model\Entity\Schedule;

/** Setup */
$this->assign('title', $isAdminView ? 'Manage Schedule' : 'Team Schedule');
$this->Html->css('schedule', ['block' => true]);

$todayStr = (new DateTime('today'))->format('Y-m-d');
$action   = $isAdminView ? 'manage' : 'index';
?>

<div class="sched-page">

    <!-- ================= HEADER ================= -->
    <div class="sched-header">

        <div class="sched-header-left">
            <?= $this->Html->link('← Back', ['controller' => 'Users', 'action' => 'dashboard'], ['class' => 'back-link']) ?>
            <h3 class="page-title"><?= $this->fetch('title') ?></h3>
        </div>

        <div class="sched-header-right">

            <!-- Week Navigation -->
            <?php
            $currentMonday = new DateTime($weekStartStr);
            $prevWeek = (clone $currentMonday)->modify('-7 days')->format('Y-m-d');
            $nextWeek = (clone $currentMonday)->modify('+7 days')->format('Y-m-d');
            ?>

            <div class="sched-week-nav">
                <?= $this->Html->link('‹', ['action' => $action, '?' => ['week' => $prevWeek]], ['class' => 'sched-nav-btn']) ?>

                <div class="sched-week-picker-wrap">
                    <input type="week"
                           value="<?= h($currentWeekInput) ?>"
                           onchange="switchWeek(this.value)"
                           class="sched-week-input">
                    <span><?= h($weekRange) ?></span>
                </div>

                <?= $this->Html->link('›', ['action' => $action, '?' => ['week' => $nextWeek]], ['class' => 'sched-nav-btn']) ?>
            </div>

            <!-- Actions -->
            <div class="sched-header-actions">
                <?= $this->Html->link('My Shifts', ['action' => 'mySchedule'], ['class' => 'btn-secondary-sched']) ?>

                <?php if ($isAdminView): ?>
                    <?= $this->Html->link('+ Add Shifts', ['action' => 'add'], ['class' => 'btn-primary-sched']) ?>
                <?php elseif ($this->request->getAttribute('identity')->get('role') === 'admin'): ?>
                    <?= $this->Html->link('Manage', ['action' => 'manage', '?' => ['week' => $weekStartStr]], ['class' => 'btn-primary-sched']) ?>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- ================= GRID ================= -->
    <div class="sched-grid-wrap">

        <?php if (empty($staffOrder)): ?>

            <div class="sched-empty">
                <p>No shifts scheduled.</p>
                <?php if ($isAdminView): ?>
                    <?= $this->Html->link('Add shifts →', ['action' => 'add'], ['class' => 'btn-primary-sched']) ?>
                <?php endif; ?>
            </div>

        <?php else: ?>

            <div class="sched-grid">

                <!-- Header Row -->
                <div class="sched-grid-header">
                    <div class="sched-staff-col-head">Staff Member</div>

                    <?php foreach ($dayDates as $dayNum => $date):
                        $dateStr = $date->format('Y-m-d');
                        $isToday = $dateStr === $todayStr;
                        ?>
                        <div class="sched-day-head <?= $isToday ? 'sched-day-head--today' : '' ?>">
                            <span><?= Schedule::DAY_NAMES_SHORT[$dayNum] ?></span>
                            <span><?= $date->format('j') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Staff Rows -->
                <?php foreach ($staffOrder as $userId => $user): ?>

                    <?php
                    $fullName = trim($user->first_name . ' ' . $user->last_name) ?: $user->email;
                    $initial  = strtoupper(substr($fullName, 0, 1));
                    ?>

                    <div class="sched-grid-row">

                        <!-- Staff Info -->
                        <div class="sched-staff-cell">
                            <div class="sched-staff-avatar"><?= h($initial) ?></div>
                            <span class="sched-staff-name"><?= h($fullName) ?></span>
                        </div>

                        <!-- Days -->
                        <?php foreach ($dayDates as $dayNum => $date):
                            $shift   = $shiftsByUserDay[$userId][$dayNum] ?? null;
                            $isToday = $date->format('Y-m-d') === $todayStr;
                            ?>
                            <div class="sched-day-cell <?= $isToday ? 'sched-day-cell--today' : '' ?>">

                                <?php if ($shift): ?>

                                    <div class="sched-shift-card">
                                        <span><?= h($shift->time_range) ?></span>
                                        <span><?= number_format($shift->hours, 1) ?>h</span>

                                        <?php if ($isAdminView): ?>
                                            <div class="sched-shift-actions">
                                                <?= $this->Html->link('Edit', ['action' => 'add', $userId, '?' => ['week' => $weekStartStr]]) ?>
                                                <?= $this->Form->postLink('✕', ['action' => 'delete', $shift->id]) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                <?php else: ?>

                                    <div class="sched-day-off">
                                        <?php if ($isAdminView): ?>
                                            <?= $this->Html->link('+', ['action' => 'add', $userId, '?' => ['week' => $weekStartStr]]) ?>
                                        <?php else: ?>
                                            —
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

    <!-- ================= SUMMARY ================= -->
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
                    <span><?= $shiftCount ?> shifts · <?= number_format($totalHours, 1) ?>h</span>
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

        window.location.href = `<?= $this->Url->build(['controller'=>'Schedule','action'=>$action]) ?>?week=${dateStr}`;
    }
</script>
