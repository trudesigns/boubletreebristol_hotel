<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2016-04-21 01:04:14 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Deetta Mcelravy', email='Kundanani72472@gmail.com', company='[Not Provided]', phone='[Not Provided]', message='Hi, If you are looking for the BEST Article Writing Software that Actually Works, learn more here: http://ow.ly/4mLJFN. This Article Builder review will literally walk you through how you can use Article Builder to get REAL, high quality articles are not not jibberish and won't get you put into SEO Jail. If you are a serious internet marketer who wants to leverage the internet to make money with whatever niche you are in, you must check out this best Article Writing Software: http://ow.ly/4mLJFN . Thanks<br />
<br />
Hi' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177
2016-04-21 21:42:54 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Aaron Anderson', email='marketing4motive@gmail.com', company='[Not Provided]', phone='7015573934', message='Hi my name is Aaron. I am part of an innovate web design and marketing company that specializes in building very attractive and well-optimized websites that perform well in Google and the other search engines. I'm writing to see if you have web development projects that you could use our help with, whether it's a full website build or just a few simple modifications. <br />
<br />
We take on clients of all shapes and sizes and we know we can help your site to perform better. I'd love to set up a call with you to talk about the goals for your site/business and how we can help you to achieve those goals. <br />
Do you have time tomorrow or sometime within the next few days for a quick introduction call? <br />
<br />
I can also provide you with information about things that have worked very well for our other clients to help them achieve their goals. I will do my best to work with your schedule, so let me know what time works best and we'll set up a call. <br />
<br />
Best Regards, <br />
<br />
Aaron Anderson <br />
<br />
I will be happy to answer any questions that you may have and help you even if we don't end up working together in the end. If you are not interested in receiving anymore emails just let me know by replying with the word remove and I will take you off our list.<br />
' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177
2016-04-21 23:09:33 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'link.href   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AN' at line 1 [ SELECT * FROM pages WHERE   slug = ''   link.href   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2016-04-21 23:09:33 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
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
2016-04-21 23:10:43 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'caption.linkHref   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photob' at line 1 [ SELECT * FROM pages WHERE   slug = ''   caption.linkHref   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2016-04-21 23:10:43 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
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
2016-04-21 23:43:31 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Doug Moss', email='staticwebsolutions@gmail.com', company='[Not Provided]', phone='(701) 484-1858', message='This is Doug, and I own a web design agency that takes great pleasure and pride by getting our clients generate more leads, sales and profits through building eye pleasing, spectacular and affordable websites. <br />
<br />
I have an abundance of expertise in the field of web development and I have a team of highly qualified, uber-experienced, and talented development pros, who create solutions tailored to the needs of each client. <br />
<br />
If you are interested in making changes on your website or are thinking about a site redesign I would like to help by providing a free consultation to turn your dream website into a reality. <br />
<br />
Let me know what time works for you and I will be happy to provide you with your consultation and answer any questions you might have. <br />
<br />
I look forward to working with you. <br />
<br />
Kind regards, <br />
<br />
Doug Moss<br />
<br />
If you are not interested in the consultation or would like to be excluded from future messages just let me know by replying with remove in the subject line and I will remove you from the list. <br />
' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177