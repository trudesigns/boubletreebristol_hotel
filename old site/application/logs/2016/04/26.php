<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2016-04-26 01:02:09 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'caption.linkHref   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photob' at line 1 [ SELECT * FROM pages WHERE   slug = ''   caption.linkHref   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2016-04-26 01:02:09 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
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
2016-04-26 03:16:18 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Carter Caroselli', email='Gerdts41824@gmail.com', company='[Not Provided]', phone='[Not Provided]', message='GoodMorning,<br />
 My name is NAME. May I kindly ask you if I can advertise in your website please? I need more visitor in my website. If you can, I will pay you 280$ monthly or we can discuss about the price.<br />
please check my website http://nroxy.com and see if you like it maybe?<br />
Thank you for your time!<br />
Regards,<br />
Nroxy Team<br />
<br />
Advertising' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177
2016-04-26 09:53:13 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Request for Proposal notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Kari Dapp', email='kari@mahfct.org', company='Make A Home Foundation,Inc.', phone='2035275100', message='Good Day, <br />
We are a non profit 501 C3. We help homeless veterans and families in need across CT, donating furniture, appliances and anything to "Make A Home", We host monthly fundraisers to help fund what we do. In the past, you guys have helped us with a donation of two nights stay at your location. We raffle this off to benefit our event. Is it possible for you guys to donate again to us. Please let me know if you need anything further from me to make this happen. Email Kari to kari@mahfct.org. Our Fed Id # 27-2932637. <br />
We cant thank you enough... <br />
Regards, <br />
Kari Dapp' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:333
2016-04-26 10:39:42 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Request for Proposal notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Amanda Paradis', email='aparadis0929@gmail.com', company='[Not Provided]', phone='860-816-0596', message='I'm currently exploring options on different venues for a baby shower. It would be on a Sunday probably in July. Do you have any shower packages? <br />
<br />
Thanks!<br />
' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:333