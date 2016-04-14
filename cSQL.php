<?php 

$x = 4;

echo "Begin";

/**
 * @author tomcat
 *
 */

interface iSQL
{
	public function __construct();
	
	// reimplementation of old functions
	public function mysql_connect($dbServerName,$dbUser,$dbPassword,$dbName);
	public function mysql_query();
	public function mysql_fetch_array();
	public function mysql_error();
	public function mysql_num_rows();
	public function mysql_fetch_assoc();
	public function mysql_fetch_row();
	public function mysql_free_result();
	public function mysql_select_db();
// 	public function mysql_affected_rows();

	// NEW FUNCTIONS
	// fetch array from a query
	public function fetchArray();
}

class cSQL extends mysqli implements iSQL
{
	private $dbServerName = '';// 'wendelstein'
	private $dbUser = ''; // 'root'
	private $dbPassword = ''; // mindfold'
	private $dbName = ''; // 'hmat_dev'
	private $isConnected = false;
	private $result;
// 	private $dbLink;
//  	private $mysqliInstance;
		
	public function __construct( $dbServerName , $dbUser , $dbPassword , $dbName ) {
		parent::__construct( $dbServerName , $dbUser , $dbPassword , $dbName );

		if ( $this->connect_error() ) {	$this->isConnected = false; die('Connect Error (' . $this->connect_errno() . ') '. $this->connect_error());	}

		$this->isConnected = true;
		$this->dbServerName = $dbServerName;
		$this->dbUser = $dbUser;
		$this->dbPassword = $dbPassword;
		$this->dbName = $dbName;
	}

// 	mysql_connect — Open a connection to a MySQL Server
//	__contruct DOES THE CONNECTION THUS THIS FUNCTION IS OBSOLETE ... HOWEVER I KEEP IT FOR COMPATIBILITY ...
	public function mysql_connect() {
		return $this->isConnected;
	}

//  mysql_query — Send a MySQL query
	public function mysql_query( $queryString ) { 
		if( ( $this->result = $this->query( $queryString ) ) === FALSE ) { die( 'Query Error (' . $this->connect_errno() . ') '. $this->connect_error()); }
		return $this->result;
	}
	
//  mysql_fetch_array — Fetch a result row as an associative array, a numeric array, or both
	public function mysql_fetch_array( $result , $mode ) { return $result->fetch_array( $mode ); }

//  mysql_error — Returns the text of the error message from previous MySQL operation
	public function mysql_error() { return $this->error; }

//  mysql_num_rows — Get number of rows in result
	public function mysql_num_rows( $result ) { return $result->num_rows; }

//  mysql_fetch_assoc — Fetch a result row as an associative array
	public function mysql_fetch_assoc( $result ) { return $result->fetch_assoc(); }

//  mysql_fetch_row — Get a result row as an enumerated array
	public function mysql_fetch_row( $result ) { return $result->fetch_row(); }
	
//  mysql_free_result — Free result memory
	public function mysql_free_result( $result ) { $result->free(); }

	// NOT DONE
//  mysql_select_db — Select a MySQL database
	public function mysql_select_db( $dbName ) { }
	
//  mysql_affected_rows — Get number of affected rows in previous MySQL operation
// 	public function mysql_affected_rows() {	}
		
//     mysql_client_encoding — Returns the name of the character set
//     mysql_close — Close MySQL connection
//     mysql_connect — Open a connection to a MySQL Server
//     mysql_create_db — Create a MySQL database
//     mysql_data_seek — Move internal result pointer
//     mysql_db_name — Retrieves database name from the call to mysql_list_dbs
//     mysql_db_query — Selects a database and executes a query on it
//     mysql_drop_db — Drop (delete) a MySQL database
//     mysql_errno — Returns the numerical value of the error message from previous MySQL operation
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

$a = 4;

echo "End!";



?>