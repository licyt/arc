<?php

//
// cCssHideTags implements tag hidding for LABEL and INPUT Tags that are autoincrementing
//

include_once ( "./database.php" );
include_once ( "./cfile.php" );

interface iCssHideIdTags
{
	public function __construct( $dbServerName, $dbUser, $dbPassword, $dbName , $cssFilePath , $cssFileName , $cssFileNameTimeStamp );
	public function createCssFiles();
}

class cCssHideIdTags extends cDbScheme implements iCssHideIdTags
{	
	protected $cssFileName = "";
	protected $fileInstance = "";
	protected $cssFileNameTimeStamp = "";
	protected $fileInstanceTimeStamp = "";
	
	public function __construct( $dbServerName = "", $dbUser = "", $dbPassword = "", $dbName = "" , $cssFilePath = "" , $cssFileName = "" , $cssFileNameTimeStamp = "" ) {
		$this->dbServerName = $dbServerName;
		$this->dbUser = $dbUser;
		$this->dbPassword = $dbPassword;
		$this->dbName = $dbName;
		$this->cssFileName = $cssFileName;
		$this->cssFile = new cFile( $cssFilePath, $cssFileName , "t" );
		$this->cssFile->fileTruncate();
		$this->cssFileNameTimeStamp = $cssFileNameTimeStamp;
		$this->fileInstanceTimeStamp = new cFile( $cssFilePath, $cssFileNameTimeStamp , "t" );
		$this->fileInstanceTimeStamp->fileTruncate();
	}

	public function createCssFiles() {
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
					$outString = "#".$field->getName()." {"."\r\n   display: none;"."\r\n"."}"."\r\n"."\r\n";
					$outString .= "#Label".$field->getName()." {"."\r\n   display: none;"."\r\n"."}"."\r\n"."\r\n";
					// echo $string;
					$this->cssFile->fileAppend( $outString );
					break;
				}
				if ( !($field->isTimeStamp() ) ) {
					continue;
				}
				else {
					$outString = "#".$field->getName()." {"."\r\n   display: none;"."\r\n"."}"."\r\n"."\r\n";
					$this->cssFileTimeStamp->fileAppend( $outString );
					break;					
				}
			}
		}
	}
}
?>