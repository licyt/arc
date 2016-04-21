<?php
// ------------------------------------------------------ C O N S T A N T S

include_once("./dbConfig.php");
require_once 'gui.php';
require_once("html.php");

// ------------------------------------------------------ I N T E R F A C E
// Declare the interface iDbField
interface iDbField
{
  public function __construct($column="");
  public function setProperties($properties); 
  public function setTableName($name);
  public function getName();
  public function isForeignKey();
  public function isDateTime();
  public function isStatusColor();
  public function foreignTableName();
  public function getHtmlControl();
}

// Declare the interface iDbTable
interface iDbTable
{
  public function __construct($tableName="");
  public function loadFields();
  public function getFieldByName($fieldName); 
  public function getFieldIndex($fieldName);
  public function getCurrentRecord();
  public function getNumRecords();
  public function go($index);
  public function setMode($mode);
  public function setName($name);
  public function insert($record);
  public function update($record);
  public function printFields();
  public function navigator();
  public function respondToNavigator();
  public function detailForm();
}  

// Declare the interface iDbSchema
interface iDbScheme 
{
  public function link ($dbServerName, $dbUser, $dbPassword);
  public function useDb ($dbName);
  public function allDetailForms();
}

// -------------------------------------------  I M P L E M E N T A T I O N
// Implement the interface iDbField
class cDbField implements iDbField
{
  protected $properties = array();
  protected $tableName;
  
  public function __construct($column="") 
  {
	 $this->setProperties($column);  
  }
  
  public function setProperties($properties) 
  {         
    // import properties from an array
    foreach ($properties as $name => $value) {
      $this->properties[$name]=$value;
    }
  }
  
  public function getName() {
    return $this->properties[Field];
  }
  
  public function isAutoInc() {
	return $this->properties[Extra]=="auto_increment";
  }
  
  public function isForeignKey() {
    // fields name concontains "_id" 
    return 
      (strpos($this->properties[Field],"_id")>0);
  }
  
  public function isDateTime() {
		return ($this->properties[Type] == "datetime");
  }
  
  public function isStatusColor() {
	return ( $this->properties[Field] == "StatusColor" );
  }
  
  public function foreignTableName() {
    $fieldName = $this->getName();
	return substr($fieldName, strpos($fieldName, "_id")+3);
  }
  
  public function setTableName($name) {
	$this->tableName=$name;
  }
  
  public function getHtmlControl($value="", $disabled=false)
  {
    if ($this->isForeignKey()) {
      // use select for foreign keys
      $htmlControl = new cHtmlSelect;
	  $htmlControl->setSelected($value);
	  $ftName = $this->foreignTableName(); 
	  $query = 
	    "SELECT id".$ftName.", ".
		  $ftName.gui("table".$ftName, "lookupField", "Name"). 
		  ($ftName=="Status" ? ", StatusColor" : "").
	    " FROM ".$ftName.
		// Status - additional filter for StatusType
		($ftName=="Status"
		  ? " WHERE StatusType=\"".$this->tableName."\""
		  : ""
		);
	  // push options
	  if ($result = mysql_query($query)) {
		while ($row = mysql_fetch_array($result))
		$htmlControl->addOption(
		  $row[$ftName.gui("table".$ftName, "lookupField", "Name")], 
		  $row["id".$ftName], 
		  ($ftName=="Status" ? $row[StatusColor] : "")
		);
	  }
    } else {
        //use input for other fields
		$htmlControl = new cHtmlInput;
	}

    // set input size based on dbField type
    $htmlControl->setAttribute("SIZE", filter_var($this->properties[Type], FILTER_SANITIZE_NUMBER_INT));
    
    // set attributes derived from Field name                                       
    $htmlControl->setAttribute("ID", $this->properties[Field]);
    $htmlControl->setAttribute("NAME", $this->properties[Field]);
    $htmlControl->setAttribute("DISABLED", ($disabled?" DISABLED":""));
    $htmlControl->setAttribute("VALUE", $value);
    
    // create  label
	$htmlLabel = new cHtmlLabel;
	$htmlLabel->setAttribute("ID", "Label".$this->properties[Field]);
	$htmlLabel->setAttribute("TARGET", $this->properties[Field]);
	$htmlLabel->setAttribute("VALUE", $this->properties[Field]);
    
    return 
      $htmlLabel->display().
      $htmlControl->display();
  }
}

// Implement the interface iDbTable
class cDbTable implements iDbTable
{
  protected $name;  
  public $fields = array();   
  // parameters for navigation
  protected $at;									// index of current record
  protected $count;									// total count of records
  protected $mode;									// object operational mode BROWSE/INSERT/UPDATE/DELETE
  protected $currentRecordId;
  protected $currentRecord;                        
  // browser parameters
  protected $columnNames = array();
  protected $ftNames = array();
  protected $start;									// browser starting position
  protected $rowCount;								// number of rows in browser
  // build SQL substrings
  protected $columns;
  protected $order;
  protected $filter;
  protected $parentLimit;							// this is a subtable for a parent with given id
  
  public function __construct($tableName="", $parentLimit="") 
  {
	$this->parentLimit = $parentLimit;
  	$this->setName($tableName);
  }

  public function getFieldIndex($fieldName) 
  {
	foreach ($this->fields as $i => $field) {
	  if ($field->getName()==$fieldName) return $i;
	}
  }
  
  public function getFieldByName($fieldName) 
  {
	foreach ($this->fields as $i => $field) {
	  if ($field->getName()==$fieldName) return $this->fields[$i];
	}
	return NULL;
  }

  public function getCurrentRecord() 
  {
	if ($this->currentRecordId) {
	  $query = "SELECT * FROM ".$this->name." WHERE id".$this->name."=".$this->currentRecordId;
	  if ($result = mysql_query($query)) {
	    $this->currentRecord = mysql_fetch_row($result);
	  }
	}	  
	return $this->currentRecord;
  }  
  
  public function getNumRecords() 
  {
    // number of records in this table
    if ($result = mysql_query($this->buildSQL())) {
      $this->count = mysql_num_rows($result);
    }
	return $this->count;
  }
  
  function setMode($mode) 
  {
    $this->mode = strtoupper($mode);
    $_SESSION[table][$this->name][mode] = $this->mode;
  }
  
  public function setName($name)
  {
    // initialize table from database
    $this->name = $name;
    $this->loadFields();
	$this->loadColumns();
    $this->loadSession();
	$this->respondToNavigator();
	$this->getNumRecords();
    if ($this->mode!="INSERT") $this->getCurrentRecord();
  }
  
  public function setOrder($order) 
  {
    $this->order = $order;
	$_SESSION[table][$this->name][order] = $order;
  }
  
  public function hasForeignTable($ftName) 
  {
  	foreach ($this->fields as $name=>$field) {
  	  if ($field->getName() == $this->name."_id".$ftName) return true;
  	}
  	return false;
  }
  
  public function go($index) 
  {
    $this->at = $index;
    $_SESSION[table][$this->name][at] = $index;
  }
  
  public function loadFields() 
  {
  	// clear list of fields
  	foreach ($this->fields as $i=>$field) unset($this->fields[$i]);
    // get list of fields for a table from database
    $query = "SHOW COLUMNS FROM ".$this->name;
    $result = mysql_query($query);
    if (!$result) {
      echo "Could not run query $query : ". mysql_error();
      exit;
    } 
    if (mysql_num_rows($result) > 0) {
      while ($column = mysql_fetch_assoc($result)) {
        // create/set cDbField object for each table column
        $field = new cDbField($column);
		$field->setTableName($this->name);
        // push this new field into array of fields of this object
        array_push($this->fields, $field);
      }
    }
  }
  
  public function loadColumns($useForeignFields=true) 
  {
  	// empty list of columns and foreign_tables
  	foreach ($this->columnNames as $i=>$columnName) unset($this->columnNames[$i]);
  	foreach ($this->ftNames as $i=>$ftName) unset($this->ftNames[$i]);
  	// load list of columns for browser from list of fields
	foreach ($this->fields as $i=>$field) {
	  array_push($this->columnNames, $field->getName());
	  if ($useForeignFields && $field->isForeignKey()) {
        // this field is a foreign key, display lookupField from referenced table
	  	$ftName = $field->foreignTableName();
	  	// default lookupField is Name
		array_push($this->columnNames, $ftName.gui("table".$ftName, "lookupField", "Name"));
		// if there is a status, fetch color too
		if ($ftName == "Status") {
		  array_push($this->columnNames, "StatusColor");
		}
		array_push($this->ftNames, $ftName);
	  }
	}
  }
  
  public function loadSession() 
  {
    // get table MODE from session 
    if ($_SESSION[table][$this->name][mode]) {
      $this->mode = $_SESSION[table][$this->name][mode];
    } else {
	  // or set mode to "BROWSE" by default
      $this->setMode("BROWSE");
    }

    // get table POSITION from session 
    if ($_SESSION[table][$this->name][at]) {
      $this->at = $_SESSION[table][$this->name][at];
    } else {
	  // or set position to 0 by default
      $this->go(0);
    }
    
    // get currentRecordId from subBrowser
    if (isset($_POST["sb".$this->name."RowId"]) && ($_POST["sb".$this->name."RowId"]>=0)) {
      $this->currentRecordId=$_POST["sb".$this->name."RowId"];
      $_SESSION[table][$this->name][currentRecordId] = $this->currentRecordId;
    } elseif ($_SESSION[table][$this->name][currentRecordId]) {
    	$this->currentRecordId = $_SESSION[table][$this->name][currentRecordId];
    }

  }

  private function assignSQL($record) 
  {
	foreach ($record as $fieldName=>$value) {
      // is there a field by this name
	  if ($field=$this->getFieldByName($fieldName)) {
        $assign.=($assign?", ":"").
	      "$fieldName = \"$value\"";
	  }
	}
	return $assign;
  }
  
  public function buildSQL() 
  {
    $this->columns = "";
  	$this->filter = "";
  	// load order and filter stored in session
  	$this->order = $_SESSION[table][$this->name][order];
  	// column names
  	foreach ($this->columnNames as $i=>$columnName) {
  		$this->columns .= ($this->columns?", ":"").$columnName;
  		// collation order
  		if (isset($_POST[$this->name."ORDER".$columnName])) {
  			$this->setOrder($columnName);
  		}
  		// filter
  		if (isset($_POST[$this->name."FILTER".$columnName])) {
  			$_SESSION[table][$this->name]["FILTER"][$columnName] =
  			$_POST[$this->name."FILTER".$columnName];
  		}
  		if ($_SESSION[table][$this->name]["FILTER"][$columnName]) {
  			$this->filter .= ($this->filter ? " AND " : "").
  			"($columnName LIKE \"".$_SESSION[table][$this->name]["FILTER"][$columnName]."%\")";
  		}
  	}
  	
  	// foreign table names as string to SQL
	$ftNames = "";
	$fkConstraints = "";
	foreach ($this->ftNames as $i=>$ftName) {
	  $ftNames .= ", ".$ftName;
	  $fkConstraints .= ($fkConstraints?" AND ":"").
	    "(".$this->name.".".$this->name."_id".$ftName."=".$ftName.".id".$ftName.")";
	}
  	
  	$query =
      "SELECT ".$this->columns.
  	  " FROM ".$this->name.$ftNames.
  	  ($fkConstraints || $this->filter
  		? " WHERE ".
  	  		  $fkConstraints.$this->parentLimit.
  	  		  ($fkConstraints && $this->filter ? " AND ": "").
  	  		  $this->filter 
  	  	: ""
  	  ).
  	  ($this->order //&& strpos($this->columns, $this->order) 
  	    ? " ORDER BY ".$this->order 
  	  	: ""
  	  );
    return $query;
  }
  
  public function insert($record) {
	$query = 
	  "INSERT INTO ".$this->name.
	  " SET ".$this->assignSQL($record);
	if ($result = mysql_query($query)) {
		
	}
  }
  
  public function update($record) {
	$query = 
	  "UPDATE ".$this->name.
	  " SET ".$this->assignSQL($record).
	  " WHERE id".$this->name."=".$record["id".$this->name];
	if ($result = mysql_query($query)) {
		
	}
  }

  public function navigator() 
  {
    // display first button
    if (($this->mode == "BROWSE")&&($this->count > 1)&&($this->at > 1)) {
      $button = new cHtmlInput($this->name."First", "SUBMIT", "|< First");
      $result .= $button->display();
    }
    // display prev button
    if (($this->mode == "BROWSE")&&($this->count > 2)&&($this->at > 2)) {
      $button = new cHtmlInput($this->name."Prev", "SUBMIT", "< Prev");
      $result .= $button->display();
    }
    // display insert button
    if ($this->mode == "BROWSE") {
      $button = new cHtmlInput($this->name."Insert", "SUBMIT", "+ Add");
      $result .= $button->display();
    }
    // display update button
    if (($this->mode == "BROWSE")&&($this->at)) {
      $button = new cHtmlInput($this->name."Update", "SUBMIT", "* Edit");
      $result .= $button->display();
    }
    // display delete button
    if (($this->mode == "BROWSE")&&($this->at)) {
      $button = new cHtmlInput($this->name."Delete", "SUBMIT", "x Delete");
      $result .= $button->display();
    }
    // display ok & cancel buttons
    if (($this->mode == "INSERT") ||
	    ($this->mode == "UPDATE") ||
		($this->mode == "DELETE")) {
      $button = new cHtmlInput($this->name."Ok", "SUBMIT", "Ok");
      $result .= $button->display();
      $button = new cHtmlInput($this->name."Cancel", "SUBMIT", "Cancel");
      $result .= $button->display();
    }
    // display next button
    if (($this->mode == "BROWSE")&&($this->at<($this->count-1))) {
      $button = new cHtmlInput($this->name."Next", "SUBMIT", "Next >");
      $result .= $button->display();
    }
    // display last button
    if (($this->mode == "BROWSE")&&($this->at < $this->count)) {
      $button = new cHtmlInput($this->name."Last", "SUBMIT", "Last >|");
      $result .= $button->display();
    }
    return $result;
  }

  public function respondToNavigator() 
  {
	// respond to navigator buttons
    if ($_POST[$this->name."First"]) $this->go(1);
    if ($_POST[$this->name."Prev"])  $this->go($_SESSION[table][$this->name][at]-1);
    if ($_POST[$this->name."Next"])  $this->go($_SESSION[table][$this->name][at]+1);
    if ($_POST[$this->name."Last"])  $this->go($this->count);

    if ($_POST[$this->name."Insert"]) $this->setMode("INSERT"); // + Add
    if ($_POST[$this->name."Update"]) $this->setMode("UPDATE"); // * Edit
    if ($_POST[$this->name."Delete"]) $this->setMode("DELETE"); // x Delete
    if ($_POST[$this->name."Cancel"]) $this->setMode("BROWSE"); // Cancel
    
    if ($_POST["go".$this->name]) $this->go($_POST["go".$this->name]);
    
    if ($_POST[$this->name."Ok"]) {                               // Ok 
	  switch ($this->mode) {
        // build SQL 
		case "DELETE" :
		  $query = "DELETE FROM ".$this->name.
		    " WHERE id".$this->name."=".$_POST["id".$this->name];
		  break;
		case "INSERT" :
		case "UPDATE" :
          // assign field values 
          foreach ($this->fields as $i => $field) {
            $fieldName = $field->getName();
            if ($fieldName != "id".$this->name) {
              $assign .= ($assign ? ", " : "").
                $fieldName." = \"".$_POST[$fieldName]."\"";
            } 
          }
		  // choose SQL keyword depending on mode
		  switch ($this->mode) {
			case "INSERT" :
              $query = "INSERT INTO ".$this->name.
			    " SET ".$assign;
			  break;
			case "UPDATE" :
			  $query = "UPDATE ".$this->name.
			    " SET ".$assign.
				" WHERE id".$this->name."=".$_POST["id".$this->name];
			  break;
		  }
		  break;
	  }

	  // execute SQL
	  if ($result = mysql_query($query)) {
		// adjust table position
		switch ($this->mode) { 
		  case "INSERT" :
			$this->count++;
			$this->go($this->count);
			break;
		  case "DELETE" :
			$this->count--;
			if ($this->at>1) $this->go($this->at-1);
			break;
		}
		$this->getCurrentRecord();
	    // log any status change 
		$fieldName = $this->name."_idStatus";
		if ($field = $this->getFieldByName($fieldName)) {
		  $fieldIndex = $this->getFieldIndex($fieldName);
		  $query=
		    "INSERT INTO StatusLog".
		    " SET ".
			  "StatusLogRecNo=".$this->at.", ".
			  "StatusLog_idStatus=".$this->currentRecord[$fieldIndex];
		  if ($result=mysql_query($query)) {
			
		  }
		}
		// return to BROWSE mode
	    $this->setMode("BROWSE");
	  } 
    }	  
  }
  
  public function printFields() {
    // display each field as a html control on a separate line
    foreach ($this->fields as $i => $field) {
      $result .= $this->fields[$i]->getHtmlControl($this->currentRecord[$i], $this->mode=="BROWSE").br();
    }   
    return $result;   
  }

  public function detailForm()
  {
    $form = new cHtmlForm();
    $form->setAttribute("ID", "detailForm".$this->name);
    $form->setAttribute("ACTION", "");
    $form->setAttribute("METHOD", "POST");
    $form->setAttribute("CONTENT", 
      $this->name." [".$this->at."/".$this->count."] ".$this->mode.br().
      $this->printFields().                          
      $this->navigator()
    );
    return $form->display().$this->subBrowsers();
  }
  
  public function subBrowsers() 
  {
  	$browsers = new cHtmlTabControl;
  	$query = "show tables";
  	$browsers->addTab("sb".$this->name.$this->name, $this->browseForm());
  	if ($dbResult = mysql_query($query)) {
  	  while ($dbRow = mysql_fetch_row($dbResult)) {
  	  	$ftable = new cDbTable($dbRow[0], "AND (".$this->name.".id".$this->name." = ".$this->currentRecord[0].")");
  	    $tableSwitch = new cHtmlInput("switchMT", "HIDDEN", $dbRow[0]);
  	  	if ($ftable->hasForeignTable($this->name)) {
  	  		$browsers->addTab("sb".$this->name.$dbRow[0], $ftable->browseForm($tableSwitch->display())); 
  	  	}
  	  	unset($ftable);
  	  }
  	}
  	return $browsers->display();
  }
  
  public function browseForm($include="") 
  {
	// create output as html table
	$table = new cHtmlTable();
	$table->addHeader(inputSet($this->columnNames, $this->name."FILTER", $_SESSION[table][$this->name][FILTER]));
	$table->addHeader(buttonSet($this->columnNames, $this->name."ORDER"));
	
	if ($dbResult = mysql_query($this->buildSQL())) {
	  $i = 0;
	  while ($dbRow = mysql_fetch_array($dbResult,MYSQL_ASSOC))	{
	  	$id = $dbRow["id".$this->name];
	  	$i++;
        $button = new cHtmlInput("go".$this->name, "SUBMIT", $i);
		$button->setAttribute("OnClick", "javascript:document.getElementById('sb".$this->name."RowId').value=$id;");
		$dbRow["id".$this->name] = $button->display();
		// add row to table
		$table->addRow($dbRow);
	  }
      mysql_free_result($dbResult);
	}
	
	$sbRowId = new cHtmlInput("sb".$this->name."RowId", "HIDDEN", -1);
	// include table in form
    $form = new cHtmlForm();
    $form->setAttribute("ID", "browseForm".$this->name);
    $form->setAttribute("ACTION", "");
    $form->setAttribute("METHOD", "POST");
    $form->setAttribute("CONTENT", 
	  $this->name." [".$this->at."/".$this->count."]".br().
      $table->display().
      $sbRowId->display().
      $include
    );
    return $form->display();
  }
}

//implement the interface iDbScheme
class cDbScheme implements iDbScheme 
{
  protected $dbLink;
  public $tables = array();
  
  // create link to MySQL database 
  public function link ($dbServerName, $dbUser, $dbPassword)
  {
    if ($this->dbLink = mysql_connect($dbServerName, $dbUser, $dbPassword)) 
    {
    } 
    else echo 'Not connected : ' . mysql_error();
  }
  
  // select database schema to work with
  public function useDb ($dbName)
  {
    if (mysql_select_db($dbName, $this->dbLink)) 
    {
      // clear old tables
      foreach ($this->tables as $name=>$table) {
        unset($this->tables[$name]);
      }
      //load all tables
      $query = "SHOW TABLES";
      if ($result = mysql_query($query, $this->dbLink)) {
        while ($row = mysql_fetch_array($result)) {
          $tableName=$row["Tables_in_$dbName"];
          $table = new cDbTable($tableName);
          // register all table objects in tables property of this object
          $this->tables[$tableName] = $table;
        }
      } 
    }
  }
  
  public function allDetailForms() 
  {
  	$tableTabs = new cHtmlTabControl($dbName."Admin");
  	foreach ($this->tables as $name=>$table) {
	  if ($_POST["tabButton"."Admin".$name] || ($_POST["switchMT"]==$name)) {
	  	$_SESSION[tabControl][Admin][selected] = $name;
	  }
  	}
	foreach ($this->tables as $name=>$table) {
	  $tableTabs->addTab(
        $name, 
        ($_SESSION[tabControl][Admin][selected] == $name
	      ? $table->detailForm()
          : ""
        )
	  ); // addTab
    }
    return $tableTabs->display();
  }
}

// -------------------------------------------- I N I T I A L I Z A T I O N

// connect to MySQL and select database
$dbScheme = new cDbScheme;
$dbScheme->link($dbServerName, $dbUser, $dbPassword);
$dbScheme->useDb($dbName);

?>