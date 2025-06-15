@echo off
SETLOCAL EnableDelayedExpansion

:: Resume Ranker AI - Windows Development Environment Setup Script
:: This script sets up the development environment for the Resume Ranker AI application on Windows.

:: Define colors for output
set "BLUE=[94m"
set "GREEN=[92m"
set "YELLOW=[93m"
set "RED=[91m"
set "ENDCOLOR=[0m"

echo %BLUE%Resume Ranker AI - Development Environment Setup%ENDCOLOR%
echo.

:: Check if .env file exists
if not exist .env (
    echo %BLUE%Creating .env file...%ENDCOLOR%
    copy .env.example .env
) else (
    echo %YELLOW%.env file already exists.%ENDCOLOR%
)

:: Install PHP dependencies
echo %BLUE%Installing PHP dependencies...%ENDCOLOR%
call composer install
if %ERRORLEVEL% neq 0 (
    echo %RED%Failed to install PHP dependencies.%ENDCOLOR%
    exit /b %ERRORLEVEL%
)

:: Generate application key
echo %BLUE%Generating application key...%ENDCOLOR%
call php artisan key:generate
if %ERRORLEVEL% neq 0 (
    echo %RED%Failed to generate application key.%ENDCOLOR%
    exit /b %ERRORLEVEL%
)

:: Install Node.js dependencies
echo %BLUE%Installing Node.js dependencies...%ENDCOLOR%
call npm install
if %ERRORLEVEL% neq 0 (
    echo %RED%Failed to install Node.js dependencies.%ENDCOLOR%
    exit /b %ERRORLEVEL%
)

:: Build frontend assets
echo %BLUE%Building frontend assets...%ENDCOLOR%
call npm run dev
if %ERRORLEVEL% neq 0 (
    echo %RED%Failed to build frontend assets.%ENDCOLOR%
    exit /b %ERRORLEVEL%
)

:: Run database migrations
set /p run_migrations=%YELLOW%Would you like to run database migrations? (y/n): %ENDCOLOR%
if /i "%run_migrations%"=="y" (
    echo %BLUE%Running database migrations...%ENDCOLOR%
    call php artisan migrate
    if %ERRORLEVEL% neq 0 (
        echo %RED%Failed to run database migrations.%ENDCOLOR%
        exit /b %ERRORLEVEL%
    )
) else (
    echo %YELLOW%Skipping database migrations.%ENDCOLOR%
)

:: Seed the database
set /p seed_database=%YELLOW%Would you like to seed the database? (y/n): %ENDCOLOR%
if /i "%seed_database%"=="y" (
    echo %BLUE%Seeding the database...%ENDCOLOR%
    call php artisan db:seed
    if %ERRORLEVEL% neq 0 (
        echo %RED%Failed to seed the database.%ENDCOLOR%
        exit /b %ERRORLEVEL%
    )
) else (
    echo %YELLOW%Skipping database seeding.%ENDCOLOR%
)

:: Generate API documentation
set /p generate_docs=%YELLOW%Would you like to generate API documentation? (y/n): %ENDCOLOR%
if /i "%generate_docs%"=="y" (
    echo %BLUE%Generating API documentation...%ENDCOLOR%
    call php artisan l5-swagger:generate
    if %ERRORLEVEL% neq 0 (
        echo %RED%Failed to generate API documentation.%ENDCOLOR%
        exit /b %ERRORLEVEL%
    )
) else (
    echo %YELLOW%Skipping API documentation generation.%ENDCOLOR%
)

echo %GREEN%Setup complete!%ENDCOLOR%
echo %BLUE%Run 'php artisan serve' to start the development server.%ENDCOLOR%

ENDLOCAL
