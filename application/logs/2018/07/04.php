<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2018-07-04 01:48:49 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='ProFunding247', email='noreply@profunding24-7.com', company='http://ProFunding24-7.com', phone='[Not Provided]', message='Hi, letting you know that http://ProFunding24-7.com can find your business a SBA or private loan for $2,000 - $350K Without high credit or collateral. <br />
 <br />
Find Out how much you qualify for by clicking here: <br />
 <br />
http://ProFunding24-7.com <br />
 <br />
Minimum requirements include your company being established for at least a year and with current gross revenue of at least 120K. Eligibility and funding can be completed in as fast as 48hrs. Terms are personalized for each business so I suggest applying to find out exactly how much you can get on various terms. <br />
 <br />
This is a free service from a qualified lender and the approval will be based on the annual revenue of your business. These funds are Non-Restrictive, allowing you to spend the full amount in any way you require including business debt consolidation, hiring, marketing, or Absolutely Any Other expense. <br />
 <br />
If you need fast and easy business funding take a look at these programs now as there is limited availability: <br />
 <br />
http://ProFunding24-7.com <br />
 <br />
Have a great day, <br />
The Pro Funding 247 Team <br />
 <br />
unsubscribe/remove - http://ProFunding24-7.com/r.php?url=doubletreebristol.com&id=e104' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177
2018-07-04 18:50:48 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '0=A' AND parent_id = 0' at line 1 [ SELECT * FROM pages WHERE   slug = ''0=A' AND parent_id = 0 ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2018-07-04 18:50:48 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
#1 /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Model/Page.php(176): Kohana_Database_Query->execute()
#2 /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Setup.php(47): Model_Page->getPagefromURL(''0=A')
#3 /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Setup.php(140): Controller_Setup->getPage()
#4 /var/www/html/doubletreebristol.prod01.pita.website/system/classes/Kohana/Controller.php(69): Controller_Setup->before()
#5 [internal function]: Kohana_Controller->execute()
#6 /var/www/html/doubletreebristol.prod01.pita.website/system/classes/Kohana/Request/Client/Internal.php(97): ReflectionMethod->invoke(Object(Controller_Default))
#7 /var/www/html/doubletreebristol.prod01.pita.website/system/classes/Kohana/Request/Client.php(114): Kohana_Request_Client_Internal->execute_request(Object(Request), Object(Response))
#8 /var/www/html/doubletreebristol.prod01.pita.website/system/classes/Kohana/Request.php(986): Kohana_Request_Client->execute(Object(Request))
#9 /var/www/html/doubletreebristol.prod01.pita.website/index.php(118): Kohana_Request->execute()
#10 {main} in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251