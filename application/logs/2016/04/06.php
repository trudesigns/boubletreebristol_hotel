<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2016-04-06 00:05:36 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Roman T.', email='marketingtrusted@gmail.com', company='[Not Provided]', phone='(302) 659-7651', message='I run a digital marketing agency that helps our clients generate more sales from the web by taking advantage of the most recent changes in Google algorithm.  Have you heard of the changes that Google has made?  If not, I'd like to tell you about them and tell you how you can take advantage of them.  <br />
<br />
I wanted to reach out to you to see if you were interested in seeing what my team and I can do to give you the edge on the competition. I would be happy to  look at your competitors with you over the phone and share some of our insights.  <br />
<br />
The information we provide gives you a full understanding of what is working in today's digital marketing world and helps you to see what my team and I can do to increase the traffic and sales to your site.  Even if you're not interested in using my services, I will still be happy to answer all your questions and provide you with this information.<br />
<br />
What would be the best time to talk?<br />
<br />
I am looking forward to speaking with you soon.<br />
<br />
Thanks,<br />
<br />
Roman T.<br />
<br />
If you are not interested in seeing how my team and I can help you outrank your competitors then just reply back and let me know and we will not message you again.<br />
' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177
2016-04-06 10:22:35 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Renato Lenzi', email='cristina.ferreira@albea-group.com', company='Albéa', phone='8602832000', message='Dear Sir, we would like to book a room from 11th April to 05th March for our Manager Mr. Renato Lenzi.<br />
Could you please confirm?' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177
2016-04-06 13:00:55 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'link.href   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AN' at line 1 [ SELECT * FROM pages WHERE   slug = ''   link.href   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2016-04-06 13:00:55 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
#1 /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Model/Page.php(176): Kohana_Database_Query->execute()
#2 /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Setup.php(47): Model_Page->getPagefromURL('assets/plugins/...')
#3 /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Setup.php(140): Controller_Setup->getPage()
#4 /var/www/html/doubletreebristol.prod01.pita.website/system/classes/Kohana/Controller.php(69): Controller_Setup->before()
#5 [internal function]: Kohana_Controller->execute()
#6 /var/www/html/doubletreebristol.prod01.pita.website/system/classes/Kohana/Request/Client/Internal.php(97): ReflectionMethod->invoke(Object(Controller_Default))
#7 /var/www/html/doubletreebristol.prod01.pita.website/system/classes/Kohana/Request/Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#8 /var/www/html/doubletreebristol.prod01.pita.website/system/classes/Kohana/Request.php(986): Kohana_Request_Client->execute(Object(Request))
#9 /var/www/html/doubletreebristol.prod01.pita.website/index.php(118): Kohana_Request->execute()
#10 {main} in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2016-04-06 13:27:55 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Jasmine Quinones ', email='jasmineq126@gmail.com', company='[Not Provided]', phone='203-510-7899', message='To whom this may concern,<br />
I am sending this email to ask about prices for a 2 night stay at your hotel. I am planning to come with my boyfriend and would be checking in on May 27, 2016 sometime at night, and will be checking out on Sunday May 29,2016. I am just wondering if you would be able to give me an estimate on the price for both nights. We are both over 18. If you could get back to me via email that would be most appreciated. Thank you!' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177