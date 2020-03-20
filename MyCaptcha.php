<?php

namespace Srglkh\Captchagen;

class MyCaptcha
{
    private static function generateRandom($charset, $generated_random_length)
    {
        $charset_last_key = strlen($charset) - 1;
        $generated_random = '';

        for ($i = 0; $i < $generated_random_length; $i++) {
            $generated_random .= $charset[rand(0, $charset_last_key)];
        }

        return $generated_random;
    }

    public static function get()
    {
        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $generated_random_length = 4;
        $width = 150;
        $height = 70;
        $font_dir = __DIR__ . '/fonts/';
        $capthas_dir = __DIR__ . '/captchas/';
        $colors = array(102, 204);
        $lines_count = rand(4, 7);

        $generated_random = self::generateRandom($charset, $generated_random_length);

        $image = imagecreatetruecolor($width, $height);

        // draw background
        shuffle($colors);
        list ($b_color, $t_color) = $colors;
        $background_color = imagecolorallocate($image, $b_color, $b_color, $b_color);
        imagefill($image, 0, 0, $background_color);

        // get fonts
        $fonts = array_values(array_diff(scandir($font_dir), array('.', '..')));
        $fonts_last_key = count($fonts) - 1;

        // choose text color and draw text
        $text_color = imagecolorallocate($image, $t_color, $t_color, $t_color);
        $x = 10; // first symbol's indent

        for ($i = 0; $i < $generated_random_length; $i++) {
            $font_size = rand(35, 40);
            $font = $font_dir . $fonts[rand(0, $fonts_last_key)];
            $angle = rand(-25, 25);
            $y = rand(40, 50);
            imagettftext ($image, $font_size, $angle, $x, $y, $text_color, $font, $generated_random[$i]);
            $x += 30;
        }

        // draw lines
        $dy = ceil($height / ($lines_count + 1)); // lines` interval 
        $y = $dy;

        for ($i = 0; $i < $lines_count; $i++) {
            $y += $dy;
            imageline($image, 0, $y + rand(-10, 10), $width, $y + rand(-10, 10), $text_color);
        }

        imagejpeg($image, $capthas_dir . $generated_random); // save image file
        imagedestroy($image);

        return $generated_random;
    }
}
