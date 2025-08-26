# eduSYMS-Proposals

Lightweight PHP app that converts unstructured flyer text into a polished proposal for website development and social media marketing with South African pricing.

## Quick start (XAMPP on Windows)

1) Copy this repo under your web root (already under `c:\xampp\htdocs\GitHub`).
2) Ensure PHP 8.3+ is enabled in XAMPP (Apache + PHP).
3) Visit in your browser:

<http://localhost/GitHub/eduSYMS-Proposals/proposal_generator/>

Paste the flyer text, click Generate Proposal, then use Print/Save as PDF.

You can also click “Download HTML” to save a standalone proposal HTML file.

## Files

- `proposal_generator/index.php` – Form UI and controller.
- `proposal_generator/extract.php` – Regex-based extraction.
- `proposal_generator/proposal_template.php` – Proposal HTML rendering.
- `proposal_generator/styles.css` – Minimal styling + print CSS.

No database or external dependencies are required.

## Notes

- Extraction is heuristic and may need tweaked input for edge cases.
- Pricing reflects 2025 SA averages and can be edited in `proposal_template.php`.
- If the page doesn’t load, ensure Apache is serving this path and PHP 8.2+ is enabled in XAMPP.
