:: 
:: Windows only. Write to log file when computer boots on/off.
:: Use Task Scheduler to trigger and exec this file.
:: Determined from Event Viewer that /User Profile Service > Event ID 1531=on and 1532=off (kinda).
::
@ECHO OFF
SETLOCAL

:: path to log file.
SET logfile="C:\myboot.log"
:: save argument.
SET logtype=%1
:: generate date (Y-m-d) and time (H:i:s.u) stamp.
SET tmpdate=%DATE:~10,4%-%DATE:~4,2%-%DATE:~7,2%
SET tmptime=%TIME:~-11,2%:%TIME:~-8,2%:%TIME:~-5,2%.%TIME:~-2,2%
:: generate log header.
SET logheader=[%tmpdate% %tmptime%]

:: append to log file.
>> %logfile% ECHO.%logheader% %logtype%
