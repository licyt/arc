<?php
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com 
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
  
  public function insertForeignKey() {
  	$ftName = $this->foreignTableName();
  	$lookupField = gui($ftName, "lookupField", $ftName."Name");
  	$query = "INSERT INTO $ftName SET $lookupField = \"".$_POST[$lookupField]."\"";
  	if ($dbResult = mysql_query($query)) {
  	  return mysql_insert_id();
  	} else {
  	  return 0;
  	}
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
	  $ftName = $this->foreignTableName();
	  if(gui($this->getName(), "type")=="suggest") {
	  	$ftName = $this->foreignTableName();
	  	$lookupField = gui("table".$ftName, "lookupField", $ftName."Name");
	  	$sql=
	  	  "SELECT id".$ftName.",".$lookupField.
	  	  " FROM ".$ftName.
	  	  " ORDER BY ".$lookupField." ASC";
	  	$optionList = Array();
	  	if( $result = mysql_query($sql) ) {
	  	  while( $row = mysql_fetch_assoc($result) ) {
			$optionList[$row["id".$this->table->getName()]] = $row[$lookupField]; 
	  	  }
	  	}
	  	$htmlControl = new cHtmlSuggest($this->properties[Field], $value);
	  	$htmlControl->setOptions($optionList, $ftColumnName);
	  	$htmlControl->setAttribute("SUGGESTID", gui($ftName, "lookupField", $ftName."Name"));
	  	$htmlControl->setAttribute("onFocus", "setupSuggestList('".$this->properties[Field]."','".$ftColumnName."','suggestList".$ftColumnName."')");
	  	$htmlControl->setAttribute("onKeyUp","updateSuggestList('".$this->properties[Field]."','".$ftColumnName."','suggestList".$ftColumnName."')");
	  	$htmlControl->setAttribute("onSelect","sanitizeSuggestValues('".$ftColumnName."','".$this->properties[Field]."')");
	  } else {
        // use select for foreign keys
        $htmlControl = new cHtmlSelect;
	    $htmlControl->setSelected($value);
	    if ($ftName=="Status") {
	  	  $js ="this.style.backgroundColor=this.options[this.selectedIndex].style.backgroundColor;";
	    }
	    if ($this->mode=="UPDATE") {
	  	  $js .= "elementById('".$this->table->getName()."Ok').click();";
	    }
	    if ($js) $htmlControl->setAttribute("onChange", $js);
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
	} 
	
	switch (gui($this->getName(), "type")) {
	  case "path":
 	    $htmlControl = new cHtmlFilePath($value);
	    break;
	}
	
	if (!isset($htmlControl)) {
	  //use input for other fields
	  $htmlControl = new cHtmlInput;
	}
	
    // set input size based on dbField type
    $htmlControl->setAttribute("SIZE", filter_var($this->properties[Type], FILTER_SANITIZE_NUMBER_INT));
    
    // set attributes derived from Field name                                       
    $htmlControl->setAttribute("ID", $this->properties[Field]);
    $htmlControl->setAttribute("NAME", $this->properties[Field]);
    $htmlControl->setAttribute("DISABLED", 
      ($disabled
        ?" onClick=\"javascript:elementById('".$this->table->getName()."Update').click();\""
      	:""
      )
    );
    $htmlControl->setAttribute("VALUE", $value);
    
    return 
      $htmlControl->display();
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
  	if ($mode=="BROWSE") $mode="UPDATE";
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

  public function reservedButton() {
  	$button = new cHtmlInput($this->name."Reserved", "SUBMIT", " ");
  	$button->setAttribute("CLASS", "ReservedButton");
  	$button->setAttribute("DISABLED", "DISABLED");
  	return $button->display();
  }
  
  public function addButton() {
  	$button = new cHtmlInput($this->name."Insert", "SUBMIT", "+"); //gui($this->name."Insert", $GLOBALS[lang], $this->name."Insert")
    $button->setAttribute("CLASS", "InsertButton");
  	return $button->display();
  }

  public function manipulator() 
  {
    // display update button
    if (($this->mode == "BROWSE")&&($this->currentRecordId>0)) {
      $button = new cHtmlInput($this->name."Update", "SUBMIT", "o");
      $button->setAttribute("CLASS", "UpdateButton");
      $result .= $button->display();
    }
    // display delete button
    if (($this->mode == "UPDATE")&&($this->currentRecordId>0)) {
      $button = new cHtmlInput($this->name."Delete", "SUBMIT", "x");
      $button->setAttribute("CLASS", "DeleteButton");
      $result .= $button->display();
    }
    // display ok & cancel buttons
    if (($this->mode == "INSERT") ||
    	($this->mode == "UPDATE") ||
		($this->mode == "DELETE")) {
      
	  $button = new cHtmlInput($this->name."Ok", "SUBMIT", "v");
      $button->setAttribute("CLASS", "OkButton");
      if ($this->mode == "UPDATE") {
      	//$button->setAttribute("STYLE", "display:none;");
      }
      $result .= $button->display();
      $button = new cHtmlInput($this->name."Cancel", "SUBMIT", "x");
      $button->setAttribute("CLASS", "CancelButton");
      $result .= $button->display();
      
      /*
      if ($this->mode == "INSERT") {
      	$result .= $this->reservedButton();
      }
      */
      
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
    // get currentRecordId 
    if (isset($_POST["id".$this->name])) {
      //$this->setMode("BROWSE");
      $this->currentRecordId=$_POST["id".$this->name];
      $_SESSION[table][$this->name][currentRecordId] = $this->currentRecordId;
    } elseif ($_SESSION[table][$this->name][currentRecordId]) {
    	$this->currentRecordId = $_SESSION[table][$this->name][currentRecordId];
    }
    
    // # button - collapse tree 
    if (isset($_POST[$this->name.ORDERid.$this->name])) {
    	$this->currentRecordId=-1;
    	$this->setMode(BROWSE); 
    	$_SESSION[table][$this->name][currentRecordId] = $this->currentRecordId;
    }
    
    // data manipulation buttons
    if ($_POST[$this->name."Insert"]) {									// + Add
    	$this->setMode("INSERT");
    	$this->currentRecordId=-1;
    	$_SESSION[table][$this->name][currentRecordId] = $this->currentRecordId;
    }
    elseif ($_POST[$this->name."Update"]) { $this->setMode("UPDATE"); }		// * Edit
    elseif ($_POST[$this->name."Delete"]) { $this->setMode("DELETE");}  // x Del
    
    elseif ($_POST[$this->name."Ok"]) {                               		// Ok 
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
            if (($fieldName != "id".$this->name)) {
              // value is not set for suggested field
              if ((gui($fieldName, "type") == "suggest") && (!$_POST[$fieldName])) {
              	// insert new value to foreign table and get new id
              	$_POST[$fieldName] = $field->insertForeignKey();
              }
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
    else { $this->setMode("BROWSE"); }							     	// Cancel
  }
  
  public function printFields() {
    // display each field as a html control on a separate line
    foreach ($this->fields as $i => $field) {
      $result .= $this->fields[$i]->getHtmlControl($this->currentRecord[$field->getName()], $this->mode=="BROWSE").br();
    }   
    return $result;   
  }

  public function editColumns($id=0)
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
  	  }
   	  if ($columnName=="id".$this->name) {
  	    $result[$columnName] = $this->manipulator();
   	  }
  	}
  	$result["CLASS"] = $this->mode;
  	$result["onKeyPress"] = "if (event && event.keyCode==13) {elementById('".$this->name."Ok').click();}"; 
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
  
  protected function insertRow() 
  {
    $newNames = array();
    switch ($this->mode) {
  	  case "INSERT":
        $input = new cHtmlInput("id".$this->name, "HIDDEN", -1);
  	    $id = $input->display();
  	    $newNames = $this->editColumns();
  	    $newNames["id".$this->name] = $this->manipulator().$id;
  	    break;
  	  default :
      	$newNames["id".$this->name] = $this->addButton().$this->reservedButton();
      	break;
    }
    return $newNames;
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
    foreach ($columnNames as $i=>$inputName) {
  	  if ($inputName=="id".$this->name) {
  	    $newNames[$i]="";
  	  } else {
	    $filter  = new cHtmlInput($setName.$inputName, "TEXT", $values[$inputName]);
	    $filter->setAttribute("onChange", "this.form.submit()");
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
	$table->addHeader($this->orderSet($this->displayColumnNames, $this->name."ORDER"));
	$table->addRow($this->insertRow());
	// add filter only for master browser
	if (!isset($this->parent)) {
	  $table->addFooter($this->filterSet($this->displayColumnNames, $this->name."FILTER", $_SESSION[table][$this->name][FILTER]));
	}
	
	if ($dbResult = mysql_query($this->buildSQL())) {
	  $i = 0;
	  while ($dbRow = mysql_fetch_array($dbResult,MYSQL_ASSOC))	{
	  	$id = $dbRow["id".$this->name];
	  	$i++;
	  	if ($id==$this->currentRecordId) {
          // current record is editable
	  	  $table->addRow($this->editColumns($id));
	  	  // sub-browsers for current record
	  	  $sbRow = array();
	  	  $sbRow["sbIndent"]="";
	  	  $sbRow["subBrowser"]=$this->subBrowsers();
	  	  $sbRow["sbColSpan"]=sizeof($dbRow)-1;
	  	  $table->addRow($sbRow);
	  	} else {
	  	// other records
	  	  $js="javascript:".
		  	"elementById('id".$this->name."').value=$id;".
		  	"document.browseForm".$this->name.".action='#".$this->name."$id';".
	  	    "document.browseForm".$this->name.".submit();";
		  $dbRow["id".$this->name] = "";
		  // hide column for lookupField if it is a lookup into parent table 
		  if (isset($this->parent)) {
		  	$ftName = $this->parent->name;
            unset($dbRow[gui("table".$ftName, "lookupField", $ftName."Name")]);
		  }
		  // add javascript to onClick event of this row
		  $dbRow[onClick] = $js;
		  // add row to table
		  $table->addRow($dbRow);
	  	}
	  }
      mysql_free_result($dbResult);
	}
	
	$RowId = new cHtmlInput("id".$this->name, "HIDDEN", $this->currentRecordId);
	// include table in form
    $form = new cHtmlForm();
    $form->setAttribute("ID", "browseForm".$this->name);
    $form->setAttribute("ACTION", "");
    $form->setAttribute("METHOD", "POST");
    $form->setAttribute("CONTENT", 
	  //gui($this->name, $GLOBALS[lang], $this->name)." [".$this->at."/".$this->count."]".br().
      $table->display().
      $RowId->display().
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
      $_SESSION[dbLink] = $this->dbLink;
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