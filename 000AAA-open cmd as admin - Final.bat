@echo off
:: Get the current directory
set current_dir=%cd%

:: Open CMD as admin in the current directory
powershell -Command "Start-Process cmd -ArgumentList '/K cd /d %current_dir%' -Verb RunAs"

:: Pause to see the output (optional)
:: pause