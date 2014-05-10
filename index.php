<?php 

/***

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
 
 | Http status code | Description | 
 | --- | --- | 
 | 200 OK | Success, No problems | 
 | 201 Created | Success, The value was inserted in to the database. | 
 | 204 No Content | Success, But no results to return.  | 
 | 400 Bad Request | There was a problem decoding the request. Modify the request before trying again. | 
 | 405 Method Not Allowed | Method used is not supported, Currently the only methods that are supported are *GET* and *POST* | 
 | 415 Unsupported Media Type | The requested media type is not supported, Currently the only media types that are accepted are JSON, TEXT, HTML | 
 | 500 Internal Server Error | Could not process the request, Server error. | 

 
 
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


*/


/***
 * Start a timer at the start of the file. This timer will be used to detect how
 * long it took to process this request. 
 */ 
$scriptTimeStart = microtime(true);

 // Settings: 
define( 'SETTING_DATABASE', 'database.sqlite' );
define( 'SETTING_CALLHOME', '192.241.237.216:3333' );


// $page['settings']['database'] 			= 'database.sqlite' ; 
// $page['settings']['callhome']			= '192.241.237.216:3333' ; // Set to false to disable. 
// $page['settings']['enabled_methods'] 	= array('post', 'get' ) ;


 // Constants 
define( 'API_VERSION', 		'1'  );
define( 'DEFAULT_LIMIT', 	'30' );
define( 'DEFAULT_OFFSET', 	'0'  );



class CDataLogger
{
	// Public
	// ------------------------------------------------------------------------
	public $page ; 
	
	function __construct() {
		$this->page['settings']['database'] = SETTING_DATABASE ; 
		$this->ConnctToDatabase() ; 
	}

	public function ProccessRequest() {

		// Build the Request. 
		$this->BuildRequest();

		// Build the Response. 
		$this->BuildResponse();

		// Render
		$this->Render(); 
	}




	// Private 
	// ------------------------------------------------------------------------

	// Connects to the database and sets up the dbhandle for future requests. 
	private function ConnctToDatabase( ) {
		// Attempt to connect to the database 
		$this->page['dbhandle'] = new SQLite3( $this->page['settings']['database'] ); 

		// Check to see if the database tables exist. if they do not create them. 
		if( ! $this->page['dbhandle']->exec('CREATE TABLE IF NOT EXISTS data (id INTEGER PRIMARY KEY AUTOINCREMENT, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP, name char(255), value char(255) )') ) {
			throw new Exception( "Could not create database", 500 ); 
		}
	}

	// Query the database. 
	private function Query( $sql_query ) {
		// Get the value 
		$data = array(); 
		$results = $this->page['dbhandle']->query( $sql_query ) ;
		if( $results !== false ) {
			while ($row = $results->fetchArray( SQLITE3_ASSOC ) ) {
				$data[ ] = $row ; 
			} 
		} else {
			// There was an error. 
			$errorCode    = $this->page['dbhandle']->lastErrorCode() ; 
			$errorMessage = $this->page['dbhandle']->lastErrorMsg () ;
			throw new Exception( "SQL query failed, error_code=". $errorCode .', error_message='. $errorMessage. ', sql_query='. $sql_query, 500 ); 
		}

		// Everything looks good. 
		return $data ; 
	}

	private function PostData( $name, $value, $created = false ) {
	 	// Check to see if the created prameter has been filled in. 
	 	if( false === $created) {
	 		$created = date('Y-m-d H:i:s') ; 
	 	}

	 	// Update the database. 
	 	$sql_query = 'INSERT INTO data (created, name, value ) VALUES ( "'. SQLite3::escapeString( $created )  .'", "'. SQLite3::escapeString( $name ) .'", "'. SQLite3::escapeString( $value ) .'" )' ; 
	 	$result = $this->page['dbhandle']->query( $sql_query );
	 	if( ! $result ) {
	 		throw new Exception( 'Could not insert the data', 500 ); 
	 		return false; 
	 	}

	 	// Send the data home if needed. 
	 	$this->callhome( $name, $value, $created ) ; 

	 	// return the ID of the inserted query. 
	 	return $this->page['dbhandle']->lastInsertRowID () ; 
	}

	private function GetData( $name = false, $search = false ) {

		if( true === $search && $name !== false ) {
			// Construct query 
			$sql_query = 'SELECT DISTINCT name FROM data ';
			if( strlen($name) > 0 ) {
				$sql_query .= ' WHERE name LIKE "%'. SQLite3::escapeString( $name ) .'%" ';
			}
		} else if( $name != false ) {
			// Construct query 
			$sql_query = 'SELECT * FROM data ';
			if( strlen($name) > 0 ) {
				$sql_query .= ' WHERE name="'. SQLite3::escapeString( $name ) .'" ';
			}
		} else {
			$sql_query = 'SELECT DISTINCT name FROM data ' ; 
		}

		$sql_query .= ' ORDER BY id DESC '; 
		$sql_query .= ' LIMIT '. SQLite3::escapeString( $this->page['request']['offset'] ).', '. SQLite3::escapeString( $this->page['request']['limit'] ).';' ; 
		return $this->Query( $sql_query ) ; 
	 }

	/***
	 * Creates a UDP packet and sends to the home server. 
	 * To disable this feature set ['settings']['callhome'] to false. 
	 * Because this function uses UDP packet so its very quick ans should not 
	 * slow down the logging of data to the database. 
	 */
	private function CallHome( $name, $value, $created ) {
		if( false === SETTING_CALLHOME ) {
			return ; // Nothing to do here. 
		}

		$socket = stream_socket_client('udp://'. SETTING_CALLHOME );
		if ( false !== $socket) {
			// Send the packet to the server. 
			fwrite($socket, json_encode ( array('name'=>$name, 'value'=>$value, 'created'=>$created ) ) );
			fclose($socket);
		}
	}


	/***
	 * Gets the requsted method. 
	 * Check the url first to explicitly declared. if not declared
	 * the method comes from the http header
	 */
	private function GetRequestMethod( $enabledMethods ) {

		// Check to see if the method was defined. 
		if( isset($_REQUEST['method'] ) ) {
			$method = $_REQUEST['method'] ; 
		} else {
			$method = $_SERVER['REQUEST_METHOD']; 
		}

		// Drop the method case
		$method = strtolower( $method ); 

		// Valadate that the method is allowed. 
		if( ! in_array($method, $enabledMethods ) ) {
			// Error, This method is either unknow or has been disabled. 
			throw new Exception( 'unknown method, method=['. $method .']', 405 ); 
			return false; 
		}

		// Everything looks okay. 
		return $method ; 
	}


	/*** 
	 * Find the response format. 
	 * First we check the url to see if the format is explicitly declared. 
	 * If the format is not set in the url then we check the http headers 
	 * to see if its defined. If the format is not decalred in the header 
	 * then we default to html. 
	 *
	 * Accepted formats
	 * - json, application/json
	 * - html, text/html
	 * - text, text/plain
	 */
	private function GetRequestFormat() {
		// Response format. 
		$request_format = 'html';
		if( isset( $_REQUEST['format'] ) ) {
			$request_format = $_REQUEST['format'] ; 
		} else {
			// Get the request type from the header. 
			if( false !== strpos($_SERVER['HTTP_ACCEPT'], 'text/html') ) {
				$request_format = 'html' ; 
			} else if( false !== strpos($_SERVER['HTTP_ACCEPT'], 'text/plain') ) {
				$request_format = 'plain' ; 
			} else if( false !== strpos($_SERVER['HTTP_ACCEPT'], 'application/json') ) {
				$request_format = 'json' ; 
			} else {
				throw new Exception( 'Unknown request type, type=['. $_SERVER['HTTP_ACCEPT'] .']', 400 ) ; 
			}		
		}

		// Drop the fromat case
		$request_format = strtolower( $request_format ); 
		if( in_array( $request_format, array( 'json','html','text') ) ) {
			// Supported format 
			return $request_format ; 
		} else {
			// Unsupported format. 
			throw new Exception( 'Unknown request type, type=['. $request_format .']', 400 ) ; 
		}
	}

	/***
	 * Check to see what version of the api is being requested. 
	 * Check the url first to xplicitly declared. if not check 
	 * the header or not set then default to the current version. 
	 */
	private function GetAPIVersion() 
	{
		// If the version is not defined, assume that its the current version. 
		$version = API_VERSION ; 
		if( isset($_REQUEST['version'] ) ) {
			$version = $_REQUEST['version'] ; 
		} 

		// Valiadate the version. 
		if($version != API_VERSION ) {
			// Unsupported version 
			throw new Exception( 'Invalid version, Current version=['. API_VERSION .'], Request version=['. $version .']', 400 ) ; 
			return false; 
		}

		// Everything looks good. 
		return $version ; 
	}

	private function BuildRequest() 
	{
		// Build the request. 
		// ------------------------
		
		// Request format. 
		$this->page['request']['format'] = $this->GetRequestFormat(); 

		// Method 
		$this->page['request']['method'] = $this->GetRequestMethod( array('post', 'get' ) ) ;

		// API Version 
		$this->page['request']['version'] = $this->GetAPIVersion(); 

		// Limit and Offset
		$this->page['request']['limit']  = DEFAULT_LIMIT ; 
		$this->page['request']['offset'] = DEFAULT_OFFSET ; 
		if( isset( $_REQUEST['limit'] ) ) {
			$this->page['request']['limit'] = $_REQUEST['limit'] ; 
		}
		if( isset( $_REQUEST['offset'] ) ) {
			$this->page['request']['offset'] = $_REQUEST['offset'] ; 
		}

		// Get the common url prameters. 
		if( isset( $_REQUEST['name']) ) {
			$this->page['request']['name'] = $_REQUEST['name'] ;
		}
		if( isset( $_REQUEST['value']) ) {
			$this->page['request']['value'] = $_REQUEST['value'] ;
		}	 
		if( isset( $_REQUEST['query']) ) {
			$this->page['request']['query'] = $_REQUEST['query'] ; 	
		}
	}


	private function BuildResponse()
	{
		// Build the response
		// ------------------------

		// Add the createdAt, when the response was built. 
		$this->page['response']['createdAt'] = date( 'r' ); 


		// Process the request. 	
		switch( $this->page['request']['method']  ) {
			default: 
			{
				throw new Exception( 'Unsupported method, method=['. $this->page['request']['method'] .']', 405 );
				return; 
				break; 
			}
			case 'get':
			{
				// There are three ways to respond to a GET request depending on the prameters provided. 
				// * Query - Seach the database for a name 
				// * List all - List all the properties 
				// * Get - Get the current values for a perdiculare property

				// Check to see if they are doing a search. 
				if( isset( $this->page['request']['query'] ) ) {
					$this->page['response']['data'] = $this->GetData( $this->page['request']['query'], true ); 
				}
				// Get - Get the current values for a perdiculare property
				else if( isset( $this->page['request']['name'] ) ) {
					$this->page['response']['data'] = $this->GetData( $this->page['request']['name'] ); 
				} 
				// List all - List all the properties 
				else {
					$this->page['response']['data'] = $this->GetData( ); 
				}

				// Check to see if there was anything to report 
				if( count( $this->page['response']['data'] ) > 0 ) {
					$this->page['response']['status'] 	 = 'ok';
					$this->page['response']['status_code'] = '200';
				} else {
					// There was no results to this request. 
					throw new Exception( 'No results to this request.', 204 );
					return ; 
				}
				break; 
			}

			case 'post':
			{
				// The post is used to CREATE data.
				// Required prameters: name, and value 

				if( ! isset( $this->page['request']['name'] ) ) {
					throw new Exception( 'Missing required prameter, name', 400 );
					return ; 
				}
				if( ! isset( $this->page['request']['value'] ) ) {
					throw new Exception( 'Missing required prameter, value', 400 );
					return ; 
				}

				$results = $this->PostData( $this->page['request']['name'], $this->page['request']['value'] );
				if( false === $results ) {
					throw new Exception( 'Could not write value to database', 500 );
					return ; 	
				}

				// Everything looks good here, Respond with okay. 		
				$this->page['response']['status'] 	 	= 'ok';
				$this->page['response']['status_code'] 	= 201;
				$this->page['response']['data'][] = array( 
							'id'      => $results , 						
							'created' => date('Y-m-d H:i:s'),  // '2014-05-03 19:40:02'
							'name'    => $this->page['request']['name'], 
							'value'   => $this->page['request']['value']						
						) ; 

				break; 
			}
		}
	}	


	private function Render()
	{
		global $scriptTimeStart;

		// Get the time that this script took to generate in ms. 
		$this->page['response']['generatedTime'] = round( ( microtime(true) - $scriptTimeStart ) * 1000 , 5 )  .' ms'; 

		// Format the response. 
		if( $this->page['request']['format'] == 'json' ) {
			header('Content-Type: application/json');	
			echo json_encode ( $this->page['response'] ); 
			exit(); 
		} else if( $this->page['request']['format'] == 'text' ) {
			header('Content-Type: text/plain');
			print_r ( $this->page['response'] ); 
			exit(); 
		}

		// Render as html 
		echo '<pre>';
		print_r ( $this->page ); 
		echo '</pre>';
		
	}

} ; 



$dataLogger = new CDataLogger ();

try {
	$dataLogger->ProccessRequest(); 
} catch (Exception $e) {

	$response['status'] 		= 'error';	
	$response['status_code']	= $e->getCode();	
	$response['error_details']	= $e->getMessage() ;

	$http_code = array(200 => "OK", 201 => "Created", 202 => "Accepted", 204 => "No Content", 400 => "Bad Request", 404 => "Not Found", 405 => "Method Not Allowed", 415 => "Unsupported Media Type", 424 => "Method Failure", 429 => "Too Many Requests", 500 => "Internal Server Error", 501 => "Not Implemented", 507 => "Insufficient Storage");

	if( array_key_exists ($response['status_code'], $http_code ) ) {
		$response['status_message']	= $http_code[ $response['status_code'] ] ; 
	} else {
		$response['status_message'] = 'Error' ; 
	}

	$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
	// header($protocol . ' ' . $http_code . ' ' . $response['status_message'] );
	header('Content-Type: application/json');	
	echo json_encode ( $response ); 

	// Close the connection 
	exit(); 
}