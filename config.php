<?php

// MySQL details
define('global_mysql_server', 'SERVER');
define('global_mysql_user', 'USERNAME');
define('global_mysql_password', 'PASSWORD');
define('global_mysql_database', 'DATABASE');

// Title. Used in page title and header
define('global_title', 'Schedulr');

// Set to '1' to enable reservation reminders. '0' otherwise
define('global_schedulr_reminders', '0');

// Reservation reminders are sent from this email
// Should be an email address that you own, and that is handled by your web host provider
define('global_schedulr_reminders_email', 'admin@test.com');

// Full URL to web site. Used in reservation reminder emails and login page
define('global_url', 'http://www.example.com');

// Set to '1' to enable a basic calendar on the login page. '0' otherwise
define('global_advanced_login_display', '0');

?>