<?php
namespace PM\_CLOUD\traits;

use Status;
use Lib\data\Transfer as Downloader;
use Lib\graphic\ImagePrinter;
use PM\_CLOUD\FileMetaModel;

trait transfer {
	private function transferImage($props){
		if (($filename = trim(PUBL_PATH.$props['LOCATION']))&&file_exists($filename)){
			switch($props['MIME']){
				case "image/jpeg":
				case "image/pjpeg":
				return ImagePrinter::JPG($filename);
				break;
				case "image/png":
				return ImagePrinter::PNG($filename);
				break;
				case "image/gif":
				return ImagePrinter::GIF($filename);
				break;
			}
		}
		new Status(404, true);//Status::notFound();
	}

	private function transferMediaAndText($props){
		if (($filename = trim(PUBL_PATH.$props['LOCATION']))&&file_exists($filename)){
			set_time_limit(0);
			if(isset($_GET['download'])){
				Downloader::execute($filename, $props['FILE_TYPE'], $this->basename);
			}else{
				Downloader::execute($filename, $props['FILE_TYPE']);
			}
			exit;
		}
		new Status(404, true);//Status::notFound();
	}

	private function transferDocument($props){
		if (($filename = trim(PUBL_PATH.$props['LOCATION']))&&file_exists($filename)){
			set_time_limit(0);
			if(isset($_GET['readonly'])){
				Downloader::execute($filename, $props['FILE_TYPE']);
			}else{
				Downloader::execute($filename, $props['FILE_TYPE'], $this->basename);
			}
			exit;
		}
		new Status(404, true);//Status::notFound();
	}

	public function transfer(){
		$props = $this->savedProperties;
		list($basename, $suffix, $type) = FileMetaModel::getSplitFileNameArray($props['FILE_NAME'], $props['MIME']);
		switch($type){
			case "image":
			$this->transferImage($props);
			break;
			case "text":
			case "audio":
			case "video":
			$this->transferMediaAndText($props);
			break;
			default:
			$this->transferDocument($props);
			break;
		}
	}

	public function resizeImageAndTransfer($imgWidth, $imgHeight = NULL){
		$props = $this->savedProperties;
		if (($filename = trim(PUBL_PATH.$props['LOCATION']))&&file_exists($filename)){
			if(empty($imgHeight)){
				$resize = explode("x", $imgWidth.'x');
				$imgWidth = $resize[0];
				$imgHeight = $resize[1];
			}
			$orgWidth = intval($props['WIDTH']);
			$orgHeight = intval($props['HEIGHT']);
			if(!is_numeric($imgWidth)&&!is_numeric($imgHeight)){
				$imgWidth = $orgWidth;
				$imgHeight = $orgHeight;
			}elseif(!is_numeric($imgHeight)){
				$imgHeight = $imgWidth / $orgWidth * $orgHeight;
			}elseif(!is_numeric($imgWidth)){
				$imgWidth = $orgWidth / $orgHeight * $imgHeight;
			}
			switch($props['MIME']){
				case "image/jpeg":
				case "image/pjpeg":
				return ImagePrinter::JPG($filename, $imgWidth, $imgHeight, $orgWidth, $orgHeight);
				break;
				case "image/png":
				return ImagePrinter::PNG($filename, $imgWidth, $imgHeight, $orgWidth, $orgHeight);
				break;
				case "image/gif":
				return ImagePrinter::GIF($filename, $imgWidth, $imgHeight, $orgWidth, $orgHeight);
				break;
			}
		}
		new Status(404, true);//Status::notFound();
	}
}