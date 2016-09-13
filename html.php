<?php
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com

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

function body($body="", $onLoad="") {
  return "<body onLoad=\"".$onLoad."\">$body</body>";
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
 
function style($css) {
  return "<style>$css</style>";
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

interface iHtmlSuggest {
	public function setOptions( $optArray , $optionsName);
	public function display();
}

// -------------------------------------------  I M P L E M E N T A T I O N

// this is a common ancestor for all html controls
class cHtmlElement  {
  protected $attributes = array();
  
  public function add($name) {
  	return
	  ($this->attributes[$name] || ($name == "VALUE") 
  	    ? " $name=\"".$this->attributes[$name]."\""
  	    : ""
  	  );
  }
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
		  $this->add(ID).
		  $this->add(onClick).
		  $this->add(onLoad).
		  $this->add("CLASS").
		  $this->add("STYLE").
		  $this->add("TITLE").
		">".
		  $this->attributes[CONTENT].
		"</DIV>";
  }
}

// implement interface iHtmlSpan
class cHtmlSpan extends cHtmlElement implements iHtmlSpan
{ 
  public function __construct ($id="", $content="") {
	  $this->setAttribute("ID", $id);
	  $this->setAttribute("CONTENT", $content);
  }
	
  public function display() {
	  return
	    "<SPAN".
		    " ID=".$this->attributes[ID].
		    " NAME=".$this->attributes[ID].
        $this->add(onClick).
        $this->add("CLASS").
		  ">".
		  $this->attributes[CONTENT].
		"</SPAN>";
  }
}

class cHtmlText extends cHtmlElement {
  public function __construct($content="", $rows=5, $cols=80) {
    $this->setAttribute("CONTENT", $content);
    $this->setAttribute("ROWS", $rows);
    $this->setAttribute("COLS", $cols);
  }
  
  public function display() {
    return 
      "<TEXTAREA ".
        " ID=".$this->attributes[ID].
        " NAME=".($this->attributes[NAME]?$this->attributes[NAME]:$this->attributes[ID]).
        " ROWS=\"".$this->attributes[ROWS]."\"".
        " COLS=\"".$this->attributes[COLS]."\"".
        $this->add(onChange).
      ">".
        $this->attributes[CONTENT].
      "</TEXTAREA>";
  }
}

class cHtmlInput extends cHtmlElement implements iHtmlInput
{
 
  public function __construct ($id="", $type="", $value="") {
    $this->setAttribute("ID", $id);
    $this->setAttribute("TYPE", $type);
    $this->setAttribute("VALUE", $value);
    $this->setAttribute("onFocus", "this.select()");
  }
  
  public function display() {
    $size=$this->attributes[SIZE];  
    return 
      "<INPUT".
        " ID=".$this->attributes[ID].
        " NAME=".($this->attributes[NAME]?$this->attributes[NAME]:$this->attributes[ID]).
        " TYPE=".($this->attributes[TYPE]
          ? $this->attributes[TYPE]
          : "TEXT").
        $this->attributes[DISABLED].
        $this->add(VALUE).
        $this->add(onChange).
        $this->add(onClick).
        $this->add(onFocus).
        $this->add(onBlur).
        $this->add(onKeyUp).
        $this->add(onKeyDown).
        $this->add(onKeyPress).
        $this->add(onSelect).
        $this->add(onInput).
        $this->add(onChange).
        $this->add(SIZE).
        $this->add(MAXLENGTH).
        $this->add("CLASS").
        $this->add(STYLE).
        $this->add("LIST").
        $this->add("AUTOCOMPLETE").
        $this->add(onSubmit).
  	  ">".
      ($this->attributes["LIST"]
      	? "<DATALIST ID=\"".$this->attributes["LIST"]."\">".
      		$this->attributes[OPTIONS].
      	  "</DATALIST>"
      	: ""
      )    
    ;
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
  	  " VALUE=\"$value\">".
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
        ($this->attributes[onClick]
          ? " onClick=\"".$this->attributes[onClick]."\""
          : ""
        ).
        ($this->selectedColor
          ? " STYLE=\"background-color:#".$this->selectedColor.";\""
          : ""
        ).
		" onChange=\"".$this->attributes[onChange]."\"".
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
  
  public function getName() {
  	return $this->name;
  }
  
  public function setSelected($tabName) {
  	$this->selected = $tabName;
  	$_SESSION[tabControl][$this->name][selected] = $this->selected;
  }
  
  public function addTab($tabName, $content) {
  	// process tab switching
  	$this->tabs[$tabName] = $content;
  	if ($_SESSION[tabControl][$this->name][selected] && !$this->selected)
      $this->selected = $_SESSION[tabControl][$this->name][selected];
	  if (!$this->selected) $this->setSelected($tabName);
  }
  
  function display() {
  	// create html  
  	$main = new cHtmlDiv("tabControl".$this->name);
  	$head = new cHtmlDiv("tabHead".$this->name);
  	$body = new cHtmlDiv("tabBody".$this->name);
  	foreach ($this->tabs as $tabName => $content) {
  	  if ($tabName == $this->selected) {
    		$button = new cHtmlSpan("tab".($this->name=="Admin"?"Admin":"").$tabName);
    		$button->setAttribute("CLASS", "tab".($this->name=="Admin"?"Admin":""));
    		$button->setAttribute("CONTENT", gui("tab".$tabName, $GLOBALS[lang], $tabName));
    		$body->setAttribute("CONTENT", $content);
  	  } else {
  	    if ($this->name=="Admin") {
          $button  = new cHtmlInput("tabButton".$this->name.$tabName, "SUBMIT", gui("tabButton".$this->name.$tabName, $GLOBALS[lang], $tabName));
  	    } else {
          $button = new cHtmlSpan("tabButton".$tabName);
          $button->setAttribute("CONTENT", gui("tabButton".$tabName, $GLOBALS[lang], $tabName));
          $button->setAttribute("onClick", "switchTab('".$this->name."' ,'".$tabName."');");
  	    }
  	    $button->setAttribute("CLASS", "tabButton".($this->name=="Admin"?"Admin":""));
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

class cHtmlTable extends cHtmlElement
{
  protected $headers = array();
  protected $footers = array();
  protected $rows  = array();
  protected $ids = array();
  
  // $columns is array of values
  public function addHeader($columns) {
    array_push($this->headers, $columns);
  }
  
  public function deleteRows() {
    while (count($this->rows)) {
      array_pop($this->rows);
      array_pop($this->ids);
    }
  }
  
  public function addRow($id, $columns) {
	  // var_dump( $columns );
	  array_push($this->ids, $id);
	  array_push($this->rows, $columns);
  }
  
  // $columns is array of values
  public function addFooter($columns) {
    array_push($this->footers, $columns);
  }
  
  public function displayRows() {
    foreach ($this->rows as $rowIndex=>$row) {
      $html = "";
      $onClick = "";
      $class = "";
      foreach ($row as $columnName=>$value) {
        //if ($columnName=="CLASS") continue;
        unset($style);
    
        if ($columnName == "onKeyPress") {
          $onKeyPress = "onKeyPress=\"$value\"";
          continue;
        }
        if ($columnName == "onClick") {
          $onClick = "onClick=\"$value\"";
          continue;
        }
        if (!$this->attributes[StatusEdit]) {
      		  if ($columnName == "StatusName") {
      		    $style = "STYLE=\"width:140px;background-color:".$row[StatusColor].";\"";
      		  }
      		  if ($columnName == "StatusColor") continue;
        }
    
        //if (strpos($columnName, "_id")) continue;
    
        if ($columnName == "sbColSpan") continue;
        if ($columnName == "subBrowser") {
          $class = " CLASS=\"subBrowserRow\"";
          $html.="<TD colspan=".$row[sbColSpan].">$value</TD>";
          continue;
        }
        if ($columnName == "statusGannt") {
          $class = " CLASS=\"statusGannt\"";
          $html.="<TD colspan=".$row[sbColSpan].">$value</TD>";
          continue;
        }
        if ($columnName == "CLASS") {
          $class = " CLASS=\"".$value."\"";
          continue;
        }
    
        $html.="<TD $style>$value</TD>";
      }
      $rows.="<TR ID=\"".$this->ids[$rowIndex]."\" $class $onClick $onKeyPress>$html</TR>";
    }
    return $rows;
  }
  
  public function display($tableId="") {
  	$table = "";
	  // display headers
  	foreach ($this->headers as $header) {
  	  $html = ""; $i-0;
  	  foreach ($header as $index=>$value) {
  	  	if (strpos($value, "StatusColor")) continue;
  	  	$html.="<TH ".($i==0?"class=\"firstColumn\"":"").">$value</TH>";
  	  	$i++;
  	  }
  	  $table.="<TR>$html</TR>";
  	}
  	// display rows 
  	$table.=$this->displayRows();
  	// display footers
  	foreach ($this->footers as $footer) {
  	  $html = "";
  	  foreach ($footer as $value) {
  	  	if (strpos($value, "StatusColor")) continue;
  	  	$html.="<TH>$value</TH>";
  	  }
  	  $table.="<TR>$html</TR>";
  	}
  	return "<TABLE ID=\"$tableId\">$table</TABLE>";
  }
}

class cHtmlJsDatePick extends cHtmlInput implements iHtmlJsDatePick
{
	// see http://javascriptcalendar.org/javascript-date-picker.php for meaning of various values
	
	// Class constructor is same as parent's (cHtmlInput)
	// public function __construct()
	
	// display is overloaded 
	public function display() {
	  
	  
	  // toto je uplne napicu, to sa takto nerobi
	  
	  
	  
		$input = new cHtmlInput($this->attributes[ID], "TEXT", $this->attributes[VALUE]);
		$input->setAttribute("SIZE", "10");
		$input->setAttribute("CLASS", "datepicker");
		$input->setAttribute("onInput", "rowHasChanged('".$this->attributes[TableName]."');");
		$input->setAttribute("onChange", "rowHasChanged('".$this->attributes[TableName]."');");
		
		$input->setAttribute("onClick", "stopEvent(event);");
		
		return
		  $input->display();
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
		  : "TEXT"
		).
		" ID=".$this->attributes[ID].
		" NAME=".$this->attributes[ID].
		($this->attributes[DISABLED] == " DISABLED"
		  ? " DISABLED"
		  : ""
		).
		" VALUE=\"".$this->attributes[VALUE]."\"".
		$this->add(onChange).
		$this->add(onClick).
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
	  } else {
		$this->attributes[VALUE] = "2020FF";
	  }
	}
	
	// display is overloaded 
	public function display() {
		$size=$this->attributes[SIZE];  
		return
		  "<input".
		    " value=\"".$this->attributes[VALUE]."\"". 
			" TYPE=".($this->attributes[TYPE]
			  ? $this->attributes[TYPE]
			  : "TEXT").
			" ID=".$this->attributes[ID].
			" NAME=".$this->attributes[ID].
			" VALUE=\"".$this->attributes[VALUE]."\"".                                        
			$this->add(SIZE).
			$this->add(onClick).
			" CLASS=\"jscolor { closable:true,closeText:'Close',width:243, height:150, position:'right', borderColor:'#FFF', insetColor:'#FFF', backgroundColor:'#CCC'}\"".
			" onChange=\"updateColor(this)\"".
		  ">";
	}
}

class cHtmlA extends cHtmlElement
{
  public function __construct($path="", $text="") {
  	$this->setAttribute("HREF", $path);
  	$this->setAttribute("TEXT", ($text?$text:$path));
  }
  public function display() {
  	return 
  	  "<A".
  	    $this->add(ID).
  	    $this->add(HREF).
  	    $this->add(onClick).
  	    $this->add(TARGET).
  	  ">".
  	    $this->attributes[TEXT].
  	  "</A>";
  }
}

class cHtmlImg extends cHtmlElement
{
  public function __construct($src="") {
  	$this->setAttribute(SRC, $src);
  }
  public function display() {
  	return 
  	  "<IMG ".
  	    $this->add(SRC).
  	    $this->add(onClick).
  	  ">";
  }
}

class cHtmlFilePath extends cHtmlElement
{
  public function __construct($path, $tableName) {
  	$this->setAttribute(PATH, $path);
  	$this->setAttribute(tableName, $tableName);
  }
  public function display() {
  	$id=$this->attributes[ID];
  	
  	$a = new cHtmlA($this->attributes[PATH]);
  	$a->setAttribute(HREF, ".".$GLOBALS['RepositoryPath'].$this->attributes[PATH]);
  	$a->setAttribute(TARGET, "EXT");
  	$a->setAttribute(ID, $id."Link");
  	
  	$input = new cHtmlInput($id, "TEXT", $this->attributes[PATH]);
  	
  	$button = new cHtmlDiv($id."Button");
  	$button->setAttribute("CLASS", "openFileBrowserButton");
  	
  	$js = 
      "el=elementById('fileBrowser');".
      "if (el.style.display=='block') {hide(el.id);}".
  	  "else {browseFile(elementById('$id'));}".
  	  "rowHasChanged('".$this->attributes[tableName]."');".
  	  "stopEvent(event);"; 
  	$button->setAttribute(onClick, $js);
  	
  	$div = new cHtmlDiv($id."Wrap");
  	$div->setAttribute("CLASS", "cHtmlFilePath");
  	$div->setAttribute(CONTENT, $input->display().$a->display().$button->display());
  	
  	return 
  	  $div->display();
  }
}

class cHtmlSuggest extends cHtmlElement implements iHtmlSuggest
{
	public function __construct ($id="", $value="", $valueVisible = "") {
		$this->setAttribute("ID", $id);
		$this->setAttribute("SUGGESTID", $id."Suggest");
		$this->setAttribute("VALUE", $value);
		$this->setAttribute("VALUEVISIBLE", $valueVisible);
	}

	public function setOptions($optArray,$optionsName) {
		foreach( $optArray as $value=>$suggest ) {
		  $options .= "<option data-value=\"$value\" name=\"".$optionsName."Options\">$suggest</option>";
		}
		$this->setAttribute("OPTIONS", $options);
	}
	
	public function display() {
		$inputHidden = new cHtmlInput($this->attributes[ID], "HIDDEN", $this->attributes[VALUE]);
		$inputVisible = new cHtmlInput($this->attributes[SUGGESTID], "TEXT", $this->attributes[VALUEVISIBLE]);
		$inputVisible->setAttribute("CLASS", "suggest");
		$inputVisible->setAttribute("onClick", $this->attributes["onClick"]);
		$inputVisible->setAttribute("onBlur", $this->attributes["onBlur"]);
		$inputVisible->setAttribute("onKeyUp", $this->attributes["onKeyUp"]);
		$inputVisible->setAttribute("onKeyPress", $this->attributes["onKeyPress"]);
		$inputVisible->setAttribute("onSelect", $this->attributes["onSelect"]);
		$inputVisible->setAttribute("onFocus", $this->attributes["onFocus"]);
		$inputVisible->setAttribute("SIZE", $this->attributes[SIZE]);
		$inputVisible->setAttribute("LIST", $this->attributes[ID]."List");
		$inputVisible->setAttribute("OPTIONS", $this->attributes[OPTIONS]);
		$inputVisible->setAttribute("AUTOCOMPLETE","OFF");
 		$inputVisible->setAttribute("onInput", $this->attributes["onInput"]);
		$div = new cHtmlDiv($this->attributes[ID]."Wrap");
		$div->setAttribute("CONTENT", $inputHidden->display().$inputVisible->display());
		return
		  $div->display();
	}
}

?>