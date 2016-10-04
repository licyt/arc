<?php
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com 
// ------------------------------------------------------ C O N S T A N T S

include_once("./dbConfig.php");
require_once 'gui.php';
require_once("html.php");
require_once 'action.php';
require_once 'relation.php';
require_once 'gantt.php';
require_once 'color.php';

function myQuery($query) {
  $GLOBALS[queryCount]++;
  //echo $query."<BR>";
  return mysql_query($query);
}

// ------------------------------------------------------ I N T E R F A C E
// Declare the interface iDbField
interface iDbField
{
  public function __construct($column="");
  public function foreignTableName();
  public function getHtmlControl($value="", $disabled=false);
  public function getLookupControl($childTable, $value=-1);
  public function getName();
  public function getSize();
  public function getType();
  public function insertForeignKey();
  public function isAutoInc();
  public function isDate();
  public function isDateTime();
  public function isForeignKey();
  public function isStatusColor();
  public function isTimeStamp();
  public function setProperties($properties); 
  public function setTable($name);
}

// Declare the interface iDbTable
interface iDbTable
{
  public function __construct($table, $parent=null);
  public function addButton();
  public function browse($include="");
  public function detailForm();
  public function getCurrentRecord();
  public function getFieldByName($fieldName); 
  public function getFieldIndex($fieldName);
  public function getMode();
  public function getName();
  public function getNumRecords();
  public function go($index);
  public function insert($record);
  public function loadFields();
  public function loadChildren();
  public function manipulator();
  public function printFields();
  public function respondToPost();
  public function setMode($mode);
  public function setName($name);
  public function update($record);
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
  protected $ftable = cDBTable;
  
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
  
  public function getType() {
    return $this->properties[Type];
  }
  
  public function getExtra() {
    return $this->properties[Extra];
  }
  
  public function getSize() {
  	return filter_var($this->getType(), FILTER_SANITIZE_NUMBER_INT);
  }
  
  public function isAutoInc() {
	  return $this->properties[Extra]=="auto_increment";
  }
  
  public function isForeignKey() {
    // field name does not contain table name 
    return 
      (strpos($this->properties[Field], $this->table->getName())===false);
  }
  
  public function insertForeignKey() {
  	$ftName = $this->foreignTableName();
  	$lookupField = gui($ftName, "lookupField", $ftName."Name");
  	$query = "INSERT INTO $ftName SET $lookupField = \"".$_POST[$lookupField]."\"";
  	if ($dbResult = myQuery($query)) {
  	  return mysql_insert_id();
  	} else {
  	  return 0;
  	}
  }
  
  public function isDateTime() {
    return ($this->properties[Type] == "datetime");
  }
  
  public function isTimeStamp() {
  	return ($this->properties["Default"] == "CURRENT_TIMESTAMP");
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
  	if ($this->getName()=="NoteText") {
  	  $htmlControl = new cHtmlText($value);
  	  $htmlControl->setAttribute("onChange", "rowHasChanged('".$this->table->getName()."');");
  	// ---------------------------------------------------------------------------- BASIC field types
    } elseif (($this->getName()=="StatusType") 
        || ($this->getName()=="NoteTable") 
        || ($this->getName()=="RelationLObject") 
        || ($this->getName()=="RelationRObject") 
        || ($this->getName()=="ActionTable")) {   // ---------------------------- List of Tables 
    	$htmlControl = new cHtmlSelect;
    	$htmlControl->setSelected($value);
     	foreach ($this->table->scheme->tables as $table) {
    	  $tableName = $table->getName();
    	  $htmlControl->addOption($tableName, $tableName);
    	}
    	if ($this->getName()=="ActionTable") {
    	  $htmlControl->setAttribute("onChange", "loadTable();");
    	}
        if ($this->getName()=="RelationRObject") {
    	  $htmlControl->setAttribute("onChange", "loadRightRows();");
    	}
        if ($this->getName()=="RelationLObject") {
    	  $htmlControl->setAttribute("onChange", "loadLeftRows();");
    	}
    } elseif ($this->getName()=="ActionCommand") { // ------------------------------ ActionCommand
      $htmlControl = new cHtmlInput("ActionCommand", "HIDDEN");
    } elseif($this->isDate()) {
  		$htmlControl = new cHtmlJsDatePick(); // --------------------------------------- DatePick
  		$htmlControl->setAttribute(TableName, $this->table->getName());
    } elseif($this->isDateTime()) {
  		$htmlControl = new cHtmlJsDateTimePick; // ------------------------------------- DateTimePick
    } elseif ($this->isStatusColor())  {
	   	$htmlControl = new cHtmlJsColorPick; // ----------------------------------------- ColorPick
  	} 
  	
  	// ----------------------------------------------------------------------------- GUI field types
  	switch (gui($this->getName(), "type")) { 
  	  case "path":
   	    $htmlControl = new cHtmlFilePath($value, $this->table->getName());
  	    break;
  	}
  	
  	// ----------------------------------------------------------------------------------- default 
  	if (!isset($htmlControl)) {
  	  //use input for other fields
  	  $htmlControl = new cHtmlInput;
  	}
  	
    // set input size based on dbField type
    if( !(gui($this->getName(), "type")=="suggest") ) {
      $htmlControl->setAttribute("SIZE", filter_var($this->properties[Type], FILTER_SANITIZE_NUMBER_INT));
      $htmlControl->setAttribute("MAXLENGTH", filter_var($this->properties[Type], FILTER_SANITIZE_NUMBER_INT));
    }
    
    // set attributes derived from Field name                                       
    $htmlControl->setAttribute("ID", $this->properties[Field]);
    $htmlControl->setAttribute("NAME", $this->properties[Field]);
    $htmlControl->setAttribute("DISABLED", 
      ($disabled
        ?" onClick=\"javascript:elementById('".$this->table->getName()."Update').click();\""
      	:""
      )
    );
    
  	$htmlControl->setAttribute("onInput", "rowHasChanged('".$this->table->getName()."');");
    $htmlControl->setAttribute("onClick", "stopEvent(event);");
    $htmlControl->setAttribute("VALUE", $value);
    
    return 
      $htmlControl->display();
  }
  
  public function getLookupControl($childTable, $value=-1) {
    // -------------------------------------------------------------------------- suggest by tomcat
    $ftName=$this->table->getName();
    $lookupField=$this->getName();
    if (gui($lookupField, "lookupType")=="suggest") {
      // load options from database
  	  $sql=
  	    "SELECT id".$ftName.",".$lookupField.
  	    " FROM ".$ftName.
  	    " ORDER BY ".$lookupField." ASC";
  	  $optionList = Array();
  	  if( $result = myQuery($sql) ) {
    		while( $row = mysql_fetch_assoc($result) ) {
    		  $optionList[$row["id".$ftName]] = $row[$lookupField];
    		}
  	  }
  	  $hiddenName = $childTable->getName()."Id".$ftName;
  	  $visibleName = $childTable->getName().gui($ftName, "lookupField", $ftName."Name");
  	  $htmlControl = new cHtmlSuggest($hiddenName, $value, $optionList[$value]);
  	  $htmlControl->setOptions($optionList, $lookupField);
  	  // get lookup field type/size
  	  $sql = "SHOW COLUMNS FROM ".$ftName." LIKE '".$lookupField."'";
  	  if( $result = myQuery($sql) ) {
    		$row = mysql_fetch_assoc($result);
    		$htmlControl->setAttribute("SIZE", filter_var($row[Type], FILTER_SANITIZE_NUMBER_INT));
  	  }
  	  $htmlControl->setAttribute("SUGGESTID", $visibleName);
  	  // attach event controllers
  	  // suggestList(event, searchType, searchString, tableName, columnName, hiddenId, visibleId, destinationId)
  	  $htmlControl->setAttribute("onFocus","suggestList(event, 'valueSearch', this.value, '$ftName', '$lookupField', '$hiddenName', '$visibleName', '$hiddenName"."List')");
      $htmlControl->setAttribute("onKeyUp","suggestList(event, 'valueSearch', this.value, '$ftName', '$lookupField', '$hiddenName', '$visibleName', '$hiddenName"."List')");
      $htmlControl->setAttribute("onSelect","sanitizeSuggestValues('$hiddenName', '$visibleName', '$hiddenName"."List')");
  	  $htmlControl->setAttribute("onBlur", "sanitizeSuggestList('$hiddenName"."List')");
  	  $htmlControl->setAttribute("onInput", "rowHasChanged('".$childTable->getName()."');");
    } else {
      $htmlControl = new cHtmlSelect;
      $htmlControl->setAttribute(ID, ($childTable->getName()==$this->table->getName()?"parent":"").$this->getName());
      $htmlControl->setAttribute(NAME, ($childTable->getName()==$this->table->getName()?"parent":"").$this->getName());
      $htmlControl->setSelected($value);
      if ($ftName=="Status") {
        if ($this->getName()=="StatusType") {
          foreach ($this->table->scheme->tables as $table) {
        	$tableName = $table->getName();
        	$htmlControl->addOption($tableName, $tableName);
          } 
          $q0 = "SELECT StatusType FROM Status WHERE idStatus=$value";
          if ($dbRes0 = myQuery($q0)) {
          	if ($dbRow0 = mysql_fetch_assoc($dbRes0)) {
          	  $htmlControl->setSelected($dbRow0[StatusType]);
          	}
          }
        }
        // color background for status
	  	  $js ="this.style.backgroundColor=this.options[this.selectedIndex].style.backgroundColor;";
  	  }
  	  // attach onChange handler
  	  if (($childTable->getMode()=="UPDATE")||($childTable->getMode()=="INSERT")) {
  	    $js .= "rowHasChanged('".$childTable->getName()."');";
  	  }
  	  if ($js) $htmlControl->setAttribute("onChange", $js);
  	  // prepare select for lookup options
  	  $query = 
  	    "SELECT id".$ftName.", ".
  		  gui($ftName, "lookupField", $ftName."Name"). 
  		  ($ftName=="Status" ? ", StatusColor" : "").
  	    " FROM ".$ftName.
  		// Status - additional filter for StatusType
  		($ftName=="Status"
  		  ? " WHERE StatusType=\"".$childTable->getName()."\""
  		  : ""
  		);
  	  // push options
  	  if ($result = myQuery($query)) {
    		while ($row = mysql_fetch_assoc($result)) {
    		  $htmlControl->addOption(
    		    $row["id".$ftName], 
    		    $row[gui($ftName, "lookupField", $ftName."Name")], 
    		 	  ($ftName=="Status" ? $row[StatusColor] : "")
    		  );
        }
  	  }
    }
    $htmlControl->setAttribute("onClick", "stopEvent(event);");
  	return $htmlControl->display();
  }
  
}

// Implement the interface iDbTable
class cDbTable implements iDbTable
{
  public $scheme;
  protected $name;  
  protected $parent; // parent browser
  protected $relation;
  protected $preprocessed=false;
  public $children = array();
  public $parents = array(); // parent tables in DB model
  public $fields = array();   
  // parameters for navigation
  protected $at;									// index of current record
  protected $count;									// total count of records
  protected $mode;									// object operational mode BROWSE/INSERT/UPDATE/DELETE
  protected $currentRecordId;
  protected $currentRecord = array();
  protected $lastRecord = array();
  // browser parameters
  protected $columnNames = array();
  public $displayColumnNames = array();
  protected $ftNames = array();
  protected $start;									// browser starting position
  protected $rowCount;								// number of rows in browser
  // build SQL substrings
  protected $order;
  protected $filter;
  // stack of executed actions, used to avoid endless loops
  protected $executed = array();
  
  public function __construct($table, $scheme=null, $parent=null) 
  {
	$this->scheme = $scheme;
  	$this->setParent($parent);
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
  
  public function setCurrentRecordId($id) {
  	$this->currentRecordId = $id;
  	$_SESSION[table][$this->name][currentRecordId] = $this->currentRecordId;
  }

  public function getCurrentRecord($id=null) 
  {
  	// store currentRecord values in lastRecord
  	if ($this->currentRecord) {
      foreach ($this->currentRecord as $fieldName=>$value) {
  	    $this->lastRecord[$fieldName] = $value;
  	  }
  	}
  	if (!is_null($id)) $this->setCurrentRecordId($id);
  	if ($this->currentRecordId>0) {
  	  // load currentRecord from database
	    if ($result = myQuery($this->buildSQL($this->currentRecordId))) {
	      $this->currentRecord = mysql_fetch_assoc($result);
  	  }
  	}
	  return $this->currentRecord;
  }  
  
  public function getCurrentRecordId() {
  	return $this->currentRecordId;
  }
  
  public function getLastRecord() {
    return $this->lastRecord;
  }
  
  public function getNumRecords() 
  {
    // number of records in this table
    if ($result = myQuery($this->buildSQL())) {
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
  }
  
  public function setOrder($order) 
  {
    $cleanOrder = substr($order, strpos($order, ".")+1);
    if ($_SESSION[table][$this->name][order]==$order) { 
      $this->order = $order." DESC"; 
    } else {
  	  $this->order = $order;
    }
    $_SESSION[table][$this->name][order] = $this->order;
  }
  
  public function setParent($parent) {
  	$this->parent = $parent;
  	//$_SESSION[table][$this->name][parentName] = $parent->getName();
  	$this->loadDisplayColumns();
  }
  
  public function unsetParent() {
  	unset($this->parent);
  	$this->loadDisplayColumns();
  }
  
  public function getParent() {
    if ($this->name=="Job") {
      return $this->scheme->getTableByName(jobParent($this->currentRecordId)[RelationRObject]);
    } else
  	  return $this->parent;
  }
  
  public function loadChildren() {
  	$query=
  	  "SELECT RelationRObject".
  	  " FROM Relation".
  	  " WHERE (RelationLObject='".$this->name."')".
  	  " AND (RelationLId=0) AND (RelationRId=0)";
  	if ($dbRes=myQuery($query)) {
  	  while ($dbRow=mysql_fetch_assoc($dbRes)) {
  	  	$parent = $this->scheme->tables[$dbRow[RelationRObject]];
  	  	array_push($this->parents, $parent);
  	  	array_push($parent->children, $this);
  	  }
  	}
  }
  
  public function isChildOf($table) {
  	foreach ($this->parents as $parent) {
  	  if ($parent->getName() == $table->getName()) return true;
  	}
  	return false; 
  }
  
  public function hasChild() {
  	return count($this->children);
  }
  
  public function getParentOfChildId($childTable) {
  	$this->setCurrentRecordId(
 	    getParentId($childTable->getName(), $childTable->getCurrentRecordId(), $this->name)
  	);
  }
  
  public function isSubBrowserOf($table) {
    $trace = $this;
    $path = "/".$this->name;
    while (isset($trace->parent)) {
      if ($trace->parent==$table) return true;
      $trace = $trace->parent;
      $newName = $trace->getName();
      if (strpos($newName, $path)>0) break; 
      $path .= "/".$newName;
    }
    return false;
  }
  
  public function hasStatusField() {
  	return $this->hasStatus();
  }
  
  public function hasStatus() {
    // look for this-"Status" table relation 
    foreach ($this->parents as $parent) {
      if ($parent->getName()=="Status") return true;
    }
  	return false;
  }
  
  public function hasParentStatus() {
  	foreach ($this->scheme->tables as $table) {
  	  if ($this->isChildOf($table) && $table->hasStatus()) {
  		return true;
  	  }
  	}
  	return false;
  }
  
  public function isSelected() {
  	$selectedItem = "Admin";
  	$selectedPath = $selectedItem;
  	while ($sI = $_SESSION[tabControl][$selectedItem][selected]) {
  	  $selectedItem = $sI;
  	  if (strpos($selectedPath, $selectedItem)) break;		// avoid loops
  	  $selectedPath .= "|".$selectedItem;
  	}
  	return strpos($selectedPath, $this->name);
  }
  
  public function go($index) 
  {
    $this->at = $index;
    $_SESSION[table][$this->name][at] = $index;
  }
  
  public function loadFields() 
  {
  	// clear list of fields
  	while (count($this->fields)) array_pop($this->fields);
    // get list of fields for a table from database
    $query = "SHOW COLUMNS FROM ".$this->name;
    $result = myQuery($query);
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
  
  public function loadColumns() 
  {
  	// clear list of columns and foreign_tables
  	while (count($this->columnNames)) { array_pop($this->columnNames); }
  	//foreach ($this->columnNames as $i=>$columnName) unset($this->columnNames[$i]);
  	//foreach ($this->ftNames as $i=>$ftName) unset($this->ftNames[$i]);
  	
  	// load list of columns for query from list of fields
  	foreach ($this->fields as $i=>$field) {
  	  $fieldName = $field->getName();
  	  array_push($this->columnNames, "C.".$fieldName);
  	}
  	// add columns for parents
  	$i = 0;
  	foreach ($this->parents as $parent) {
  	  $ftName = $parent->getName();
  	  $lookupName = gui($ftName, "lookupField", $ftName."Name");
  	  array_push($this->ftNames, $ftName." P".$i);
  	  array_push($this->columnNames, "P".$i.".id".$ftName.
  	  		($ftName==$this->name?" as parentId".$ftName:""));
  	  array_push($this->columnNames, "P".$i.".".$lookupName.
  	  		($ftName==$this->name?" as parent".$lookupName:""));
  	  if ($lookupName=="StatusName") {
  	  	array_push($this->columnNames, "P".$i.".StatusColor");
  	  }
  	  $i++;
  	}
  	if ($this->name=="StatusLog") {
  	  array_push($this->columnNames, "S.StatusType");
  	  array_push($this->columnNames, "S.StatusName");
  	  array_push($this->columnNames, "S.StatusColor");
  	}
  }
  
  public function loadDisplayColumns() 
  {
    if ($dcn=gui($this->name, "displayColumnNames", false)) {
      $this->displayColumnNames = explode(",", $dcn);
      return;
    }
    
  	while (count($this->displayColumnNames)) {
  	  array_pop($this->displayColumnNames);
  	}
  	foreach ($this->fields as $i=>$field) {
  	  $fieldName = $field->getName();
  	  
  	  $continue=false;
  	  switch ($fieldName) {
  	  	case "RelationLId":
  	  	case "RelationLObject": 
  	  	  if ((!isset($this->parent))||($_SESSION[relation]!=1)) {
  	  	  	array_push($this->displayColumnNames, $fieldName);
  	  	  }
  	  	  $continue=true;
  	  	  break;
  	  	case "RelationRId":
  	  	case "RelationRObject": 
  	  	  if ((!isset($this->parent))||($_SESSION[relation]!=2)) {
  	  	  array_push($this->displayColumnNames, $fieldName);
  	  	  }
  	  	  $continue=true;
  	  	  break;
  	  }
  	  if ($continue) continue; // move continue outside of switch to continue to next foreach iteration 
  	  
  	  if (!isset($this->parent) ||  
  	  	   ( 
  		     ($fieldName!="StatusLogRowId")
  		     &&($fieldName!="NoteTable")
  	  	   	 &&($fieldName!="NoteRowId")
  	  	   	 &&($fieldName!="RelationType")
  		     &&($fieldName!="RelationLType")
  		     &&($fieldName!="RelationRType")
  	  	   )
  		 ) {
  		if ($fieldName!="StatusLog_idStatus") {
  	  	  array_push($this->displayColumnNames, $fieldName);
  		}
  	  }
  	}
  	
  	// add columns for parents
  	foreach ($this->parents as $parent) {
  	  // skip parent browsers lookup
  	  if (!isset($this->parent)||($this->parent!=$parent)) {
    		$ftName = $parent->getName();
    		$lookupName = gui($ftName, "lookupField", $ftName."Name");
    		array_push($this->displayColumnNames, ($ftName==$this->name?"parent":"").$lookupName);
  	  }
  	}
  	if ($this->name=="StatusLog") {
  	  array_push($this->displayColumnNames, "StatusType");
  	  array_push($this->displayColumnNames, "StatusName");
  	}
  	
  	ugi($this->name, "displayColumnNames", implode(",", $this->displayColumnNames));
  }
  
  public function swapColumns($columnA, $columnB) {
    $flip = array_flip($this->displayColumnNames);
    $indexA = $flip[$columnA];
    $indexB = $flip[$columnB];
    $temp = $this->displayColumnNames[$indexA];
    $this->displayColumnNames[$indexA] = $this->displayColumnNames[$indexB];
    $this->displayColumnNames[$indexB] = $temp;
    ugi($this->name, "displayColumnNames", implode(",", $this->displayColumnNames));
  }
  
  public function addColumn($columnName, $displayedName, $dataType, $afterColumn) {
    $query =
      "ALTER TABLE ".$this->name." ".
      "ADD COLUMN $columnName $dataType ".
      "AFTER $afterColumn";
    myQuery($query);
    // add translation text
    ugi($this->name."ORDER".$columnName, $lang, $displayedName);
    // add to displayColumnNames
    $i = count($this->displayColumnNames);
    while (($this->displayColumnNames[$i-1]) != $afterColumn) {
      $this->displayColumnNames[$i] = $this->displayColumnNames[$i-1];
      $i--;
    }
    $this->displayColumnNames[$i] = $columnName;
    ugi($this->name, "displayColumnNames", implode(",", $this->displayColumnNames));
    $this->reload();
  }
  
  public function modifyColumn($columnName, $displayedName, $dataType) {
    $query =
      "ALTER TABLE ".$this->name." ".
      "MODIFY COLUMN $columnName $dataType";
      myQuery($query);
    ugi($this->name."ORDER".$columnName, $lang, $displayedName);
    $this->reload();
  }
  
  public function deleteColumn($columnName) {
    $query =
      "ALTER TABLE ".$this->name." ".
      "DROP COLUMN $columnName";
    myQuery($query);
    // remove from displayColumnNames
    $flip = array_flip($this->displayColumnNames);
    $index = $flip[$columnName];
    while ($index<(count($this->displayColumnNames)-1)) {
      $this->displayColumnNames[$index] = $this->displayColumnNames[$index+1];
      $index++;
    }
    array_pop($this->displayColumnNames);
    ugi($this->name, "displayColumnNames", implode(",", $this->displayColumnNames));
    $this->reload();
  }
  
  public function columnMenu($columnName) {
    if (isset($this->parent)) return "";
    $addButton = new cHtmlDiv("btnAddColumn");
    $addButton->setAttribute("CONTENT", "+ Add column");
    $addButton->setAttribute("onClick", "hide('columnMenu');addColumn(event, '".$this->name."', '$columnName')");
    $lookupButton = new cHtmlDiv("btnAddLookup");
    $lookupButton->setAttribute("CONTENT", "^ Add lookup");
    $lookupButton->setAttribute("onClick", "hide('columnMenu');addLookup(event, '".$this->name."', '$columnName')");
    $alterButton = new cHtmlDiv("btnAlterColumn");
    $alterButton->setAttribute("CONTENT", "* Modify column");
    $alterButton->setAttribute("onClick", "hide('columnMenu');changeColumn(event, '".$this->name."', '$columnName')");
    $moveButton = new cHtmlDiv("btnMoveColumn");
    $moveButton->setAttribute("CONTENT", "< Move ".($_SESSION[column][mode]=="move"?"<strong>".$_SESSION[column][name]."</strong>":"column"));
    $moveButton->setAttribute("onClick", "hide('columnMenu');moveColumn(event, '".$this->name."', '$columnName')");
    $deleteButton = new cHtmlDiv("btnDeleteColumn");
    $deleteButton->setAttribute("CONTENT", "x Delete column");
    $deleteButton->setAttribute("onClick", "hide('columnMenu');deleteColumn(event, '".$this->name."', '$columnName')");
    $closeButton = new cHtmlDiv("btnCloseMenu");
    $closeButton->setAttribute("CONTENT", "o Cancel");
    $closeButton->setAttribute("onClick", "hide('columnMenu');");
    return
        "<strong>$columnName</strong>".
        $addButton->display().
        $lookupButton->display().
        $alterButton->display().
        $moveButton->display().
        $deleteButton->display().
        $closeButton->display();
  }
  
  public function columnEditor($columnName) {
    if ($field = $this->getFieldByName($columnName)) {
      $result = 
        "<table>".
          "<tr><th>Displayed name</th><td><input id='displayedName' value='".gui($this->name."ORDER".$columnName, $GLOBALS[lang], $columnName)."' type=text></td></tr>".
          "<tr><th>Column name</th><td><input readonly id='columnName' value='$columnName' type=text></td></tr>".
          "<tr><th>Table name</th><td>".$this->name."</td></tr>".
          //"<tr><th>Schema</th><td>".$this->scheme->getName()."</td></tr>".
          "<tr><th>Data type</th><td><input id='dataType' value='".$field->getType()."' type=text></td></tr>".
          //"<tr><th>Default expression</th><td><input value='".$field->getExtra()."' id='defaultExpression' type=text></td></tr>".
          "<tr><td><div onClick=\"confirmColumn();\">Ok</div></td><td><div onClick=\"hide('columnMenu');\">Cancel</div></td></tr>".
        "</table>";
    } else {
      $result =
        "<table>".
        "<tr><th>Displayed name</th><td><input id='displayedName' value='' type=text></td></tr>".
        "<tr><th>Column name</th><td><input id='columnName' value='' type=text></td></tr>".
        "<tr><th>Table name</th><td>".$this->name."</td></tr>".
        //"<tr><th>Schema</th><td>".$this->scheme->getName()."</td></tr>".
        "<tr><th>Data type</th><td><input id='dataType' value='' type=text></td></tr>".
        //"<tr><th>Default expression</th><td><input value='".$field->getExtra()."' id='defaultExpression' type=text></td></tr>".
        "<tr><td><div onClick=\"confirmColumn();\">Ok</div></td><td><div onClick=\"hide('columnMenu');\">Cancel</div></td></tr>".
        "</table>";
    }
    return $result;
  }

  public function reload() {
    $this->loadFields();
    $this->loadColumns();
    $this->loadDisplayColumns();
    //$this->setCurrentRecordId(-1);
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
    
    $this->currentRecordId = $_SESSION[table][$this->name][currentRecordId];
    $this->getCurrentRecord();
    
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
  	return "(".$this->parent->getName().".id".$this->parent->getName()." = ".$this->parent->getCurrentRecordId().")";  	
  }

  protected function assignSQL($record) 
  {
	foreach ($record as $fieldName=>$value) {
      // is there a field by this name
	  if ($field=$this->getFieldByName($fieldName)) {
        $assign .= 
          ($assign?", ":"").
	        "$fieldName = \"".addslashes($value)."\"";
	  }
	}
	return $assign;
  }
  
  protected function buildSQL($id=-1) 
  {
  	// lookup values from parent tables
  	$joins=""; 										// joins
  	$i=0;                                     		// relation index
  	foreach ($this->parents as $parent) {
  	  $parentName=$parent->getName();
  	  if (($this->parent)&&($parentName==$this->parent->getName())) {
  	  	$pi=$i;
  	  }
  	  $joins .=
  	    " LEFT OUTER JOIN (".$parentName." P".$i.", Relation R".$i.") ON (".
  	    " (R".$i.".RelationLObject='".$this->name."') AND". 
  	    " (R".$i.".RelationLId=C.id".$this->name.") AND ".
  	    " (R".$i.".RelationRObject='".$parentName."') AND". 
  	    " (R".$i.".RelationRId=P".$i.".id".$parentName."))";
  	  $i++;
  	}
  	
  	if ($this->name=="StatusLog") {
  	  $otherTables .= ", Status S";
  	}
  	
  	$query =
      "SELECT ".implode(", ", $this->columnNames).
  	  " FROM ".$this->name." C ".$otherTables.
  	  $joins.
  	  " WHERE (C.id".$this->name.($id>-1?"=$id":">-1").")".
  	  (isset($this->parent)
  	    &&($this->name!="Relation")
  	    &&($this->name!="Note")
  	    &&($this->name!="StatusLog")
  	    ? " AND (P".$pi.".id".$this->parent->getName()."=".$this->parent->getCurrentRecordId().")"
  	    : ""
  	  ).
  	  ($this->filter && !$this->parent
  		? " AND ".$this->filter
  	  	: ""
  	  ).
  	  
  	  // restrict table StatusLog 
  	  ($this->name=="StatusLog"
  	  	? " AND (StatusLog_idStatus=idStatus)". 
  	  	  (isset($this->parent)
  	  	    ? " AND ".
  	  	      "(StatusType = \"".$this->parent->getName()."\") AND ".
  	  	      "(StatusLogRowId = ".$this->parent->getCurrentRecordId().")"
  	  	  	: ""
  	  	  )
  	  	: ""
  	  ).
  	  // restrict table note 
  	  (($this->name=="Note") && (isset($this->parent))
  	  	? " AND ". 
  	  	  "(NoteTable = \"".$this->parent->getName()."\") AND ".
  	  	  "(NoteRowId = ".$this->parent->getCurrentRecordId().")"
  	  	: ""
  	  ).
  	  // restrict table relation 
  	  (($this->name=="Relation") && (isset($this->parent) && ($_SESSION[relation]==1))
  	  	? " AND ". 
  	  	  "(RelationLObject = \"".$this->parent->getName()."\") AND ".
  	  	  "(RelationLId = ".$this->parent->getCurrentRecordId().")"
  	  	: ""
  	  ).
  	  (($this->name=="Relation") && (isset($this->parent) && ($_SESSION[relation]==2))
  	  	? " AND ". 
  	  	  "(RelationRObject = \"".$this->parent->getName()."\") AND ".
  	  	  "(RelationRId = ".$this->parent->getCurrentRecordId().")"
  	  	: ""
  	  ).
  	  
  	  // set colation order
  	  " ORDER BY ".($this->order ? $this->order : "id".$this->name." DESC");
    return $query;
  }
  
  public function insert($record) {
  	$query = 
  	  "INSERT INTO ".$this->name.
  	  " SET ".$this->assignSQL($record);
  	if ($result = myQuery($query)) {
  	  $this->setCurrentRecordId(mysql_insert_id());
  	  $this->getCurrentRecord();
  	  return $this->currentRecordId;
  	} else {
  	  return false;
  	}
  }
  
  public function update($record) {
  	$query = 
  	  "UPDATE ".$this->name.
  	  " SET ".$this->assignSQL($record).
  	  " WHERE id".$this->name."=".$record["id".$this->name];
  	if ($result = myQuery($query)) {
  	  $this->setCurrentRecordId($record["id".$this->name]);
  		$this->getCurrentRecord();
  		return $this->currentRecordId;
  	} else {
  	  return false;
  	}
  }
  
  public function reservedButton() {
  	$button = new cHtmlInput($this->name."Reserved", "SUBMIT", " ");
  	$button->setAttribute("CLASS", "ReservedButton");
  	$button->setAttribute("DISABLED", "DISABLED");
  	return $button->display();
  }
  
  public function addButton() {
  	$button = new cHtmlSpan($this->name."Insert", "+"); //gui($this->name."Insert", $GLOBALS[lang], $this->name."Insert")
    $button->setAttribute("CLASS", "InsertButton");
    $button->setAttribute(onClick, "ajaxInsert('".$this->name."', '".(isset($this->parent) ? $this->parent->getName() : "")."');stopEvent(event);");
  	return $button->display();
  }

  public function cancelButton() {
  	$button = new cHtmlSpan($this->name."Cancel", "o"); //gui($this->name."Insert", $GLOBALS[lang], $this->name."Insert")
    $button->setAttribute("CLASS", "CancelButton");
    $button->setAttribute(onClick, "CancelEdit('".$this->name."');stopEvent(event);");
  	return $button->display();
  }

  public function manipulator() {
    // display update button
    if (($this->mode == "BROWSE")&&($this->currentRecordId>0)) {
      $button = new cHtmlInput($this->name."Update", "SUBMIT", "o");
      $button->setAttribute("CLASS", "UpdateButton");
      $result .= $button->display();
    }
    // display delete button
    if (($this->mode == "UPDATE")&&($this->currentRecordId>0)) {
      $button = new cHtmlSpan($this->name."Delete", "x");
      $button->setAttribute("CLASS", "DeleteButton");
      $button->setAttribute(
          "onClick", 
          "ajaxDelete('".$this->name."'); stopEvent(event);"
      );
      $result .= $button->display();
    }
    // display ok & cancel buttons
    if (($this->mode == "INSERT") ||
    	($this->mode == "UPDATE") ||
		  ($this->mode == "DELETE")) {
      
  	  $button = new cHtmlSpan($this->name."Ok", "v");
      $button->setAttribute("CLASS", "OkButton");
      $button->setAttribute(
          "onClick", 
          "ajaxPost('".$this->name."', '".(isset($this->parent) ? $this->parent->getName(): "")."'); stopEvent(event);"
      );
      if ($this->mode == "DELETE") {
      	$button->setAttribute("STYLE", "display:block;");
      }
      $result .= $button->display();
      
      $result .= $this->cancelButton();
      
		  // display erase button (confirm delete)
      if (($this->mode == "UPDATE")&&($this->currentRecordId>0)) {
        $button = new cHtmlSpan($this->name."Erase", "x");
        $button->setAttribute("CLASS", "EraseButton");
        $button->setAttribute(
            "onClick", 
            "ajaxErase('".$this->name."'); stopEvent(event);"
        );
        $result .= $button->display();
      }
      
      $anchor = new cHtmlA("");
      $anchor->setAttribute(ID, $this->name.$this->currentRecordId);
      $result .= $anchor->display();
      
      if (!is_null($this->parent)) {
      	$parrentName = gui($this->parent->getName(), "lookupField", $this->parent->getName()."Name");
      	$parentId = new cHtmlInput($this->name.$parrentName, "HIDDEN", $this->parent->getCurrentRecordId());
      	// attribute ID must be unique therefore add prefix to it (NAME attribute remains the same as for parent)
      	$parentId->setAttribute("NAME", $parrentName);
      	$result .= $parentId->display();
      }
      
      if ($_SESSION[relation]) {
      	$hidden = new cHtmlInput("RelationDirection", "HIDDEN", $_SESSION[relation]);
      	$result .= $hidden->display;
      }
    }
    return $result;
  }
  
  protected function commitSQL() {
  	// build SQL for commit
  	switch ($this->mode) {
  	  case "DELETE" :
  		$query = 
  		  "DELETE FROM ".$this->name.
  		  " WHERE id".$this->name."=".$this->currentRecordId; // $_POST["id".$this->name];
  		  break;
  		case "INSERT" :
  		case "UPDATE" :
  		  // assign field values
  		  foreach ($this->fields as $i => $field) {
    			$fieldName = $field->getName();
    			
    			if ($this->name=="Note") {
    			  foreach ($_POST as $name=>$value) {
    			    if ($name == "id".$this->name) continue;
    			    if (strpos($name, "Note")!==0) {
    			  	  $_POST[NoteTable] = iug($name, "lookupField", substr($name,0,-4)); // parent table name 
    			  	  $_POST[NoteRowId] = $value;
    			    }
    			  }
    			}
    			
    			if ($this->name=="Relation") {
    			  $_POST[RelationType] = ($_POST[RelationType] ? $_POST[RelationType] : "RRCP");
    			  foreach ($_POST as $name=>$value) {
    			    if ($name == "id".$this->name) continue;
    			    if (strpos($name, "Relation")!==0) {
    			      $tableName = iug($name, "lookupField", substr($name,0,-4)); 
    			      if (!isset($_POST[RelationLObject])) {
  	  			      $_POST[RelationLObject] = $tableName;
  	  			  	  $_POST[RelationLId] = $value;
    			      }
    			      if (!isset($_POST[RelationRObject])) {
  	  			      $_POST[RelationRObject] = $tableName; 
     			  	    $_POST[RelationRId] = $value;
    			      }
    			    }
    			  }
    			}
    			
    			// skip empty fields 
    			if (($fieldName!=$this->name."Name")&&($_POST[$fieldName]=="")) continue;
    			// skip id and any timestamps
    			if ($fieldName == "id".$this->name) continue;
    			// if ($field->isTimeStamp()) continue;
    			
    			// if value is not set for suggested field
    			if ((gui($fieldName, "type") == "suggest") && ($_POST[$fieldName]==-1)) {
    			  // insert new value to foreign table and get new id
    			  $_POST[$fieldName] = $field->insertForeignKey();
    			}
    			
    			// append assignment of value
    			$assign .= 
    			  ($assign ? ", " : "").
    			  $fieldName." = \"".addslashes($_POST[$fieldName])."\"";
    			
    		} // foreach 
    		  
    	  if ($this->name=="StatusLog") {
    			$q0=
      		  "SELECT idStatus ".
      		  "FROM Status ".
    			  "WHERE (StatusType='".$_POST[StatusType]."') ".
    			  "AND (StatusName='".$_POST[StatusName]."')";
    			if ($dbRes0 = myQuery($q0)) {
    			  if ($dbRow0 = mysql_fetch_assoc($dbRes0)) {
    			   $assign .= ", StatusLog_idStatus=".$dbRow0[idStatus];
    			  }
    			}
  		  }
  		  
  		  // choose SQL keyword depending on mode
  		  switch ($this->mode) {
  			case "INSERT" :
  			  $query = 
  			    "INSERT INTO ".$this->name.
  				($assign ? " SET ".$assign : "");
  			  break;
  			case "UPDATE" :
  			  $query = 
  			    "UPDATE ".$this->name.
  				" SET ".$assign.
  				" WHERE id".$this->name."=".$_POST["id".$this->name];
  			  break;
  		  }
  		break;
  	}
  	return $query;
  }
  
  public function statusHasChanged() {
  	if (!$this->hasStatus()) return false;
  	return 
  	  (($this->mode == "INSERT") && ($this->currentRecord["idStatus"])) ||
  	  (($this->mode == "UPDATE") && ($this->lastRecord["idStatus"] != $this->currentRecord["idStatus"]));
  }
  
  public function whatsUp() {
  	$event = array();
  	switch ($this->mode) {
  	  case "INSERT":
  	  	if ($this->parent) {
  	  	  $event[ActionTable] = $this->parent->getName();
  	  	  $event[ActionCommand] = "CREATE CHILD";
  	  	  $event[ActionParam1] = $this->name;
  	  	} else {
  	  	  $event[ActionTable] = $this->name;
  	  	  $event[ActionCommand] = "CREATE";
  	  	}
  	  	break;
  	  default:
  	  	$event[ActionTable] = $this->name;
  	  	$event[ActionCommand] = $this->mode;
  	  	break;
  	}
  	return $event;
  }
  
  public function getEventId($event) {
  	$query = 
  	  "SELECT idAction FROM Action".
  	  " WHERE (ActionTable=\"".$event[ActionTable]."\")".
  	  " AND (ActionSequence=0)".
  	  " AND (ActionCommand=\"".$event[ActionCommand]."\")".
  	  ($event[ActionField]
  	    ? " AND (ActionField=\"".$event[ActionField]."\")"
  	  	: ""
  	  ).
  	  ($event[ActionParam1]
  	    ? " AND (ActionParam1=\"".$event[ActionParam1]."\")"
  	  	: ""
  	  );
    return 
      (($dbRes=myQuery($query)) && ($dbRow=mysql_fetch_assoc($dbRes))
        ? $dbRow[idAction]
      	: 0
      );
  }
  
  public function hasExecuted($action) {
  	// search for $action in list of $executed 
  	foreach ($this->executed as $event) {
  	  if (($action[ActionTable]==$event[ActionTable]) 
  	  	&& ($action[ActionCommand]==$event[ActionCommand])
  	  	&& (!isset($action[ActionParam1]) || ($action[ActionParam1]==$event[ActionParam1]))) 
  	  	return true;
  	}
  	return false;
  }
  
  public function logAction($action) {
  	$id = (($action[ActionCommand]=="DELETE")
  	  ? $this->lastRecord["id".$this->name]
  	  : $this->currentRecordId
    );
  	$query =
  	  "INSERT INTO History SET ".
  	  "HistoryTable='".$this->name."', ".
  	  "HistoryRowId=".$id.", ".
  	  "HistoryCommand='".$action[ActionCommand]."', ".
  	  "HistorySQL='".addslashes(implode("|", $action))."'";
  	myQuery($query);
  }
  
  public function handleEvent($event) {
    //X-recursive with execAction
  	  //echo "handleEvent(".implode("|", $event).")<br>";
    // search for specific event (including parameters) in Actions table
    if ($eid = $this->getEventId($event)) {
      array_push($this->executed, $event);    // must be done prior to x-recursion 
      // if found get:execute:handle actions in sequence
      $query = 
        "SELECT * FROM Action ".
        "WHERE idAction in (".
          "SELECT RelationLId FROM Relation ".
          "WHERE (RelationType=\"RRCP\") ".
            "AND (RelationLObject=\"Action\") ".
            "AND (RelationRObject=\"Action\") ".
            "AND (RelationRId=$eid)".
        ") ".
        "ORDER BY ActionSequence ASC";
      if ($dbRes=myQuery($query)) {
      	while ($action=mysql_fetch_assoc($dbRes)) {
      	  if (!$this->hasExecuted($action)) {
      	  	if (!$this->execAction($action)) break;
      	  }
      	}
      }
    }
  }
  
  public function execAction($action) {
    //X-recursive with handleEvent()
  	  //echo "execAction(".implode("|", $action).")<br>";
    $targetTable = $this; // if not said other way later
  	switch ($action[ActionCommand]) {
  	  case "SET STATUS":
  	  	// climb up for target table (and recordId) 
  	  	while (($action[ActionTable]!=$targetTable->getName())&& !is_null($targetTable->getParent())) {
  	  	  $targetTable->getParent()->getParentOfChildId($targetTable);
  	  	  $targetTable = $targetTable->getParent();
  	  	}
  	  	// target table is found
  	  	if ($action[ActionTable]==$targetTable->getName()) {
  	  	  // update relation to status
  	  	  updateStatus($action[ActionTable], $targetTable->getCurrentRecordId(), $action[ActionParam1]);
  	    }
  	    break;
  	  case "CREATE":
  	    $targetTable = $this->scheme->getTableByName($action[ActionTable]);
  	    // prepare new record
  	    switch ($action[ActionTable]) {
  	    	case "Job":
  	    	  // nothing special here so far
  	    	default:
  	    	  $record = array();
  	    	  $record[$action[ActionTable]."Name"] = $action[ActionTable]." created by LiCyT Automation";
  	    }
  	    // insert record
  	    $idTarget = $targetTable->insert($record);
  	    // add relations 
  	    switch ($action[ActionTable]) {
  	    	case "Job":
  	    	  // job is related to task which has come as the first action parameter 
  	    	  insertRRCP("Job", $idTarget, "Task", $action[ActionParam1]);
  	    	default:
  	    	  // new record is created as a child of the current record of this table
  	    	  if ($this->name != "Job") {
  	    	    insertRRCP($action[ActionTable], $idTarget, $this->name, $this->currentRecordId);
  	    	  }
  	    }
  	    break;
  	}
	  //$targetTable->getCurrentRecord($idTarget); // refresh
	  $this->logAction($action);
  	$targetTable->logStatus();
	  $targetTable->handleEvent($action);
	  
	  // Job post- processing
	  if (($action[ActionTable]=="Job")) {
	    switch ($action[ActionCommand]) {
	      case "SET STATUS":
	        $parentJobId = getParentId("Job", $this->currentRecordId, "Job");
	        if ($parentJobId>0) {
  	        $status = $action[ActionParam1];
  	        if (flagsAreSet($status, 1)) { // bit0 - terminal status (ends the job)
    	        if (flagsAreSet($status, 2)) { // bit1 - success, continue to next job in sequence 
      	        if ($nextTask = getNextTask(getParentId("Job", $this->currentRecordId, "Task"))) {
        	        // create next job in sequence and set its relation to parent
        	        $action[ActionTable] = "Job";
        	        $action[ActionCommand] = "CREATE";
      	          $action[ActionParam1] = $nextTask[idTask];    // set task for next child job
      	          $idSubJob = $this->execAction($action);
        	        insertRRCP("Job", $idSubJob, "Job", $parentJobId);
        	        copyJobTarget($parentJobId, $idSubJob);
      	        } else { // this was the last job in sequence
      	          // promote successful status to parent
      	          $this->setCurrentRecordId($parentJobId);
      	          $action[ActionTable] = "Job";
      	          $action[ActionField] = getParentId("Job", $this->currentRecordId, "Task");
      	          $action[ActionCommand] = "SET STATUS";
      	          $action[ActionParam1] = $status;
      	          $this->execAction($action);
      	        }
    	        } else { // failed job, do not continue to next job 
    	          // promote failed status to parent
    	          $this->setCurrentRecordId($parentJobId);
    	          $action[ActionTable] = "Job";
    	          $action[ActionField] = getParentId("Job", $this->currentRecordId, "Task");
    	          $action[ActionCommand] = "SET STATUS";
    	          $action[ActionParam1] = $status;
    	          $this->execAction($action);
    	        }
  	        }
	        }
	        break;
	      case "CREATE":
	        // process tasks hierarchy - create child job(s)
	    	  if ($subTask = getSubTask($action[ActionParam1])) {
    	      $action[ActionParam1] = $subTask[idTask];    // set task for child job
    	      // create child job and set its relation to parent job
    	      $idSubJob = $this->execAction($action);
    	      insertRRCP("Job", $idSubJob, "Job", $idTarget);  
    	      copyJobTarget($idTarget, $idSubJob);
    	    }
    	    break;
	    }
	  }
	  return $targetTable->getCurrentRecordId(); // done
  }
  
  protected function logStatus() {
  	myQuery(
      "INSERT INTO StatusLog SET ".
  	  "StatusLogRowId=".$this->currentRecord["id".$this->name].", ".
  	  "StatusLog_idStatus=".$this->currentRecord["idStatus"]  	
  	);
  }
  
  protected function updateRelations() {
  	// update relations on commit for current record
  	// iterate through all parent tables
  	foreach ($this->parents as $parent) {
  	  $parentName = $parent->getName();
  	  $oldParentId = getParentId($this->name, $this->currentRecordId, $parentName);
  	  $lookupField = gui($parentName, "lookupField", $parentName."Name");
  	  $lookupSelf = "";
  	  if (gui($lookupField, "lookupType")=="suggest") {
        $newParentId = $_POST[$this->name."Id".$parentName];
  	  } elseif ($parentName==$this->name) {
  	    $lookupSelf = "parent"; 
  	  	$newParentId = $_POST[$lookupSelf.$lookupField];
  	  } else {
  	    $newParentId = $_POST[$lookupField]; 
  	  }
  	  if (($newParentId == -1) && ($_POST[$lookupSelf.$lookupField]!="")) {
  	  	// non existent parent value
  	  	// INSERT new value into parent table
  	  	if (myQuery("INSERT INTO $parentName SET $lookupField='".$_POST[$lookupSelf.$lookupField]."'")) {
  	  	  $newParentId = mysql_insert_id();
  	  	}
  	  }
  	  if (($oldParentId==-1)&&($newParentId>0)) {
  	  	// non existing relation
  	  	// INSERT new relation
  	  	insertRRCP($this->name, $this->currentRecordId, $parentName, $newParentId);
  	  } elseif ($oldParentId!=$newParentId) {
  	  	// parent has changed
  	    // UPDATE relation
  	    myQuery( 
    	  "UPDATE Relation SET RelationRId=".$newParentId.
    	  " WHERE (RelationType='RRCP')".
  	    " AND (RelationLObject='".$this->name."')".
    	  " AND (RelationLId=".$this->currentRecordId.") ".
    	  " AND (RelationRObject='$parentName')"
  	  	);
  	  }
  	}
  }
  
  protected function deleteRelations() {
  	// delete relations on commit for current record
  	myQuery(
  	  "DELETE FROM Relation ".
  	  "WHERE (RelationType='RRCP') ". 
  		"AND (((RelationLObject='".$this->name."') AND (RelationLId=".$this->currentRecordId.")) ".
  	       "OR ((RelationRObject='".$this->name."') AND (RelationRId=".$this->currentRecordId.")))"    
   	);
  }
  
  public function commit() {
  	// save changes - execute SQL
  	if ($result = myQuery($this->commitSQL())) {
  	  switch ($this->mode) {
  	  	case "INSERT":
  	  	  $this->setCurrentRecordId(mysql_insert_id());
  	  	  //$this->getCurrentRecord();
  	  	case "UPDATE": 
  	  	  $this->updateRelations();
  	  	  break;
  	  	case "DELETE":
  	  	  $this->deleteRelations();
  	  	  $this->setCurrentRecordId(-1);
  	  	  break;
  	  }
  	  $this->getCurrentRecord();
  	  
  	  // ---------------------------------------------------------- event->action processing
  	  if ($event = $this->whatsUp()) {
  	    $this->logAction($event);
  	  	// clear $executed 
  	  	foreach ($this->executed as $i=>$action) {
  	  	  unset($this->executed[$i]);
  	  	}
  	  	// handle event created by executed action
  	  	$this->handleEvent($event);
  	  	// additionally handle eventual status change event
  	  	if ($this->statusHasChanged()) {
  	      $event[ActionTable] = $this->name;
  	  	  if ($this->name == "Job") {
  	  	    $event[ActionField] = getParentId("Job", $this->currentRecordId, "Task");
  	  	  }
  	  	  $event[ActionCommand] = "SET STATUS";
  	  	  $event[ActionParam1] = $this->currentRecord["idStatus"];
  	  	  $this->execAction($event);
  	  	}
  	  	switch ($event[ActionCommand]) {
  	  	  case "CREATE CHILD":
  	  	  	// CREATE CHILD event also triggers CREATE event for child table ??
  	  	  	$action[ActionTable]=$event[ActionParam1];
  	  	  	$action[ActionCommand]="CREATE";
  	  	  	$this->handleEvent($action); // action has been already executed, we only need to handle it
  	  	  	break;
  	  	}
  	  }
  	  
  	  // return to BROWSE mode
  	  $this->setMode("BROWSE");
  	}
  }
  
  public function respondToPost() 
  {
    // get currentRecordId 
    if (isset($_POST["id".$this->name])) {
      //$this->setMode("BROWSE");
      $this->setCurrentRecordId($_POST["id".$this->name]);
    } elseif (isset($_POST["subid".$this->name])) {
      $this->setCurrentRecordId($_POST["subid".$this->name]);
    } elseif ($_SESSION[table][$this->name][currentRecordId]) {
    	$this->setCurrentRecordId($_SESSION[table][$this->name][currentRecordId]);
    }
    
    if ($_POST["RelationDirection"]) {
      $_SESSION[relation] = $_POST["RelationDirection"]; 
    }
    
    // # button - collapse tree 
    if (isset($_POST[$this->name."ORDERid".$this->name])) {
    	$this->setCurrentRecordId(-1);
    	$this->setMode(BROWSE); 
    }
    // data manipulation buttons
    else if ($_POST[$this->name."Insert"]) {														// + Add
    	$this->setMode("INSERT");
    	$this->setCurrentRecordId(-1);
    }
    elseif ($_POST[$this->name."Update"]) { $this->setMode("UPDATE"); }						// * Edit
    elseif ($_POST[$this->name."Delete"]) { $this->setMode("DELETE");}  					// x Del
    elseif ($_POST[$this->name."Ok"]){ 	// Ok
      if ($this->scheme->getStatus()=="initialized") {
                                                                        //------------------      	
      	$this->commit();												//    C O M M I T 
      	                                                                //------------------
      }
    }
    else { $this->setMode("BROWSE"); }							     	// Cancel
    
  	// load order and filter stored in session
    $this->filter = "";
  	$this->order = $_SESSION[table][$this->name][order];
  	// column namess
  	foreach ($this->columnNames as $i=>$columnName) {
  	  $cleanColumnName = substr($columnName, strpos($columnName, ".")+1);
  	  // collation order
  	  if ($_POST[$this->name."ORDER".$cleanColumnName]!="") {
  	  	$this->setOrder($columnName);
  	  }
  	  // filter
  	  if (isset($_POST[$this->name."FILTER".$cleanColumnName])) {
  		  $_SESSION[table][$this->name]["FILTER"][$cleanColumnName] = $_POST[$this->name."FILTER".$cleanColumnName];
  	  }
  	  if ($_SESSION[table][$this->name]["FILTER"][$cleanColumnName]) {
  		  $this->filter .= 
  		    ($this->filter ? " AND " : "").
  		    "($columnName LIKE \"".$_SESSION[table][$this->name]["FILTER"][$cleanColumnName]."%\")";
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

  public function editColumns($id=0)
  {
  	// display fields as controls in a row of a html table
  	$result = array();
  	foreach ($this->displayColumnNames as $i => $columnName) {
  	  // skip some special columns for notes and relations when they are displayed as subbrowser
  	  if ((($columnName=="NoteTable") || ($columnName=="NoteRowId")) && (isset($this->parent)))
  	  	continue;
  	  if ((($columnName=="RelationLObject") || ($columnName=="RelationLId")) 
  	  	&& (isset($this->parent) && ($_SESSION[relation]==1)))
  	  	continue;
  	  if ((($columnName=="RelationRObject") || ($columnName=="RelationRId"))
  	  	&& (isset($this->parent) && ($_SESSION[relation]==2)))
  	  	continue;
  	  	
  	  if ($this->mode=="UPDATE") {
  	    // action editor
  	  	if ($columnName=="ActionField") {
      	  $table = $this->scheme->tables[$this->currentRecord[ActionTable]];
      	  $result[$columnName] = fieldsForAction($table, $this->currentRecord[ActionField])->display();
      	  continue;
      	}
      	if ($columnName=="ActionCommand") {
      	  $table = $this->scheme->tables[$this->currentRecord[ActionTable]];
  	      $result[$columnName] = commandsForAction($table, $this->currentRecord[ActionCommand])->display();
          continue;
  	    }
  	    if ($columnName=="ActionParam1") {
  	      $table = $this->scheme->tables[$this->currentRecord[ActionTable]];
  	      $params = loadParameters($table, $this->currentRecord[ActionCommand], $this->currentRecord[ActionParam1]);
  	      if ($params[1]) $result[ActionParam1] = $params[1]->display();
  	  	  continue;
  	    }
  	    // relation editor
  	    if ($columnName=="RelationRId") {
  	      $result[$columnName] = loadRightRows($this->currentRecord[RelationRObject], $this->currentRecord[RelationRId]);
  	      continue;
  	    }
  	    if ($columnName=="RelationLId") {
  	      $result[$columnName] = loadLeftRows($this->currentRecord[RelationLObject], $this->currentRecord[RelationLId]);
  	      continue;
  	    }
    	}
  	  	
  	  // get html control for field
  	  if ($field = $this->getFieldByName($columnName)) {
  	  	$html = $field->getHtmlControl($this->currentRecord[$columnName], $this->mode=="BROWSE");
  	  	if (($columnName=="StatusLogRowId")&&!is_null($this->parent)) {
  	  	  	
  	  	} else {
  	        $result[$columnName] = $html;
  	  	}
  	  } else {
  	  	// this column is a lookup field from another table
  	  	$parentColumnName = $columnName;
  	  	if (strpos($columnName, "parent")===0) {
  	  	  $parentColumnName=substr($columnName,6);
  	  	} 
  	  	$lookupTableName=iug($parentColumnName, "lookupField", substr($parentColumnName, 0, -4)); // -4 (default): remove "Name"  from the end of the string
  	  	if ($foreignField = $this->scheme->tables[$lookupTableName]->getFieldByName($parentColumnName)) {
  	  	  $parentId = getParentId($this->name, $this->currentRecordId, $lookupTableName);
  	  	  $result[$columnName] = $foreignField->getLookupControl($this, $parentId);
  	  	} else {
  	  	  $result[$columnName] = "";
  	  	}
  	  }
  	  
   	  if ($columnName=="id".$this->name) {
  	    $result[$columnName] = $this->manipulator();
   	  }
  	}
  	$result["CLASS"] = $this->mode;
  	$result["onKeyPress"] = "if (event && event.keyCode==13) {elementById('".$this->name."Ok').click();}";
  	$result["onKeyPress"].= "if (event && event.keyCode==27) {elementById('".$this->name."Cancel').click();}";
  	$sbName = $this->name."Sb".$id;
  	$result[onClick] = "toggleDisplay('$sbName');";
  	return $result;
  }
  
  public function detailForm()
  {
    $form = new cHtmlForm();
    $form->setAttribute("ID", "detailForm".$this->name);
    $form->setAttribute("ACTION", "");
    $form->setAttribute("METHOD", "POST");
    $form->setAttribute("CONTENT", 
      gui($this->name, $GLOBALS[lang], $this->name)." [".$this->at."/".$this->count."] ".$this->mode.br().
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
      	//$newNames["id".$this->name] = $this->addButton();
      	break;
    }
    return $newNames;
  }
  
  protected function orderSet(array $columnNames, $setName="") 
  {
    $buttons = array();
    $add = $this->addButton();
    foreach ($columnNames as $i=>$buttonName) {
      $button  = new cHtmlInput($setName.$buttonName, "SUBMIT", gui($setName.$buttonName, $GLOBALS[lang], $buttonName));
      $button->setAttribute("CLASS", $this->name."OrderButton");
      $buttons[$i]=$button->display().$add;
      $add="";
    }
    return $buttons;	
  }

  protected function filterSet(array $columnNames, $setName="", $values)
  {
    $result = array();
    foreach ($columnNames as $i=>$columnName) {
  	  if ($columnName=="id".$this->name) {
  	    $result[$i]="";
  	  } else {
	    $filter  = new cHtmlInput($setName.$columnName, "TEXT", $values[$columnName]);
	    // autofire form submit
	    $filter->setAttribute("onChange", "this.form.submit()");
	    $filter->setAttribute("CLASS", "filter");
	    if ($field = $this->getFieldByName($columnName)) {
	      $filter->setAttribute("SIZE", $field->getSize());
	    }
	    $result[$i]=$filter->display();
  	  }
    }
    //$result[0]="";
    return $result;
  }
  
  public function lumpChildren() {
    foreach ($this->scheme->tables as $table) {
      $tableName = $table->getName();
      //if ($tableName==$this->name) continue;
      /*
      if ($_SESSION[table][$tableName][parentName]==$this->name) {
        $table->setParent($this);
      } else
      */
      if ($table->isChildOf($this)) {
        if (!isset($this->parent) || ($this->parent->getName()!=$this->name)) {
          $table->setParent($this);
          $table->preProcess();
          if (isset($this->parent) && ($this->parent->getName()==$this->name)) {
            $table->setParent(null);
          }
        }
      }
      if (($tableName=="Note")) $table->setParent($this);
      if (($tableName=="Relation")) $table->setParent($this);
      if (($tableName=="StatusLog")) $table->setParent($this);
    }
  }
  
  public function preProcess() {
    if ($this->preprocessed) return; 
    
  	$this->loadSession();
  	$this->respondToPost();
  	$this->getNumRecords();
  	$this->preprocessed = true;
  	
  	if ($this->name=="Note") return;
  	if ($this->name=="Relation") return;
  	if ($this->name=="StatusLog") return;
  	
  	$this->lumpChildren();
  }
  
  public function gantt($dbRow) {
    $sG = new statusGantt();
    $sG->iFrom = "2016-04-14";
    $sG->iTill = "2016-07-21";
    $sG->statusType = $this->name;
    $sG->statusLogRowId = $this->currentRecordId;
    $sG->loadLanes();
    return $sG->display();
  }
  
  public function displayRow($id, $dbRow=null) {
    // --- special lookup for Action/Event
    if ($this->name=="Action") {
      // replace idStatus  with StatusName
      if ($dbRow[ActionCommand]=="SET STATUS") {
        $query="SELECT StatusName FROM Status WHERE idStatus=".$dbRow[ActionParam1];
        if (($dbRes2=myQuery($query))&&($dbRow2=mysql_fetch_assoc($dbRes2))) {
          $dbRow[ActionParam1]=$dbRow2[StatusName];
        }
      }
    }
    
    // --- Relation - load lookup value
    if ($this->name=="Relation") {
      switch ($_SESSION[relation]) {
        case 1:
          $lookupName = gui($dbRow[RelationRObject], "lookupField", $dbRow[RelationRObject]."Name");
          $q2 =
          "SELECT $lookupName".
          " FROM ".$dbRow[RelationRObject].
          " WHERE id".$dbRow[RelationRObject]."=".$dbRow[RelationRId];
          if ($dbr2=myQuery($q2)) {
            if ($r2=mysql_fetch_assoc($dbr2)) {
              $dbRow[RelationRId]=$r2[$lookupName];
            }
          }
          break;
        case 2:
          $lookupName = gui($dbRow[RelationLObject], "lookupField", $dbRow[RelationLObject]."Name");
          $q2 =
          "SELECT $lookupName".
          " FROM ".$dbRow[RelationLObject].
          " WHERE id".$dbRow[RelationLObject]."=".$dbRow[RelationLId];
          if ($dbr2=myQuery($q2)) {
            if ($r2=mysql_fetch_assoc($dbr2)) {
              $dbRow[RelationLId]=$r2[$lookupName];
            }
          }
          break;
      } // switch
    } // if
    
    // display only columns included in displayColumnNames
    foreach ($this->displayColumnNames as $dcn) {
      switch ($dcn) {
        case "StatusName":
          $displayRow[$dcn] =
          "<div style=\"color:".(RGBToHSL(HTMLToRGB($dbRow[StatusColor]))->lightness>128?"black":"white").
          ";background-color:#".$dbRow[StatusColor]."\">".
          $dbRow[$dcn].
          "</div>";
          break;
        default:
          $displayRow[$dcn] = $dbRow[$dcn];
      }
    }
    // jump to related record button 
    if ($this->name=="Relation") {
      $button = new cHtmlSpan("GoRelation".$id, ">");
      $button->setAttribute("CLASS", "GoButton");
      $js=
        "jumpToRow($id, ".$_SESSION[relation].");".
        "stopEvent(event);";
      $button->setAttribute("onClick", $js);
      $displayRow["idRelation"] = $button->display();
    } else {
      $displayRow["id".$this->name] = "";
    }
    
    if (isset($this->parent)&&($this->name=="StatusLog")) {
      unset($displayRow["StatusLogRowId"]);
    }
    // add javascript to onClick event of this row
    $js=
      "loadRow(".
        "'".(isset($this->parent) ? $this->parent->getName() : "")."', ".
        "'".$this->name."', ".
        $id.
      ");";
    $displayRow[onClick] = $js;
    return $displayRow;
  }
 
  public function browse($include="") {
    $sub = (isset($this->parent)&&($this->parent->getName()==$this->name)?"sub":"");
    if ($this->mode!="INSERT") $this->getCurrentRecord();
  	// create output as html table
	  $table = new cHtmlTable($this);
  	if (($this->name=="Status")&&!isset($this->parent)) {
  	  $table->setAttribute("StatusEdit", true);
  	}
  	$table->addHeader($this->orderSet($this->displayColumnNames, $this->name."ORDER"));
  	if (($this->name!="StatusLog") && ($this->name!="History") && ($this->mode=="INSERT")) {
  	  $table->addRow($this->name."InsertRow", $this->insertRow());
  	}
  	// add filter only for master browser
  	if (!isset($this->parent)) {
  	  $table->addFooter($this->filterSet($this->displayColumnNames, $this->name."FILTER", $_SESSION[table][$this->name][FILTER]));
  	}
  	// run query on database 
  	if ($dbResult = myQuery($this->buildSQL())) {
  	  $i = 0;
  	  while ($dbRow = mysql_fetch_array($dbResult,MYSQL_ASSOC))	{
  	  	$id = $dbRow["id".$this->name];
  	  	$i++;
  	  	if ($id==$this->currentRecordId) {
  	  	  
          // --------------------------------------------------------- current record is editable
          //$this->currentRecord = $dbRow;
  	  	  $table->addRow($this->name."Row".$id, $this->editColumns($id));
  	  	  // sub-data for the current record
  	  	  if (($this->name != "Note")&&($this->name != "Relation")&&($this->name != "StatusLog")) { 
  	  	    // gantt
  	  	  	if ($this->hasStatus()) {
  	  	    	//$table->addRow($this->name."Gannt".$id, $this->gantt($dbRow));
  	  	    }
  	  	    // sub-browsers
  	  	    if (!isset($this->parent) || ($this->name != $this->parent->getName())) {
  	  	      $sbRow["sbIndent"]="";
  	  	      $sbRow["sbColSpan"]=sizeof($dbRow)-1;
  	  	      $sbRow["subBrowser"] = $this->subBrowsers();
  	  	      $table->addRow($this->name."Sb".$id, $sbRow);
  	  	    }
  	  	  }
  	  	} else {
  	  	  
  	  	  // ------------------------------------------------------------------------ other records
    		  // add row to table
    		  $table->addRow($this->name."Row".$id, $this->displayRow($id, $dbRow));
    	  }
    	}
      mysql_free_result($dbResult);
    }
	
	  $RowId = new cHtmlInput($sub."id".$this->name, "HIDDEN", $this->currentRecordId);
	  // include table in form
    $form = new cHtmlForm();
    $form->setAttribute("ID", $sub."browseForm".$this->name);
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
  	$browsers = new cHtmlTabControl($this->name);
  	
  	foreach ($this->scheme->tables as $table) {
  	  $tableName = $table->getName();
  	  if (isset($_REQUEST[switchTab]) && ($tableName == $_REQUEST[tableName]) && ($_REQUEST[tabName])) {
  	    $browsers->setSelected($_REQUEST[tabName]);
  	  } elseif ($_POST["tabButton".$browsers->getName().$tableName]) {
		    $browsers->setSelected($tableName);
	    } elseif ($_POST["tabButton".$browsers->getName()."RelationLeft"]) {
    		$browsers->setSelected("RelationLeft");
	    } elseif ($_POST["tabButton".$browsers->getName()."RelationRight"]) {
		    $browsers->setSelected("RelationRight");
	    }
  	}
	    
  	foreach ($this->scheme->tables as $table) {
  	  $tableName = $table->getName();
  	  if ($table->isChildOf($this)) {
  	  	$table->setParent($this);
  	  	$browsers->addTab(
  	  	  $tableName, 
  	  	  ($table->isSelected()
  	  	    ? $table->browse()
  	  	  	: ""
  	  	  )
  	  	); 
  	  	if ($table->getName()==$this->name) {
  	  	  $this->parent=null;
  	  	}
  	  }
  	}
  	
  	// relations for current record
   	$ftable = $this->scheme->getTableByName("Relation");
  	$ftable->setParent($this);
  	//$ftable->setRelation(1); // this table on the left side of the relation
  	$ftable->loadDisplayColumns();
  	$browsers->addTab("RelationLeft", $ftable->browse());
  	//$ftable->setRelation(2); // this table on the right side of the relation
  	//$ftable->loadDisplayColumns();
  	$browsers->addTab("RelationRight", $ftable->browse());
  	unset($ftable);
  	
  	// notes for current record
  	$ftable = $this->scheme->tables["Note"];
  	$ftable->setParent($this);
  	$browsers->addTab("Note", $ftable->browse());
  	unset($ftable);
  	
  	// history of statuses for current record
  	if ($this->hasStatusField()) {
  	  $ftable = $this->scheme->tables["StatusLog"];
  	  $ftable->setParent($this);
      $browsers->addTab("Status", $ftable->browse());
  	  unset($ftable);
  	}
  	
  	return $browsers->display();
  }
  
}

//implement the interface iDbScheme
class cDbScheme implements iDbScheme 
{
  protected $name;
  protected $status = "undefined";
  protected $dbLink;
  public $tables = array();
  public $style;
  
  // create link to MySQL database 
  public function link ($dbServerName, $dbUser, $dbPassword)
  {
    if ($this->dbLink = mysql_connect($dbServerName, $dbUser, $dbPassword)) 
    {
      $_SESSION[dbLink] = $this->dbLink;
  	  $this->status = "connected";
    } 
    else echo 'Not connected : ' . mysql_error();
  }
  
  public function getName() {
    return $this->name;
  }
  
  public function setName($dbName) {
    $this->useDb($dbName);
  }
  
  // select database schema to work with
  public function useDb ($dbName)
  {
  	if (mysql_select_db($dbName, $this->dbLink)) 
    {
      $this->name = $dbName;
      loadGUI();
      // clear old tables
      foreach ($this->tables as $name=>$table) {
        unset($this->tables[$name]);
      }
      // load all tables
      $query = "SHOW TABLES";
      if ($result = myQuery($query)) {
        while ($row = mysql_fetch_array($result)) {
          $tableName=$row["Tables_in_$dbName"];
          $table = new cDbTable($tableName, $this);
          // register all table objects in tables property of this object
          $this->tables[$tableName] = $table;
        }
      } 
      // initialize children
      foreach ($this->tables as $table) {
      	$table->loadChildren();
      }
      $this->status = "initialized";
	    $this->setup();
    }
  }
  
  public function getStatus() {
  	return $this->status;
  }
  
  public function getTableByName($tableName) {
    foreach ($this->tables as $table) {
      if ($table->getName()==$tableName) return $table;
    }
    return null;
  }

  public function setup() {
    /*
    if ($_POST["GoRelation1"]) {
      $query =
      "SELECT RelationRObject, RelationRId".
      " FROM Relation".
      " WHERE idRelation=".$_POST[idRelation];
      if ($Relation = mysql_fetch_assoc(myQuery($query))) {
        $_SESSION[tabControl][Admin][selected] = $Relation[RelationRObject];
        $this->tables[$Relation[RelationRObject]]->setCurrentRecordId($Relation[RelationRId]);
      }
    } elseif ($_POST["GoRelation2"]) {
      $query =
      "SELECT RelationLObject, RelationLId".
      " FROM Relation".
      " WHERE idRelation=".$_POST[idRelation];
      if ($Relation = mysql_fetch_assoc(myQuery($query))) {
        $_SESSION[tabControl][Admin][selected] = $Relation[RelationLObject];
        $this->tables[$Relation[RelationLObject]]->setCurrentRecordId($Relation[RelationLId]);
      }
    } else {
    */
      foreach ($this->tables as $name=>$table) {
        // check POST for any admin button
        if ($_POST["tabButtonAdmin".$name]) {
          // store selected table in session
          $_SESSION[tabControl][Admin][selected] = $name;
        }
      }
    /*
    }
    */
    
    foreach ($this->tables as $name=>$table) {
      $table->loadColumns();
    }
     
    if ($selectedTable = $this->tables[$_SESSION[tabControl][Admin][selected]]) {
      $selectedTable->preProcess();
      $this->tables[Note]->preProcess();
      $this->tables[Relation]->preProcess();
    }
     
    $this->style = "";
    foreach ($this->tables as $name=>$table) {
      $table->loadDisplayColumns();
      $this->style .= 
        str_replace(
          "<color>", 
          gui($name, "color", "lightgray"),
          str_replace(
            "<table>",
            $name, 
            file_get_contents("./css/table.css.model")
          )
        );
    }
  }
  
  public function admin() 
  {
	// display all tables from scheme as tabs
  $tableTabs = new cHtmlTabControl($dbName."Admin");
  foreach ($this->tables as $name=>$table) {
		$tableTabs->addTab(
        $name, 
        ($_SESSION[tabControl][Admin][selected] == $name
          // browse selected table 
	      ? $table->browse()
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


