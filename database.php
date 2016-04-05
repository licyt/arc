<?php
// ------------------------------------------------------ C O N S T A N T S

include_once("./dbConfig.php");

// ------------------------------------------------------ I N T E R F A C E
// Declare the interface iDbField
interface iDbField
{
  public function setProperties($properties); 
  public function getName();
  public function isForeignKey();
  public function getHtmlControl();
  public function __construct($column);
}

// Declare the interface iDbTable
interface iDbTable
{
  public function loadFields();
  public function getCurrentRecord();
  public function getNumRecords();
  public function go($index);
  public function setName($name);
  public function printFields();
  public function detailForm();
  public function navigator();
	//public function getFieldByName($fieldName);
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
  private $properties = array();
  
  public function __construct($column) 
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
  
  public function isForeignKey() {
    // PK is a FK if fields name concontains "_id" 
    return 
      (strpos($this->properties[Field],"_id")>0) &&
      ($this->properties[Key]=="PRI");
  }

  public function getHtmlControl($value="")
  {
    if ($this->isForeignKey()) {
      // use select for foreign keys
      $htmlControl = new cHtmlSelect;
    } else {
      // use input for other fields
      $htmlControl = new cHtmlInput;
      // set input size based on dbField type
      $htmlControl->setAttribute("SIZE", filter_var($this->properties[Type], FILTER_SANITIZE_NUMBER_INT));
    }
    
    // set attributes derived from Field name                                       
    $htmlControl->setAttribute("ID", $this->properties[Field]);
    $htmlControl->setAttribute("NAME", $this->properties[Field]);
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
  protected $fields = array(); 
  
  protected $at;
  protected $count;
  protected $status;
  protected $currentRecord;

  public function loadFields() {
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
        // push this new field into array of fields of this object
        array_push($this->fields, $field);
      }
    }
  }

  public function getCurrentRecord() {
	// get current record from database
	if ($this->at) {
	  $query = "SELECT * FROM ".$this->name." LIMIT ".($this->at-1).",1";
	  $result = mysql_query($query);
	  $this->currentRecord = mysql_fetch_row($result);
	}	  
	return $this->currentRecord;
  }  
  
  public function getNumRecords() {
    // number of records in this table
    $query = "SELECT * FROM ".$this->name;
    $result = mysql_query($query);
    $this->count = mysql_num_rows($result);
	return $this->count;
   }
   
  public function go($index) {
    $this->at = $index;
    $_SESSION[table][$this->name][at] = $this->at;
    $this->getCurrentRecord();
  }
  
  function setStatus($status) {
    $this->status = $status;
    $_SESSION[table][$this->name][status] = $this->status;
  }

  public function setName($name)
  {
    // initialize table from database
    $this->name = $name;
    $this->loadFields();
    $this->getNumRecords();

    // get last table STATUS from session 
    if ($_SESSION[table][$this->name][status]) {
      $this->status = $_SESSION[table][$this->name][status];
    } else {
	  // or set status to "BROWSE" by default
      $this->setStatus("BROWSE");
    }

    // get table POSITION from session 
    if ($_SESSION[table][$this->name][at]) {
      $this->at = $_SESSION[table][$this->name][at];
    } else {
	  // or set position to 0 by default
      $this->go(0);
    }

	// respond to navigation buttons
    if ($_POST[$this->name."First"]) $this->go(1);
    if ($_POST[$this->name."Prev"])  $this->go($_SESSION[table][$this->name][at]-1);
    if ($_POST[$this->name."Next"])  $this->go($_SESSION[table][$this->name][at]+1);
    if ($_POST[$this->name."Last"])  $this->go($this->count);

    // + Add button was pressed
    if ($_POST[$this->name."Insert"]) {
	  $this->setStatus("INSERT");
	}
    // * Edit button was pressed
    if ($_POST[$this->name."Update"]) {
      $this->getCurrentRecord();
      $this->setStatus("UPDATE");
    }
    // x Delete button was pressed
    if ($_POST[$this->name."Delete"]) {
      $this->getCurrentRecord();
      $this->setStatus("DELETE");
    }
    // Cancel button was pressed
    if ($_POST[$this->name."Cancel"]) {
      $this->getCurrentRecord();
      $this->setStatus("BROWSE");
    }

    // Ok button was pressed
    if ($_POST[$this->name."Ok"]) {
	  switch ($this->status) {
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
		  // choose SQL keyword depending on status
		  switch ($this->status) {
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
		switch ($this->status) { 
		  case "INSERT" :
			$this->count++;
			$this->at = $this->count;
			$_SESSION[table][$this->name][at] = $this->at;
			break;
		  case "DELETE" :
			$this->count--;
			if ($this->at>1) $this->at--;
			$_SESSION[table][$this->name][at] = $this->at;
			break;
		}
		// return to BROWSE mode
	    $this->setStatus("BROWSE");
		$this->getCurrentRecord();
	  } else {
	    // sql error handling
	    echo "Could not run query $query : ". mysql_error();
	    exit;      
	  } 
    }

  }
  
  public function printFields() {
    // display each field as a html control on a separate line
    foreach ($this->fields as $i => $field) {
      if ($this->count) $value = $this->currentRecord[$i];      
      $result .= $this->fields[$i]->getHtmlControl($value).br();
    }   
    return $result;   
  }

  public function navigator() 
  {
    // display first button
    if (($this->status == "BROWSE")&&($this->count > 1)&&($this->at > 1)) {
      $button = new cHtmlInput($this->name."First", "SUBMIT", "|< First");
      $result .= $button->display();
    }
    // display prev button
    if (($this->status == "BROWSE")&&($this->count > 2)&&($this->at > 2)) {
      $button = new cHtmlInput($this->name."Prev", "SUBMIT", "< Prev");
      $result .= $button->display();
    }
    // display insert button
    if ($this->status == "BROWSE") {
      $button = new cHtmlInput($this->name."Insert", "SUBMIT", "+ Add");
      $result .= $button->display();
    }
    // display update button
    if (($this->status == "BROWSE")&&($this->at)) {
      $button = new cHtmlInput($this->name."Update", "SUBMIT", "* Edit");
      $result .= $button->display();
    }
    // display delete button
    if (($this->status == "BROWSE")&&($this->at)) {
      $button = new cHtmlInput($this->name."Delete", "SUBMIT", "x Delete");
      $result .= $button->display();
    }
    // display ok & cancel buttons
    if (($this->status == "INSERT") ||
	    ($this->status == "UPDATE") ||
		($this->status == "DELETE")) {
      $button = new cHtmlInput($this->name."Ok", "SUBMIT", "Ok");
      $result .= $button->display();
      $button = new cHtmlInput($this->name."Cancel", "SUBMIT", "Cancel");
      $result .= $button->display();
    }
    // display next button
    if (($this->status == "BROWSE")&&($this->at<($this->count-1))) {
      $button = new cHtmlInput($this->name."Next", "SUBMIT", "Next >");
      $result .= $button->display();
    }
    // display last button
    if (($this->status == "BROWSE")&&($this->at < $this->count)) {
      $button = new cHtmlInput($this->name."Last", "SUBMIT", "Last >|");
      $result .= $button->display();
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
      $this->name." [".$this->at."/".$this->count."]".br().
      $this->printFields().                          
      $this->navigator()
    );
    return $form->display();
  }
  
}

//implement the interface iDbScheme
class cDbScheme implements iDbScheme 
{
  protected $dbLink;
  protected $tables = array();
  
  // create link to MySQL database 
  public function link ($dbServerName, $dbUser, $dbPassword)
  {
    if ($this->dbLink = mysql_connect($dbServerName, $dbUser, $dbPassword)) 
    {
    } 
    else die('Not connected : ' . mysql_error());
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
          // create cDbTable object for each database table
          $tableName=$row["Tables_in_$dbName"];
          $table = new cDbTable;
          $table->setName($tableName);
          // register all table objects in tables property of this object
          $this->tables[$tableName] = $table;
        }
      } else die("oops ".mysql_error());
    } 
    else echo "oops ".mysql_error();
  }
  
  public function allDetailForms() 
  {
    foreach ($this->tables as $name=>$table) {
      $result.=
        $table->detailForm().br();      
    }
    return $result;
  }
}

// -------------------------------------------- I N I T I A L I Z A T I O N

// connect to MySQL and select database
$dbScheme = new cDbScheme;
$dbScheme->link($dbServerName, $dbUser, $dbPassword);
$dbScheme->useDb($dbName);


?>