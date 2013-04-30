<?php
// Facebook API URL
$FBGraphUrl = "https://graph.facebook.com/";
// Default image size
if(isset($_GET['size']) && intval($_GET['size'])) {
    $size = $_GET['size'];
} else {
    $size = 320;
}
$token = "BAAAAUQC61RkBAGuHc74A5QHoGsHCOZBzTuAAOjgE3bzEcRkzPdIoY1eh0UKXZAiOFZBpEubx2aN3ARTYypKOCuPowZCw6wNdghMGl0rMvOE1DquU9IcSsBzvUBf4SixFJqwV2ZASQMMursOZBzdIYTw6stZCfLlkXEuw1K5mQrgSbZBSAulVDmndGZBq3bvGAzZCUZD";

if(isset($_GET['id'])) {
    $FBGraphUrl = $FBGraphUrl . $_GET['id'] . "?access_token=" . $token;
    $json = file_get_contents($FBGraphUrl, 0, null, null);
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

    if(!isset($resultUrl) && isset($FBData->cover_photo)) {
        $resultUrl = $_SERVER['PHP_SELF'] . "?id=" . $FBData->cover_photo . "&size=" . $size; // redirect to self!
    }

    if(!isset($resultUrl)) {
        $resultUrl = "https://keboola-bi.s3.amazonaws.com/liveworld/LW_No_image.jpg";
    }
} else {
    $resultUrl = "https://keboola-bi.s3.amazonaws.com/liveworld/LW_No_image.jpg";
}

header("Location: " . $resultUrl);
?>
