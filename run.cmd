@Echo Off
SETLOCAL
SET WD=%CD%

CD www

rem +--------------------------------------------
rem | Port 80 may be blocked by the firewall or
rem | if the ports is occupied by another service
rem | use 8080 as an alternative and access via
rem |    http://localhost:8080
rem +--------------------------------------------
C:\PHP\php -S localhost:80 -c c:\php\php.ini-development _mvp.php

CD %WD%
