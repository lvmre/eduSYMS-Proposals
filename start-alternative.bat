@echo off
echo ========================================
echo  eduSYMS-Proposals Alternative URLs
echo ========================================

echo.
echo The main localhost is running Laravel. Try these alternatives:
echo.
echo 1. Direct file access:
echo    http://localhost/GitHub/eduSYMS-Proposals/proposal_generator/index.php
echo.
echo 2. Using XAMPP port (if different):
echo    http://localhost:8080/GitHub/eduSYMS-Proposals/proposal_generator/
echo.
echo 3. Using IP directly:
echo    http://127.0.0.1/GitHub/eduSYMS-Proposals/proposal_generator/
echo.

echo Testing direct file access...
start http://localhost/GitHub/eduSYMS-Proposals/proposal_generator/index.php

echo.
echo If that doesn't work, we'll create a virtual host...
pause
