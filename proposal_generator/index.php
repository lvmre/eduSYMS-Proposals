<?php
declare(strict_types=1);

// Simple, single-file front controller for the proposal generator
// Security headers (best-effort; some are sent by web server normally)
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: no-referrer-when-downgrade');
header('X-XSS-Protection: 1; mode=block');

require_once __DIR__ . '/extract.php';
require_once __DIR__ . '/proposal_template.php';

$proposal = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF-lite: check a simple token stored in session
    session_start();
    $token = $_POST['csrf_token'] ?? '';
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        $errors[] = 'Invalid form submission. Please refresh and try again.';
    } else {
        $inputText = trim($_POST['flyer_text'] ?? '');

        if ($inputText === '') {
            $errors[] = 'Please provide flyer text.';
        } else {
            // Sanitize input for downstream processing and output
            // Keep the original for parsing; escape only on output
            $rawText = $inputText;

            try {
                $extractedData = extractFlyerData($rawText);
                if (!$extractedData) {
                    $errors[] = 'Could not extract data. Try refining the input.';
                } else {
                    $proposal = generateProposal($extractedData);
                    // If user requested a direct HTML download, stream a standalone HTML file
                    if (isset($_POST['download']) && $_POST['download'] === '1') {
                        $name = trim((string)($extractedData['name'] ?? 'proposal'));
                        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
                        $slug = trim($slug, '-') ?: 'proposal';
                        $filename = sprintf('proposal-%s.html', $slug);

                        $doc = '<!DOCTYPE html>'
                            . '<html lang="en"><head><meta charset="UTF-8" />'
                            . '<meta name="viewport" content="width=device-width, initial-scale=1" />'
                            . '<title>Proposal</title>'
                            . '<style>'
                            . 'body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:24px;}'
                            . 'table{width:100%;border-collapse:collapse;margin-top:12px}'
                            . 'th,td{border:1px solid #e5e7eb;padding:8px;text-align:left}'
                            . 'th{background:#f2f2f2}'
                            . '</style></head><body>'
                            . $proposal
                            . '</body></html>';

                        header('Content-Type: text/html; charset=UTF-8');
                        header('Content-Disposition: attachment; filename=' . $filename);
                        header('X-Content-Type-Options: nosniff');
                        echo $doc;
                        exit;
                    }
                }
            } catch (Throwable $e) {
                $errors[] = 'An unexpected error occurred while generating the proposal.';
            }
        }
    }
}

// Prepare CSRF token for GET or next POST
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
$csrfToken = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Flyer → Proposal Generator</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        /* Minimal inline fallback in case styles.css fails to load */
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 24px; }
        .container { max-width: 980px; margin: 0 auto; }
        textarea { width: 100%; min-height: 220px; }
        .errors { color: #b00020; background: #fde7e9; padding: 12px; border-radius: 8px; }
        .proposal-output { border: 1px solid #e5e7eb; padding: 20px; margin-top: 20px; border-radius: 10px; background: #fff; }
        .actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .btn { background: #111827; color: #fff; padding: 10px 14px; border: 0; border-radius: 8px; cursor: pointer; }
        .btn.secondary { background: #374151; }
        .meta { color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Flyer to Proposal Generator</h1>
    <p class="meta">Paste unstructured text from a flyer. The app extracts key details and builds a proposal with SA pricing.</p>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" class="flyer-form">
        <label for="flyer_text"><strong>Paste Flyer Text</strong></label>
        <textarea id="flyer_text" name="flyer_text" required placeholder="Paste text here..."><?php
            if (!empty($rawText ?? '')) {
                echo htmlspecialchars($rawText, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            }
        ?></textarea>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" />
        <div class="actions">
            <button type="submit" class="btn">Generate Proposal</button>
            <button type="button" class="btn secondary" onclick="window.print()">Print / Save as PDF</button>
            <button type="submit" name="download" value="1" class="btn secondary">Download HTML</button>
        </div>
    </form>

    <?php if ($proposal): ?>
        <section class="proposal-output" id="proposal">
            <?= $proposal ?>
        </section>
    <?php endif; ?>
</div>
</body>
</html>
