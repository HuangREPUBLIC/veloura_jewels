<?php
/**
 * Base HTML email layout.
 *
 * A centred card on a beige field: emerald masthead over an earth-yellow rule,
 * a white content panel, the trust bar, then the footer. A quiet legal line
 * sits outside the card.
 *
 * Templates can set three blocks:
 *   title     - the <title> and, by convention, the subject line's twin
 *   preheader - the hidden line most inboxes show next to the subject
 *   footnote  - the "why you received this" line under the card
 *   trustbar  - set to 'on' to show the returns/shipping/warranty strip. Off by
 *               default: it belongs on commerce mail, not on a password reset,
 *               where retail promises read as filler under a security message.
 *
 * Radii are 12px on the outer card and 8px on the panels inside it, so nested
 * surfaces read as layers instead of two rings of nearly the same curve.
 *
 * @var \App\View\AppView $this
 */

use App\View\EmailTheme as T;
use Cake\Core\Configure;

$preheader = trim(strip_tags((string)$this->fetch('preheader')));
$footnote = trim(strip_tags((string)$this->fetch('footnote')));
if ($footnote === '') {
    $footnote = 'You received this email because it relates to your Veloura Jewels account.';
}

/**
 * Configure these under Veloura.social to bring the row back, e.g.
 * 'Veloura' => ['social' => ['Instagram' => 'https://instagram.com/...']].
 * Unset, the row is dropped rather than shipping href="#" to a real inbox.
 */
$social = array_filter((array)Configure::read('Veloura.social', []));

$showTrustBar = trim((string)$this->fetch('trustbar')) === 'on';
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="x-apple-disable-message-reformatting">
<meta name="format-detection" content="telephone=no,address=no,email=no,date=no">
<meta name="color-scheme" content="light dark">
<meta name="supported-color-schemes" content="light dark">
<title><?= h($this->fetch('title', 'Veloura Jewels')) ?></title>
<!--[if mso]>
<noscript><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
<![endif]-->
<style type="text/css">
  body { margin:0; padding:0; width:100% !important; background-color:<?= T::BEIGE ?>;
         -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
  table, td { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
  img { border:0; line-height:100%; outline:none; text-decoration:none; -ms-interpolation-mode:bicubic; }
  a { color:<?= T::EMERALD ?>; }

  @media only screen and (max-width:620px) {
    .card    { width:100% !important; }
    .pad     { padding:20px 14px !important; }
    .inner   { padding:30px 22px !important; }
    .mastpad { padding:26px 22px 20px !important; }
    .footpad { padding:24px 22px !important; }
    .h1      { font-size:23px !important; }
    /* Three 33% cells squeeze each promise into a column of single words. */
    .trust-cell { display:block !important; width:100% !important;
                  border-right:0 !important; border-bottom:1px solid <?= T::HAIRLINE ?> !important; }
    .trust-cell-last { border-bottom:0 !important; }
  }

  @media (prefers-color-scheme: dark) {
    body, .field { background-color:<?= T::DARK_BG ?> !important; }
    .card   { background-color:<?= T::DARK_CARD ?> !important; border-color:<?= T::DARK_BORDER ?> !important; }
    .inner  { background-color:<?= T::DARK_CARD ?> !important; border-color:<?= T::DARK_BORDER ?> !important; }
    .panel  { background-color:<?= T::DARK_PANEL ?> !important; border-color:<?= T::DARK_BORDER ?> !important; }
    .foot   { background-color:<?= T::DARK_FOOT ?> !important; border-color:<?= T::DARK_BORDER ?> !important; }
    .hair   { border-color:<?= T::DARK_BORDER ?> !important; }
    .t-ink  { color:<?= T::DARK_TEXT ?> !important; }
    .t-mid  { color:<?= T::DARK_MID ?> !important; }
    .t-soft, .trust-cell { color:<?= T::DARK_SOFT ?> !important; }
    .t-accent, .t-accent a { color:<?= T::DARK_ACCENT ?> !important; }
  }
</style>
</head>
<body style="margin:0;padding:0;background-color:<?= T::BEIGE ?>;">

<div style="display:none;font-size:1px;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;">
  <?= h($preheader) ?>
  <?php // Filler stops the client from pulling body copy into the inbox preview. ?>
  <?= str_repeat('&#847;&zwnj;&nbsp;', 40) ?>
</div>

<table role="presentation" class="field" width="100%" cellpadding="0" cellspacing="0" border="0"
       style="width:100%;background-color:<?= T::BEIGE ?>;">
  <tr>
    <td align="center" style="padding:44px 16px 40px;">

      <table role="presentation" class="card" cellpadding="0" cellspacing="0" border="0"
             style="width:100%;max-width:600px;background-color:<?= T::BEIGE_LIGHT ?>;
                    border:1px solid <?= T::BORDER ?>;border-radius:12px;overflow:hidden;">

        <!-- MASTHEAD -->
        <tr>
          <td class="mastpad" align="center"
              style="background-color:<?= T::EMERALD ?>;padding:32px 40px 26px;text-align:center;
                     border-bottom:2px solid <?= T::EARTH_YELLOW ?>;">
            <p style="margin:0 0 6px;font-family:<?= T::FONT_DISPLAY ?>;font-size:24px;font-weight:400;
                      letter-spacing:0.24em;text-transform:uppercase;color:<?= T::BEIGE_LIGHT ?>;line-height:1.1;">
              Veloura Jewels
            </p>
            <p style="margin:0;font-family:<?= T::FONT_BODY ?>;font-size:9px;letter-spacing:0.18em;
                      text-transform:uppercase;color:rgba(240,237,228,0.6);">
              Fine Jewellery &amp; Home D&eacute;cor
            </p>
          </td>
        </tr>

        <!-- CONTENT -->
        <tr>
          <td class="pad" style="padding:26px 26px 18px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                   class="inner"
                   style="width:100%;background-color:<?= T::PAPER ?>;border:1px solid <?= T::BORDER ?>;
                          border-radius:8px;overflow:hidden;">
              <tr>
                <td class="inner t-ink"
                    style="padding:40px 34px 34px;font-family:<?= T::FONT_BODY ?>;
                           font-size:15px;line-height:1.7;color:<?= T::BODY ?>;">
                  <?= $this->fetch('content') ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- TRUST BAR -->
        <?php if ($showTrustBar) : ?>
        <tr>
          <td class="pad" style="padding:0 26px 24px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                   class="inner"
                   style="width:100%;background-color:<?= T::PAPER ?>;border:1px solid <?= T::BORDER ?>;
                          border-radius:8px;overflow:hidden;">
              <tr>
                <td class="trust-cell" align="center" width="33%"
                    style="padding:13px 6px;font-family:<?= T::FONT_BODY ?>;font-size:10px;
                           letter-spacing:0.1em;text-transform:uppercase;color:<?= T::MUTED ?>;
                           border-right:1px solid <?= T::HAIRLINE ?>;">
                  30-Day Returns
                </td>
                <td class="trust-cell" align="center" width="34%"
                    style="padding:13px 6px;font-family:<?= T::FONT_BODY ?>;font-size:10px;
                           letter-spacing:0.1em;text-transform:uppercase;color:<?= T::MUTED ?>;
                           border-right:1px solid <?= T::HAIRLINE ?>;">
                  Free Shipping
                </td>
                <td class="trust-cell trust-cell-last" align="center" width="33%"
                    style="padding:13px 6px;font-family:<?= T::FONT_BODY ?>;font-size:10px;
                           letter-spacing:0.1em;text-transform:uppercase;color:<?= T::MUTED ?>;">
                  3-Year Warranty
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <?php endif; ?>

        <!-- FOOTER -->
        <tr>
          <td class="footpad" align="center"
              style="background-color:<?= T::EMERALD ?>;padding:28px 40px 26px;text-align:center;">
            <p style="margin:0 0 <?= $social ? '14px' : '10px' ?>;font-family:<?= T::FONT_DISPLAY ?>;
                      font-size:13px;letter-spacing:0.22em;text-transform:uppercase;color:<?= T::BEIGE_LIGHT ?>;">
              Veloura Jewels
            </p>
            <?php if ($social) : ?>
            <p style="margin:0 0 14px;font-size:0;line-height:0;">
              <?php $first = true; foreach ($social as $name => $url) : ?>
                <?php if (!$first) : ?>
                <span style="font-family:<?= T::FONT_BODY ?>;font-size:10px;color:rgba(240,237,228,0.25);">&#124;</span>
                <?php endif; $first = false; ?>
                <a href="<?= h($url) ?>"
                   style="font-family:<?= T::FONT_BODY ?>;font-size:10px;letter-spacing:0.1em;
                          text-transform:uppercase;color:rgba(240,237,228,0.7);text-decoration:none;margin:0 8px;">
                  <?= h($name) ?>
                </a>
              <?php endforeach; ?>
            </p>
            <?php endif; ?>
            <p style="margin:0;font-family:<?= T::FONT_BODY ?>;font-size:10px;
                      color:rgba(240,237,228,0.4);letter-spacing:0.04em;line-height:1.9;">
              &copy; <?= date('Y') ?> Veloura Jewels. All rights reserved.
            </p>
          </td>
        </tr>

      </table>

      <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:600px;">
        <tr>
          <td align="center" style="padding:18px 12px 0;text-align:center;">
            <p class="t-soft" style="margin:0;font-family:<?= T::FONT_BODY ?>;font-size:11px;
                      line-height:1.7;color:<?= T::MUTED ?>;">
              <?= h($footnote) ?>
            </p>
          </td>
        </tr>
      </table>

    </td>
  </tr>
</table>

</body>
</html>
