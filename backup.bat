batch
@echo off
set BACKUP_DIR=C:\Users\dear-user\Desktop
set DATE=%date:~10,4%%date:~4,2%%date:~7,2%
set TIME=%time:~0,2%%time:~3,2%%time:~6,2%
set BACKUP_NAME=backup-new-avs-%DATE%-%TIME%

echo Creating backup: %BACKUP_NAME%.zip
tar -a -c -f "%BACKUP_DIR%\%BACKUP_NAME%.zip" --exclude="vendor" --exclude="node_modules" --exclude=".env" *
echo Backup completed: %BACKUP_DIR%\%BACKUP_NAME%.zip
pause
