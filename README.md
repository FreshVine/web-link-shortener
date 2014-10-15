# Link Shortener by Fresh Vine

A simple Link shortener that you can setup for your project. Written in PHP it passes through the desired end point based on the input.  
  
This particular URL shortener was designed to work along side a SaaS product that has different customer account, and different types of content. Therefore some of the database structure will reflect those realities.  
  
It's super easy to use. Fill out the config file - drop all the contents into a folder or new domain. Then drop three files into your app so you can start making short links!  
  
## Why another Shortener  

When looking around at options they are either designed very simply, or hosted by a third party who wants you to upgrade. Yet for many of us, we just want a simple way to track who is clicking around our apps.  
  
### What Makes This Awesome

1. *You host it - You own the data*  

1. *Easy to customize landing and error pages*  

1. *Only takes three files to include and start making links in your app*  

1. *Automatically breaks down the devices, browsers, and geographic data for every click*

1. *Optionally Track links by client id and user id*  

1. *Optionally Manage your links by what they are for, and specific IDs (ex: General, Event #124, or Email #324)*  

1. *Optionally add up to 5 variables on to your shortened links to track behavior - stored with each click*
	1.  **Location**  
	Use the same short link multiple times in a page/email and mark their location for reference later. (varchar)  
	
	1.  **Person ID**  
	Apply your unique identifier for an individual to the link. Use the same link for everyone, and see who clicks it. (integer)  
	
	1.  **a,b,c**  
	An additional three variables for you to use in tracking other variables. Maybe you're using the same link over different days, or colors, or wording. Whatever the case - you can track it. (varchar)  

1. *Coming Soon: Social Media share tracking. This is for version 2.0 of the shortener. Making it easy to see when/where it's been shared.*

## Setup and Configuration  
 
There are a few things that you need to do to get this configured and ready for use.  
  
1. **Configure your Software**  
To do this you simply make a copy of the `default-fvls_config.php` file without the 'default-' prefix. Then consult ensure all the values needed are correctly entered into it.

1. **Database Setup** *optional*  
Based on the configuration settings you supplied in the first step your database will be created or updated. You can manually run the version scripts if you choose, but it will be done automatically for you.

1. **Design Error and Homepage** *optional*  
If you do nothing this will use the default pages (which I still think look swell). If you wish to customize these simply make a copy of the folder and remove the 'default-' prefix. You must have either an index.php, index.htm, or index.html file (they will be looked for in that order). 


## Implementation and Use  
  
There are two parts to the shortener. The site itself that manages the redirects, and the include that helps you make the shortened links. Both parts required the same configuration file mentioned above.  

### Part 1: Your Shortener Site  
  
The assumption is that you're running apache and mysql. If those are both true then all you need to do is fill out the config file, and drop everything into your directory. It can be in a folder (ex: http://yoursite.com/l/) or for a whole domain (we're using it at http://freshv.in/). Once you have the files online - simply open the URL in your browser and the database will be setup for you. In the future all updates will be managed the same way.  

### Part 2: Shortening Links in your App or Service  
  
Drop three of the files anywhere into your app (as long as they are in the same directory). Then you need to include one to get access to our pre-written functions. Use the snippet below to make a link.

	  <?php
	  require(PATH_TO_INCLUDE.'fvls_link_shortener.php');	// All you need to do
	  $ShortLink = CreateShortLink('https://freshvine.co/');	// Make your first link!
	  echo '<a href="' . $ShortLink . '">' . $ShortLink . ' sends you to https://freshvine.co/</a>';
	  ?>

The three files you need are:  

*   fvls\_link_shortener.php  
*   fvls_config.php  
*   fvls_db.php  