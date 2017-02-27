<?php

require_once __DIR__ . '/vendor/autoload.php';

class KirbyImageOptim {

  public static function is_localhost() {
    $whitelist = array( '127.0.0.1', '::1' );
    if( in_array( $_SERVER['REMOTE_ADDR'], $whitelist) )
        return true;
  }

  public static function imageoptim(
      $file, 
      $width = null, 
      $height = null, 
      $crop = 'fit', 
      $dpr = 1, 
      $quality = 'medium') {

    $url = $file->url();

    if(!$width) {
      $width = $file->width();
    }

    if(!$height) {
      $height = round($file->height() * $width / $file->width());
    }

    $imageoptimAPIKey = trim(c::get('plugin.imageoptim.apikey',''));

    // If can do imageoptim...
    if(!KirbyImageOptim::is_localhost() && 
        c::get('plugin.imageoptim', false) && 
        strlen($imageoptimAPIKey) > 0) {

      $wxh = $width.'x'.$height;
      $hash = sha1(
          $file->name().'-'.
          $wxh.'-'.
          $dpr.'-'.
          $quality.'-'.
          $file->modified()).
          '.'.$file->extension();

      $filepath = str_replace(
        $file->filename(), 
        $hash,
        kirby()->roots()->thumbs().DS.$file->uri());

      $urlOptim = str_replace(
        $file->filename(), 
        $hash,
        kirby()->urls()->thumbs().'/'.$file->uri());

      if(!f::exists($filepath)) {
        $api = new ImageOptim\API($imageoptimAPIKey);
        try{
          $imageData = $api->imageFromURL($file->url()) 
              ->quality($quality)
              ->dpr(intval($dpr))
              ->resize($width, $height, $crop)
              ->getBytes();
            
          f::write($filepath, $imageData);
          $url = $urlOptim;
        } catch (Exception $ex) {
          return $ex->getMessage();
        }
      } else {
        $url = $urlOptim;
      }

    // ... use kirby thumb instead
    } else {

      if($file->orientation() == 'portrait') {
        $nw = round($file->width() * $height / $file->height());
        $url = $file->thumb($nw, $height);
      } else {
        $url = $file->thumb($width);
      }
      $url = str_replace(['<img src="','" alt="">'],['',''], $url);

    }

    return $url;
  }
}

$kirby->set('file::method', 'imageoptim', 
  function($file, $width = null, $height = null, $crop = 'fit', $dpr = 1) {
    return KirbyImageOptim::imageoptim($file, $width, $height, $crop, $dpr);
});
