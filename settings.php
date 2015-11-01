<?php

/** ************** Start: RabbitMQ Credentials ********** */
define('RMQ_HOST', 'localhost');
define('RMQ_PORT', 5672);
define('RMQ_USERNAME', 'guest');
define('RMQ_PASSWORD', 'guest');
/** ************** End: RabbitMQ Credentials ********** */

/** ************** Start: MailTrap Credentials ********** */
define('MAILTRAP_HOST', 'mailtrap.io');
define('MAILTRAP_USERNAME', 'YOUR_USERNAME_HERE');
define('MAILTRAP_PASSWORD', 'YOUR_PASSWORD_HERE');
define('MAILTRAP_PORT', '25');
/** ************** End: MailTrap Credentials ********** */

/** ************** Start: Pause Timings **************** */
define('PAUSE_START_TIME', '18:00:00');
define('PAUSE_END_TIME', '6:00:00');
/** ************** End: Pause Timings **************** */

define('LOG_PATH', 'app_logs/');


/** *************** Start: Set the Log File Names ***************** */

define('LOG_PROCESS_INCOMING','process_incoming.log');
define('LOG_SMS_FOR_NON_PAUSE', 'processed_sms_at_valid_time.log');
define('LOG_FOR_EMAIL_SENT', 'processed_email_messages.log');

/** *************** End: Set the Log File Names ***************** */