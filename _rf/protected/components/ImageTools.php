<?php

class ImageTools extends CApplicationComponent
{

	public function blob2img($image_data) {
		
		$encoded_image = base64_encode($image_data);

		$decoded_image = base64_decode($encoded_image);
		// header("Content-Type: image/jpeg");
		echo $decoded_image;
	}
	/*
	 * @parameter $file string // Path of file to be resized
	 * @parameter $width // New image width int pixels
	 * 
	 */
	public function resizeImage($file,$width)
	{
		$maxWidth = $width;
		$imageSize = getimagesize($file);
		if ($imageSize[0] > $maxWidth) {
			$newWidth = $maxWidth;
			$newHeight = ($imageSize[1] * $maxWidth) / $imageSize[0];
			$this->resize($file,$newWidth,$newHeight,$file,255,255,255);
			return true;
		}
	}
	/*
	 * @parameter $file string // Path of source file
	 * @parameter $subfolder string // Subfolder for thumbnail
	 * 
	 * 
	 */
	public function makeImageThumb($file,$subfolder = "thumb", $width = 100,$height = 100)
	{

		$imageSize = getimagesize($file);
		$filePathInfo = pathinfo($file);
		$dirName = $filePathInfo['dirname'];
		$fileName = $filePathInfo['basename'];
		$newWidth = $width;
		$newHeight = $height;
		$this->resize($file,$newWidth,$newHeight,$dirName."/".$subfolder."/".$fileName,255,255,255);
		return true;
		
	}
	private function getImageProperties($file)
	{
		$imageSize = getimagesize($file);
		return array('width'=>$imageSize[0],'height'=>$imageSize[1],'filesize'=>filesize($file));
	}
	
	private function resize($url, $box_w, $box_h, $savePath, $r, $g, $b)
	{
		$background = ImageCreateTrueColor($box_w, $box_h);
		$color=imagecolorallocate($background, $r, $g, $b);
		imagefill($background, 0, 0, $color);
		$image = $this->openImage($url, $r, $g, $b);
		if ($image === false) { die ('Unable to open image'); }
		$w = imagesx($image);
		$h = imagesy($image);
		$ratio=$w/$h;
		$target_ratio=$box_w/$box_h;
		if ($ratio<$target_ratio){
			$new_w=$box_w;
			$new_h=round($box_w/$ratio);
			$x_offset=0;
			$y_offset=round(($box_h-$new_h)/2);
		}else {
			$new_h=$box_h;
			$new_w=round($box_h*$ratio);
			$x_offset=round(($box_w-$new_w)/2);
			$y_offset=0;
		}
		$insert = ImageCreateTrueColor($new_w, $new_h);
		imagecopyResampled ($insert, $image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
		imagecopymerge($background,$insert,$x_offset,$y_offset,0,0,$new_w,$new_h,100);
		imagejpeg($background, $savePath, 80);
		imagedestroy($insert);
		imagedestroy($background);
	}

	private function openImage ($file, $r='255', $g='255', $b='255') 
	{
	    $size=getimagesize($file);
	
	    switch($size["mime"]){
	        case "image/jpeg":
				$fh = fopen($file, 'rb');
				$str = '';
				while($fh !== false && !feof($fh)){
	    		$str .= fread($fh, 1024);
				}
	            //$im = imagecreatefromjpeg($file); //jpeg file
	            $im = imagecreatefromstring($str); //jpeg file
	        break;
	        case "image/gif":
	            $im = imagecreatefromgif($file); //gif file
	            imageAlphaBlending($im, false);
				imageSaveAlpha($im, true);
				$background = imagecolorallocate($im, 0, 0, 0);
				imagecolortransparent($im, $background);
	
				$color=imagecolorallocate($im, $r, $g, $b);
				for ($i=0;$i<imagesy($im);$i++){
					for ($j=0; $j<imagesx($im); $j++){
						$rgb=imagecolorat($im, $j, $i);
						if ($rgb==2){
							imagesetpixel($im, $j, $i, $color);
						}
					}
				}
	
	      break;
	      case "image/png":
	          $im = imagecreatefrompng($file); //png file
	          $background = imagecolorallocate($im, 0, 0, 0);
	          imageAlphaBlending($im, false);
			  imageSaveAlpha($im, true);
			  imagecolortransparent($im, $background);
			  $color=imagecolorallocate($im, $r, $g, $b);
				for ($i=0;$i<imagesy($im);$i++){
					for ($j=0; $j<imagesx($im); $j++){
						$rgb=imagecolorat($im, $j, $i);
						if ($rgb==2){
							imagesetpixel($im, $j, $i, $color);
						}
					}
				}
	
	      break;
	    default:
	        $im=false;
	    break;
	    }
	    return $im;
	}

}
?>
