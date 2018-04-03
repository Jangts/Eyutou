<?php
namespace PM\_CLOUD\traits;

use Status;
use Lib\data\Transfer as Downloader;
use Lib\graphics\ImagePrinter;
use PM\_CLOUD\FileMetaModel;
use PM\_CLOUD\AttachmentMetaModel;

trait transfer {
	private function transferImage($props, $basename, $speed){
		if (($filename = trim(PUBL_PATH.$props['LOCATION']))&&file_exists($filename)){
			set_time_limit(0);
			
			if(isset($_GET['download'])){
				exit(1);
				return Downloader::execute($filename, $props['FILE_TYPE'], $basename, $speed);
			}
			exit(2);
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

	private function transferMediaAndText($props, $basename, $speed){
		if (($filename = trim(PUBL_PATH.$props['LOCATION']))&&file_exists($filename)){
			set_time_limit(0);
			if(isset($_GET['download'])){
				Downloader::execute($filename, $props['FILE_TYPE'], $basename, $speed);
			}else{
				Downloader::execute($filename, $props['FILE_TYPE'], null, $speed);
			}
			exit;
		}
		new Status(404, true);//Status::notFound();
	}

	private function transferDocument($props, $basename, $speed){
		if (($filename = trim(PUBL_PATH.$props['LOCATION']))&&file_exists($filename)){
			set_time_limit(0);
			if(isset($_GET['readonly'])){
				Downloader::execute($filename, $props['FILE_TYPE'], null, $speed);
			}else{
				Downloader::execute($filename, $props['FILE_TYPE'], $basename, $speed);
			}
			exit;
		}
		new Status(404, true);//Status::notFound();
	}

	public function transfer($speed = 0){
		$props = $this->savedProperties;
		list($basename, $extn, $type) = FileMetaModel::getSplitFileNameArray($props['FILE_NAME'], $props['MIME']);
		switch($type){
			case "image":
			$this->transferImage($props, $basename, $speed);
			break;
			case "text":
			case "audio":
			case "video":
			$this->transferMediaAndText($props, $basename, $speed);
			break;
			default:
			$this->transferDocument($props, $basename, $speed);
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