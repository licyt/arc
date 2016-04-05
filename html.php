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
                      
interface iHtmlInput {
  public function display();
}
                      
interface iHtmlSelect {
  public function display();
}
                 
interface iHtmlForm {
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

class cHtmlInput extends cHtmlElement {
  /**
   * Attributes
   *   ID
   *   NAME
   *   SIZE
   */

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
  protected $options = array();
  
  public function displayOptions() {
    foreach ($this->options as $option => $value) {
      $result.=
        "<OPTION VALUE=$value>$option</OPTION>";
    }
    $result .= "<OPTION VALUE=0>Add</OPTION>";
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

class cHtmlForm extends cHtmlElement {
  /**
   * Attributes  
   *   ACTION - URL of script to execute after form commit   
   *   METHOD - GET/POST
   *   CONTENT - form elements
   */

  public function display() {
	  return
      "<FORM".
        " ACTION=\"".$this->attributes[ACTION]."\"".
        " METHOD=\"".$this->attributes[METHOD]."\"".
      ">".
        $this->attributes[CONTENT].
      "</FORM>";
  }
}                   

?>