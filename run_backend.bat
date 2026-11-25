@echo off
title Together - Laravel Backend Server
echo ========================================================
echo Starting Laravel Backend Server with Laragon PHP 8.3
echo ========================================================
set PATH=C:\LaragonUser\bin\php\php-8.3.31-Win32-vs16-x64;C:\composer;%PATH%
cd /d "%~dp0test"

echo Running migrate & seed check...
php artisan migrate --force

echo.
echo Server running at http://127.0.0.1:8000
echo Press Ctrl+C to stop the server.
echo ========================================================
php artisan serve --host=0.0.0.0 --port=8000
pause
