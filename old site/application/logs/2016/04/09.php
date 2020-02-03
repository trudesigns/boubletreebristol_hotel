<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2016-04-09 01:18:49 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Shelby Nixon', email='go4designsite@gmail.com', company='[Not Provided]', phone='(612) 405-4211', message='Hi I'm Shelby. I am a web design expert, my team and I create visually appealing, easy to manage and maintain websites that are responsive on mobile devices. If you have been or are interested in looking at options to help your site stand out from the crowd I would be happy to speak with you and give you some ideas and tips that will help your site.<br />
<br />
I will go an extra mile to make sure my clients are happy and satisfied. I have been doing this for a long time and am interested in building long term relationships where I can provide real value to my clients.  <br />
<br />
Let me know if you are interested in speaking about how my team and I can help your site. I will come ready with some ideas and research on your site and will be more than happy to answer any questions that you may have.<br />
<br />
Thanks,<br />
<br />
Shelby Nixon<br />
<br />
If you are not interested just let me know and I will remove you from my list. Thanks.<br />
' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177
2016-04-09 18:16:08 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Request for Proposal notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Wendy Tompkins', email='wendy.tompkins@bioscrip.com', company='[Not Provided]', phone='8607966869', message='This would be a college graduation party. Approx 40 people possibly 50 max. We will need a screen to show a photo slide show. As of yet I have not hired a dj or caterer. I would like an estimate for 5/21/16 starting at 7PM. Thank you,' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:333
2016-04-09 19:22:59 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Josh Williams', email='real4youmarketing@gmail.com', company='[Not Provided]', phone='(802) 441-5331', message='Hi, my name is Josh and I am a digital marketing expert who has been helping site owners rank their sites for over 12 years. I have seen many changes in the digital marketing world during these 12 years. I still see a lot of the same mistakes being made by companies when trying to outrank their competition, most of these mistakes can be solved easily before resulting in a penalty from Google.<br />
<br />
My team and I are currently offering a free consultation where I will be able to help you see what can be done on your site to increase your traffic as well improving sales. I offer this info and my experience to give you a chance to "try me before you buy me." I'm always looking to help those who really need it and am interested in forming a long-term relationship.<br />
<br />
If you are or have been thinking of ranking your site higher on the web I would be happy to have a call with you to answer any questions that you may have and also show you what we can do for you.<br />
<br />
Just let me know when you are available and the best number to reach you on and I will be happy to call you. <br />
<br />
Thanks,<br />
<br />
Josh Williams<br />
<br />
If you are not interested in our help or are not looking for any help with your site just let me know by replying with remove in the subject line and we will not contact you again. <br />
' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177
2016-04-09 23:19:48 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'link.href   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AN' at line 1 [ SELECT * FROM pages WHERE   slug = ''   link.href   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2016-04-09 23:19:48 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
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