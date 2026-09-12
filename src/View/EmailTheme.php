<?php
declare(strict_types=1);

namespace App\View;

/**
 * Colour and type values shared by every email template.
 *
 * Mail clients strip CSS custom properties, so webroot/css/tokens.css cannot be
 * reused directly. This class mirrors those tokens as literal values so the
 * layout, the shared email elements and each individual email stay in step
 * instead of drifting apart one hardcoded hex at a time — which is exactly what
 * had happened: the templates carried #c9a84c and #e1a95e, neither of which
 * exists in tokens.css.
 *
 * Light values map 1:1 onto tokens.css unless noted. The DARK_* values have no
 * counterpart on the website, which is light only; they exist for the
 * prefers-color-scheme block in the email layout.
 */
final class EmailTheme
{
    /** tokens.css --emerald / --emerald-light / --emerald-dark */
    public const EMERALD = '#284D40';
    public const EMERALD_LIGHT = '#4F7867';
    public const EMERALD_DARK = '#18382F';

    /** tokens.css --beige / --beige-light */
    public const BEIGE = '#DAD7C5';
    public const BEIGE_LIGHT = '#F0EDE4';
    public const PAPER = '#FFFFFF';

    /** tokens.css --color-ink / --color-body / --color-text-muted */
    public const INK = '#2A2A26';
    public const BODY = '#3D3D3A';
    public const MUTED = '#6B6457';

    /**
     * Decorative gold, tokens.css --emerald-gold. Rules and separators only:
     * at 2.8:1 on white it is too weak to carry text.
     */
    public const GOLD = '#B7975A';

    /**
     * Gold for small type, tokens.css --color-price. Reaches 4.8:1 on white,
     * so the uppercase eyebrow labels stay legible.
     */
    public const GOLD_DEEP = '#8A6D3B';

    /** tokens.css --earth-yellow, the masthead underline. */
    public const EARTH_YELLOW = '#D9A75E';

    /** Flattened form of the storefront card border, rgba(120,108,59,0.2) over --beige-light. */
    public const BORDER = '#D8D3C2';

    /** Hairline for rules inside the white content panel. */
    public const HAIRLINE = '#E8E3D8';

    public const DARK_BG = '#12201B';
    public const DARK_CARD = '#1A2B24';
    public const DARK_PANEL = '#213328';
    public const DARK_FOOT = '#16241E';
    public const DARK_BORDER = '#33453B';
    public const DARK_TEXT = '#EDEAE0';
    public const DARK_MID = '#CFC9BA';
    public const DARK_SOFT = '#A79F8D';
    public const DARK_ACCENT = '#C9AF77';

    /**
     * Raleway and Cormorant Garamond are webfonts the storefront loads over the
     * network; mail clients overwhelmingly refuse that, so each stack names the
     * brand face first for the rare client that has it and falls back to the
     * closest system face. Georgia carries the display voice in practice.
     */
    public const FONT_DISPLAY = "'Cormorant Garamond',Georgia,'Times New Roman',serif";
    public const FONT_BODY = "'Raleway','Helvetica Neue',Helvetica,Arial,sans-serif";
    public const FONT_MONO = "SFMono-Regular,Consolas,'Liberation Mono',Menlo,monospace";
}
