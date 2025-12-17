<?php
// favicon.php or favicon.ico (served via PHP)

// Cache for 1 year
$seconds = 31536000; // 365 days

// Send image headers
header('Content-Type: image/png');
// Cache-Control header (recommended)
header("Cache-Control: public, max-age={$seconds}, immutable");
// Expires header (1 year from now, GMT)
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
// Persistent ETag (can be any stable string)
$etag = 'abc123def456';
header('ETag: ' . $etag);

// Create a 32x32 black image
$size = 32;
$img = imagecreatetruecolor($size, $size);

// Black color
$black = imagecolorallocate($img, 0, 0, 0);
imagefill($img, 0, 0, $black);

// Put information
$message = microtime(1) * 10000;
$message_arr = [];
while ($message > 0) {
    $i = $message % 256;
    $message_arr[] = $i;
    $message = $message / 256 | 0;
}
error_log(implode(",", $message_arr) . PHP_EOL, 3, __DIR__ . '/app.log');
$id = array_slice($message_arr, 0, $size * $size * 3);
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
