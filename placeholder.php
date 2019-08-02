<?php
// PHP placeholder images
// Version: 0.1
// Author: Hinerangi Courtenay - @sky_maiden

// Usage (all parameters are optional):
// <img src="placeholder.php?size=400x150&bg=eee&fg=999&text=Generated+image" alt="Placeholder image" />

// Inspired by http://placehold.it/

function hex2rgb($colour)
{
    $colour = preg_replace("/[^abcdef0-9]/i", "", $colour);
    if (strlen($colour) == 6)
    {
        list($r, $g, $b) = str_split($colour, 2);
        return Array("r" => hexdec($r), "g" => hexdec($g), "b" => hexdec($b));
    }
    elseif (strlen($colour) == 3)
    {
        list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
        return Array("r" => hexdec($r), "g" => hexdec($g), "b" => hexdec($b));
    } else {
        return Array("r" => 192, "g" => 192, "b" => 192);
    }
}


// Dimensions
$getsize    = isset($_GET['size']) ? $_GET['size'] : '100x100';
$dimensions = explode('x', $getsize);
if (($dimensions[0]<32) or ($dimensions[0]>2048) or ($dimensions[1]<32) or ($dimensions[1]>2048)) {
    die('dimensions not in range of 32-2048');
}

$image      = imagecreate($dimensions[0], $dimensions[1]);

// Colours
$bg         = isset($_GET['bg']) ? $_GET['bg'] : 'ccc';
$bg         = hex2rgb($bg);
$setbg      = imagecolorallocate($image, $bg['r'], $bg['g'], $bg['b']);

$fg         = isset($_GET['fg']) ? $_GET['fg'] : '000';
$fg         = hex2rgb($fg);
$setfg      = imagecolorallocate($image, $fg['r'], $fg['g'], $fg['b']);

// Text
$text       = isset($_GET['text']) ? strip_tags($_GET['text']) : $getsize;
$text       = str_replace('+', ' ', $text);

$font='../Roboto/Roboto-Regular.ttf';

if ($dimensions[0]<256) {
        $fontsize   = 10;
} elseif ($dimensions[0]>512) {
        $fontsize = 36;
} else {
        $fontsize = 18;
}
$bbox=imagettfbbox($fontsize,0,$font,$text);
$xpos = $bbox[0] + (imagesx($image) / 2) - ($bbox[4] / 2);
$ypos = $bbox[1] + (imagesy($image) / 2) - ($bbox[5] / 2);
imagettftext($image, $fontsize, 0, $xpos, $ypos, $setfg, $font, $text);

header ("Content-type: image/png");
imagepng($image);
