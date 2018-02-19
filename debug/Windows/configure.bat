@ECHO OFF
REM ########################################
REM Symfony Server Configure Script
REM Script by TURX (turuixuan@foxmail.com)
REM Powered by (c) Crystal Studio
REM ########################################
:start_config
composer install
php bin/console doctrine:schema:update --force
:term_script
ECHO Press any key to exit..
PAUSE >NUL