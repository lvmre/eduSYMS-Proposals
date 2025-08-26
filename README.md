# eduSYMS-Proposals

Lightweight PHP app that converts unstructured flyer text into a polished proposal for website development and social media marketing with South African pricing.

## Quick Start

⚠️ **Important**: Your localhost has a Laravel app that intercepts all requests. Use the PHP development server instead:

1. **Run the development server** (Recommended):
   ```powershell
   .\start-dev-server.bat
   ```
   This starts PHP's built-in server on port 8000 and opens your browser.

2. **Manual server start**:

   ```powershell
   cd proposal_generator
   C:\xampp\php\php.exe -S localhost:8000
   ```
   Then visit: <http://localhost:8000/>

3. **Test with sample data**:
   - Visit: <http://localhost:8000/demo.html> for sample flyer text
   - Copy the text, go back to main app, paste and generate proposal
   - Use "Download HTML" to save or "Print" for PDF

## Files

- `proposal_generator/index.php` – Main app (form + controller)
- `proposal_generator/extract.php` – Text extraction logic
- `proposal_generator/proposal_template.php` – HTML proposal generator
- `proposal_generator/styles.css` – Styling + print CSS
- `proposal_generator/demo.html` – Sample data and instructions
- `proposal_generator/test.php` – PHP diagnostic page
- `start-app.bat` – Windows startup script

## System Requirements

- Windows with XAMPP
- Apache running on port 80
- PHP 8.2+ (8.3+ recommended)
- No database required

## Troubleshooting

| Issue | Solution |
|-------|----------|
| **404 Not Found Error** | Use PHP dev server: `.\start-dev-server.bat` (port 8000) |
| **Laravel App Interference** | Localhost has Laravel; use port 8000 instead of 80 |
| **PHP Not Working** | Check XAMPP PHP path: `C:\xampp\php\php.exe` |
| **Blank Page** | Check `C:\xampp\apache\logs\error.log` for PHP errors |
| **CSS/JS Missing** | Ensure all files copied to same directory |

## Notes

- Extraction is heuristic; variable input may need manual adjustments
- Pricing reflects 2025 SA averages; edit in `proposal_template.php`
- App uses sessions for CSRF protection; no external dependencies
