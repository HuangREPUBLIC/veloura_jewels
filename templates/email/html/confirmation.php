<?php
/**
 * @var \App\View\AppView $this
 * @var string $firstName
 * @var string $userMessage
 */
?>
<p style="margin:0 0 10px;font-family:Arial,sans-serif;font-size:10px;
           letter-spacing:0.2em;text-transform:uppercase;color:#c9a84c;">
  Message Received
</p>

<h1 style="margin:0 0 24px;font-family:Georgia,'Times New Roman',serif;
            font-size:28px;font-weight:400;color:#284d40;line-height:1.2;">
  Thank you for reaching out.
</h1>

<p style="margin:0 0 20px;font-family:Arial,sans-serif;font-size:14px;
           color:#5c5549;line-height:1.8;">
  Hi <?= h($firstName) ?>, we have received your message and will get back to you
  as soon as possible.
</p>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="margin-bottom:32px;">
  <tr>
    <td style="padding:12px 16px;border-left:3px solid #e1a95e;
                background-color:#faf7f2;
                font-family:Arial,sans-serif;font-size:13px;
                color:#9b9080;line-height:1.7;font-style:italic;">
      <?= nl2br(h($userMessage)) ?>
    </td>
  </tr>
</table>

<p style="margin:0;font-family:Arial,sans-serif;font-size:14px;
           color:#5c5549;line-height:1.8;">
  Kind regards,<br>
  <strong style="color:#284d40;">Veloura Jewels</strong>
</p>
