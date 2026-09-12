<?php
/**
 * Generic email body: a person's message wrapped in the Veloura shell.
 *
 * This is the template CakePHP falls back to when nothing calls setTemplate(),
 * and it is what ContactSubmissionsController::reply() uses to answer a
 * customer enquiry. It used to be a standalone 167-line HTML document on a
 * #4a3728 brown field, so it bypassed the layout and read as a different brand.
 *
 * Renderer::render() injects the body as the $content view var; $this->fetch()
 * only reaches it from a layout, so read the var first and keep the block as a
 * fallback.
 *
 * @var \App\View\AppView $this
 * @var string|null $content
 * @var string|null $subjectLine Optional, used for the <title> and the heading.
 */

use App\View\EmailTheme as T;

$body = trim((string)($content ?? ''));
if ($body === '') {
    $body = trim((string)$this->fetch('content'));
}

$heading = trim((string)($subjectLine ?? ''));

/**
 * The inbox preview line: the opening of what was actually written beats a
 * canned sentence, and a greeting on its own tells the reader nothing.
 */
$preheader = '';
foreach (preg_split('/\R/', $body) ?: [] as $line) {
    $line = trim($line);
    if ($line !== '' && !preg_match('/^(hi|hello|hey|dear)\b/i', $line)) {
        $preheader = mb_strimwidth($line, 0, 120, '…');
        break;
    }
}

$this->assign('title', $heading !== '' ? $heading : 'A message from Veloura Jewels');
$this->assign('preheader', $preheader);
$this->assign('footnote', 'You received this email because you contacted Veloura Jewels.');
?>
<?php if ($heading !== '') : ?>
<p class="t-accent" style="margin:0 0 10px;font-family:<?= T::FONT_BODY ?>;font-size:10px;font-weight:700;
          letter-spacing:0.2em;text-transform:uppercase;color:<?= T::GOLD_DEEP ?>;">
  A Reply From Our Team
</p>

<h1 class="h1 t-ink" style="margin:0 0 22px;font-family:<?= T::FONT_DISPLAY ?>;font-size:26px;
           font-weight:400;line-height:1.25;color:<?= T::EMERALD ?>;">
  <?= h($heading) ?>
</h1>
<?php endif; ?>

<p class="t-mid" style="margin:0;font-family:<?= T::FONT_BODY ?>;font-size:15px;
          line-height:1.75;color:<?= T::BODY ?>;">
  <?= nl2br(h($body)) ?>
</p>
