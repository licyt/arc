<?php

// html wrapper functions add tags and attributes
function br($count=1) {
  for ($i=0;$i<$count;$i++) {
    $result.="<BR>";
  }
  return $result;
}

function head($head="") {
  return "<head>$head</head>";
}

function body($body="") {
  return "<body>$body</body>";
}

function linkCss($cssFileName) {
  return "<link rel=\"stylesheet\" type=\"text/css\" href=\"$cssFileName\">";
}

function charset($charset="UTF-8") {
  return "<meta http-equiv=\"Content-Type\" content=\"text/html;charset=$charset\">";
}
 
function buttonSet(array $columnNames, $setName="") 
{
  $newNames = array();
  foreach ($columnNames as $i=>$buttonName) {
	$button  = new cHtmlInput($setName.$buttonName, "SUBMIT", $buttonName);
	$newNames[$i]=$button->display();
  }
  return $newNames;	
}

function inputSet(array $columnNames, $setName="", $values)
{
  $newNames = array();
  foreach ($columnNames as $i=>$inputName) {
	$filter  = new cHtmlInput($setName.$inputName, "TEXT", $values[$inputName]);
	$filter->setAttribute("OnChange", "this.form.submit()");
	$newNames[$i]=$filter->display();
  }
  return $newNames;
}

// ------------------------------------------------------ I N T E R F A C E
// common ancestor for all Html elements on a page
interface iHtmlElement {
  public function setAttribute($name, $value);
  public function display();
}

interface iHtmlDiv {
  public function __construct ($id);
  public function display();
}   
   
interface iHtmlSpan {
  public function __construct ($id);
  public function display();
}   
   
interface iHtmlLabel {
  public function display();
}    

interface iHtmlInput {
  public function __construct ($id="", $type="", $value="");
  public function display();
}
                      
interface iHtmlSelect {
  public function setSelected($value);
  public function addOption($option, $value, $color);
  public function displayOptions();
  public function display();
}
                 
interface iHtmlForm {
  public function display();
}    

interface iHtmlTabControl {
  public function __construct ($name="");
  public function setSelected($tabName);
  public function addTab($name, $content);	
  public function display();
}

interface iHtmlJsDatePick {
  public function setAttribute($name, $value);
  public function display();	
}   

interface iHtmlJsColorPick {
  public function setAttribute($name, $value);
  public function display();	
}   
            
// -------------------------------------------  I M P L E M E N T A T I O N

// this is a common ancestor for all html controls
class cHtmlElement  {
  protected $attributes = array();
    
  public function setAttribute($name, $value) {
    $this->attributes[$name] = $value;
  }
  
  // display list of attributes with values
  public function display() {
    foreach ($this->attributes as $name => $value) {
      $result .= "[$name=$value]";
    }
    return $result;
  }
}

// implement interface iHtmlDiv
class cHtmlDiv extends cHtmlElement implements iHtmlDiv
{ 
  public function __construct ($id="") {
	$this->setAttribute("ID", $id);
  }
	
  public function display() {
	  return
	    "<DIV ".
		  " ID=".$this->attributes[ID].
		">".
		  $this->attributes[CONTENT].
		"</DIV>";
  }
}

// implement interface iHtmlDiv
class cHtmlSpan extends cHtmlElement implements iHtmlSpan
{ 
  public function __construct ($id="") {
	$this->setAttribute("ID", $id);
  }
	
  public function display() {
	  return
	    "<SPAN ".
		  " ID=".$this->attributes[ID].
		">".
		  $this->attributes[CONTENT].
		"</SPAN>";
  }
}

class cHtmlInput extends cHtmlElement implements iHtmlInput
{
  /**
   * Attributes
   *   ID
   *   NAME
   *   SIZE
   */
   
  public function __construct ($id="", $type="", $value="") {
    $this->setAttribute("ID", $id);
    $this->setAttribute("TYPE", $type);
    $this->setAttribute("VALUE", $value);
  }

  public function display() {
    $size=$this->attributes[SIZE];  
    return 
      "<INPUT".
        " TYPE=".($this->attributes[TYPE]
          ? $this->attributes[TYPE]
          : "TEXT").
        " ID=".$this->attributes[ID].
        " NAME=".$this->attributes[ID].
        $this->attributes[DISABLED].
        " VALUE=\"".$this->attributes[VALUE]."\"".                                        
        " OnChange=\"".$this->attributes[OnChange]."\"".                                        
        ($size?" SIZE=$size":"").
      ">";
  }
}

class cHtmlSelect extends cHtmlElement implements iHtmlSelect
{
  /**
   * Attributes
   *   ID
   */
  protected $selected;
  protected $selectedColor;
  protected $options = array();
  protected $colors = array();
  
  public function setSelected($value) {
	$this->selected = $value;
  }
  
  public function addOption($option, $value, $color="#FFFFFF") {
	$this->options[$option] = $value;
	$this->colors[$option] = $color;
  }
  
  public function displayOptions() {
	$result = "<OPTION VALUE=0></OPTION>";
    foreach ($this->options as $option => $value) {
      if ($value == $this->selected) {
	    $this->selectedColor = $this->colors[$option];		  
	  }
      $result.=
        "<OPTION ".
		  ($value==$this->selected?" SELECTED":"").
		  " STYLE=\"background-color:#".$this->colors[$option].";\"".
		  " VALUE=$value>".
		  $option.
		"</OPTION>";
    }
    return $result;
  }

  public function display() {
	$options = $this->displayOptions();
    return
      "<SELECT".
        " ID=".$this->attributes[ID].
        " NAME=".$this->attributes[NAME].
        $this->attributes[DISABLED].
        " STYLE=\"background-color:#".$this->selectedColor.";\"".
		" OnChange=\"this.style.backgroundColor=this.options[this.selectedIndex].style.backgroundColor;\"".
      ">".
        $options.
      "</SELECT>";
  }
}

class cHtmlLabel extends cHtmlElement implements iHtmlLabel
{
  /**
   * Attributes
   *   ID      
   *   TARGET - id of labeled element / "FOR" is a reserved word in php
   *   VALUE  - displayed text
   */

  public function display() {
    return
      "<LABEL".
        " ID=".$this->attributes[ID].
        " FOR=".$this->attributes[TARGET].
      ">".
        $this->attributes[VALUE].
      "</LABEL>";
  }
}

// implement interface iHtmlForm
class cHtmlForm extends cHtmlElement implements iHtmlForm
{
  /**
   * Attributes
   *   ID   
   *   ACTION - URL of script to execute after form commit   
   *   METHOD - GET/POST
   *   CONTENT - form elements
   */

  public function display() {
	  return
      "<FORM".
	    " ID=".$this->attributes[ID].
	    " NAME=".$this->attributes[ID].
        " ACTION=\"".$this->attributes[ACTION]."\"".
        " METHOD=\"".$this->attributes[METHOD]."\"".
      ">".
        $this->attributes[CONTENT].
      "</FORM>";
  }
}                   

// implement interface cHtmlTabControl
class cHtmlTabControl extends cHtmlElement implements iHtmlTabControl
{
  protected $name;
  protected $tabs;
  protected $selected;
  
  public function __construct ($name="") {
	$this->name = $name;
  }
  
  public function setSelected($tabName) {
	$this->selected = $tabName;
	$_SESSION[tabControl][$this->name][selected] = $this->selected;
  }
  
  public function addTab($tabName, $content) {
    $this->tabs[$tabName] = $content;
	if ($_SESSION[tabControl][$this->name][selected] && !$this->selected)
      $this->selected = $_SESSION[tabControl][$this->name][selected];
	if (!$this->selected) $this->setSelected($tabName);
  }
  
  function display() {
	// process tab switching
    foreach ($this->tabs as $tabName => $content) {
	  if ($_POST["tabButton".$this->name.$tabName]) {
		$this->setSelected($tabName);
	  }
	}
	// create html  
	$main = new cHtmlDiv("tabControl".$this->name);
	$head = new cHtmlDiv("tabHead".$this->name);
	$body = new cHtmlDiv("tabBody".$this->name);
	foreach ($this->tabs as $tabName => $content) {
	  if ($tabName == $this->selected) {
		$button = new cHtmlSpan("tab".$this->name.$tabName);
		$button->setAttribute("CONTENT", GUI("tabButton".$this->name.$tabName, "ENG", $tabName));
		$body->setAttribute("CONTENT", $content);
	  } else {
        $button  = new cHtmlInput("tabButton".$this->name.$tabName, "SUBMIT", GUI("tabButton".$this->name.$tabName, "ENG", $tabName));
	  }
      $switch.=$button->display();
	}
	$form = new cHtmlForm();
    $form->setAttribute("ID", "tabSwitch".$this->name);
    $form->setAttribute("ACTION", "");
    $form->setAttribute("METHOD", "POST");
    $form->setAttribute("CONTENT", $switch);
    $head->setAttribute("CONTENT", $form->display());
	$main->setAttribute("CONTENT", $head->display().$body->display());
	return $main->display();
  }
}

class cHtmlTable 
{
  protected $headers = array();
  protected $rows  = array();
  
  // $columns is array of values
  public function addHeader($columns) {
	array_push($this->headers, $columns);
  }
  public function addRow($columns) {
	// var_dump( $columns );
	array_push($this->rows, $columns);
  }
  public function display() {
  	$table = "";
	// display headers
	foreach ($this->headers as $headerIndex=>$header) {
	  $html = "";
	  foreach ($header as $columnName=>$value) {
	  	if (strpos($value, "StatusColor")) continue;
	  	$html.="<TH>$value</TH>";
	  }
	  $table.="<TR>$html</TR>";
	}
	// display rows 
	foreach ($this->rows as $rowIndex=>$row) {
	  $html = "";
	  foreach ($row as $columnName=>$value) {
	  	unset($style);
	  	if ($columnName == "StatusName") {
	  	  $style = "STYLE=\"background-color:".$row[StatusColor].";\"";
	  	}
	  	if ($columnName == "StatusColor") continue;
		$html.="<TD $style>$value</TD>";
	  }
	  $table.="<TR>$html</TR>";
	}
	return "<TABLE>$table</TABLE>";
  }
}

class cHtmlJsDatePick extends cHtmlInput implements iHtmlJsDatePick
{
	// see http://javascriptcalendar.org/javascript-date-picker.php for meaning of various values
	
	// Class constructor is same as parent's (cHtmlInput)
	// public function __construct()
	
	// display is overloaded 
	public function display() {
		$size=$this->attributes[SIZE];  
		return
			"<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"jsDatePick_ltr.min.css\" />".
			"<script language=\"JavaScript\" type=\"text/javascript\" src=\"jsDatePick.min.1.3.js\"></script>".
			"<script language=\"JavaScript\" type=\"text/javascript\">".
			"window.onload = function(){".
			"new JsDatePick({".
			"useMode:2,".
			"target:\"".$this->attributes[ID]."\",".
			"dateFormat:\"%Y-%m-%d\"".
			"});".
			"};".
			"</script>".
			"<INPUT".
			" TYPE=".($this->attributes[TYPE]
			? $this->attributes[TYPE]
			: "TEXT").
			" ID=".$this->attributes[ID].
			" NAME=".$this->attributes[ID].
			" VALUE=\"".$this->attributes[VALUE]."\"".                                        
			($size?" SIZE=$size":"").
			">";
	}
}

class cHtmlJsColorPick extends cHtmlInput implements iHtmlJsColorPick
{
	// see http://jscolor.com/
	
	// Class constructor is same as parent's (cHtmlInput)
	// public function __construct()
	
	// display is overloaded 
	public function display() {
		$size=$this->attributes[SIZE];  
		return
			"<script src=\"jscolor.js\"></script>".
			"<input  value=\"".$this->attributes[VALUE]."\"". 
			"class=\"jscolor {closable:true,closeText:'Close',width:243, height:150, position:'right', borderColor:'#FFF', insetColor:'#FFF', backgroundColor:'#CCC'} ".
			" TYPE=".($this->attributes[TYPE]
			? $this->attributes[TYPE]
			: "TEXT").
			" ID=".$this->attributes[ID].
			" NAME=".$this->attributes[ID].
			" VALUE=\"".$this->attributes[VALUE]."\"".                                        
			($size?" SIZE=$size":"").
			">";
	}
}
?>