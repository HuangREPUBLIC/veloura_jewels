<?php
/**
 * Primary call-to-action button for emails.
 *
 * Outlook on Windows ignores padding on an anchor, so the button is drawn twice:
 * a VML roundrect only Outlook sees, and a normal anchor every other client
 * sees. The VML shape needs a fixed pixel width, so pass $width when the label
 * runs longer than about 18 characters.
 *
 * Height is 48px rather than the 43px the old inline buttons came to, so the
 * tap target clears 44px on a phone.
 *
 * @var \App\View\AppView $this
 * @var string $url   Destination, already absolute.
 * @var string $label Button text. Three words at most.
 * @var int    $width Optional fixed width in px, used by the Outlook shape.
 * @var bool   $last  True when nothing follows the button, so the trailing
 *                    margin does not stack with the panel's own padding.
 */

use App\View\EmailTheme as T;

$width = $width ?? 240;
$height = 48;
$gapBelow = !empty($last) ? '0' : '32px';
?>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto <?= $gapBelow ?>;">
  <tr>
    <td align="center">
      <!--[if mso]>
      <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word"
                   href="<?= h($url) ?>" arcsize="8%"
                   style="height:<?= $height ?>px;v-text-anchor:middle;width:<?= (int)$width ?>px;"
                   fillcolor="<?= T::EMERALD ?>" strokecolor="<?= T::EMERALD_DARK ?>">
        <w:anchorlock/>
        <center style="color:<?= T::BEIGE_LIGHT ?>;font-family:Arial,sans-serif;font-size:11px;font-weight:700;letter-spacing:1.6px;">
          <?= h(strtoupper($label)) ?>
        </center>
      </v:roundrect>
      <![endif]-->
      <!--[if !mso]><!-- -->
      <a href="<?= h($url) ?>" target="_blank"
         style="display:inline-block;background-color:<?= T::EMERALD ?>;border:1px solid <?= T::EMERALD_DARK ?>;
                border-radius:4px;padding:17px 40px;font-family:<?= T::FONT_BODY ?>;font-size:11px;
                font-weight:700;line-height:1;letter-spacing:0.16em;text-transform:uppercase;
                color:<?= T::BEIGE_LIGHT ?>;text-decoration:none;">
        <?= h($label) ?>
      </a>
      <!--<![endif]-->
    </td>
  </tr>
</table>
