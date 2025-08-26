<?php
declare(strict_types=1);

/**
 * Extract key data from flyer text using regex and simple heuristics.
 * Returns an associative array. Empty values are omitted.
 *
 * Expected keys:
 * - name, address, phone (array), email, website, accreditations (array), courses (array)
 */
function extractFlyerData(string $text): array
{
    $data = [
        'name' => null,
        'address' => null,
        'phone' => [],
        'email' => null,
        'website' => null,
        'accreditations' => [],
        'courses' => [],
    ];

    // Work on a normalized copy for matching, keep original for some heuristics
    $norm = preg_replace('/[\t\r\f]+/', ' ', $text);
    $norm = preg_replace('/\s{2,}/', ' ', (string) $norm);

    // Name: look for lines with common educational institution words
    if (preg_match('/^(.*?(college|university|institute|academy|school).*)$/im', $text, $m)) {
        $data['name'] = trim($m[1]);
    }

    // Address: simplistic patterns, SA-like street lines with numbers, street/road/avenue
    if (preg_match('/\b(\d+\s+[A-Za-z][A-Za-z\s-]*\s(?:Street|St|Road|Rd|Avenue|Ave|Lane|Ln|Drive|Dr)\b.*?\d{3,5})/i', $text, $m)) {
        $data['address'] = trim($m[1]);
    } elseif (preg_match('/(P\.O\.? Box\s*\d+\b.*)/i', $text, $m)) {
        $data['address'] = trim($m[1]);
    }

    // Phones (SA formats like 053 215 0033 or 0821234567 or +27 82 123 4567)
    preg_match_all('/\b(?:\+27\s?)?(0?\d{2})\s?\d{3}\s?\d{4}\b/', $norm, $pm);
    if (!empty($pm[0])) {
        $data['phone'] = array_values(array_unique(array_map('trim', $pm[0])));
    }

    // Email
    if (preg_match('/([A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,})/i', $norm, $m)) {
        $data['email'] = trim($m[1]);
    }

    // Website (with or without protocol)
    if (preg_match('/\b(?:https?:\/\/)?(www\.[A-Za-z0-9.-]+\.[A-Za-z]{2,})\b/i', $norm, $m)) {
        $data['website'] = trim($m[1]);
    }

    // Accreditations: heuristics for DHET, UMALUSI, QCTO, SETA, accreditation numbers
    preg_match_all('/\b(?:DHET|UMALUSI|QCTO|SETA|SAQA)[^\r\n]*|\b(?:accreditation|centre)\s*(?:no|number)\s*[:#-]?\s*[A-Za-z0-9\/-]+/i', $text, $am);
    if (!empty($am[0])) {
        $accs = array_map(static fn($s) => trim(preg_replace('/\s+/', ' ', $s)), $am[0]);
        $data['accreditations'] = array_values(array_unique($accs));
    }

    // Courses: lines starting with a dash or bullet or containing level hints like (N1-N6), NCV
    $lines = preg_split('/\R/', $text) ?: [];
    foreach ($lines as $line) {
        $l = trim($line);
        if ($l === '') {
            continue;
        }
        if (preg_match('/^[-•*]\s*(.+)$/', $l, $m)) {
            $data['courses'][] = trim($m[1]);
            continue;
        }
        if (preg_match('/\b(NCV|N\d(?:-N\d)?|Diploma|Certificate|Engineering|Business|IT|Hospitality|Tourism)\b/i', $l) && mb_strlen($l) <= 120) {
            // Treat as potential course item if it's short enough
            $data['courses'][] = $l;
        }
    }
    $data['courses'] = array_values(array_unique($data['courses']));

    // Final cleanup: remove empty/null entries
    return array_filter($data, static function ($v) {
        if (is_array($v)) {
            return !empty($v);
        }
        return $v !== null && $v !== '';
    });
}
