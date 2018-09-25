<?php
/*******************************************************************************
 *   copyright				: (C) 2014 - 2018 Pro-Auction-Script
 *   site					: https://www.pro-auction-script.com
 *   Script License			: https://www.pro-auction-script.com/contents.php?show=free_license
 *******************************************************************************/

if ($myversion == 'SE 1 (Build 1)') //Frist SE version DB update
{	
	$query[] = "UPDATE " . $DBPrefix . "settings SET value = 'SE 1 (Build 2)' WHERE fieldname = 'version';";
}
if ($myversion == 'SE 1 (Build 2)')
{
	$query[] = "INSERT INTO " . $DBPrefix . "regionalCodes VALUES (NULL, 'Danish', 'DK');";
	$query[] = "ALTER TABLE " . $DBPrefix . "categories CHANGE cat_colour cat_color VARCHAR(20);";
	$query[] = "ALTER TABLE " . $DBPrefix . "users MODIFY bank_account varchar(50) DEFAULT '';";
	$query[] = "ALTER TABLE " . $DBPrefix . "users MODIFY bank_routing varchar(50) DEFAULT '';";
	$query[] = "ALTER TABLE " . $DBPrefix . "users MODIFY avatar varchar(250) DEFAULT '';";
	$query[] = "ALTER TABLE " . $DBPrefix . "users MODIFY facebook_id varchar(100) DEFAULT '';";	
	$query[] = "INSERT INTO `" . $DBPrefix . "settings` VALUES ('debugging', 'bool', 'n', UNIX_TIMESTAMP(), 1);";
	$query[] = "INSERT INTO `" . $DBPrefix . "settings` VALUES ('liveChat', 'bool', 'n', UNIX_TIMESTAMP(), 1);";
	$query[] = "INSERT INTO `" . $DBPrefix . "settings` VALUES ('liveChatTitle', 'str', 'Live Chat', UNIX_TIMESTAMP(), 1);";
	$query[] = "INSERT INTO `" . $DBPrefix . "settings` VALUES ('liveChatLockNick', 'bool', 'y', UNIX_TIMESTAMP(), 1);";
	$query[] = "INSERT INTO `" . $DBPrefix . "settings` VALUES ('liveChatPMLimit', 'int', '5', UNIX_TIMESTAMP(), 1);";
	$query[] = "INSERT INTO `" . $DBPrefix . "settings` VALUES ('liveChatTextLen', 'int', '400', UNIX_TIMESTAMP(), 1);";
	$query[] = "INSERT INTO `" . $DBPrefix . "settings` VALUES ('liveChatMaxMSG', 'int', '20', UNIX_TIMESTAMP(), 1);";
	$query[] = "INSERT INTO `" . $DBPrefix . "settings` VALUES ('liveChatMaxDisplayMSG', 'int', '150', UNIX_TIMESTAMP(), 1);";
	$query[] = "INSERT INTO `" . $DBPrefix . "settings` VALUES ('liveChatTheme', 'bool', '0', UNIX_TIMESTAMP(), 1);";

	$query[] = "DROP TABLE IF EXISTS `" . $DBPrefix . "phpchat`;";
	$query[] = "CREATE TABLE `" . $DBPrefix . "phpchat` (
	  `server` varchar(32) NOT NULL default '',
	  `group` varchar(64) NOT NULL default '',
	  `subgroup` varchar(128) NOT NULL default '',
	  `leaf` varchar(128) NOT NULL default '',
	  `leafvalue` text,
	  `timestamp` int(11) NOT NULL default 0,
	  PRIMARY KEY  (`server`),
	) ;";
	
	$query[] = "ALTER TABLE " . $DBPrefix . "faqs_translated DROP PRIMARY KEY;";
	$query[] = "ALTER TABLE " . $DBPrefix . "auctions MODIFY starts int(14) DEFAULT NULL;";
	$query[] = "ALTER TABLE " . $DBPrefix . "auctions MODIFY ends int(14) DEFAULT NULL;";
	$query[] = "ALTER TABLE " . $DBPrefix . "auctions MODIFY shipping int(1) DEFAULT NULL;";
	$query[] = "ALTER TABLE " . $DBPrefix . "auctions MODIFY secondcat int(11) DEFAULT NULL;";
	$query[] = "ALTER TABLE " . $DBPrefix . "auctions MODIFY international int(1) DEFAULT NULL;";
	$query[] = "ALTER TABLE " . $DBPrefix . "users MODIFY user_key varchar(200) DEFAULT '';";
	$query[] = "UPDATE " . $DBPrefix . "settings SET value = '" . base64_encode(md5(uniqid(rand(0,99), true))) . "' WHERE fieldname = 'encryption_key';";

	$query[] = "DROP TABLE IF EXISTS " . $DBPrefix . "faqs_translated;";
	$query[] = "CREATE TABLE `" . $DBPrefix . "faqs_translated` (
	  `faq_id` int(11) NOT NULL default '0',
	  `lang` char(2) NOT NULL default '',
	  `question` varchar(200) NOT NULL default '',
	  `answer` longtext NOT NULL
	) ;";
	
	# 
	# Dumping data for table `" . $DBPrefix . "faqs_translated`
	# 
	$settingsArray = getSettings();
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (1, 'EN', 'Registering', 'To register as a new user, click on Register at the top of the window. You will be asked for your name, a username and password, and contact information, including your email address.\r\n\r\n<B>You must be at least 18 years of age to register.</B>!');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (2, 'EN', 'Item Watch', '<p><b>Item watch</b>&nbsp;notifies you when someone bids on the auctions that you have added to your Item Watch.</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (3, 'EN', 'What is a Dutch auction?', 'Dutch auction is a type of auction where the auctioneer begins with a high asking price which is lowered until some participant is willing to accept the auctioneer\'s price. The winning participant pays the last announced price.');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (4, 'EN', 'Reporting an Auction', '<p>Report a Auction always the user to report a auction to the " . $settingsArray['sitename'] . " Support Team if that person fells that the auction has broken any " . $settingsArray['sitename'] . " rules. </p><br><p>They can click on the Report this Auction button that is located in the Additional Details tab and then Auction details. Then they would fill in the report form that gets sent to the " . $settingsArray['sitename'] . " Support Team.</p><br><p>Please Note: All reports are confidential your details will not be disclosed to the Seller </p><br><p>Please read the <a style=\"color:blue\"  href=\"http://" . $settingsArray['siteurl'] . "contents.php?show=terms\"><u> Terms & Conditions</u></a> and <a style=\"color:blue\" href=\"http://" . $settingsArray['siteurl'] . "contents.php?show=priv\"><u>Privacy Policy</u></a> pages before reporting a auction to " . $settingsArray['sitename'] . " Support Team.</p><br><p style=\"color:red\"><b>Misuse of the reporting system is not taken lightly</b></p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (5, 'EN', 'Biding contract', '<p><strong>Biding contract: </strong>By placing a bid you are making a CONTRACT between you and the seller. Once you place a bid, you may not retract it. In some states, it is illegal to win an auction and not purchase the item. In other words, if you don&#39;t want to pay for it, don&#39;t bid!</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (6, 'EN', 'Buyer and Seller Arrangements', 'The buyer and seller are responsible for making their own arrangements to conclude the deal by payment and shipment. This auction merely offers a venue for buying and selling. We will not, and legally cannot, be held responsible in any way for any lack of performance by any and all parties. We simply provide a place that allows people to buy and sell. HOWEVER, do let us know of any problems. We can and will close the account of anyone who abuses our auction.');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (7, 'EN', 'How do I pay for an item?', 'If you are the highest bidder when an auction closes, the item is yours, and you will need to pay the seller according to the terms of the auction. You will receive an email notifying you of your winning bid. The email will contain contact information for the seller, as well as button so that you can see the total due (shipping, handling, etc.) In some cases, sellers may opt to do calculated shipping rather than charge a flat rate, in which case you will need to contact the seller for a total amount due.');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (8, 'EN', 'Bidding Basics', '<p>Buying is easy on " . $settingsArray['sitename'] . "! Start by using the Search feature to find what you\'re looking for, or just browse the categories. To bid on an auction, first make sure your are registered and have logged in, then enter your maximum bid amount. Remember, that this is an auction, and you can always be outbid, so be sure to add the item to your Watch List and check it often. IMPORTANT! By placing a winning bid, you are entering into a binding contract with the seller to purchase the item. Non-paying bidders may have their accounts suspended or they may be permanently banned from " . $settingsArray['sitename'] . "</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (9, 'EN', 'How do I retract a bid?', '<p>" . $settingsArray['sitename'] . " doesn\'t currently have a mechanism for retraction. You will be asked to confirm your bid, so please take that opportunity to double-check your bid amount. Remember that if you are the winning bidder you are obligated to follow through with the transaction. If you believe that the description of an auction item has changed significantly since you placed your bid, you are encouraged to contact the seller directly to resolve the issue. If you are unable to come to a resolution, <a href=\"http://" . $settingsArray['siteurl'] . "email_request_support.php\">contact us</a> at " . $settingsArray['sitename'] . "</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (10, 'EN', 'Why did I get outbid at the last second?', 'Bidding at the last second is common in all online auctions. Many bidders will wait until the last possible moment to place their bid in an effort to protect them from becoming involved in a (bidding war). We don\'t encourage last second bidding, or (sniping) for the sole reason that if your timing is off, or there is some type of delay in your information being sent through the Internet, your bid may not be placed before the item bidding time ends.');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (11, 'EN', 'What is a Reserve Price?', '<p>Sellers will occasionally set a Reserve Price that is above the minimum starting bid, in an effort to make sure that they do not sell an item for less a specific amount. If a reserve price is in effect, then the seller does not have to sell the item unless the high bid meets or exceeds the reserve. Auctions with a reserve price will be noted in their listing, describing whether the reserve has been met or not. The actual amount of the reserve price may or may not be revealed to bidders in the auction description. When you submit a bid on a reserve price auction, one of three things might happen:</p><ol><li>If the reserve has already been met, then your bid will be submitted at one increment above the next highest competitor, in the same manner as an auction without a reserve price.</li><li>If the reserve has not been met, and your maximum bid is also less than the reserve, then your bid will be entered at one increment above the next highest competitor.</li><li>If the reserve has not been met, but your maximum bid is enough to meet the reserve, then your bid will be entered at one increment above the next highest competitor and at that point, the item will sell to the highest bidder.</li></ol><p>If your maximum was above the seller&#39;s reserve, then " . $settingsArray['sitename'] . "&#39;s proxy bidding will defend your bid, up to your maximum. If you are the highest bidder at auction close but the reserve was not met, then seller is not obligated to complete the transaction.</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (12, 'EN', 'How do I view auctions that I am bidding on?', '<p>At the top of the " . $settingsArray['sitename'] . " page hover your mouse over My " . $settingsArray['sitename'] . " Panel a drop down menu will appear. Then under the Buying category click on the <a href=\"http://" . $settingsArray['siteurl'] . "yourbids.php\"> Your bids</a> link you will be able to see any items you are bidding on.</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (13, 'EN', 'Can I leave feedback for a buyer/seller?', '<p>You can leave feedback for a buyer or seller after an auction has closed. The feedback at " . $settingsArray['sitename'] . " is a simple positive/negative/neutral system. A feedback score is calculated on a percentage basis - for instance, five out of five positive feedback ratings equals an overall rating of 100%. To see your own feedback, or leave feedback for others, login and hovering your mouse over My " . $settingsArray['sitename'] . " Panel at the top of the " . $settingsArray['sitename'] . " page, then under My Account category click on Leave Feedback. Remember, members should do everything they can to resolve an issue before leaving negative feedback.</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (14, 'EN', 'What is your policy on copyright infringement?', '" . $settingsArray['sitename'] . " is essentially an open marketplace, and it is up to individual sellers to insure that items they are listing do not violate copyright law. " . $settingsArray['sitename'] . " is obligated to comply with US Copyright law and may remove auctions in violation of copyright at the request of affected intellectual property owners.');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (15, 'EN', 'Basic Fees', '<p>This page sets out the fees charged by " . $settingsArray['sitename'] . ". There are no fees for browsing the site or using the services of the site, unless otherwise clearly stated.<br /><br>There are no charges for buyers, such as buyer&#39;s premium charged by many auction houses, and no charges for just browsing or searching the site, but you do need to register if you decide to bid on an item or use other facilities of the site.<br><br>The fees are charged to the seller for a &quot;normal&quot; auction. In effect the person who places the auction pays the fees.<br><br>Commission fees are charged to your account immediately a listing closes successfully and are not refundable. If a Buyer fails to make payment for an item, a commission fee refund is made only when the Procedures for non-paying bidders (NPB) has been followed and completed.<br /><br>You can find the " . $settingsArray['sitename'] . " fees by <a href=\"http://" . $settingsArray['siteurl'] . "fees.php\">Clicking Here</a>!</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (16, 'EN', 'Proxy bidding', 'Proxy bidding for all bids: Please bid the maximum amount you are willing to pay for this item. Your maximum amount will be kept secret; " . $settingsArray['sitename'] . " will bid on your behalf as necessary by increasing your bid by the current bid increment up until your maximum is reached. This saves you the trouble of having to keep track of the auction as it proceeds and prevents you from being outbid at the last minute unless your spending limit is exceeded. Also, in case of a tie for high bidder, earlier bids take precedence. And, keep in mind that you cannot reduce your maximum bid at a later date. If you have bid on this item before, note that your new bid must be greater than your previous bid.');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (17, 'EN', 'What features does " . $settingsArray['sitename'] . " offer for free?', '<p>" . $settingsArray['sitename'] . " actually has a great list of features, including some things even the &quot;big guy&quot; doesn&#39;t offer.</p><br><p><b>Free " . $settingsArray['sitename'] . " features:</b><br />Image Gallery (upload up to 20 images) - Buy It Now - Dutch Auction - Relist - Reserve Price - Auction Setup - Extended Auction Durations (up to 3 weeks!) - Return Policy - Packing Slip - Reserve Price - Automatic Relists - Shipping Status ( The buyer and seller can update the shipping status from Item was Shipped to Item was Delivered ) - Seller Active Action Gallery (Displays the Sellers Active Auction list on the item page)</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (18, 'EN', 'Hide my online status', '<p>Hide my online status will allow you to browse " . $settingsArray['sitename'] . " well your online status displays offline well you are online.<br><br>You can hide your online status by checking the box for (Hide my online status) well you are filling in you username and password to login.<br><br>If you logged in to " . $settingsArray['sitename'] . " with out checking the (Hide my online status) box and you want to hide your online status go to My " . $settingsArray['sitename'] . " panel > Edit your personal profile page and check the box for (Hide my online satus) than click save changes.</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (19, 'EN', 'How do I list an item I want to sell?', '<p>1. you must be a registered user and logged in</p><p>2. select the sell an item button on the home page</p><p>3. choose the category of the item you are going to sell  ex. books,clothing, etc. </p><p>4. select type of category</p><p>5. enter the item title and item subtitle (if desired) and as much information you may have on that item and pictures if you have any. select the item quantity, set the auction starting price and the required and or desired settings for your auction, and your shipping fees and any and all terms or agreements with the listed item</p><p>6. if your item is listed the way you  like it, select the submit auction button and you will see the auction preview window, this is where you can see how your auction will look like and you are able to make changes at this time please look over all of your posting and make any necessary changes before continuing</p><p>7. select the confirm auction button    </p><p>8. check your email that is registered with your " . $settingsArray['sitename'] . " account for the confirmation email</p><p>9. congratulations your item is now posted  good luck!! </p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (20, 'EN', 'How can I tell if a item was paid for?', '<p>1. Must be logged in.</p> <p>2. At the top of the " . $settingsArray['sitename'] . " page click My Account</p><p>3. Than click on WINNING DETAILS </p><p>4. You can find the Payment Status on the right side of the auction info.</p><p>5. If the item was not paid the status will display ( Waiting for Payment or Set as Paid ) </p><p>6. If the item was paid then the status will change to Paid.</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (21, 'EN', 'Auction picture - How to create a thumnail', '<p>1. click on Choose file button (pick your image you want)<br>2. Click on Upload file button.<br><b>**</b> If the image is the first picture that was uploaded it will be displayed with a Save and Back buttons. If you choose the Save button the image will be displayed as the default image. If you choose the Back button the image will not be used as the default image<br><b>**</b>Repeat steps 1. and 2. for each picture you want to upload.<br>3.Make sure the default has been checked.)');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (22, 'EN', 'If an item does not sell can I relist?', '<p>If your item does not sell, you can relist as many as you\'d like. To relist an item, login and go to My " . $settingsArray['sitename'] . " panel, then under the Selling category click on the Closed Auctions link, then find your auction and click Relist.</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (23, 'EN', 'How do I request payment for an item?', '<p>You will choose your payment method when you set up your auction listing. Payment options - Paypal. Once the auction has closed you will automatically receive an email with the buyer\'s contact information.</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (24, 'EN', 'What is Buy-It-Now?', '<p>Buy-It-Now is an exciting optional enhancement to your listings. As a seller, if you choose to use the Buy-It-Now feature at the time of listing, you will be able to name a price at which you would be willing to sell your item to any buyer who meets your specified price. Your listing will be run as a normal auction, but will now feature a Buy-It-Now price. Buyers will have the option to buy your item instantly without waiting for the listing to end or can bid on your item as usual. </p><br><p><b>Why use Buy-It-Now?</b></p><br><table cellspacing=\"0\" cellpadding=\"8\" width=\"600\" border=\"2\"><tbody align=\"middle\"><tr bgcolor=\"#b4d0fa\"><td align=\"middle\"><b>Buyers</b></td><td align=\"middle\"><b>Sellers</b></td></tr><tr><td>Buy items without having to wait for the auction to end.</td><td>Sell your items fast, without waiting for the auction to end.</td></tr><tr><td>Quick, easy, and convenient way to shop for the holidays.</td><td>Quick, easy, and convenient way to sell items. </td></tr><tr><td>The choice to buy first or bid!</td><td>Get potential buyers to act on your items earlier</td></tr><tr><td>Buy an item at a fair price. </td><td>Sell your item for the price you want.</td></tr></tbody></table><br><p><b>How do I know an auction has a Buy-It-Now price?</b></p><p>It\'s easy! Look for the Buy Now icon:  <span class=\"buyN shadow5\"> Buy Now</span> or <img border=\"0\" align=\"absbottom\"  src=\"language/EN/images/buy_it_now.gif\"> on the search results page, and the item page itself.</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (25, 'EN', 'How To Bid?', '<p>1. Register - You must be registered.<br>2. Learn more about the seller - Read the opinions of other users for the seller.<br>3. Learn more about the item - read the items description and ask any questions before bidding.<br>4. Ask a question - If you still have questions, then please contact the seller.<br></p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (26, 'EN', 'Login with Facebook', '<p>You can use your facebook account to login to your " . $settingsArray['sitename'] . " account.<br /><br />1. You can link your facebook account to your " . $settingsArray['sitename'] . " account when you register for a new account on " . $settingsArray['sitename'] . ".<br /><br />2. If you have a account already on " . $settingsArray['sitename'] . " you can link your facebook account to your " . $settingsArray['sitename'] . " account in your Control Panel -&gt; Edit your personal profile page.<br /><br />3. You can unlink your facebook account from " . $settingsArray['sitename'] . " at any time in your Control Panel -&gt; &nbsp;Edit your personal profile page</p>');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (27, 'EN', 'Changing language', 'You can change the language to a different language by clicking on the flag that is on the home page to change the language you want use.<br>This does not change the auctions description language.');";
	$query[] = "INSERT INTO `" . $DBPrefix . "faqs_translated` VALUES (28, 'EN', 'Unpaid items', '<p>Any unpaid items that are not paid with in 30 days will be deleted from our database and will not show up in the winner and seller control panel that the item was buaght.</p>');";

	$query[] = "ALTER TABLE " . $DBPrefix . "users DROP COLUMN user_key;";
	$query[] = "ALTER TABLE " . $DBPrefix . "facebookLogin DROP COLUMN address;";
	$query[] = "ALTER TABLE " . $DBPrefix . "facebookLogin DROP COLUMN phone;";
	$query[] = "ALTER TABLE " . $DBPrefix . "facebookLogin DROP COLUMN birthday;";
	$query[] = "ALTER TABLE " . $DBPrefix . "facebookLogin CHANGE postdate timestamp int(15) default NULL;";
	$query[] = "ALTER TABLE " . $DBPrefix . "sms_settings MODIFY COLUMN smsActivationCode varchar(9) NOT NULL;";
	$query[] = "ALTER TABLE " . $DBPrefix . "sms_ip MODIFY COLUMN temp_timer text NOT NULL;";
	$query[] = "ALTER TABLE " . $DBPrefix . "sms_settings ADD codeStrength ENUM('y','n') NOT NULL DEFAULT 'y' AFTER outBiddedAlert;";
	
	$query[] = "INSERT INTO `" . $DBPrefix . "settings` VALUES ('googleMapKey', 'str', '', UNIX_TIMESTAMP(), 1);";
	$query[] = "INSERT INTO `" . $DBPrefix . "settings` VALUES ('sms_alerts', 'bool', 'y', UNIX_TIMESTAMP(), 1);";
	
	$query[] = "ALTER TABLE " . $DBPrefix . "users CHANGE COLUMN `groups` `user_groups` TEXT;";
	$query[] = "ALTER TABLE " . $DBPrefix . "sms_settings ADD smsActivationTimer INT(13) NULL AFTER smsActivationCode;";
	$query[] = "UPDATE " . $DBPrefix . "settings SET value = 'SE 1 (Build 3)' WHERE fieldname = 'version';";
}