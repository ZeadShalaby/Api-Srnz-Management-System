@ECHO OFF
ECHO Running Autorun.bat
ECHO Running artisan serve..
start /min cmd /k php artisan serve
ECHO Running npx run watch..
start /min cmd /k npx mix watch
start chrome http://127.0.0.1:8000/
ECHO Everything is running, browser has been synced, close all the CMD windows manually to stop the server
ECHO if it takes too long to load make sure that you started XAMPP, or the web server then try again
ECHO Thanks for using Zodic's services
PAUSE
cls
