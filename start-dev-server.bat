@echo off
echo ========================================
echo  FINAL SOLUTION: PHP Built-in Server
echo ========================================

echo.
echo Starting PHP development server on port 8000...
echo This bypasses all Apache/Laravel routing issues.
echo.

cd /d "c:\xampp\htdocs\GitHub\eduSYMS-Proposals\proposal_generator"

echo Starting server...
echo URL: http://localhost:8000
echo.
echo Press Ctrl+C to stop the server
echo.

start http://localhost:8000

"C:\xampp\php\php.exe" -S localhost:8000
