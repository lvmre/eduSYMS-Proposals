@echo off
echo ========================================
echo  eduSYMS-Proposals Startup Checker
echo ========================================

echo.
echo 1. Checking if Apache is running...
netstat -ano | findstr ":80" | findstr "LISTENING" >nul
if %errorlevel%==0 (
    echo    ✓ Apache is running on port 80
) else (
    echo    ✗ Apache is NOT running on port 80
    echo    Please start Apache in XAMPP Control Panel
    pause
    exit /b 1
)

echo.
echo 2. Testing PHP execution...
php -v >nul 2>&1
if %errorlevel%==0 (
    echo    ✓ PHP is available
    php -v | findstr "PHP"
) else (
    echo    ✗ PHP not found in PATH
    echo    Using XAMPP PHP...
    "C:\xampp\php\php.exe" -v | findstr "PHP"
)

echo.
echo 3. Checking file structure...
if exist "proposal_generator\index.php" (
    echo    ✓ index.php found
) else (
    echo    ✗ index.php missing
)

if exist "proposal_generator\extract.php" (
    echo    ✓ extract.php found
) else (
    echo    ✗ extract.php missing
)

echo.
echo 4. Opening application...
echo    URL: http://localhost/GitHub/eduSYMS-Proposals/proposal_generator/
start http://localhost/GitHub/eduSYMS-Proposals/proposal_generator/

echo.
echo If the page shows a Tomcat error:
echo - Clear browser cache (Ctrl+Shift+Delete)
echo - Try incognito mode (Ctrl+Shift+N)
echo - Check XAMPP Apache is green/running
echo.
pause
