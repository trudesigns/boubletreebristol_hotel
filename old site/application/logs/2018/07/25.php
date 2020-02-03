<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2018-07-25 11:15:04 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'caption.linkHref   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photob' at line 1 [ SELECT * FROM pages WHERE   slug = ''   caption.linkHref   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2018-07-25 11:15:04 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
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
2018-07-25 11:31:20 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Rachel Peterson', email='Rachiebaby26@gmail.com', company='[Not Provided]', phone='8607518105', message='I saw on a wedding website that your hotel hosts weddings in the ballroom. I was looking for more information- do you have any packages and pricing? What is the guest minimum for a Saturday evening?' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177
2018-07-25 12:04:12 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Max Williams', email='max.seomarketing1@gmail.com', company='[Not Provided]', phone='2356895054', message='Hello,<br />
 <br />
<br />
My name is Max and I am a Digital Marketing Specialists for a Creative Agency.<br />
<br />
I was doing some industry benchmarking for a client of mine when I came across your website.<br />
<br />
I noticed a few technical errors which correspond to a drop of website traffic over the last 6-8 months which I thought I would bring to your attention.<br />
<br />
After closer inspection, it appears your site is lacking in 4 key criteria.<br />
<br />
 <br />
1- Website Speed<br />
2- Link Diversity<br />
3- Domain Authority<br />
4- Competition Comparison<br />
<br />
<br />
We would be happy to send you a proposal using the top search phrases for your area of expertise. Please contact me at your convenience so we can start saving you some money.<br />
 <br />
In order for us to respond to your request for information, please include your Name, company’s website address (mandatory) and /or phone number.<br />
<br />
 <br />
Regards,<br />
Max Williams<br />
max.seomarketing1@gmail.com' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177