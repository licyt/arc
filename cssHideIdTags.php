<?php

//
// cCssHideTags implements tag hidding for LABEL and INPUT Tags that are autoincrementing
//

include_once ( "./database.php" );
include_once ( "./cfile.php" );

interface iCssHideIdTags
{
	public function __construct( $dbServerName, $dbUser, $dbPassword, $dbName , $cssFilePath , $cssFileName );
	public function createCssFile();
}

class cCssHideIdTags extends cDbScheme implements iCssHideIdTags
{	
	protected $cssFileName = "";
	protected $fileInstance = "";
	
	public function __construct( $dbServerName = "", $dbUser = "", $dbPassword = "", $dbName = "" , $cssFilePath = "" , $cssFileName = "" ) {
		$this->dbServerName = $dbServerName;
		$this->dbUser = $dbUser;
		$this->dbPassword = $dbPassword;
		$this->dbName = $dbName;
		$this->cssFileName = $cssFileName;
		$this->cssFile = new cFile( $cssFilePath, $cssFileName , "t" );
		$this->cssFile->fileTruncate();
	}

	public function createCssFile() {
		$this->cssFile->setMod('a');
		$this->link( $this->dbServerName, $this->dbUser, $this->dbPassword );
		$this->useDb( $this->dbName );
		foreach ( $this->tables as $tableName => $table ) {
			foreach ( $table->fields as $i => $field ) {
				if ( !( $field->isAutoInc() ) ) {
					// this field is not autoincrementing
					continue;
				}
				else {
					// we got an autoincrementing field
					$string = "#".$field->getName()." {"."\r\n   display: none;"."\r\n"."}"."\r\n"."\r\n";
					$string .= "#Label".$field->getName()." {"."\r\n   display: none;"."\r\n"."}"."\r\n"."\r\n";
					// echo $string;
					$this->cssFile->fileAppend( $string );
					break;
				}
			}
		}
	}
}
?>