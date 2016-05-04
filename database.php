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
  public function setTable($name);
  public function getName();
  public function isForeignKey();
  public function isDateTime();
  public function isDate();
  public function isStatusColor();
  public function foreignTableName();
  public function getHtmlControl();
}

// Declare the interface iDbTable
interface iDbTable
{
  public function __construct($table, $parent=null);
  public function loadFields();
  public function getMode();
  public function getName();
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
  public function manipulator();
  public function respondToPost();
  public function detailForm();
}  

// Declare the interface iDbSchema
interface iDbScheme 
{
  public function link ($dbServerName, $dbUser, $dbPassword);
  public function useDb ($dbName);
  public function admin();
}

// -------------------------------------------  I M P L E M E N T A T I O N
// Implement the interface iDbField
class cDbField implements iDbField
{
  protected $properties = array();
  protected $table = cDbTable;
  
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
  
  public function isDate() {
  		return ($this->properties[Type] == "date");
  }
  
  public function isStatusColor() {
	return ( $this->properties[Field] == "StatusColor" );
  }
  
  public function foreignTableName() {
    $fieldName = $this->getName();
	return substr($fieldName, strpos($fieldName, "_id")+3);
  }
  
  public function setTable($table) {
	$this->table=$table;
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
		  gui("table".$ftName, "lookupField", $ftName."Name"). 
		  ($ftName=="Status" ? ", StatusColor" : "").
	    " FROM ".$ftName.
		// Status - additional filter for StatusType
		($ftName=="Status"
		  ? " WHERE StatusType=\"".$this->table->getName()."\""
		  : ""
		);
	  // push options
	  if ($result = mysql_query($query)) {
		while ($row = mysql_fetch_assoc($result)) {
		  $htmlControl->addOption(
		    $row["id".$ftName], 
		  	$row[gui("table".$ftName, "lookupField", $ftName."Name")], 
		  	($ftName=="Status" ? $row[StatusColor] : "")
		  );
        }
	  }
	  // add button for parent table
	  if ($this->table->getMode()=="BROWSE") {
	  	$htmlButton = new cHtmlInput("", "SUBMIT", "...");
	  }
    } elseif (($this->getName()=="StatusType") || ($this->getName()=="NoteTable")) {
    	$htmlControl = new cHtmlSelect;
    	$htmlControl->setSelected($value);
    	$dbResult = mysql_query("SHOW TABLES");
    	while ($dbRow = mysql_fetch_row($dbResult)) {
    	  $htmlControl->addOption($dbRow[0], $dbRow[0]);
    	}
    } elseif($this->isDate()) {
		  $htmlControl = new cHtmlJsDatePick;
    } elseif($this->isDateTime()) {
		  $htmlControl = new cHtmlJsDateTimePick;
    } elseif ($this->isStatusColor())  {
			$htmlControl = new cHtmlJsColorPick;
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
    
    /*/ create  label
	$htmlLabel = new cHtmlLabel;
	$htmlLabel->setAttribute("ID", "Label".$this->properties[Field]);
	$htmlLabel->setAttribute("TARGET", $this->properties[Field]);
	$htmlLabel->setAttribute("VALUE", $this->properties[Field]);
	*/
    
    return 
      //$htmlLabel->display().
      $htmlControl->display().
      ($htmlButton
        ? $htmlButton->display()
        : ""
      );
  }
}

// Implement the interface iDbTable
class cDbTable implements iDbTable
{
  protected $name;  
  protected $parent;
  public $fields = array();   
  // parameters for navigation
  protected $at;									// index of current record
  protected $count;									// total count of records
  protected $mode;									// object operational mode BROWSE/INSERT/UPDATE/DELETE
  protected $currentRecordId;
  protected $currentRecord;                        
  // browser parameters
  protected $columnNames = array();
  protected $displayColumnNames = array();
  protected $ftNames = array();
  protected $start;									// browser starting position
  protected $rowCount;								// number of rows in browser
  // build SQL substrings
  protected $columns;
  protected $order;
  protected $filter;
  
  public function __construct($table, $parent=null) 
  {
	$this->parent = $parent;
  	$this->setName($table);
  }

  public function getMode() {
    return $this->mode;	
  }
  
  public function getName() 
  {
    return $this->name;	
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
	$query = "SELECT * FROM ".$this->name." WHERE id".$this->name."=".$this->currentRecordId;
	if ($result = mysql_query($query)) {
	  $this->currentRecord = mysql_fetch_assoc($result);
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
	$this->respondToPost();
	$this->getNumRecords();
    if ($this->mode!="INSERT") $this->getCurrentRecord();
  }
  
  public function setOrder($order) 
  {
    if ($_SESSION[table][$this->name][order]==$order) { 
      //$this->order = $order." DESC"; 
    } else {
  	  $this->order = $order;
    }
    $_SESSION[table][$this->name][order] = $this->order;
  }
  
  public function hasForeignTable($ftName) 
  {
  	foreach ($this->fields as $i=>$field) {
  	  if ($field->getName() == $this->name."_id".$ftName) return true;
  	}
  	return false; 
  }
  
  public function hasStatusField() {
  	foreach ($this->fields as $i=>$field) {
  	  if ($field->getName() == $this->name."_idStatus") return true;
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
		$field->setTable($this);
        // push this new field into array of fields of this object
        array_push($this->fields, $field);
      }
    }
  }
  
  public function loadColumns($useForeignFields=true) 
  {
  	// empty list of columns and foreign_tables
  	foreach ($this->columnNames as $i=>$columnName) unset($this->columnNames[$i]);
  	foreach ($this->displayColumnNames as $i=>$displayColumnName) unset($this->displayColumnNames[$i]);
  	foreach ($this->ftNames as $i=>$ftName) unset($this->ftNames[$i]);
  	// load list of columns for browser from list of fields
	foreach ($this->fields as $i=>$field) {
	  $fieldName = $field->getName();
	  array_push($this->columnNames, $fieldName);
	  if (strpos($fieldName, "_id") || (($fieldName=="StatusLogRowId")&& !is_null($this->parent))) {
	  	// do not display foreign index field
	  	// do not display StatusLogRowId in subBrowser (table with defined parent)
	  } else {
 	  	array_push($this->displayColumnNames, $fieldName);
	  }
	  // foreign key lookup fields
	  if ($useForeignFields && $field->isForeignKey()) {
        // this field is a foreign key, display lookupField from referenced table
	  	$ftName = $field->foreignTableName();
	  	// default lookupField is Name
		array_push($this->columnNames, gui("table".$ftName, "lookupField", $ftName."Name"));
		// do not display this column if it is a lookup from parent browser
		if (is_null($this->parent) || ($ftName!=$this->parent->name))
          array_push($this->displayColumnNames, gui("table".$ftName, "lookupField", $ftName."Name"));
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
    
    // get currentRecordId 
    if (isset($_POST["go".$this->name])) {
      $this->setMode("BROWSE");
      $this->currentRecordId=$_POST["sb".$this->name."RowId"];
      $_SESSION[table][$this->name][currentRecordId] = $this->currentRecordId;
    } elseif ($_SESSION[table][$this->name][currentRecordId]) {
    	$this->currentRecordId = $_SESSION[table][$this->name][currentRecordId];
    }
  }
  
  protected function parentLimit() 
  {
  	if (is_null($this->parent)) return "";
  	return "(".$this->parent->name.".id".$this->parent->name." = ".$this->parent->currentRecordId.")";  	
  }

  protected function assignSQL($record) 
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
  
  protected function buildSQL() 
  {
    $this->columns = "";
  	$this->filter = "";
  	// load order and filter stored in session
  	$this->order = $_SESSION[table][$this->name][order];
  	// column names
  	foreach ($this->columnNames as $i=>$columnName) {
  		$this->columns .= ($this->columns?", ":"").$columnName;
  		// collation order
  		if ($_POST[$this->name."ORDER".$columnName]!="") {
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
  	  	  ($fkConstraints 
  	  	  	? $fkConstraints.
  	  	  	  (is_null($this->parent) ? "" : " AND ".$this->parentLimit()) 
  	  	  	: ""
  	  	  ).
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
  
  public function addButton() {
  	$button = new cHtmlInput($this->name."Insert", "SUBMIT", gui($this->name."Insert", $GLOBALS[lang], $this->name."Insert"));
  	return $button->display();
  }

  public function manipulator() 
  {
    // display update button
    if (($this->mode == "BROWSE")&&($this->at)) {
      $button = new cHtmlInput($this->name."Update", "SUBMIT", "* Edit");
      $result .= $button->display();
    }
    // display delete button
    if (($this->mode == "BROWSE")&&($this->at)) {
      $button = new cHtmlInput($this->name."Delete", "SUBMIT", "x Del");
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
      if (!is_null($this->parent)) {
      	$parrentName = $this->name."_id".$this->parent->name;
      	$parentId = new cHtmlInput($parrentName, "HIDDEN", $this->parent->currentRecordId);
      	$result .= $parentId->display();
      }
    }
    return $result;
  }

  public function respondToPost() 
  {
    if ($_POST["go".$this->name]) $this->go($_POST["go".$this->name]);
    
    // data manipulation buttons
    if ($_POST[$this->name."Insert"]) {							// + Add
    	$this->setMode("INSERT");
    	$this->currentRecordId=-1;
    	$_SESSION[table][$this->name][currentRecordId] = $this->currentRecordId;
    }
    if ($_POST[$this->name."Update"]) $this->setMode("UPDATE"); // * Edit
    if ($_POST[$this->name."Delete"]) $this->setMode("DELETE"); // x Del
    if ($_POST[$this->name."Cancel"]) $this->setMode("BROWSE"); // Cancel
    
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
            if (($fieldName != "id".$this->name) && ($_POST[$fieldName] != "")) {
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
			$this->currentRecordId = mysql_insert_id();
			break;
		  case "DELETE" :
			$this->count--;
			if ($this->at>$this->count) $this->go($this->count);
			$this->currentRecordId = 0;
			break;
		}
		$this->getCurrentRecord();
	    // log any status change 
		$fieldName = $this->name."_idStatus";
		if ($field = $this->getFieldByName($fieldName)) {
		  $query=
		    "INSERT INTO StatusLog SET ".
			  "StatusLogRowId=".$this->currentRecord["id".$this->name].", ".
			  "StatusLog_idStatus=".$this->currentRecord[$fieldName];
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
      $result .= $this->fields[$i]->getHtmlControl($this->currentRecord[$field->getName()], $this->mode=="BROWSE").br();
    }   
    return $result;   
  }

  public function editColumns($rowIndex=0)
  {
  	// display fields as controls in a row of a html table
  	$result = array();
  	foreach ($this->columnNames as $i => $columnName) {
  	  if ($field = $this->getFieldByName($columnName)) {
  	  	$htmlControl = $field->getHtmlControl($this->currentRecord[$columnName], $this->mode=="BROWSE");
  	  	if ($field->isForeignKey()) {
  	  	  $ftName = $field->foreignTableName();
  	  	  if (is_null($this->parent) || ($ftName!=$this->parent->name)) {
  	  	    $result[gui("table".$ftName, "lookupField", $ftName."Name")] = $htmlControl;
  	  	  }
  	  	} else {
  	      $result[$columnName] = $htmlControl;
  	  	}
  	  	if ($field->getName()=="id".$this->name) {
	      $button = new cHtmlInput("go".$this->name, "SUBMIT", $rowIndex);
		  $button->setAttribute("CLASS","goButtons");
		  $button->setAttribute("OnClick", 
		    "javascript:".
		  	  "document.getElementById('sb".$this->name."RowId').value=-1;"
		  );
		  $input = new cHtmlInput("id".$this->name, "HIDDEN", $this->currentRecordId);
  	  	  $result[$columnName] = 
  	  	    $button->display().
  	  	    $this->manipulator().
  	  	    $input->display();
  	  	}
  	  } else {
  	  	//$result[$columnName] = "";
  	  }
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
      gui("table".$this->name, $GLOBALS[lang], $this->name)." [".$this->at."/".$this->count."] ".$this->mode.br().
      $this->printFields()
    );
    return $form->display();
  }
  

protected function orderSet(array $columnNames, $setName="") 
{
  $newNames = array();
  foreach ($columnNames as $i=>$buttonName) {
	$button  = new cHtmlInput($setName.$buttonName, "SUBMIT", gui($setName.$buttonName, $GLOBALS[lang], $buttonName));
	$newNames[$i]=$button->display();
  }
  //$newNames[0]="";
  return $newNames;	
}

protected function filterSet(array $columnNames, $setName="", $values)
{
  $newNames = array();
  
  if ($this->mode=="INSERT") {
 	$input = new cHtmlInput("id".$this->name, "HIDDEN", $this->currentRecordId);
  	$id = $input->display();
  	$newNames = $this->editColumns();
  	$newNames["id".$this->name] = $this->manipulator().$id.$parentId;
    return $newNames;
  }
  
  foreach ($columnNames as $i=>$inputName) {
  	if ($inputName=="id".$this->name) {
  	  $newNames[$i]=$this->addButton();
  	} else {
	  $filter  = new cHtmlInput($setName.$inputName, "TEXT", $values[$inputName]);
	  $filter->setAttribute("OnChange", "this.form.submit()");
	  $filter->setAttribute("CLASS", "filter");
	  $newNames[$i]=$filter->display();
  	}
  }
  //$newNames[0]="";
  return $newNames;
}
  public function browseForm($include="") 
  {
	// create output as html table
	$table = new cHtmlTable();
	$table->addHeader($this->filterSet($this->displayColumnNames, $this->name."FILTER", $_SESSION[table][$this->name][FILTER]));
	$table->addHeader($this->orderSet($this->displayColumnNames, $this->name."ORDER"));
	
	if ($dbResult = mysql_query($this->buildSQL())) {
	  $i = 0;
	  while ($dbRow = mysql_fetch_array($dbResult,MYSQL_ASSOC))	{
	  	$id = $dbRow["id".$this->name];
	  	$i++;
	  	if ($id==$this->currentRecordId) {
        // current record is editable
	  	  $table->addRow($this->editColumns($i));
	  	  // sub-browsers for current record
	  	  $sbRow = array();
	  	  $sbRow["sbIndent"]="";
	  	  $sbRow["subBrowser"]=$this->subBrowsers();
	  	  $sbRow["sbColSpan"]=sizeof($dbRow)-1;
	  	  $table->addRow($sbRow);
	  	} else {
	  	// other records
	      $button = new cHtmlInput("go".$this->name, "SUBMIT", $i);
		  $button->setAttribute("OnClick", 
		    "javascript:".
		  	  "document.getElementById('sb".$this->name."RowId').value=$id;".
		  	  "this.form.action='#".$this->name."$id';"
		  );
		  $button->setAttribute("CLASS","goButtons");
		  // replace id value with button
		  $dbRow["id".$this->name] = $button->display();
		  // hide column for lookupField if it is a lookup into parent table 
		  if (isset($this->parent)) {
		  	$ftName = $this->parent->name;
            unset($dbRow[gui("table".$ftName, "lookupField", $ftName."Name")]);
		  }
		  // add row to table
		  $table->addRow($dbRow);
	  	}
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
	  //gui($this->name, $GLOBALS[lang], $this->name)." [".$this->at."/".$this->count."]".br().
      $table->display().
      $sbRowId->display().
      $include
    );
    return $form->display();
  }
  
  public function subBrowsers() 
  {
  	$browsers = new cHtmlTabControl("sb".$this->name);
  	
  	$query = "show tables";
  	if ($dbResult = mysql_query($query)) {
  	  while ($dbRow = mysql_fetch_row($dbResult)) {
  	  	$ftable = new cDbTable($dbRow[0], $this);
  	  	if ($ftable->hasForeignTable($this->name)) {
  	  		$browsers->addTab("sb".$this->name.$dbRow[0], $ftable->browseForm()); 
  	  	}
  	  	unset($ftable);
  	  }
  	}
  	
  	// history of statuses for current record
  	if ($this->hasStatusField()) {
  	  $ftable = new cDbTable("StatusLog", $this);
      $browsers->addTab("sb".$this->name."Status", $ftable->browseForm());
  	  unset($ftable);
  	}
    
    // notes for current record
  	$ftable = new cDbTable("Note", $this);
    $browsers->addTab("sb".$this->name."Note", $ftable->browseForm());
  	unset($ftable);
  	
  	return $browsers->display();
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
  
  public function admin() 
  {
  	$tableTabs = new cHtmlTabControl($dbName."Admin");
  	foreach ($this->tables as $name=>$table) {
	  if ($_POST["tabButton"."Admin".$name]) {
	  	$_SESSION[tabControl][Admin][selected] = $name;
	  }
  	}
	foreach ($this->tables as $name=>$table) {
	  $tableTabs->addTab(
        $name, 
        ($_SESSION[tabControl][Admin][selected] == $name
	      ? $table->browseForm()
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