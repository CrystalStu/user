@ECHO OFF
REM ########################################
REM Symfony Debug Server Startup Script
REM Script by TURX (turuixuan@foxmail.com)
REM Powered by (c) Crystal Studio
REM ########################################
:start_serv
php -S localhost:8000 -t public
CHOICE /T 10 /D Y /M "Press Y for restart the server, Press N for terminate the script."
IF %ERRORLEVEL%==1 GOTO start_serv ELSE GOTO term_script
:term_script
ECHO Press any key to exit..
PAUSE >NUL