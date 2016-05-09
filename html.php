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

function linkJs($jsFileName) {
	return "<script language=\"JavaScript\" type=\"text/javascript\" src=\"".$jsFileName."\"></script>";
}

function charset($charset="UTF-8") {
  return "<meta http-equiv=\"Content-Type\" content=\"text/html;charset=$charset\">";
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

interface iHtmlJsDateTimePick {
  public function setAttribute($name, $value);
  public function display();	
}   

interface iHtmlJsColorPick {
  public function setAttribute($name, $value);
  public function setColor( $color );
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

// implement interface iHtmlSpan
class cHtmlSpan extends cHtmlElement implements iHtmlSpan
{ 
  public function __construct ($id="") {
	$this->setAttribute("ID", $id);
  }
	
  public function display() {
	  return
	    "<SPAN ".
		  " ID=".$this->attributes[ID].
		  " NAME=".$this->attributes[ID].
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
        ($this->attributes[OnChange]
          ? " OnChange=\"".$this->attributes[OnChange]."\""
          : ""
        ).                                        
        ($this->attributes[OnClick]
          ? " OnClick=\"".$this->attributes[OnClick]."\""
          : ""
        ).                                        
        ($size?" SIZE=$size":"").
        ($this->attributes["CLASS"]
  		  ? " CLASS=\"".$this->attributes["CLASS"]."\""
  		  : ""
  		).
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
  
  public function addOption($value, $option, $color="") {
	$this->options[$value] = $option;
	$this->colors[$value] = $color;
  }
  
  public function displayOptions() {
	$result = "<OPTION VALUE=0></OPTION>";
    foreach ($this->options as $value => $option) {
      if ($value == $this->selected) {
	    $this->selectedColor = $this->colors[$value];		  
	  }
      $result.=
        "<OPTION ".
		  (($value==$this->selected) ? " SELECTED" : "").
		  ($this->colors[$value]
		    ? " STYLE=\"background-color:#".$this->colors[$value].";\""
		  	: ""
		  ).
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
        ($this->attributes[OnClick]
          ? " OnClick=\"".$this->attributes[OnClick]."\""
          : ""
        ).
        ($this->selectedColor
          ? " STYLE=\"background-color:#".$this->selectedColor.";\""
          : ""
        ).
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
        gui($this->attributes[ID], $GLOBALS[lang], $this->attributes[VALUE]).
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
	$this->selected = "";
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
		$button->setAttribute("CONTENT", gui("tab".$this->name.$tabName, $GLOBALS[lang], $tabName));
		$body->setAttribute("CONTENT", $content);
	  } else {
        $button  = new cHtmlInput("tabButton".$this->name.$tabName, "SUBMIT", gui("tabButton".$this->name.$tabName, $GLOBALS[lang], $tabName));
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
  protected $footers = array();
  protected $rows  = array();
  
  // $columns is array of values
  public function addHeader($columns) {
    array_push($this->headers, $columns);
  }
  public function addRow($columns) {
	// var_dump( $columns );
	array_push($this->rows, $columns);
  }
  // $columns is array of values
  public function addFooter($columns) {
    array_push($this->footers, $columns);
  }
  public function display() {
  	$table = "";
	// display headers
	foreach ($this->headers as $header) {
	  $html = "";
	  foreach ($header as $value) {
	  	$html.="<TH>$value</TH>";
	  }
	  $table.="<TR>$html</TR>";
	}
	// display rows 
	foreach ($this->rows as $rowIndex=>$row) {
	  $html = "";
	  $onClick = "";
	  $class = "";
	  foreach ($row as $columnName=>$value) {
	  	unset($style);
	  	
	  	if ($columnName == "onClick") {
	  		$onClick = "onClick=\"$value\"";
	  		continue;
	  	} 
	  	if ($columnName == "StatusName") {
	  	  $style = "STYLE=\"background-color:".$row[StatusColor].";\"";
	  	}
	  	if ($columnName == "StatusColor") continue;
	  	
	  	if (strpos($columnName, "_id")) continue;
	  	
	  	if ($columnName == "sbColSpan") continue;
	  	if ($columnName == "subBrowser") {
	  		$class = " CLASS=\"subBrowserRow\"";
	  		$html.="<TD colspan=".$row[sbColSpan].">$value</TD>";
	  		continue;
	  	}
	  	if ($columnName == "CLASS") {
	  		$class = " CLASS=\"".$value."\"";
	  	}
	  	
	  	$html.="<TD $style>$value</TD>";
	  }
	  $table.="<TR $class $onClick>$html</TR>";
	}
	// display footers
  	foreach ($this->footers as $footer) {
	  $html = "";
	  foreach ($footer as $value) {
	  	$html.="<TH>$value</TH>";
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
			"<INPUT".
			" TYPE=".($this->attributes[TYPE]
			? $this->attributes[TYPE]
			: "TEXT").
			(($this->attributes["DISABLED"] === " DISABLED")
					? " DISABLED"
					: ""
			).
			" ID=".$this->attributes[ID].
			" NAME=".$this->attributes[ID].
 			" CLASS=\"datepicker\"".
			" VALUE=\"".$this->attributes[VALUE]."\"".                                        
			" SIZE=\"10\"".
			">";
	}
}

class cHtmlJsDateTimePick extends cHtmlInput implements iHtmlJsDateTimePick
{
	// see http://javascriptcalendar.org/javascript-date-picker.php for meaning of various values

	// Class constructor is same as parent's (cHtmlInput)
	// public function __construct()

	// display is overloaded
	public function display() {
		$size=$this->attributes[SIZE];
		return
		"<INPUT".
		" TYPE=".($this->attributes[TYPE]
				? $this->attributes[TYPE]
				: "TEXT").
				" ID=".$this->attributes[ID].
				" NAME=".$this->attributes[ID].
				($this->attributes[DISABLED] == " DISABLED"
						? " DISABLED"
						: ""
						).
						" VALUE=\"".$this->attributes[VALUE]."\"".
						($this->attributes[OnChange]
								? " OnChange=\"".$this->attributes[OnChange]."\""
								: ""
								).
								($this->attributes[OnClick]
										? " OnClick=\"".$this->attributes[OnClick]."\""
										: ""
										).
										($size?" SIZE=$size":"").
										" CLASS=\"isDateTimePick\"".
										">";
	}
}

class cHtmlJsColorPick extends cHtmlInput implements iHtmlJsColorPick
{
	// see http://jscolor.com/
	
	// Class constructor is same as parent's (cHtmlInput)
	public function setColor( $initColor ) {
		if( empty( $initColor ) ) {
			$this->attributes[VALUE] = $initColor;
		}
		else {
			$this->attributes[VALUE] = "2020FF";
		}
	}
	
	// display is overloaded 
	public function display() {
		$size=$this->attributes[SIZE];  
		return
			"<input  value=\"".$this->attributes[VALUE]."\"". 
			" TYPE=".($this->attributes[TYPE]
			? $this->attributes[TYPE]
			: "TEXT").
			" ID=".$this->attributes[ID].
			" NAME=".$this->attributes[ID].
			" VALUE=\"".$this->attributes[VALUE]."\"".                                        
			($size?" SIZE=$size":"").
			" CLASS=\"jscolor { closable:true,closeText:'Close',width:243, height:150, position:'right', borderColor:'#FFF', insetColor:'#FFF', backgroundColor:'#CCC'}\"".
			" OnChange=\"updateColor(this)\"".
			">";
	}
}
?>