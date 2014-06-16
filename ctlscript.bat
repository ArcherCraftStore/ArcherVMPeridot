@echo off
rem START or STOP Services
rem ----------------------------------
rem Check if argument is STOP or START

if not ""%1"" == ""START"" goto stop

if exist E:\xampp\hypersonic\scripts\ctl.bat (start /MIN /B E:\xampp\server\hsql-sample-database\scripts\ctl.bat START)
if exist E:\xampp\ingres\scripts\ctl.bat (start /MIN /B E:\xampp\ingres\scripts\ctl.bat START)
if exist E:\xampp\mysql\scripts\ctl.bat (start /MIN /B E:\xampp\mysql\scripts\ctl.bat START)
if exist E:\xampp\postgresql\scripts\ctl.bat (start /MIN /B E:\xampp\postgresql\scripts\ctl.bat START)
if exist E:\xampp\apache\scripts\ctl.bat (start /MIN /B E:\xampp\apache\scripts\ctl.bat START)
if exist E:\xampp\openoffice\scripts\ctl.bat (start /MIN /B E:\xampp\openoffice\scripts\ctl.bat START)
if exist E:\xampp\apache-tomcat\scripts\ctl.bat (start /MIN /B E:\xampp\apache-tomcat\scripts\ctl.bat START)
if exist E:\xampp\resin\scripts\ctl.bat (start /MIN /B E:\xampp\resin\scripts\ctl.bat START)
if exist E:\xampp\jboss\scripts\ctl.bat (start /MIN /B E:\xampp\jboss\scripts\ctl.bat START)
if exist E:\xampp\jetty\scripts\ctl.bat (start /MIN /B E:\xampp\jetty\scripts\ctl.bat START)
if exist E:\xampp\subversion\scripts\ctl.bat (start /MIN /B E:\xampp\subversion\scripts\ctl.bat START)
rem RUBY_APPLICATION_START
if exist E:\xampp\lucene\scripts\ctl.bat (start /MIN /B E:\xampp\lucene\scripts\ctl.bat START)
if exist E:\xampp\third_application\scripts\ctl.bat (start /MIN /B E:\xampp\third_application\scripts\ctl.bat START)
goto end

:stop
echo "Stopping services ..."
if exist E:\xampp\third_application\scripts\ctl.bat (start /MIN /B E:\xampp\third_application\scripts\ctl.bat STOP)
if exist E:\xampp\lucene\scripts\ctl.bat (start /MIN /B E:\xampp\lucene\scripts\ctl.bat STOP)
rem RUBY_APPLICATION_STOP
if exist E:\xampp\subversion\scripts\ctl.bat (start /MIN /B E:\xampp\subversion\scripts\ctl.bat STOP)
if exist E:\xampp\jetty\scripts\ctl.bat (start /MIN /B E:\xampp\jetty\scripts\ctl.bat STOP)
if exist E:\xampp\hypersonic\scripts\ctl.bat (start /MIN /B E:\xampp\server\hsql-sample-database\scripts\ctl.bat STOP)
if exist E:\xampp\jboss\scripts\ctl.bat (start /MIN /B E:\xampp\jboss\scripts\ctl.bat STOP)
if exist E:\xampp\resin\scripts\ctl.bat (start /MIN /B E:\xampp\resin\scripts\ctl.bat STOP)
if exist E:\xampp\apache-tomcat\scripts\ctl.bat (start /MIN /B /WAIT E:\xampp\apache-tomcat\scripts\ctl.bat STOP)
if exist E:\xampp\openoffice\scripts\ctl.bat (start /MIN /B E:\xampp\openoffice\scripts\ctl.bat STOP)
if exist E:\xampp\apache\scripts\ctl.bat (start /MIN /B E:\xampp\apache\scripts\ctl.bat STOP)
if exist E:\xampp\ingres\scripts\ctl.bat (start /MIN /B E:\xampp\ingres\scripts\ctl.bat STOP)
if exist E:\xampp\mysql\scripts\ctl.bat (start /MIN /B E:\xampp\mysql\scripts\ctl.bat STOP)
if exist E:\xampp\postgresql\scripts\ctl.bat (start /MIN /B E:\xampp\postgresql\scripts\ctl.bat STOP)

:end

