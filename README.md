# Fresh Vine URL Shortener

A simple URL shortener that you can setup for your project. Written in PHP it passes through the desired end point based on the input.  
  
This particular URL shortener was designed to work along side a SaaS product that has different customer account, and different types of content. Therefore some of the database structure will reflect those realities.


## Setup and Configuration
 
There are a few things that you need to do to get this configured and ready for use.  
  
1. **Configure your Software**  
To do this you simply make a copy of the `default-fvls_config.php` file without the 'default-' prefix. Then consult ensure all the values needed are correctly entered into it.

1. **Database Setup** *optional*  
Based on the configuration settings you supplied in the first step your database will be created or updated. You can manually run the version scripts if you choose, but it will be done automatically for you.

1. **Design Error and Homepage** *optional*  
If you do nothing this will use the default pages (which I still think look swell). If you wish to customize these simply make a copy of the folder and remove the 'default-' prefix. You must have either an index.php, index.htm, or index.html file (they will be looked for in that order). 


## Implementation and Use 