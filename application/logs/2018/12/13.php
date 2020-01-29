<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2018-12-13 06:15:37 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Blake Thompson', email='blakethompson@recognitioncity.net', company='NA', phone='888-207-3442', message='I am happy to tell you that Doubletree by Hilton Hotel Bristol was chosen for the 2018 Best of Bristol Awards in the category of Hotels. The Best of Bristol Award was created to acknowledge the best businesses in our community.<br />
<br />
For additional information please visit us at:<br />
<br />
https://bristol.recognitioncity.net/MDKNC-UBHA-A4UU<br />
<br />
If needed for reference - your code is: DKNC-UBHA-A4UU<br />
<br />
Congratulations,<br />
<br />
Blake Thompson<br />
Best of Bristol Awards<br />
' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177
2018-12-13 06:17:04 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'link.href   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AN' at line 1 [ SELECT * FROM pages WHERE   slug = ''   link.href   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2018-12-13 06:17:04 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
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
2018-12-13 16:28:34 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='BusinessCapitalAdvisor247', email='noreply@businesscapitaladvisor247.com', company='http://BusinessCapitalAdvisor247.com', phone='[Not Provided]', message='Hi, letting you know that http://BusinessCapitalAdvisor247.com can find your business a SBA or private loan for $2,000 - $350K Without high credit or collateral. <br />
 <br />
Find Out how much you qualify for by clicking here: <br />
 <br />
http://BusinessCapitalAdvisor247.com <br />
 <br />
Minimum requirements include your company being established for at least a year and with current gross revenue of at least 120K. Eligibility and funding can be completed in as fast as 48hrs. Terms are personalized for each business so I suggest applying to find out exactly how much you can get on various terms. <br />
 <br />
This is a free service from a qualified lender and the approval will be based on the annual revenue of your business. These funds are Non-Restrictive, allowing you to spend the full amount in any way you require including business debt consolidation, hiring, marketing, or Absolutely Any Other expense. <br />
 <br />
If you need fast and easy business funding take a look at these programs now as there is limited availability: <br />
 <br />
http://BusinessCapitalAdvisor247.com <br />
 <br />
Have a great day, <br />
The Business Capital Advisor 247 Team <br />
 <br />
unsubscribe/remove - http://BusinessCapitalAdvisor247.com/r.php?url=doubletreebristol.com&id=e124' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177