<?php
// favicon.php or favicon.ico (served via PHP)

// Send image headers
header('Content-Type: image/png');
header('Cache-Control: public, max-age=86400');

// Create a 32x32 black image
$size = 32;
$img = imagecreatetruecolor($size, $size);

// Black color
$black = imagecolorallocate($img, 0, 0, 0);
imagefill($img, 0, 0, $black);

// Put information
$message = [11,22,33,4,0,5];
error_log(implode(",", $message) . PHP_EOL, 3, __DIR__ . '/app.log');
$id = array_slice($message, 0, $size * $size * 3);
for ($i=0; $i < count($id); $i += 3) {
    $r = $id[$i];
    $g = $id[$i + 1] ?? 0;
    $b = $id[$i + 2] ?? 0;

    $x = ($i / 3) % $size;
    $y = ($i / 3) / $size | 0;

    imagesetpixel($img, $x, $y, imagecolorallocate($img, $r, $g, $b));
}

// Output image
imagepng($img);
// imagedestroy($img);
exit;

// $x = 50;
// $y = 100;
// $rgb = imagecolorat($image, $x, $y);
// $r = ($rgb >> 16) & 0xFF;
// $g = ($rgb >> 8) & 0xFF;
// $b = $rgb & 0xFF;
