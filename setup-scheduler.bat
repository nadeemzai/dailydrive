@echo off
echo ========================================
echo  DAILYdRIVE - Windows Task Scheduler
echo  Setup: Run Laravel Scheduler Every Min
echo ========================================
echo.

schtasks /create ^
  /tn "DAILYdRIVE-Laravel-Scheduler" ^
  /tr "C:\laragon\www\AutoBlog\run-scheduler.bat" ^
  /sc MINUTE /mo 1 ^
  /ru SYSTEM ^
  /rl HIGHEST ^
  /f

if %errorlevel% == 0 (
    echo.
    echo  SUCCESS: Task created.
    echo  The scheduler will run every minute.
    echo  Laravel's everyTenMinutes() will fire every 10 min.
    echo.
) else (
    echo.
    echo  ERROR: Could not create task. Run as Administrator.
    echo.
)

pause
