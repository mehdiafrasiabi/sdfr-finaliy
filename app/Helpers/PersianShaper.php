<?php

namespace App\Helpers;

/**
 * Persian/Arabic Text Shaper for DomPDF
 *
 * Converts Persian/Arabic Unicode base characters to their correct contextual
 * presentation forms (initial / medial / final / isolated) so that DomPDF +
 * DejaVu Sans can render them as connected (joined) glyphs.
 *
 * Also handles LTR reversal needed for DomPDF's RTL word-order output.
 */
class PersianShaper
{
    // ── Joining type constants ───────────────────────────────────────
    // D = dual-joining (joins both left and right)
    // R = right-joining only
    // T = transparent (diacritics, doesn't affect joining)
    // U = non-joining

    // For each codepoint: [isolated, final, initial, medial]
    // null = use isolated form as fallback
    protected static array $forms = [
        // ‍ء Hamza
        0x0621 => [0xFE80, null,   null,   null],
        // آ Alef Madda
        0x0622 => [0xFE81, 0xFE82, null,   null],
        // أ Alef Hamza above
        0x0623 => [0xFE83, 0xFE84, null,   null],
        // ؤ Waw Hamza
        0x0624 => [0xFE85, 0xFE86, null,   null],
        // إ Alef Hamza below
        0x0625 => [0xFE87, 0xFE88, null,   null],
        // ئ Ya Hamza
        0x0626 => [0xFE89, 0xFE8A, 0xFE8B, 0xFE8C],
        // ا Alef
        0x0627 => [0xFE8D, 0xFE8E, null,   null],
        // ب Ba
        0x0628 => [0xFE8F, 0xFE90, 0xFE91, 0xFE92],
        // ة Ta Marbuta
        0x0629 => [0xFE93, 0xFE94, null,   null],
        // ت Ta
        0x062A => [0xFE95, 0xFE96, 0xFE97, 0xFE98],
        // ث Tha
        0x062B => [0xFE99, 0xFE9A, 0xFE9B, 0xFE9C],
        // ج Jeem
        0x062C => [0xFE9D, 0xFE9E, 0xFE9F, 0xFEA0],
        // ح Ha
        0x062D => [0xFEA1, 0xFEA2, 0xFEA3, 0xFEA4],
        // خ Kha
        0x062E => [0xFEA5, 0xFEA6, 0xFEA7, 0xFEA8],
        // د Dal
        0x062F => [0xFEA9, 0xFEAA, null,   null],
        // ذ Zal
        0x0630 => [0xFEAB, 0xFEAC, null,   null],
        // ر Ra
        0x0631 => [0xFEAD, 0xFEAE, null,   null],
        // ز Zain
        0x0632 => [0xFEAF, 0xFEB0, null,   null],
        // س Seen
        0x0633 => [0xFEB1, 0xFEB2, 0xFEB3, 0xFEB4],
        // ش Sheen
        0x0634 => [0xFEB5, 0xFEB6, 0xFEB7, 0xFEB8],
        // ص Sad
        0x0635 => [0xFEB9, 0xFEBA, 0xFEBB, 0xFEBC],
        // ض Dad
        0x0636 => [0xFEBD, 0xFEBE, 0xFEBF, 0xFEC0],
        // ط Ta heavy
        0x0637 => [0xFEC1, 0xFEC2, 0xFEC3, 0xFEC4],
        // ظ Za heavy
        0x0638 => [0xFEC5, 0xFEC6, 0xFEC7, 0xFEC8],
        // ع Ain
        0x0639 => [0xFEC9, 0xFECA, 0xFECB, 0xFECC],
        // غ Ghain
        0x063A => [0xFECD, 0xFECE, 0xFECF, 0xFED0],
        // ف Fa
        0x0641 => [0xFED1, 0xFED2, 0xFED3, 0xFED4],
        // ق Qaf
        0x0642 => [0xFED5, 0xFED6, 0xFED7, 0xFED8],
        // ك Kaf (Arabic)
        0x0643 => [0xFED9, 0xFEDA, 0xFEDB, 0xFEDC],
        // ل Lam
        0x0644 => [0xFEDD, 0xFEDE, 0xFEDF, 0xFEE0],
        // م Meem
        0x0645 => [0xFEE1, 0xFEE2, 0xFEE3, 0xFEE4],
        // ن Nun
        0x0646 => [0xFEE5, 0xFEE6, 0xFEE7, 0xFEE8],
        // ه Ha
        0x0647 => [0xFEE9, 0xFEEA, 0xFEEB, 0xFEEC],
        // و Waw
        0x0648 => [0xFEED, 0xFEEE, null,   null],
        // ى Alef Maqsura
        0x0649 => [0xFEEF, 0xFEF0, null,   null],
        // ي Ya (Arabic)
        0x064A => [0xFEF1, 0xFEF2, 0xFEF3, 0xFEF4],

        // ── Extended Persian characters ──────────────────────────────
        // پ Pe
        0x067E => [0xFB56, 0xFB57, 0xFB58, 0xFB59],
        // چ Che
        0x0686 => [0xFB7A, 0xFB7B, 0xFB7C, 0xFB7D],
        // ژ Zhe
        0x0698 => [0xFB8A, 0xFB8B, null,   null],
        // گ Gaf
        0x06AF => [0xFB92, 0xFB93, 0xFB94, 0xFB95],
        // ک Ka (Persian)
        0x06A9 => [0xFB8E, 0xFB8F, 0xFB90, 0xFB91],
        // ی Ya (Persian)
        0x06CC => [0xFBFC, 0xFBFD, 0xFBFE, 0xFBFF],
    ];

    // Dual-joining characters (connect on both sides)
    protected static array $dualJoining = [
        0x0626, 0x0628, 0x062A, 0x062B, 0x062C, 0x062D, 0x062E,
        0x0633, 0x0634, 0x0635, 0x0636, 0x0637, 0x0638, 0x0639, 0x063A,
        0x0641, 0x0642, 0x0643, 0x0644, 0x0645, 0x0646, 0x0647, 0x064A,
        0x0649, // ى — actually right-joining, but has final form
        // Persian
        0x067E, 0x0686, 0x06AF, 0x06A9, 0x06CC, 0x0629,
    ];

    // Right-joining only characters (join on right side only)
    protected static array $rightJoining = [
        0x0621, 0x0622, 0x0623, 0x0624, 0x0625, 0x0627,
        0x062F, 0x0630, 0x0631, 0x0632, 0x0648,
        // Persian
        0x0698,
    ];

    // Transparent / non-joining (diacritics, etc.)
    protected static array $transparent = [
        0x064B, 0x064C, 0x064D, 0x064E, 0x064F, 0x0650, 0x0651, 0x0652,
        0x0670, 0x0653, 0x0654, 0x0655,
    ];

    // ── Public API ───────────────────────────────────────────────────

    /**
     * Shape Persian/Arabic text for DomPDF.
     * - Converts base characters to presentation forms
     * - Reverses visual order of RTL runs (so DomPDF renders LTR correctly)
     *
     * Non-Persian words (Latin, digits, etc.) are kept as-is.
     */
    public static function shape(string $text): string
    {
        if (empty($text)) return $text;

        // Split on whitespace, keeping the delimiter
        $tokens = preg_split('/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $shaped = [];

        foreach ($tokens as $token) {
            if (preg_match('/^\s+$/', $token)) {
                $shaped[] = $token;
                continue;
            }
            $shaped[] = self::shapeWord($token);
        }

        // Reverse word order for RTL visual display in DomPDF
        $rtlWords  = [];
        $ltrBuffer = [];

        // Collect, then reverse RTL runs
        $result = [];
        foreach (array_reverse($shaped) as $token) {
            $result[] = $token;
        }

        return implode('', $result);
    }

    /**
     * Shape a single word/token.
     */
    protected static function shapeWord(string $word): string
    {
        $codepoints = self::toCodepoints($word);
        $n = count($codepoints);

        if ($n === 0) return $word;

        $output = [];

        for ($i = 0; $i < $n; $i++) {
            $cp = $codepoints[$i];

            // Not a shapeable Arabic character → keep as-is
            if (!isset(self::$forms[$cp])) {
                $output[] = self::fromCodepoint($cp);
                continue;
            }

            $forms = self::$forms[$cp];

            // Can this character join on its left side?
            $canJoinLeft = in_array($cp, self::$dualJoining, true);

            // Does the previous non-transparent character join on right?
            $prevJoins = false;
            if ($canJoinLeft) {
                for ($j = $i - 1; $j >= 0; $j--) {
                    $pcp = $codepoints[$j];
                    if (in_array($pcp, self::$transparent, true)) continue;
                    $prevJoins = in_array($pcp, self::$dualJoining, true)
                        || in_array($pcp, self::$rightJoining, true);
                    break;
                }
            }

            // Does the next non-transparent character join on left?
            $nextJoins = false;
            for ($j = $i + 1; $j < $n; $j++) {
                $ncp = $codepoints[$j];
                if (in_array($ncp, self::$transparent, true)) continue;
                $nextJoins = in_array($ncp, self::$dualJoining, true);
                break;
            }

            // Select form: [isolated, final, initial, medial]
            if ($prevJoins && $canJoinLeft && $nextJoins) {
                // Medial
                $form = $forms[3] ?? $forms[1] ?? $forms[0];
            } elseif ($prevJoins && $canJoinLeft) {
                // Final
                $form = $forms[1] ?? $forms[0];
            } elseif ($nextJoins) {
                // Initial
                $form = $forms[2] ?? $forms[0];
            } else {
                // Isolated
                $form = $forms[0];
            }

            $output[] = $form ? self::fromCodepoint($form) : self::fromCodepoint($cp);
        }

        // Reverse character order within the word for LTR PDF rendering
        return implode('', array_reverse($output));
    }

    // ── Unicode helpers ──────────────────────────────────────────────

    /** Convert a UTF-8 string to an array of Unicode codepoints */
    protected static function toCodepoints(string $str): array
    {
        $codepoints = [];
        $len = mb_strlen($str, 'UTF-8');
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($str, $i, 1, 'UTF-8');
            $codepoints[] = self::ord($char);
        }
        return $codepoints;
    }

    /** Get Unicode codepoint of a single character */
    protected static function ord(string $char): int
    {
        $bytes = unpack('N', mb_convert_encoding($char, 'UCS-4BE', 'UTF-8'));
        return $bytes[1];
    }

    /** Convert a Unicode codepoint to a UTF-8 character */
    protected static function fromCodepoint(int $cp): string
    {
        return mb_convert_encoding(pack('N', $cp), 'UTF-8', 'UCS-4BE');
    }
}
