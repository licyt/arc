<?php
// ------------------------------------------------------ C O N S T A N T S

$dbServerName = 'wendelstein';
$dbUser       = 'root';
$dbPassword   = 'mindfold';
$dbName       = 'hmat';

// ------------------------------------------------------ I N T E R F A C E
// Declare the interface iDbField
interface iDbField
{
  public function setProperties($properties); 
  public function isForeignKey();
  public function getHtmlControl();
}

// Declare the interface iDbTable
interface iDbTable
{
  public function setName($name);
  public function printFields();
}

// -------------------------------------------  I M P L E M E N T A T I O N
// Implement the interface iDbField
class cDbField implements iDbField
{
  private $properties = array();
  
  public function setProperties($properties) 
  {         
    // import properties from an array
    foreach ($properties as $name => $value) {
      $this->properties[$name]=$value;
    }
  }
  
  public function isForeignKey() {
    // PK is a FK if fields name concontains "_id" 
    return 
      (strpos($this->properties[Field],"_id")>0) &&
      ($this->properties[Key]=="PRI");
  }

  public function getHtmlControl()
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
    private $name;
    private $fields = array();                     
  
    public function setName($name)
    {
      // set table name 
      $this->name = $name;
      // clear past list of fields 
      foreach ($this->fields as $i => $value) {
          unset($this->fields[$i]);
      }      
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
          $field = new cDbField;
          $field->setProperties($column);
          // push this new field into array of fields of this object
          array_push($this->fields, $field);
        }
      }
    }
    
    public function printFields() {
      // display each field as a html control on a separate line
      foreach ($this->fields as $i => $value) {
        $result .= $this->fields[$i]->getHtmlControl().br();
      }   
      return $result;   
    }
  
}

// -------------------------------------------- I N I T I A L I Z A T I O N

// Connect to mysql database
$dbLink = mysql_connect($dbServerName, $dbUser, $dbPassword);
if (!$dbLink) {
    die('Not connected : ' . mysql_error());
}

// make hmat the current db
$dbSelected = mysql_select_db($dbName, $dbLink);
if (!$dbSelected) {
    die ("Can't use $dbName : " . mysql_error());
}
       

?>