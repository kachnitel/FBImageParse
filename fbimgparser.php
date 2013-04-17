<?php
$FBGraphUrl = "https://graph.facebook.com/";					// Facebook API URL
$size = is_numeric(@$_GET['size']) ? @$_GET['size'] : 320;		// Default image size

if(isset($_GET['id'])) {
	$json = @file_get_contents($FBGraphUrl . $_GET['id'], 0, null, null);
	$FBData = json_decode($json);
	
	if(isset($FBData->images)) {
		foreach($FBData->images as $img) {
			if($img->width == $size){
				$resultUrl = $img->source;
				break;
			} elseif($img->width < $size){
				$images[] = $img;
			}
		}
		
		if(!isset($resultUrl) && isset($images)) {
			$curSize = 0;
			foreach($images as $img) {
				if($img->width > $curSize) {
					$resultUrl = $img->source;
					$curSize = $img->width;
				}
			}
		}
	}
	
	if(!isset($resultUrl) && isset($FBData->picture)) {
		$resultUrl = $FBData->picture;
	}
	
	if(!isset($resultUrl)) {
		$resultUrl = "https://keboola-bi.s3.amazonaws.com/liveworld/LW_No_image.jpg";
	}
} else {
	$resultUrl = "https://keboola-bi.s3.amazonaws.com/liveworld/LW_No_image.jpg";
}

if(!isset($errMsg) && isset($resultUrl)) {
	header("Location: " . $resultUrl);
} else {
	echo $errMsg;
}
?>
