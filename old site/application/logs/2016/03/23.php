<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2016-03-23 00:58:39 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND par' at line 1 [ SELECT * FROM pages WHERE   slug = ''' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2016-03-23 00:58:39 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
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
2016-03-23 00:58:53 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND par' at line 1 [ SELECT * FROM pages WHERE   slug = ''' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2016-03-23 00:58:53 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
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
2016-03-23 01:00:24 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND par' at line 1 [ SELECT * FROM pages WHERE   slug = ''' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2016-03-23 01:00:24 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
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
2016-03-23 01:00:29 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND par' at line 1 [ SELECT * FROM pages WHERE   slug = ''' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2016-03-23 01:00:29 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
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
2016-03-23 01:00:33 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND par' at line 1 [ SELECT * FROM pages WHERE   slug = ''' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2016-03-23 01:00:33 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
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
2016-03-23 16:43:49 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Peter Mulally', email='petermulally@gmail.com', company='ESPN', phone='2066045370', message='Hi there, I’m hoping you can help me out with a hotel room receipt from last year…lost my copy and need for my records.<br />
<br />
Information here. Let me know if you can send when you have a sec. Thanks in advance. <br />
<br />
-<br />
<br />
Peter Mulally<br />
Bristol DoubleTree<br />
One night charge - $170.82<br />
CITI Visa<br />
6/24/2015<br />
<br />
Let me know if you need any more information. Thanks very much. <br />
' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177
2016-03-23 21:04:18 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Amy Wessman-Pelletier', email='Amyctodd@yahoo.com', company='[Not Provided]', phone='860-919-6283', message='Would like reservations for Sunday Easter brunch for three adults and a child. Was hoping for 12:00. Thanks' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177