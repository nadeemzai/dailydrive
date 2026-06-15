@echo off
cd /d "C:\laragon\www\AutoBlog"
php artisan schedule:run >> storage\logs\scheduler.log 2>&1
