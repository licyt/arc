<?php 

define("GLOBAL_FREE_OFF", 0 );
define("GLOBAL_FREE_ON", 1 );
define("LOCAL_FREE_OFF",0);
define("LOCAL_FREE_ON", 1);

interface iSQL
{
	public function __construct( $dbServerName = "" , $dbUser = "" , $dbPassword = "" , $dbName = "" , $freeMode = "" );
	
	// reimplementation of old functions
	public function mysql_connect($dbServerName,$dbUser,$dbPassword,$dbName);
	public function mysql_query($queryString,$unnecessaryLinkIdentifier);
	public function mysql_fetch_array($freeMode,$mode);
	public function mysql_error($resource);
	public function mysql_num_rows();
	public function mysql_fetch_assoc($freeMode);
	public function mysql_fetch_row($freeMode);
	public function mysql_free_result($result);
	public function mysql_select_db($dbName);
		
	// NEW FUNCTIONS
	// fetch array from a query
	public function mysql_fetch_all( $mode , $freeMode);
	public function fetchWholeArray( $mode , $freeMode);
	public function queryAndFetchWholeArray( $query, $mode ,  $freeMode );
// 	protected function testConnection();
}

class cSQL extends mysqli implements iSQL
{
	private $dbServerName = '';// 'wendelstein'
	private $dbUser = ''; // 'root'
	private $dbPassword = ''; // mindfold'
	private $dbName = ''; // 'hmat_dev'
	private $isConnected = false;
	private $result;
	private $globalFree = GLOBAL_FREE_OFF;
	private $localFree = LOCAL_FREE_OFF;
// 	private $dbLink;
//  	private $mysqliInstance;

	public function __construct( $dbServerName = "" , $dbUser = "" , $dbPassword = "" , $dbName = "" , $freeMode = null ) {
		parent::__construct( $dbServerName , $dbUser , $dbPassword , $dbName );
	
		if ( $this->connect_errno ) {
			$this->isConnected = false;
			die('Connect Error (' . $this->connect_errno() . ') '. $this->connect_error());
		}
	
		$this->isConnected = true;
		$this->dbServerName = $dbServerName;
		$this->dbUser = $dbUser;
		$this->dbPassword = $dbPassword;
		$this->dbName = $dbName;
		$this->globalFree = $freeMode;
	}	

// 	mysql_connect — Open a connection to a MySQL Server
//	__contruct ESTABLISHES CONNECTION HENCE FUNCTION mysql_connect IS OBSOLETE ... HOWEVER I KEEP IT FOR COMPATIBILITY ...
	public function mysql_connect( $dbServerName = null , $dbUser = null , $dbPassword = null , $dbName = null ) {
		return $this->isConnected;
	}

//  mysql_query — Send a MySQL query
	public function mysql_query( $queryString , $unnecessaryLinkIdentifier = null ) { 
		if( ( $this->result = $this->query( $queryString ) ) === FALSE ) { 
			die( 'QUERY ERROR: ' . $queryString . ' (' . $this->errno . ') '. $this->error ); 
		}
		return $this->result;
	}
	
//  mysql_fetch_array — Fetch from result a row as an associative array, a numeric array, or both.
	public function mysql_fetch_array( $freeMode = null , $mode = null  ) { 
		if ( $mode == null ) {
			$mode = MYSQLI_BOTH;
		}

		$retval = $this->result->fetch_array( $mode );
		$this->localFree = $freeMode;
		$this->autoFree();
		return retval;
	}

//  mysql_error — Returns the text of the error message from previous MySQL operation
	public function mysql_error( $resource = null ) { return $this->error; }

//  mysql_num_rows — Get number of rows in result
	public function mysql_num_rows( $result = null ) { 
		if ( $result == null ) { 
			return $this->result->num_rows;
		} else {
			return $result->num_rows; 
		}
	}

//  mysql_fetch_assoc — Fetch a result row as an associative array
	public function mysql_fetch_assoc( $freeMode = null ) {
		$retval = $this->result->fetch_assoc();
		$this->localFree = $freeMode;
		$this->autoFree();
		return $retval;
	}

//  mysql_fetch_row — Get a result row as an enumerated array
	public function mysql_fetch_row( $result = null, $freeMode = null ) { 
		if ( $result == null ) {
			$retval =  $this->result->fetch_row();
			$this->localFree = $freeMode;
			$this->autoFree();
			return $retval;
		} else {
			$retval = $result->fetch_row();
			$this->localFree = $freeMode;
			$this->autoFree();
			return $retval;
		}
	}
	
//  mysql_free_result — Free result memory
	public function mysql_free_result( $result = null ) { 
		if ( $result == null ) {
			return $this->result->free();
		} else {
			$result->free();
		}
	}
	
//  mysql_select_db — Select a MySQL database
	public function mysql_select_db( $dbName ) { $this->select_db( $dbName ); }
	
	public function fetchWholeArray( $result = null, $mode = null , $freeMode = null ) {
		if( $mode == null ) {
			$mode = MYSQLI_BOTH;
		}
		
		$index = 0;
		while( $row = $this->result->fetch_array( $mode ) ) {
			$wholeArray[$index] = $row;
			$index++;
		}
		$this->localFree = $freeMode;
		$this->autoFree();
		
		return $wholeArray;
	}
	
	public function queryAndFetchWholeArray( $queryString, $mode , $freeMode = null ) {
		$this->mysql_query( $queryString );
		$retval = $this->fetchWholeArray( $mode , $freeMode );
		return $retval;
	}
	
	public function mysql_fetch_all( $mode , $freeMode = null ) {
		$retval = $this->result->fetch_all( $mode );
		$this->localFree = $freeMode;
		$this->autoFree();
		return $retval;
	}
	
	private function autoFree() {
		if( $this->globalFree == GLOBAL_FREE_ON || $this->localFree == LOCAL_FREE_ON ) {
			$this->result->free();
		}
	}
	
	// NOT DONE	
//  mysql_affected_rows — Get number of affected rows in previous MySQL operation
// 	public function mysql_affected_rows() {	}
		
//     mysql_client_encoding — Returns the name of the character set
//     mysql_close — Close MySQL connection
//     mysql_create_db — Create a MySQL database
//     mysql_data_seek — Move internal result pointer
//     mysql_db_name — Retrieves database name from the call to mysql_list_dbs
//     mysql_db_query — Selects a database and executes a query on it
//     mysql_drop_db — Drop (delete) a MySQL database
//     mysql_escape_string — Escapes a string for use in a mysql_query
//     mysql_fetch_field — Get column information from a result and return as an object
//     mysql_fetch_lengths — Get the length of each output in a result
//     mysql_fetch_object — Fetch a result row as an object
//     mysql_field_flags — Get the flags associated with the specified field in a result
//     mysql_field_len — Returns the length of the specified field
//     mysql_field_name — Get the name of the specified field in a result
//     mysql_field_seek — Set result pointer to a specified field offset
//     mysql_field_table — Get name of the table the specified field is in
//     mysql_field_type — Get the type of the specified field in a result
//     mysql_get_client_info — Get MySQL client info
//     mysql_get_host_info — Get MySQL host info
//     mysql_get_proto_info — Get MySQL protocol info
//     mysql_get_server_info — Get MySQL server info
//     mysql_info — Get information about the most recent query
//     mysql_insert_id — Get the ID generated in the last query
//     mysql_list_dbs — List databases available on a MySQL server
//     mysql_list_fields — List MySQL table fields
//     mysql_list_processes — List MySQL processes
//     mysql_list_tables — List tables in a MySQL database
//     mysql_num_fields — Get number of fields in result
//     mysql_pconnect — Open a persistent connection to a MySQL server
//     mysql_ping — Ping a server connection or reconnect if there is no connection
//     mysql_real_escape_string — Escapes special characters in a string for use in an SQL statement
//     mysql_result — Get result data
//     mysql_set_charset — Sets the client character set
//     mysql_stat — Get current system status
//     mysql_tablename — Get table name of field
//     mysql_thread_id — Return the current thread ID
//     mysql_unbuffered_query — Send an SQL query to MySQL without fetching and buffering the result rows.
}

echo 'Begin'."\r\n";
include_once 'dbConfig.php';

///////////////// CONNECTING
// OLD WAYS
// if ($dbLink = mysql_connect($dbServerName, $dbUser, $dbPassword) ) { } 
// else { echo 'Not connected : ' . mysql_error(); }
// NEW WAYS
// create instance of cSQL and immediatelly connect.
$instanceOfcSQL = new cSQL($dbServerName,$dbUser,$dbPassword,$dbName);

///////////////// SQL QUERY
// our SQL statement
$stringSQLStatement = "SHOW TABLES";
// OLD WAYS
// if ($dbResult = mysql_query($stringSQLStatement) ) { /* do some stuff with dbResult */ }
// NEW WAYS 
$instanceOfcSQL->mysql_query($stringSQLStatement);
// result is stored in $test->result
$numRows = $instanceOfcSQL->mysql_num_rows( );
// var_dump($numRows);

///////////////// Fetching array
// OLD WAYS
// $row = mysql_fetch_array($result)
// NEW WAYS
// fetch one row
// $row = $instanceOfcSQL->mysql_fetch_array( LOCAL_FREE_ON );
$row = $instanceOfcSQL->mysql_fetch_array();
// var_dump($row);

// fetch all rows 
$allRows = $instanceOfcSQL->fetchWholeArray( MYSQL_ASSOC );
// var_dump($allRows);

// fetch all rows second option
$instanceOfcSQL->mysql_query($stringSQLStatement);
$allRowsTwo = $instanceOfcSQL->mysql_fetch_all( MYSQL_ASSOC );
// var_dump($allRowsTwo);

// query DB and fetch all rows. MYSQL_FREE_RESULT is called by the method.
$queryAllRows = $instanceOfcSQL->queryAndFetchWholeArray( $stringSQLStatement , MYSQL_ASSOC );
// var_dump($queryAllRows);

// Fetch associative array
$instanceOfcSQL->mysql_query($stringSQLStatement);
$assocFetch = $instanceOfcSQL->mysql_fetch_assoc();
$instanceOfcSQL->mysql_free_result();
// var_dump($assocFetch);

echo "End!";
?>