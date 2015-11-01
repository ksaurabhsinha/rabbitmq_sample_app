#RabbitMQ Sample App with Swift Mailer
This is the a sample app to describe the working with RabbitMQ and Swift Mailer

<b>Basic Info</b>
<br>
<ul>
<li><b>create_incoming_queue.php</b> - This is the file to create the dummy entries in the MAIN Incoming QUEUE</li>
<li><b>process_incoming_queue.php</b> - This file process the messages in the MAIN Incoming QUEUE. If the present time is in the defined Pause Timings, the correspoding SMS type message is pushed to a new Custom Queue names 'sms_queue'</li>
<li><b>process_sms_queue.php</b> - This files process the messages in the Custom defined Queue named 'sms_queue'. This file will execute as a CRON JOB outside the defined time of the Pause timing</li>
</ul>
