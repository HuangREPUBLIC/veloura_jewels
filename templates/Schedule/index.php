<?php
/**
 * Schedule/index.php
 *
 * Shows the weekly roster as a grid — one row per staff member, one column per day.
 * This same template is used for two different views:
 *   - Staff view  (/schedule)        — read-only, no edit or delete buttons
 *   - Admin view  (/schedule/manage) — shows Edit/Delete on shifts, and a hoverable + to add new ones
 */

use App\Model\Entity\Schedule;

$this->assign('title', $isAdminView ? 'Manage Schedule' : 'Team Schedule');
$this->Html->css('schedule', ['block' => true]);

$todayStr = (new DateTime('today'))->format('Y-m-d');
?>

<div class="sched-page">

    <!-- ===== Page header: title on the left, week navigation on the right ===== -->
    <div class="sched-header">

        <div class="sched-header-left">
            <?= $this->Html->link('← Back', ['controller' => 'Users', 'action' => 'dashboard'], ['class' => 'back-link']) ?>
            <h3 class="page-title"><?= $this->fetch('title') ?></h3>
        </div>

        <div class="sched-header-right">
            <?php
            // Work out the Monday dates for the previous and next week links
            $monday   = new DateTime($weekStartStr);
            $prevWeek = (clone $monday)->modify('-7 days')->format('Y-m-d');
            $nextWeek = (clone $monday)->modify('+7 days')->format('Y-m-d');
            ?>

            <div class="sched-week-nav">
                <?= $this->Html->link('Prev', ['action' => 'index', '?' => ['week' => $prevWeek]], ['class' => 'sched-nav-btn']) ?>

                <div class="sched-week-picker-wrap">
                    <!-- The week input lets the user jump to any week directly.
                         When changed, switchWeek() converts it to a YYYY-MM-DD and reloads. -->
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


    <!-- ===== Main calendar grid ===== -->
    <div class="sched-grid-wrap">

            <!-- Grid layout: 1 staff column + 7 day columns.
                 Uses CSS grid with display:contents on rows so every cell lines up in the same grid. -->
            <div class="sched-grid">

                <!-- Column headers: "STAFF MEMBER" then Mon 4, Tue 5, etc. -->
                <div class="sched-grid-header">
                    <div class="sched-staff-col-head">Staff Member</div>

                    <?php foreach ($dayDates as $dayNumber => $date):
                        $isToday = $date->format('Y-m-d') === $todayStr;
                        ?>
                        <div class="sched-day-head <?= $isToday ? 'sched-day-head--today' : '' ?>">
                            <span class="sched-day-name"><?= Schedule::DAY_NAMES_SHORT[$dayNumber] ?></span>
                            <span class="sched-day-num"><?= $date->format('j') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>


                <!-- One row per staff member -->
                <?php foreach ($staffOrder as $userId => $user):
                    $fullName = trim($user->first_name . ' ' . $user->last_name) ?: $user->email;
                    $initial  = strtoupper(substr($fullName, 0, 1));
                    ?>
                    <!-- sched-grid-row uses display:contents so its children slot directly into the grid columns -->
                    <div class="sched-grid-row">

                        <!-- Staff name + avatar -->
                        <div class="sched-staff-cell">
                            <div class="sched-staff-avatar"><?= h($initial) ?></div>
                            <span class="sched-staff-name"><?= h($fullName) ?></span>
                        </div>

                        <!-- One cell per day of the week -->
                        <?php foreach ($dayDates as $dayNumber => $date):
                            // Look up whether this person has a shift on this day.
                            // $shiftsByUserDay is pre-indexed [userId][dayOfWeek] so this is just an array lookup.
                            $shift   = $shiftsByUserDay[$userId][$dayNumber] ?? null;
                            $isToday = $date->format('Y-m-d') === $todayStr;
                            ?>
                            <div class="sched-day-cell <?= $isToday ? 'sched-day-cell--today' : '' ?>">

                                <?php if ($shift): ?>
                                    <!-- This person is working — show the shift card -->
                                    <div class="sched-shift-card">
                                        <span class="sched-shift-times"><?= h($shift->time_range) ?></span>
                                        <span class="sched-shift-hours"><?= number_format($shift->hours, 1) ?>h</span>

                                        <?php if ($isAdminView): ?>
                                            <div class="sched-shift-actions">
                                                <!-- Edit goes to the add/edit form for this staff member's week -->
                                                <?= $this->Html->link(
                                                    'Edit',
                                                    ['action' => 'shifts', $userId, '?' => ['week' => $weekStartStr]],
                                                    ['class' => 'sched-shift-edit']
                                                ) ?>
                                                <!-- Delete removes just this one shift row -->
                                                <?= $this->Form->postLink(
                                                    'Delete',
                                                    ['action' => 'delete', $shift->id],
                                                    ['class' => 'sched-shift-delete', 'confirm' => 'Delete this shift?']
                                                ) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                <?php else: ?>
                                    <!-- No shift — show a hoverable + button for admins.
                                         The + is invisible until the row is hovered (handled in CSS). -->
                                    <?php if ($isAdminView): ?>
                                        <div class="sched-day-off">
                                            <?= $this->Html->link(
                                                '+',
                                                ['action' => 'shifts', $userId, '?' => ['week' => $weekStartStr]],
                                                ['class' => 'sched-add-btn', 'title' => 'Add shift']
                                            ) ?>
                                        </div>
                                    <?php endif; ?>

                                <?php endif; ?>

                            </div>
                        <?php endforeach; ?>

                    </div>
                <?php endforeach; ?>

            </div>
    </div>


    <!-- ===== Summary chips: total shifts and hours per staff member ===== -->
    <?php if (!empty($staffOrder)): ?>
        <div class="sched-summary">

            <?php foreach ($staffOrder as $userId => $user):
                // Add up this person's hours and shift count for the week
                $totalHours = 0.0;
                $shiftCount = 0;
                foreach ($dayDates as $dayNumber => $_) {
                    if (!empty($shiftsByUserDay[$userId][$dayNumber])) {
                        $totalHours += $shiftsByUserDay[$userId][$dayNumber]->hours;
                        $shiftCount++;
                    }
                }
                // Skip people with no shifts this week
                if ($shiftCount === 0) continue;
                ?>
                <div class="sched-summary-chip">
                    <strong><?= h($user->first_name ?: $user->email) ?></strong>
                    <span class="sched-summary-stat">
                        <?= $shiftCount ?> shift<?= $shiftCount !== 1 ? 's' : '' ?>
                        / <?= number_format($totalHours, 1) ?>h
                    </span>
                </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>

</div>


<script>
    /**
     * Called when the user changes the <input type="week"> picker.
     * The browser gives us a value like "2026-W19". We convert that to a
     * plain YYYY-MM-DD Monday date and reload the page with it as ?week=
     */
    function switchWeek(weekValue) {
        const match = weekValue.match(/^(\d{4})-W(\d{2})$/);
        if (!match) return;

        const year = parseInt(match[1]);
        const week = parseInt(match[2]);

        // ISO week: find Jan 4 (always in week 1), snap to its Monday, then jump forward by week count
        const jan4       = new Date(Date.UTC(year, 0, 4));
        const jan4Monday = new Date(jan4 - ((jan4.getUTCDay() + 6) % 7) * 86400000);
        const weekMonday = new Date(jan4Monday.getTime() + (week - 1) * 7 * 86400000);

        const dateStr = weekMonday.toISOString().slice(0, 10);

        window.location.href = `<?= $this->Url->build(['controller' => 'Schedule', 'action' => 'index']) ?>?week=${dateStr}`;
    }
</script>
