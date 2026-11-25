@echo off
title Together - Flutter Mobile App
echo ========================================================
echo Starting Together Mobile App (Flutter)
echo ========================================================
set PATH=C:\Antigravity IDE\resources\flutter\bin;%PATH%
cd /d "%~dp0together_mobile"

echo Available devices:
flutter devices

echo.
echo Choose your target device:
echo 1) Chrome (Web) - Recommended for quick testing
echo 2) Windows (Desktop)
echo 3) Default / Connected Mobile Emulator
echo.
set /p choice="Enter option (1, 2, or 3, default 1): "

if "%choice%"=="2" (
    flutter run -d windows
) else if "%choice%"=="3" (
    flutter run
) else (
    flutter run -d chrome
)

pause
