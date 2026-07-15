//sheduler command

php artisan schedule:run
php artisan saas:check-expiry


$action = New-ScheduledTaskAction -Execute "php" -Argument "D:\lms-saas\artisan schedule:run" -WorkingDirectory "D:\lms-saas"
$trigger = New-ScheduledTaskTrigger -Daily -At "00:05"
Register-ScheduledTask -TaskName "LaravelScheduler" -Action $action -Trigger $trigger -RunLevel Highest


//cron job command

php artisan queue:work --tries=3 --timeout=90
php artisan queue:retry all
php artisan serve
php artisan optimize:clear
php artisan cache:clear

//production sheduler command

5 0 * * * cd /var/www/lms-saas && php artisan schedule:run >> /dev/null 2>&1
# Open crontab editor
crontab -e

# Paste the line above, save and exit
# Verify it was saved
crontab -l





// run the worker on production

That's it. No need to run queue:work or serve manually in cron — those are separate:

Command	How to run on Linux
php artisan serve	Not used in production — use Nginx/Apache instead
php artisan queue:work	Run via supervisor (keeps it alive)
php artisan schedule:run	Run via cron (above)
For the queue worker on Linux, use supervisor:

# Install supervisor
sudo apt install supervisor

# Create config
sudo nano /etc/supervisor/conf.d/lms-worker.conf

[program:lms-worker]
command=php /var/www/lms-saas/artisan queue:work --tries=3 --timeout=90
directory=/var/www/lms-saas
autostart=true
autorestart=true
user=www-data

ini
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start lms-worker



