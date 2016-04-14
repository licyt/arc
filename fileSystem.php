<?php
//
// This class simplifies file manipulation (open,flush,close,read,write,,append,truncate)
//

//
// EXAMPLE:
// $path = "./";
// $file = "testfile.css";
// $mode = "a";
// $teststring = "\r\n" . "this is a testHAHAH". "\r\n" . "LALALA" . "TRALALAL";
// $test = new cFile( $path, $file, $mode );
// $test->fileAppend( $teststring );
// $test->setMod( 't' );
// $test->fileTruncate();
//

interface iFile
{
	public function __construct( $path = "" , $filename  = "" , $mod  = "" );
	public function getFileName();
	public function getFilePath();
	public function getFileMod();
	public function getFileSize();
	public function setMod( $mode );
	public function fileRead( $numberOfBytes );
	public function fileWrite( $writeString );
	public function fileAppend( $appendString );
	public function fileTruncate();
}

class cFile implements iFile
{
	protected $path;
	protected $filename;
	protected $mod;
	protected $fileHandler;
	
	public function __construct( $path = "" , $filename  = "" , $mod  = "" ) {
		$this->path = $path;
		$this->filename = $filename;
		$this->mod = $mod;
	}
	
	public function getFileName() {
		return $this->filename;
	}
	
	public function getFilePath() {
		return $this->path;
	}
	
	public function getFileMod() {
		return $this->mod;
	}
	
	public function getFileSize() {
		return filesize($path.$filename);
	}
	
	public function setMod( $mode ) {
		$this->mod = $mode;
	}

	private function fileOpen() {
		$this->fileHandler = fopen( $this->path.$this->filename, $this->mod );
		if( !$this->fileHandler ) {
			die( "ERROR: Ooops could not open file ".$this->path.$this->filename );
		}
	}
	
	private function fileFlush() {
		if( !fflush( $this->fileHandler ) ) {
			die( "ERROR: Ooops could not flush file ".$this->path.$this->filename );	
		}
	}

	private function fileClose() {
		if( !fclose( $this->fileHandler ) ) {
			die( "ERROR: Ooops could not close file ".$this->path.$this->filename );	
		}
	}

	public function fileRead( $numberOfBytes ) {
		// $this->fileOpen();
		// if( $numberOfBytes == "ALL" ) {
			// $retval = fread()
		// }
		// $this->fileClose();
	}
	
	public function fileWrite( $writeString ) {
		if( $this->mod == 'w') {
			$this->fileOpen();
			if ( !fwrite( $this->fileHandler, $writeString ) ) {
				die( "ERROR writing into file ".$this->path.$this->filename );
			}
			$this->fileFlush();
			$this->fileClose();
		}
		else {
			die( "ERROR: file mode does not allow for writing." );
		}
	}
	
	public function fileAppend( $appendString ) {
		if ( $this->mod == 'a' ) {
			$this->fileOpen();
			if ( !fwrite( $this->fileHandler, $appendString ) ) {
				die( "ERROR appending to file ".$this->path.$this->filename );
			}
			$this->fileFlush();
			$this->fileClose();
		}
		else {
			die( "ERROR attempting to file append in wrong mode." );
		}
	}
	
	public function fileTruncate() {
		if ( $this->mod = 't' ) {
			$this->mod = 'w';
			$this->fileOpen();
			$this->fileFlush();
			$this->fileClose();
			$this->mod = 't';
		}
		else {
			die( "ERROR could not truncate file." );
		}
	}
}
?>