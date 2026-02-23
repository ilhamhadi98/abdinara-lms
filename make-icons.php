<?php
$img = imagecreate(192, 192);
imagecolorallocate($img, 30, 64, 175);
$text = imagecolorallocate($img, 255, 255, 255);
imagestring($img, 5, 50, 85, "Abdinara", $text);
imagepng($img, "public/icon-192.png");
imagedestroy($img);

$img2 = imagecreate(512, 512);
imagecolorallocate($img2, 30, 64, 175);
$text2 = imagecolorallocate($img2, 255, 255, 255);
imagestring($img2, 5, 220, 240, "Abdinara", $text2);
imagepng($img2, "public/icon-512.png");
imagedestroy($img2);
echo "Icons created.\n";
