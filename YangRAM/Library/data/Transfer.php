<?php
namespace Lib\data;

Class Transfer {
    public static function checkResourceModification($lastModified) {
		if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"])){
			if (strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) < $lastModified) {
				return true;
			}
			return false;
		}
		if (isset($_SERVER['HTTP_IF_UNMODIFIED_SINCE'])){
			if (strtotime($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) > $lastModified) {
				return true;
			}
			return false;
		}
		if (isset($_SERVER['HTTP_IF_NONE_MATCH'])){
			if ($_SERVER['HTTP_IF_NONE_MATCH'] !== $etag) {
				return true;
			}
			return false;
		}
		return true;
	}

    public static function checkFileModification($filename) {
        if(is_file($filename)){
            $lastModified = filemtime($filename);
            if(self::checkResourceModification($lastModified)){
                return $filename;
            }
            return false;
        }
        return $filename;
    }
    
    public static function execute($filePath, $mimeType = null, $filename = null){
        $transfer = new self($filePath, $mimeType, $filename);
		$transfer->send();
    }

	private $speed = 32;
	private $filePath;
	private $fileSize;
	private $mimeType;
	private $filename;

	public function __construct($filePath, $mimeType = null, $filename = null) {
		$this->filePath = $filePath;
		$this->fileSize = filesize($filePath);
		$this->mimeType = $mimeType ? $mimeType : 'application/octet-stream';
		$this->filename = $filename;
	}

	private function getRange() {
		if(isset($_SERVER['HTTP_RANGE']) && preg_match('/^bytes=(\d*)-(\d*)$/', $_SERVER['HTTP_RANGE'], $matches)){
			list ($all, $start, $end) = $matches;
			$start = $start == '' ? $this->fileSize - $end : $start;
			$end = $this->fileSize - 1;
			return array(
				'start'	=> $start,
				'end'	=> $end
			);
		}
		return NULL;
	}

	public function send() {
		self::checkFileModification($this->filePath);
		$fileHandler = fopen($this->filePath, 'rb');
		$ranges = $this->getRange();
		header('Content-Type: '.$this->mimeType);
		if($this->filename){
			header('Content-Disposition: attachment; filename='.$this->filename);
		}
		if ($ranges) {
			header('HTTP/1.1 206 Partial Content');
			header('Accept-Ranges: bytes');
			header(sprintf('content-length:%u', $this->fileSize-$ranges['start']));
			header(sprintf('Content-Range: bytes %s-%s/%s', $ranges['start'], $ranges['end'], $this->fileSize));
			fseek($fileHandler, sprintf('%u', $ranges['start']));
		}else {
			header('HTTP/1.1 200 OK');
			header('Content-Length: '.$this->fileSize);
		}
		while(!feof($fileHandler) && !connection_aborted()) {
			echo fread($fileHandler, round($this->speed*1024, 0));
			flush();
			ob_flush();
		}
		if ($fileHandler != null) {
			fclose($fileHandler);
		}
	}

	public function setSpeed($speed){
        if(is_numeric($speed) && $speed>16 && $speed<4096){
            $this->speed = $speed;
        }
    }
}