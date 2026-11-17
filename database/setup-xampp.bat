@echo off
setlocal

for %%I in ("%~dp0..\..\..\..") do set "XAMPP_DIR=%%~fI"
set "MYSQL=%XAMPP_DIR%\mysql\bin\mysql.exe"
set "PHP=%XAMPP_DIR%\php\php.exe"

if not exist "%MYSQL%" (
    echo MySQL introuvable : "%MYSQL%"
    echo Modifiez XAMPP_DIR dans ce fichier si XAMPP est installe ailleurs.
    exit /b 1
)

if not exist "%PHP%" (
    echo PHP introuvable : "%PHP%"
    echo Modifiez XAMPP_DIR dans ce fichier si XAMPP est installe ailleurs.
    exit /b 1
)

pushd "%~dp0.."

echo Creation de la base boutique_mode...
"%MYSQL%" -u root -e "CREATE DATABASE IF NOT EXISTS boutique_mode CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if errorlevel 1 goto :error

echo Creation des tables et ajout des produits de demonstration...
"%PHP%" artisan config:clear
if errorlevel 1 goto :error
"%PHP%" artisan migrate --seed
if errorlevel 1 goto :error

echo.
echo Installation terminee. La base boutique_mode est prete.
popd
exit /b 0

:error
echo.
echo Echec de l'installation. Verifiez que MySQL est demarre dans XAMPP
echo et que le compte MySQL root est accessible sans mot de passe.
popd
exit /b 1
