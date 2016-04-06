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

// ------------------------------------------------------ I N T E R F A C E
// common ancestor for all Html elements on a page
interface iHtmlControl {
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
   
interface iHtmlInput {
  public function display();
  public function __construct ($id="", $type="", $value="");
}
                      
interface iHtmlSelect {
  public function display();
}
                 
interface iHtmlForm {
  public function display();
}    

interface iHtmlTabControl {
  public function __construct ($name="");
  public function addTab($name, $content);	
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
class cHtmlDiv extends cHtmlElement 
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
class cHtmlSpan extends cHtmlElement 
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

class cHtmlInput extends cHtmlElement {
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
        " VALUE=\"".$this->attributes[VALUE]."\"".                                        
        ($size?" SIZE=$size":"").
      ">";
  }
}

class cHtmlSelect extends cHtmlElement {
  /**
   * Attributes
   *   ID
   */
  protected $selected;
  protected $options = array();
  
  public function setSelected($value) {
	$this->selected = $value;
  }
  
  public function addOption($option, $value) {
	$this->options[$option] = $value;
  }
  
  public function displayOptions() {
	$result = "<OPTION VALUE=0></OPTION>";
    foreach ($this->options as $option => $value) {
      $result.=
        "<OPTION ".
		  ($value==$this->selected?" SELECTED":"").
		  " VALUE=$value>".
		  $option.
		"</OPTION>";
    }
    return $result;
  }

  public function display() {
    return
      "<SELECT".
        " ID=".$this->attributes[ID].
        " NAME=".$this->attributes[NAME].
      "/>".
        $this->displayOptions().
      "</SELECT>";
  }
}

class cHtmlLabel extends cHtmlElement {
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
class cHtmlForm extends cHtmlElement {
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
class cHtmlTabControl extends cHtmlElement {
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
		$button->setAttribute("CONTENT", $tabName);
		$body->setAttribute("CONTENT", $content);
	  } else {
        $button  = new cHtmlInput("tabButton".$this->name.$tabName, "SUBMIT", $tabName);
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

?>