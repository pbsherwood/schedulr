<?php

// MySQL details -- USED CURRENTLY
define('global_mysql_server', 'SERVER');
define('global_mysql_user', 'USER');
define('global_mysql_password', 'PASSWORD');
define('global_mysql_database', 'DATABASE');

// Title. Used in page title and header -- USED CURRENTLY
define('global_title', 'Schedulr');

// Set to '1' to enable reservation reminders. Adds an option in the control panel
// Check out the wiki for instructions on how to make it work
define('global_schedulr_reminders', '0');

// Reservation reminders are sent from this email
// Should be an email address that you own, and that is handled by your web host provider
define('global_schedulr_reminders_email', 'user@test.ca');

// Full URL to web site. Used in reservation reminder emails and login page -- USED CURRENTLY
define('global_url', 'http://www.example.com/');

?>