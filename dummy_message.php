<?php

$messageArray = array();

$messageArray[] = '{
  "type": "email",
  "from": "no-reply@company.com",
  "recipients": ["test1@test.com", "test2@test.com"],
  "html": "This is a test email. It can <i>also</i> contain HTML code",
  "text": "This is a test email. It is text only"
}';


$messageArray[] = '{
  "type": "sms",
  "from": "JRD",
  "recipients": ["+971501478902"],
  "body": "Test SMS message body"
}';