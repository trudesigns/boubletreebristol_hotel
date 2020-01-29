<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2017-02-23 07:45:52 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Request for Proposal notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Jonathan Tyrol', email='jtyrol24@gmail.com', company='[Not Provided]', phone='860-301-9976', message='Hello,<br />
<br />
We would like to block off rooms and request a shuttle service to our wedding venue...please contact us as soon as possible!  We've had a hard time getting through to anyone.<br />
<br />
Thank you,<br />
<br />
Jonathan Tyrol' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:333
2017-02-23 15:48:21 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''admin'' AND parent_id = 0' at line 1 [ SELECT * FROM pages WHERE   slug = 'admin'' AND parent_id = 0 ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2017-02-23 15:48:21 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
#1 /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Model/Page.php(176): Kohana_Database_Query->execute()
#2 /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Setup.php(47): Model_Page->getPagefromURL('admin'')
#3 /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Setup.php(140): Controller_Setup->getPage()
#4 /var/www/html/doubletreebristol.prod01.pita.website/system/classes/Kohana/Controller.php(69): Controller_Setup->before()
#5 [internal function]: Kohana_Controller->execute()
#6 /var/www/html/doubletreebristol.prod01.pita.website/system/classes/Kohana/Request/Client/Internal.php(97): ReflectionMethod->invoke(Object(Controller_Default))
#7 /var/www/html/doubletreebristol.prod01.pita.website/system/classes/Kohana/Request/Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#8 /var/www/html/doubletreebristol.prod01.pita.website/system/classes/Kohana/Request.php(986): Kohana_Request_Client->execute(Object(Request))
#9 /var/www/html/doubletreebristol.prod01.pita.website/index.php(118): Kohana_Request->execute()
#10 {main} in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2017-02-23 17:31:43 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Chris Rodgersen', email='marketing7partners@gmail.com', company='[Not Provided]', phone='(201) 503-4744', message='I'd like to have a consultation with you regarding your website rankings on the web if you're interested.  You have a great site, but from the report we ran I can see that you're not getting nearly as much traffic as you should be.  We are an elite optimization firm so we're not cheap, but we get results where others cannot.  <br />
<br />
I would like to set up a time to talk with you about your business and discuss providing a report on where you're at now and what we can do for you (no cost).  Is that something you'd be interested in?  If so, what would be the best time to reach you and at what number?<br />
Thanks, <br />
Chris Rodgerson<br />
' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177