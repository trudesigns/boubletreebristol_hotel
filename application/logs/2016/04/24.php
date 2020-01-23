<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2016-04-24 09:04:13 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Ileen', email='Geffken55641@yahoo.com', company='google', phone='(219) 477-0619', message='If you’re like most of my clients, you want to shine more attention on your Social Media presence. <br />
 <br />
If that’s the case, let me take your Social Media Marketing over the TOP! <br />
 <br />
Our Promotion Packages: <br />
----------------------- <br />
 <br />
1) 5,000 Facebook Fans/Likes($75)        = Order at:- http://khalaghor.com/5kfb/ <br />
2) 5,000 Twitter Followers($35)          = Order at:- http://khalaghor.com/twitter/ <br />
3) 25,000 YouTube Views($25)             = Order at:- http://khalaghor.com/youtube/ <br />
4) 1,000 Google+ Ones($50)               = Order at:- http://khalaghor.com/google/ <br />
5) 5,000 Instagram Followers($40)        = Order at:- http://khalaghor.com/instagram/ <br />
 <br />
 <br />
You can find more options for smaller or even bigger packages at our website.  Click here NOW http://socialeum.com to see what we can do for you! <br />
 <br />
Don't reply to this mail.We don't monitor inbox. <br />
 <br />
Thank You <br />
 <br />
 <br />
To unsubscribe, visit:- http://tinyurl.com/jh2j82q' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177
2016-04-24 12:04:13 --- EMERGENCY: Database_Exception [ 1064 ]: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'caption.linkHref   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photob' at line 1 [ SELECT * FROM pages WHERE   slug = ''   caption.linkHref   '' AND parent_id = (SELECT id FROM pages WHERE slug = 'photobox'  AND parent_id = (SELECT id FROM pages WHERE slug = 'plugins'  AND parent_id = (SELECT id FROM pages WHERE slug = 'assets' AND parent_id = 0 ))) ] ~ MODPATH/database/classes/Kohana/Database/MySQL.php [ 194 ] in /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php:251
2016-04-24 12:04:13 --- DEBUG: #0 /var/www/html/doubletreebristol.prod01.pita.website/modules/database/classes/Kohana/Database/Query.php(251): Kohana_Database_MySQL->query(1, 'SELECT * FROM p...', true, Array)
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
2016-04-24 23:22:22 --- INFO:  Email request TO: Lynn.Dell@Hilton.com SUBJECT: Doubletreebristol.com: Contact Us notification MESSAGE: A user has filled out the Contact Us form. Their information is below. DATA: name='Chris Rodgersen', email='onlineeammarketing@gmail.com', company='[Not Provided]', phone='(201) 503-4744', message='I know you probably receive a lot of emails talking about search engine optimization services but we are different from the rest and I am willing to prove it.<br />
<br />
Here are a few reasons we are not your normal SEO agency.<br />
<br />
1) Our Search Engine Optimization and Marketing services are designed for success.  Our services generally start at $1500 a month in order to provide your campaign with the necessary resources to do the job right and confidently predict a successful campaign.  If you've been burned by another company in the past, it was likely that they were just giving you the price you wanted rather than what was required to get results.  We are focused on getting results!<br />
<br />
2) We are in it for the long-term.  We are an extremely low turn-over business and our costs go up significantly if we loose clients within the first year, so we have a large incentive to keep you happy and keep you on-board long-term.<br />
<br />
3) We provide realistic expectations to our clients rather than telling them what they want to hear just to get them to sign up.   The truth isn't as exciting as the fairy tales that other companies are willing to tell you, but it's the truth.<br />
<br />
If you're interested in hearing what we can do for your site then I would love to set up a consultation call with you.  Let me know what time/day works best (and the best number).<br />
<br />
Thanks!<br />
<br />
Chris<br />
' in /var/www/html/doubletreebristol.prod01.pita.website/application/classes/Controller/Custom.php:177