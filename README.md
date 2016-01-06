# NewMonk 

#### What is NewMonk?
NewMonk is a completely free, open source Real User Monitoting (RUM) and logging tool, with many additional user behaviors, like heatmap and error tracking features.

NewMonk also displays these logs, in form of user-friendly dashboards and reports, which can be viewed not only by your dev teams to help improve the page performance, but also by your marketing and product teams to help improve the user experience of your website.

Some of the major features provided by NewMonk are listed below:

#### RUM (Real User Monitoring):
* TTFB (Time To First Byte)
* DOM Ready Time
* Page Ready Time
* Page Load Time
* Happy/Unhappy Users
* Resource wise load time
* and more...

This module will help you analyze the performance of your web pages. You can basically specify the time duration(date range) and track the average page load time of a specific page. You can also track the page views count of a specific page. This way you can do the traffic analysis of a particular page.
Features you will be able to see on a page are:
* DNS lookup time
* First byte time of a page
* Page rendering time(DOM load time)
* Detailed report of visitors(like per browser average load time and total visits) and pie chart representation of the same.
* Platform details(average load time and total visits per platform)
* Load time distribution

#### HeatMap Click Tracking:
*	Track and log the areas of your webpage which are most clicked by the site visitors
*	Present this data in form of a heatmap to help improve the page (such as better placement of products in case of an e-commerce website, or better placement of buttons / form elements to help increase the conversions on your website)


With HeapMap, you’ll see exactly how visitors use your site. Instead of guessing, you'll know exactly how to transform the design, flow and layout of your website to make visitors happy. Heat Map tracking allows you to visualize data that other analytics tools can't give you. Within couple of days you'll get enough actionable information that you can use to generate more conversions from your website and grow your business.

Heat Maps will show you:-
* If your visitors are distracted by too many choices
* How to improve conversions
* How to get more email subscribers
*  What to emphasize, repurpose, and remove from your pages
*  Which images to reuse for ads, sales pages, and emails
"Heat Maps allow us to see which images people are the most intrigued by. We can then highlight these images for our advertising, or in any featured blogs, as well as look for similar images to highlight in the future."

#### Error Logging and Reporting:
Error Tracking in New monk will let you view the list of javascript error on various pages of your web site.
Errors related informations will be captured from your page and displayed on NewMonk Dashboard.
*	Front-end (javascript) error logging
*	Back-end (server side) error logging
*	Mobile app crash logging
*	Alerting on slack in case of error SLA threshold breach

Error details will be captured in the form of charts and tables, along with the information of error count and environment details corresponding to that error.

#### NewMonk Integration:
To integrate NewMonk on your web page, follow the below mentioned steps:

1.	Include ‘nLoggerJB.js’ file(available in assets folder) on top of your web page(before any other JS file inclusion).
2.	Just next to the ‘nLoggerJB.js’ file inclusion, initialise nLogger call, as given below:

```
nLogger.init({
   "tag": "yourTagName",
   "nLogger": {
       "beaconUrl": "http://localhost/newmonk/server/errLogger.php"
   },
   "boomerang": {
       "logBW": false,
       "beaconUrl": "http://localhost/newmonk/server/boomLogger.php",
       "imageURL": "http://localhost/newmonk/dashboard/assets/i/"
   },
   "heatmap": {
       "beaconUrl": "http://localhost/newmonk/server/heatmapLogger.php",
       "pluginBaseUrl": "http://localhost/newmonk/dashboard/assets/j/"
   },
   "appId": 2,
   "userIP": "3232267043"
});
```

##### Explaination : 

* **tag**: This will be the name by which you want to track/identify your webpage in the dashboard.
* **boomerang** : to enable performance tracking
* **boomerang [logBw]** : true/false flag to decide whether to capture bandwidth 
* **boomerang [beaconUrl]**: This will be the URL where you want to log performance tracking
* **boomerang [imageURL]** : Mention the url of images in Asset folder
* **nLogger** : to enable error log module
* **nLogger [beaconUrl]**: This will be the URL where you want to log error details
* **heatmap**: to enable heatmap module
* **heatmap [beaconUrl]**: This will be the URL where you want to log heatmap data
* **heatmap [pluginBaseUrl]**: This will be the URL which points to heatMap.js file present in j directory.
* **appId** : This will be the Unique ID for your App


#### Technical Architecture

At a higher level, NewMonk consists of three components working together:

1. **Front-end Data Collector**:
	This is a small javascript snippet that is injected in your web page which tracks page performance, user behavior and error related data.

1.	**Back-end Data Logger (Server)**:
	The data collected above is then sent to the back-end server APIs which then log this data into mysql, elasticsearch, redis, disk etc.

1.	**Visualizer (Dashboard)**:
	The data logged above can be then visualized in beautiful graphs using web-based graphical dashboards and kibana.

Also, you can write your own data collector for collecting and sending app crash data from native mobile applications for iOS or Android.



#### Requirements

* **php** >= 5.4 (alongwith a web server, such as nginx)
* **mysql** >= 5.5
* **redis** >= 2.8
* **elasticsearch** >= 1.6 (alongwith kibana >= 4.1)
* phpredis extension (https://github.com/phpredis/phpredis)

We are yet to test this with php 7. Also, please understand that it is also very diffcult for us to test this product with every version of php/mysql/redis/elasticsearch.

In case you find any errors due to incompatibility with a specific version of these tools, be sure to let us know at engineering@naukri.com or help us by raising a merge request.

#### Installing

The installation process might look a bit steep right now, but be assured, you are going to end up with a very beatiful product on your hands - or your server ;-) when you are done.

We are working on a one-click installation process, which will ease this process with a wizard very soon. But until then, follow along!

##### Understanding the concepts:

* **Domain:**  
   You can setup a single installation of NewMonk to log data from multiple domains. Typically, most installations would need only one domain on which your website is running.

*	**Application Id (appId):**  
	   An appId is a unique integer assigned (for example, 84) to every application which will be runining on your NewMonk setup.  
    So, for instance, if you are a big website with multiple sub-applications (or multiple codebases for every component) such as search, catalog etc. then, each of these applications could have a different appId associated with it.  
For small codebases, you should be okay with just one appId.

##### Setting up the directory structure:

We are assuming that we will be installing NewMonk in “/applications” directory, which should NOT be publically accessible by your web server (such as, nginx).
Also, we will be assuming that your web server’s publically accessible directory (aka webroot) is: 
```
/opt/nginx/html
```


```
$ git clone https://github.com/naukri-engineering/NewMonk.git /applications/newmonk
$ cd /applications/newmonk
$ ./composer.phar install
$ mkdir /opt/nginx/html/newmonk
$ ln -s /apps/newmonk/server/web /opt/nginx/html/newmonk/server
$ ln -s /apps/newmonk/dashboard/web /opt/nginx/html/newmonk/dashboard
```

**Configuring the server (logging apis):**
```
$ cd /applications/newmonk/server/config
$ cp config.sample.php config.php

Edit config.php and specify different parameters of your application, such as the installation directory, URLs etc.

$ cp nc_databases.sample.yml nc_databases.yml
Edit nc_databases.yml and setup your database configuration such as host, port, username, password etc.

$ cp elasticsearch.sample.yml elasticsearch.yml
Edit elasticsearch.yml and setup elasticsearch configuration parameters such as host, port etc.

$ cp nc_caches.sample.yml nc_caches.yml
Edit nc_caches.yml and setup redis configuration parameters such as host, port etc.

$ cp logging.sample.yml logging.yml
Edit logging.yml and specify the application ids (appId’s) for which you want to enable real user monitoring.

$ cp error.sample.yml error.yml
Edit error.yml and specify any middleware code you would want to be executed before javascript or server errors or mobile app crashes are logged into the system.
You can also configure Slack alerts if the crash log threshold gets breached.
```


**Configuring the dashboard (visualizer):**
```
$ cd /apps/newmonk/dashboard/config

$ cp config.sample.php config.php
Edit config.php and specify different parameters of your application, such as the installation directory, URLs etc.

$ cp sla.sample.yml sla.yml
Edit sla.yml and specify SLA parameters (acceptable values) for page performance metrics such as TTFB, page load time etc.
```

**MySQL Data Setup:**
```
$ cd /applications/newmonk/server/data/sql
```

##### Please import the following sql files into your mysql instance:
* newmonk_common.sql
* nLogger.sql
* nLogger_heatmap.sql

Insert data into nLogger.domains and nLogger.apps. For example:  
```
INSERT INTO nLogger.domains VALUES(1, ‘Naukri’, ‘y’, NOW());  
INSERT INTO nLogger.apps VALUES(1, 1, ‘Job Search’, ‘y’, NOW()), (2, 1, ‘HomePage’, ‘y’, NOW());
```

Now, for every app you INSERTed, in the file nLogger_boomerang.sql, replace <appId> with the actual appId (such as 2), and import the resulting file into your mysql.

**Create a user who will have access to log in to the dashboard, and view the graphical visualizations and reports:**  
```
$ php /applications/newmonk/server/batch/newMonkCreateUser.php myusername mypassword
(replace mypassword with the actual password you wish to set)
You will get an encrypted password as output like: abc786sjdhgsd1984bhhsd
```

**Now, insert data into nLogger.users:**  
```
INSERT INTO nLogger.users(null, ‘myusername’, ‘abc786sjdhgsd1984bhhsd’, ‘y’);
```

##### Configuring Nginx for Kibana:  
In your nginx config, add the following (change ip/port to whatever your kibana installation is using):
```
upstream elasticsearch-upstream {
   server 127.0.0.1:5601;
}
```
And add this inside the server block of your nginx config:
```
location  ~ /newmonk/dashboard/errors/report/(.*)$ {
   proxy_pass http://elasticsearch-upstream/$1;
}
rewrite ^/newmonk/dashboard/errors/*$ /newmonk/dashboard/errors.php last;
```

##### Configuring Elasticsearch:
In elasticsearch.yml, set:  
```
cluster.name: newmonk  
node.name: “newmonk_el"
```

##### Setting up Kibana Index:
Extract the file /applications/newmonk/server/data/elasticsearch/kibana-index.tar.bz2 at the location where your elasticsearch indexes are located.  
This location should look similar to:  
```
<your-elasticsearch-location>/data/newmonk/nodes/0/indices/
```
You will need to restart your elasticsearch instance after this.

##### Creating Elasticsearch Mappings:
Copy-paste the contents of the file  
```
/applications/newmonk/server/data/elasticsearch/newmonk_error-elasticsearch-mapping
```
and execute them on your shell (you might want to change ip/port to what your elasticsearch instance is using).

##### Setting up Crontab Jobs:  
Edit your crontab (crontab -e), and create cron jobs from the file:  
```
/applications/newmonk/server/data/crontab/cron
```

#### Contributing  
NewMonk is open source. Help us by submitting merge requests, forking and playing around with the codebase :-)

#### Contact Us
Get in touch with us with your suggestions, thoughts and queries at:
engineering@naukri.com

#### License
Please see [LICENSE.md](LICENSE.md) for details.
