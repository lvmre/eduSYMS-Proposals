@echo off
echo ========================================
echo  eduSYMS-Proposals Setup Fix
echo ========================================

echo.
echo PROBLEM DETECTED: Laravel app is intercepting all localhost requests
echo.
echo QUICK FIX OPTIONS:
echo.
echo 1. **Move our app to root level** (recommended for testing):
xcopy "c:\xampp\htdocs\GitHub\eduSYMS-Proposals\proposal_generator\*" "c:\xampp\htdocs\proposals\" /E /Y
if not exist "c:\xampp\htdocs\proposals\" mkdir "c:\xampp\htdocs\proposals"
xcopy "c:\xampp\htdocs\GitHub\eduSYMS-Proposals\proposal_generator\*" "c:\xampp\htdocs\proposals\" /E /Y /I

echo.
echo App copied to: c:\xampp\htdocs\proposals\
echo.
echo 2. **Test the new location**:
echo    http://localhost/proposals/
echo.

echo Opening the app at new location...
start http://localhost/proposals/

echo.
echo If this still doesn't work, the Laravel app may need to be disabled temporarily.
echo Check C:\xampp\htdocs\index.php and rename it to index.php.bak
echo.
pause
