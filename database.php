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
  public function __construct($name);
}

// Declare the interface iDbTable
interface iDbTable
{
  public function setName($name);
  public function printFields();
  public function detailForm();
  //public function getFieldByName($fieldName);
  public function navigator();
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
    // no control for auto_increment fields
    if ($this->properties[Extra]=="auto_increment") return;

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
    $htmlControl->setAttribute("VALUE", $this->value);
    
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

  public function setName($name)
  {
    // set table name 
    $this->name = $name;

    // get list of fields for a table from database
    $query = "SHOW COLUMNS FROM $name";
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

    // number of records in this table
    $query = "SELECT * FROM $name";
    $result = mysql_query($query);
    $this->count = mysql_num_rows($result);

    // get last table status from session or set status to "BROWSE" by default
    if ($_SESSION[table][$this->name][status]) {
      $this->status = $_SESSION[table][$this->name][status];
    } else {
      $this->status = "BROWSE";
      $_SESSION[table][$this->name][status] = $this->status;
    }
    // get table position from session or set position to 0 by default
    if ($_SESSION[table][$this->name][at]) {
      $this->at = $_SESSION[table][$this->name][at];
    } else {
      $this->at = 0;
      $_SESSION[table][$this->name][at] = $this->at;
    }
    if ($_POST[$this->name."First"]) {
	  $this->at = 1;
	  $_SESSION[table][$this->name][at] = $this->at;
	}
    if ($_POST[$this->name."Prev"]) {
	  $this->at = $_SESSION[table][$this->name][at]-1;
	  $_SESSION[table][$this->name][at] = $this->at;
	}
    if ($_POST[$this->name."Next"]) {
	  $this->at = $_SESSION[table][$this->name][at]+1;
	  $_SESSION[table][$this->name][at] = $this->at;
	}
    if ($_POST[$this->name."Last"]) {
	  $this->at = $this->count;
	  $_SESSION[table][$this->name][at] = $this->at;
	}
    // get current record from database
    if (($this->status == "BROWSE")&&($this->at)) {
      $query = "SELECT * FROM $name LIMIT ".($this->at-1).",1";
      $result = mysql_query($query);
      $this->currentRecord = mysql_fetch_row($result);
    }

    // + Add button was pressed
    if ($_POST[$this->name."Insert"]) {
      $this->status = "INSERT";
      $_SESSION[table][$this->name][status] = $this->status;
    }

    // Cancel button was pressed
    if ($_POST[$this->name."Cancel"]) {
      $this->status = "BROWSE";
      $_SESSION[table][$this->name][status] = $this->status;
    }
    // Ok button was pressed while in INSERT mode
    if (($this->status=="INSERT")&&($_POST[$this->name."Ok"])) {
      // assign field values 
      foreach ($this->fields as $i => $field) {
        $fieldName = $field->getName();
        if (strpos($fieldName, "id")===false) {
          $assign .= ($assign ? ", " : "").
            $fieldName." = \"".$_POST[$fieldName]."\"";
        } 
      }
      // build SQL 
      $query = "INSERT INTO ".$this->name." SET ".$assign;
      // execute SQL
      if ($result = mysql_query($query)) {
        $this->status = "BROWSE";
        $this->count++;
        $this->at = $this->count;
		$_SESSION[table][$this->name][at] = $this->at;
      } else {
        // sql error handling
        echo "Could not run query $query : ". mysql_error();
        exit;      
      } 
      $_SESSION[table][$this->name][status] = $this->status;
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
      $button = new cHtmlInput;
      $button->setAttribute("ID", $this->name."First");
      $button->setAttribute("TYPE", "SUBMIT");
      $button->setAttribute("VALUE", "|< First");
      $result .= $button->display();
    }
    // display prev button
    if (($this->status == "BROWSE")&&($this->count > 2)&&($this->at > 2)) {
      $button = new cHtmlInput;
      $button->setAttribute("ID", $this->name."Prev");
      $button->setAttribute("TYPE", "SUBMIT");
      $button->setAttribute("VALUE", "< Prev");
      $result .= $button->display();
    }
    // display insert button
    if ($this->status == "BROWSE") {
      $button = new cHtmlInput;
      $button->setAttribute("ID", $this->name."Insert");
      $button->setAttribute("TYPE", "SUBMIT");
      $button->setAttribute("VALUE", "+ Add");
      $result .= $button->display();
    }
    // display update button
    if (($this->status == "BROWSE")&&($this->at)) {
      $button = new cHtmlInput;
      $button->setAttribute("ID", $this->name."Update");
      $button->setAttribute("TYPE", "SUBMIT");
      $button->setAttribute("VALUE", "* Change");
      $result .= $button->display();
    }
    // display delete button
    if (($this->status == "BROWSE")&&($this->at)) {
      $button = new cHtmlInput;
      $button->setAttribute("ID", $this->name."Delete");
      $button->setAttribute("TYPE", "SUBMIT");
      $button->setAttribute("VALUE", "x Delete");
      $result .= $button->display();
    }
    // display ok & cancel buttons
    if ($this->status == "INSERT") {
      $button = new cHtmlInput;
      $button->setAttribute("ID", $this->name."Ok");
      $button->setAttribute("TYPE", "SUBMIT");
      $button->setAttribute("VALUE", "Ok");
      $result .= $button->display();
      $button = new cHtmlInput;
      $button->setAttribute("ID", $this->name."Cancel");
      $button->setAttribute("TYPE", "SUBMIT");
      $button->setAttribute("VALUE", "Cancel");
      $result .= $button->display();
    }
    // display next button
    if (($this->status == "BROWSE")&&($this->at<($this->count-1))) {
      $button = new cHtmlInput;
      $button->setAttribute("ID", $this->name."Next");
      $button->setAttribute("TYPE", "SUBMIT");
      $button->setAttribute("VALUE", "Next >");
      $result .= $button->display();
    }
    // display last button
    if (($this->status == "BROWSE")&&($this->at < $this->count)) {
      $button = new cHtmlInput;
      $button->setAttribute("ID", $this->name."Last");
      $button->setAttribute("TYPE", "SUBMIT");
      $button->setAttribute("VALUE", "Last >|");
      $result .= $button->display();
    }
    return $result;
  }

  public function detailForm()
  {
    $form = new cHtmlForm();
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