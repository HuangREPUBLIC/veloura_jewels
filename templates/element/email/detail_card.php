<?php
/**
 * Panel of label/value facts: the quiet "here is what we have on record" block.
 *
 * Built for the reset-password account line and the order summary header, but
 * kept generic so any email that has to restate a handful of facts uses this
 * instead of hand-rolling another table. Keep it to six rows; past that the
 * reader stops scanning and the panel turns into a spec sheet.
 *
 * Radius is 8px inside the content panel's 24px padding, so the two surfaces
 * read as separate layers rather than two rings of nearly the same curve.
 *
 * @var \App\View\AppView $this
 * @var string $title Optional heading. Pass '' or omit to drop it.
 * @var string $note  Optional supporting line under the title.
 * @var array<int, array{label: string, value: string, mono?: bool}> $rows
 */

use App\View\EmailTheme as T;

$title = $title ?? '';
$note = $note ?? '';
$rows = array_values($rows ?? []);
?>
<table role="presentation" class="panel" width="100%" cellpadding="0" cellspacing="0" border="0"
       style="width:100%;margin:0 0 28px;background-color:<?= T::BEIGE_LIGHT ?>;
              border:1px solid <?= T::BORDER ?>;border-radius:8px;">
  <?php if ($title !== '') : ?>
  <tr>
    <td class="hair" align="center" style="padding:18px 22px 14px;border-bottom:1px solid <?= T::BORDER ?>;text-align:center;">
      <p class="t-ink" style="margin:0;font-family:<?= T::FONT_DISPLAY ?>;font-size:20px;line-height:1.3;color:<?= T::INK ?>;">
        <?= h($title) ?>
      </p>
      <?php if ($note !== '') : ?>
      <p class="t-soft" style="margin:6px 0 0;font-family:<?= T::FONT_BODY ?>;font-size:13px;line-height:1.5;color:<?= T::MUTED ?>;">
        <?= h($note) ?>
      </p>
      <?php endif; ?>
    </td>
  </tr>
  <?php endif; ?>

  <?php foreach ($rows as $i => $row) : ?>
  <tr>
    <?php // A hairline sits between rows only, never above the first or below the last. ?>
    <td class="hair" align="center"
        style="padding:14px 22px;text-align:center;<?= $i > 0 ? 'border-top:1px solid ' . T::BORDER . ';' : '' ?>">
      <p class="t-soft" style="margin:0 0 5px;font-family:<?= T::FONT_BODY ?>;font-size:10px;font-weight:700;
                letter-spacing:0.14em;text-transform:uppercase;color:<?= T::MUTED ?>;">
        <?= h($row['label']) ?>
      </p>
      <p class="t-ink" style="margin:0;font-family:<?= !empty($row['mono']) ? T::FONT_MONO : T::FONT_BODY ?>;
                font-size:<?= !empty($row['mono']) ? '13.5px' : '15px' ?>;font-weight:600;line-height:1.45;
                font-variant-numeric:tabular-nums;color:<?= T::INK ?>;<?= !empty($row['mono']) ? 'letter-spacing:0.06em;' : '' ?>">
        <?= h($row['value']) ?>
      </p>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
