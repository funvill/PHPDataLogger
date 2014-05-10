Data logger
============
This script creates a API interface to a name value database with history. This script is 

This project is used to learn the proper way of building a JSON REST API. 

**Project Design goals** 

 - **Single file** - A single file should make installation of this script as easy as possible. The user should only have to copy the file to a web accessible directly and browse to it. 
 - **No configuration** - This script should work without having to configure any parameters. This is one of the reasons that SQLite was chosen as the default database. 
 - **Well documented** - There is nothing worse then a badly documented API and source code. 
 - **1000 Writes a min** - This script on a moderate server should be able to handle 1000 writes a min. 
 - **Data visualizations** - The data should look good. this script uses D3js to generate charts data 
 
**This script vs [Xively][1] (previously pachtube, COSM)** 
This script was not built to replace Xively. Xively is a well made service that many engineers have spent 1000s of man hours building. In my personal option there are a few reasons that you might use this script over Xively. 

 - **You own your own data** - The database is stored on your server. 
 - **Limit on writes is based on your system specs** - Xively rate limits the amount of writes that you can do with the free version. 
 - **Pretty graphs** - This script uses D3js to chart the values in a visually appealing way. 

**This script vs [ThingSpeak][2]**
This script was not built to replace ThingSpeak. ThingSpeak does a fantastic job and is [open source][3] nor non commercial use. I can only see one reason that you would use this script over ThingSpeak and thats if your server does not support RAILS and you want to host your own data. 

Look in to ThingSpeak, They are great! 

**Resources** 

During the construction of this script I referenced several tutorials. Some of the major tutorials are listed below. 
 
- [Apigee's - api design ebook 2012][4]  
- [Google's - Web fundamentals tutorial][5] 
- [stackoverflow's - how to create rest urls without verbs][6] 

**Author:** Steven Smethurst ( [Github][7], [Twitter][8], [Blog][9] )
**Last updated:** May 03, 2014 





----------

Installation 
-------------
Drag and drop! 


Common URL Parameter
-------------

| Parameters   | Description                                    | 
| -------------|:---------------------------------------------- |
| **method**   | Currently the only two methods that are supported are 'get', 'post'. Get returns variable, while post creates variable **Default:** get                               |
| **version**  | The version of the API that this request is formated for. Optional.   |
| **format**   | The format to return the response in. Currently supported formats are HTML, JSON, TEXT. **Default:** HTML  |


GET method
----------
Gets values from the database. 

| Parameters              | Description                                    | 
| ----------------------- |:---------------------------------------------- |
| **name** (*optional*)   | The name of the parameter that this action will be acted on. If the name is not set then all the records up to the limit will be returned.  |
| **query** (*optional*)  | The search term.                               |
| **limit** (*optional*)  | How many rows to return from the database. **Default:** 30 |
| **offset** (*optional*) | The offset in to the database of how many records to skip. **Default:** 0 |



### List the variables in the database 

    GET /
    GET /?method=get

**Response** 

    {"data": [
         {"name":"example1"},
         {"name":"temperature"},
         {"name":"humidity"}
      ],
      "createdAt":"Sat, 03 May 2014 21:32:55 +0000",
      "generatedTime":"0.69714 ms"
    }

### Get the value of a particular variable 

    GET /?name={name of variable} 

**Response** 
   
    {"data":[
            {"id":31,"created":"2014-05-03 20:02:04","name":"one.two","value":"26349"},
            {"id":30,"created":"2014-05-03 20:01:46","name":"one.two","value":"130"},
            {"id":28,"created":"2014-05-03 19:39:58","name":"one.two","value":"24603"}
        ],
        "createdAt":"Sat, 03 May 2014 21:45:29 +0000",
        "generatedTime":"1.51896 ms"
    }

### Search the system for variables that math a string

    GET /?query={search term} 

**Response** 
   
    {"data":[
            {"name":"one.two"},
            {"name":"one.three"}
        ],
        "createdAt":"Sat, 03 May 2014 21:44:02 +0000",
        "generatedTime":"0.68378 ms"
    }


POST method
-----------

Create a new value in the database. 

    POST /?name={name of variable}&value={value}
    GET /?method=post&name={name of variable}&value={value} 

**URL Parameter**

| Parameters              | Description                                    | 
| ----------------------- |:---------------------------------------------- |
| **name** (*required*)   | The name of the parameter that this action will be acted on.  |
| **value** (*required*)  | The value to be inserted into the database.    |


API Version 
-------------
**Version 1** - Released May 2, 2014 

HTTP Status codes
-------------
 
| Http status code            | Description           | 
| --------------------------- |:--------------------- | 
| 200 OK                      | Success, No problems  | 
| 201 Created                 | Success, The value was inserted in to the database. | 
| 204 No Content              | Success, But no results to return.  | 
| 400 Bad Request             | There was a problem decoding the request. Modify the request before trying again. | 
| 405 Method Not Allowed      | Method used is not supported, Currently the only methods that are supported are *GET* and *POST* | 
| 415 Unsupported Media Type  | The requested media type is not supported, Currently the only media types that are accepted are JSON, TEXT, HTML | 
| 500 Internal Server Error   | Could not process the request, Server error. | 

 
 
Error response 
---------------

    {
        "createdAt":"Sun, 04 May 2014 00:43:21 +0000",
        "data":[],
        "status_code":400,
        "status_message":"Bad Request",
        "status":"error",
        "error_details":"No results"
    }

Settings 
---------------

| Setting | Default value | Description |
| --- |--- | --- |
| **database** | database.sqlite | The SQLite database file name | 
| **callhome** | 192.241.237.216:3333 | The IP address and port of the call home server. See the Call home section below for more information | 


Call home
---------------
By default this script will send every variable that is written to the database back to my personal server. The call home message is a UDP message that is sent without conformation from my personal server. It should not slow down POST requests very much.  

This feature is enabled by default for two reasons. 

 1. Test my personal data logger against a large amount of traffic from many different sources. 
 2. I'm obsessed with data and I am curious what people are using this script to store. 

This feature can be turned off by setting the callhome setting to false; 



 


  [1]: https://xively.com/
  [2]: https://thingspeak.com/
  [3]: https://github.com/iobridge/thingspeak
  [4]: http://pages.apigee.com/rs/apigee/images/api-design-ebook-2012-03.pdf
  [5]: https://developers.google.com/web/fundamentals/
  [6]: http://stackoverflow.com/questions/1619152/how-to-create-rest-urls-without-verbs/1619677#1619677
  [7]: https://github.com/funvill
  [8]: https://twitter.com/funvill
  [9]: http://www.abluestar.com
