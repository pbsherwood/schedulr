# schedulr
Schedulr - PHP, MYSQL, Javascript Calendar that can be used for sporting events, work schedules, or anything!

Installation:
1. Copy the contents of zip file into directory on your web server.
2. Create a database in MySQL and run 'schedulr.sql' in it to create the required tables.
3. Edit 'config.php' and update it with your MySQL DB info as well as the other settings.
4. Optional: If you enable reservation emails add the following line to the crontab on your server. (Changing the path to match where you have uploaded it to.) This will run the script daily, at 05:00. 
	0 5 * * * php /full/path/to/schedulr/tools/reminders.php
	
