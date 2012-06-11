<?php

/*
 * =========================================================================
 * IMagick_Image
 * 
 * Image manipulation class using IMagick API or ImageMagick CLI. 
 * Cropping and resizing options determined by image url.
 * 
 * Format for url:
 * /images/imagedir/resizecode/width/height/filename.ext
 * 
 * imagedir and filename will typically be the same as when image directories
 * are created by the system they are named the same as the image filename sans 
 * extension such that
 * 
 * myimagename.jpg will create directory myimagename and will contain the 
 * master image myimagename.jpg
 * All additional image sizes will be stored in this directory in a folder 
 * structure corresponding to the url
 * 
 * 
 * Image resizing codes:
		0 => 'width',
		1 => 'height',
		2 => 'area',
		3 => 'crop-centre',
		4 => 'crop-topleft',
		5 => 'crop-bottomright',
		6 => 'distort'
 * 
 * =========================================================================
 */
class IMagick_Image {

  private $util;
  private $topDirectory;
  private $noImageFilename = 'no_image.png';
  private $noImage;
  private $sourceImage;
  private $newImage;
  private $debug = false;
  private $log = array();

  function __construct($params = array()) {

	$this->util = new ImageUtil();
	$this->noImage = new ImageFactory();
	$this->sourceImage = new ImageFactory();
	$this->newImage = new ImageFactory();
	$this->init($params);

	$this->log('IMagick_Image instantiated');
  }

  function init($params = array()) {

	if (!is_array($params) || !count($params))
	  return;

	foreach ($params as $k => $v) {
	  if (property_exists($this, $k))
		$this->$k = $v;
	}

	if (!$this->topDirectory)
	  return;

	$this->topDirectory .= substr($this->topDirectory, -1) == '/' ? '' : '/';

	$this->noImage->filename($this->noImageFilename);
	$this->noImage->directory($this->image_directory($this->noImageFilename));

	$this->log('IMagick_Image initialised');
  }

  /*
   * principle method called to stream an image to browser
   */

  function get() {

	if (!$this->topDirectory)
	  throw new Exception('No top image directory nominated.[IMagick_Image:get]');

	if (func_num_args() < 5)
	  throw new Exception("Insufficient arguments supplied. We only have " . func_num_args() . ".[IMagick_Image:get]"); // make sure we have correct args count

	$this->noImage->top_directory($this->topDirectory);

	$this->sourceImage->top_directory($this->topDirectory);
	$this->sourceImage->directory(func_get_arg(0));
	$this->sourceImage->filename(func_get_arg(4));
	$this->log('sourceImage path: ' . $this->sourceImage->path());

	$this->newImage->top_directory($this->topDirectory);
	$this->newImage->size_code(func_get_arg(1));
	$this->newImage->size_mode($this->get_mode(func_get_arg(1)));
	$this->newImage->width(func_get_arg(2));
	$this->newImage->height(func_get_arg(3));
	$this->newImage->filename(func_get_arg(4));
	$this->newImage->directory($this->sourceImage->directory());
	$this->log('newImage path: ' . $this->newImage->path());


	if (!$this->sourceImage->exists()) {

	  $this->log('Source image doesn\'t exist.');
	  $this->log('master image. ' . $this->topDirectory . $this->sourceImage->filename());

	  if (file_exists($this->topDirectory . $this->sourceImage->filename())) {
		$this->log('Source image found in top directory. Moving it to own directory.');
		$this->util->imageToDir($this->topDirectory);
	  } else {
		$this->log('Source image not found. Getting default image.');
		$this->get_no_image();
		
		return;
	  }
	}

	if ($this->newImage->exists()) {
	  $this->log('Requested image found - streaming to browser.');
	  $this->stream_image($this->newImage->path());
	  return;
	}

	$this->make_image();
  }

  private function image_directory($imageName) {

	return implode('.', array_slice(explode('.', $imageName), 0, -1));
  }

  private function modes() {
	return array(
		0 => 'width',
		1 => 'height',
		2 => 'area',
		3 => 'crop-centre',
		4 => 'crop-topleft',
		5 => 'crop-bottomright',
		6 => 'distort'
	);
  }

  private function get_mode($int) {

	$modes = $this->modes();

	if (!array_key_exists($int, $modes))
	  throw new Exception("Resize mode not available - $int.[IMagick_Image:get_mode]");

	return $modes[$int];
  }

  /*
   * when creating a new image, make sure directory path all exists
   */

  private function check_new_directory() {

	$chk1 = implode('/', array_slice(explode('/', $this->newImage->path()), 0, -3));
	$chk2 = implode('/', array_slice(explode('/', $this->newImage->path()), 0, -2));
	$chk3 = implode('/', array_slice(explode('/', $this->newImage->path()), 0, -1));

	if (!file_exists($chk1)) {
	  $this->log('Creating directory. ' . $chk1);
	  mkdir($chk1);
	}
	if (!file_exists($chk2)) {
	  $this->log('Creating directory. ' . $chk2);
	  mkdir($chk2);
	}
	if (!file_exists($chk3)) {
	  $this->log('Creating directory. ' . $chk3);
	  mkdir($chk3);
	}
  }

  private function get_no_image() {

	if (!$this->noImage->exists()) {
	  $this->make_no_image();
	}

	$this->newImage->filename($this->noImage->filename());
	$this->newImage->directory($this->noImage->directory());

	if (file_exists($this->newImage->path())) {
	  $this->log('Default image streamed');
	  $this->stream_image($this->newImage->path());
	  return;
	}

	$this->make_image();
  }

  /*
   * create default image master
   */

  private function make_no_image() {

	$chk = implode('/', array_slice(explode('/', $this->noImage->path()), 0, -1));

	if (!file_exists($chk)) {
	  $this->log('Create no_image directory');
	  if (!mkdir($chk)) throw new Exception('Unable to create directory - '.$chk);
	  chdir($chk);
	}

	$this->make_no_image_api();

	return;
  }

  /*
   * create default image using IMagick api
   */

  private function make_no_image_api() {

	$img = new Imagick();
	$txt = new ImagickDraw();
	$txt->setFont('Arial');
	$txt->setFontSize(160);

	$img->newImage(800, 600, 'white', 'png');
	$img->annotateImage($txt, 50, 250, 0, 'No Image');
	$img->annotateImage($txt, 40, 450, 0, 'Available');
	$img->writeImage($this->noImage->filename());
	$this->log('Create master default image with IMagick api');
  }

  /*
   * create default image using ImageMagick command line inteface
   */

  private function make_no_image_cli() {

	$cmd = 'convert -size 800x600 xc: ';
	$cmd .= '-fill black -pointsize 160 -draw "text 50,250 \'No Image\'" ';
	$cmd .= '-fill black -pointsize 160 -draw "text 60,450 \'Available\'" ';
	$cmd .= $this->noImage->filename();
	exec($cmd);
  }

  /*
   * create and stream the image. yibida, yibida, that's all folks...
   */

  private function make_image() {

	$this->check_new_directory();
	$this->make_image_api();
  }

  /*
   * create the image with IMagick api
   */

  private function make_image_api() {
	$this->sourceImage->IM = new Imagick($this->sourceImage->path());
	$this->sourceImage->width($this->sourceImage->IM->getImageWidth());
	$this->sourceImage->height($this->sourceImage->IM->getImageHeight());

	$this->newImage->IM = new Imagick($this->sourceImage->path());

	switch ($this->newImage->size_mode()) {
	  case 'width':

		if ($this->newImage->width() >= $this->sourceImage->width()) {
		  copy($this->sourceImage->path(), $this->newImage->path());
		  $this->stream_image($this->newImage->path()); // master is narrower than required format
		  return;
		}

		$this->width_api();
		break;
	  case 'height':

		if ($this->newImage->height() >= $this->sourceImage->height()) {
		  copy($this->sourceImage->path(), $this->newImage->path());
		  $this->stream_image($this->newImage->path()); // master is shorter than required format
		  return;
		}

		$this->height_api();
		break;
	  case 'area':

		if ($this->newImage->height() >= $this->sourceImage->height() && $this->newImage->width() >= $this->sourceImage->width()) {
		  copy($this->sourceImage->path(), $this->newImage->path());
		  $this->stream_image($this->newImage->path()); // master is smaller than required format
		  return;
		}

		$this->area_api();
		break;
	  case 'crop-centre':
	  case 'crop-topleft':
	  case 'crop-bottomright':
		$this->crop_api();
		break;
	  case 'distort':
		$this->distort_api();
		break;
	}

	$this->stream_image($this->newImage->path());
  }

  /*
   * create the image with ImageMagick command line interface
   */

  private function make_image_cli() {

	$rtn = exec('/usr/bin/identify -format "%w,%h" ' . $this->sourceImage->path());

	if (!$rtn)
	  throw new Exception('Unable to get source image dimensions.');

	list($width, $height) = explode(',', $rtn);
	$this->sourceImage->width($width);
	$this->sourceImage->height($height);
  }

  private function width_api() {

	$this->newImage->IM->scaleImage($this->newImage->width(), 0);
	$this->newImage->IM->writeImage($this->newImage->path());
	$this->log('Image sized to width with IMagick api');
  }

  private function width_cli() {

	exec("/usr/bin/convert {$this->sourceImage->filename()} -resize '{$this->newImage->width()}' {$this->newImage->path()}");
  }

  private function height_api() {

	$this->newImage->IM->scaleImage(0, $this->newImage->height());
	$this->newImage->IM->writeImage($this->newImage->path());
	$this->log('Image sized to height with IMagick api');
  }

  private function height_cli() {

	exec("/usr/bin/convert {$this->sourceImage->filename()} -resize 'x{$this->newImage->height()}' {$this->newImage->path()}");
  }

  private function area_api() {

	$this->newImage->IM->scaleImage($this->newImage->width(), $this->newImage->height(), 1);
	$this->newImage->IM->writeImage($this->newImage->path());
	$this->log('Image sized within area with IMagick api');
  }

  private function area_cli() {

	exec("/usr/bin/convert {$this->sourceImage->filename()} -resize '{$this->newImage->width()}x{$this->newImage->height()}' {$this->newImage->path()}");
  }

  private function crop_api() {

	if ($this->newImage->ratio() > $this->sourceImage->ratio()) {
	  $this->height_api();
	  $this->newImage->IM = new Imagick($this->newImage->path()); // did this in case stored IM doesn't know new dims
	} else if ($this->newImage->ratio() < $this->sourceImage->ratio()) {
	  $this->width_api();
	  $this->newImage->IM = new Imagick($this->newImage->path()); // did this in case stored IM doesn't know new dims
	} else {
	  $this->area_api();
	  $this->stream_image($this->newImage->path());
	  return;
	}

	$x = 0;
	$y = 0;
	$dif_width = $this->newImage->IM->getImageWidth() - $this->newImage->width();
	$dif_height = $this->newImage->IM->getImageHeight() - $this->newImage->height();

	switch ($this->newImage->size_mode()) {
	  case 'crop-centre':
		$x = floor($dif_width / 2); // crop horiz
		$y = floor($dif_height / 2); // crop vert
		break;
	  case 'crop-bottomright':
		$x = $dif_width; // crop horiz
		$y = $dif_height; // crop vert
		break;
	}

	$this->newImage->IM->cropImage($this->newImage->width(), $this->newImage->height(), $x, $y);
	$this->newImage->IM->writeImage($this->newImage->path());
  }

  private function distort_api() {

	$this->newImage->IM->scaleImage($this->newImage->width(), $this->newImage->height(), 0);
	$this->newImage->IM->writeImage($this->newImage->path());
  }

  private function image_content_type($path = false) {

	$types = array(
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'png' => 'image/png'
	);

	$ext = strtolower(end(explode('.', $path)));

	if (!array_key_exists($ext, $types))
	  throw new Exception('Image content type not recognised.[IMagick_Image:image_content_type]');

	return $types[$ext];
  }

  private function stream_image($path = false) {

	if (!$path OR !file_exists($path))
	  throw new Exception("Not a valid image to stream: {$path}. [IMagick_Image:stream_image]");

	$content_type = $this->image_content_type($path);

	if ($this->debug) {
	  print nl2br($this->log());
	  exit;
	}


	header('Content-Type:' . $content_type);
	header('Content-Length:' . filesize($path));
	readfile($path);
	exit;
  }

  private function log($str = false) {

	if (!$this->debug)
	  return;

	if ($str !== false)
	  $this->log[] = $str;
	else
	  return implode("\n", $this->log);
  }

}

/*
 * storage container for images involved in IMagick_Image class
 */

class ImageFactory {

  protected $filename; // do you really need to ask....
  protected $directory; // directory that contains master image for this
  protected $topDirectory; // directory that holds ALL images
  protected $width; // image width
  protected $height; // image height
  protected $size_code; // integer - resize code used in url
  protected $size_mode; // string - textual name of code
  protected $path; // file system path to image
  public $IM; // ImageMagick object

  function __construct() {
	
  }

  private function set($prop, $value) {

	if (property_exists($this, $prop)) {
	  $this->$prop = $value;
	}
  }

  function filename($filename = false) {

	if ($filename !== false)
	  $this->set('filename', $filename);
	return $this->filename;
  }

  function directory($directory = false) {

	if ($directory !== false) {
	  $directory .= substr($directory, -1) == '/' ? '' : '/';
	  $this->set('directory', $directory);
	}
	return $this->directory;
  }

  function top_directory($topDirectory = false) {

	if ($topDirectory !== false) {
	  $topDirectory .= substr($topDirectory, -1) == '/' ? '' : '/';
	  $this->set('topDirectory', $topDirectory);
	}
	return $this->topDirectory;
  }

  function width($width = false) {

	if ($width !== false)
	  $this->set('width', (int) $width);
	return $this->width;
  }

  function height($height = false) {

	if ($height !== false)
	  $this->set('height', (int) $height);
	return $this->height;
  }

  function size_code($size_code = false) {

	if ($size_code !== false)
	  $this->set('size_code', (int) $size_code);
	return $this->size_code;
  }

  function size_mode($size_mode = false) {

	if ($size_mode !== false)
	  $this->set('size_mode', $size_mode);
	return $this->size_mode;
  }

  function path() {

	if (is_null($this->path)) {

	  $path = $this->top_directory() . $this->directory();

	  if (!is_null($this->size_code())) {
		$path .= $this->size_code() . '/' . $this->width() . '/' . $this->height() . '/';
	  }

	  $path .= $this->filename();
	  $this->set('path', $path);
	}

	return $this->path;
  }

  function exists() {

	if (is_null($this->path()))
	  return false;
	return file_exists($this->path());
  }

  function ratio() {

	return sprintf("%0.4f", $this->width() / $this->height());
  }

}

class ImageUtil {

  private $log = array();
  private $debug = false;

  /*
   *  scans a directory of images and places each into it's own directory
   */

  function imageToDir($parentPath) {

	$parentPath .= substr($parentPath, -1) == '/' ? '' : '/';
	$files = array_slice(scandir($parentPath), 2);

	if (!count($files))
	  return;

	foreach ($files as $file) {

	  if (is_dir($parentPath . $file))
		continue;

	  $a = explode('.', $file);
	  $ext = array_pop($a);
	  $regex = "/[^a-z0-9-_]+/i";
	  $dirname = preg_replace($regex, '_', implode('.', $a));
	  $newfilename = $dirname . '.' . strtolower($ext);

	  if (!file_exists($parentPath . $dirname)) {
		mkdir($parentPath . $dirname);
		rename($parentPath . $file, $parentPath . $dirname . '/' . $newfilename);
	  }
	}
  }

  /*
   * get array of directories in gallery directory
   */

  function getImageArray($parentPath, $title_replace = array('-', '_')) {
	$parentPath .= substr($parentPath, -1) == '/' ? '' : '/';
	$dirs = array_slice(scandir($parentPath), 2);
	if (!count($dirs))
	  return array();

	$array = array();
	foreach ($dirs as $dir) {
	  if (substr($dir, 0, 1) == '_')
		continue;

	  $_d = scandir($parentPath . $dir);
	  if (count($_d) < 3)
		continue; // has only . and ..
	  $_f = '';
	  foreach ($_d as $__d) {
		if ($__d == '.' OR $__d == '..' OR !is_file($parentPath . $dir . '/' . $__d))
		  continue;
		$_f = $__d;
		break;
	  }
	  $_t = ucwords(trim(str_replace($title_replace, ' ', $dir)));
	  $array[] = array('dir' => $dir, 'filename' => $_f, 'title' => $_t);
	}
	return $array;
  }

  /*
   * iterate through gallery directories and kill off any resized images and
   * their parent directories
   */

  function cleanUp($directory) {

	$this->log('Cleaning up gallery - ' . $directory);
	$directory .= substr($directory, -1) == '/' ? '' : '/';
	$galleries = array_slice(scandir($directory), 2);

	$this->log('There are ' . count($galleries) . ' gallery directories.');

	if (count($galleries)) {

	  foreach ($galleries as $gallery) {

		if (is_dir($directory . $gallery)) {

		  $this->log('Cleaning up gallery - ' . $directory . $gallery);
		  $children = array_slice(scandir($directory . $gallery), 2);

		  if (count($galleries)) {

			foreach ($children as $child) {
			  if (is_dir($directory . $gallery . '/' . $child)) {
				$this->removeDir($directory . $gallery . '/' . $child);
			  }
			}
		  }
		}
	  }
	}

	if ($this->debug == true) {
	  return nl2br($this->log());
	}
  }

  function removeDir($path) {

	$path .= substr($path, -1) == '/' ? '' : '/';
	
	if (!file_exists($path)) {
	  $this->log('Directory - '.$path.' - does not exist. leaving...');
	  return;
	}

	$children = array_slice(scandir($path), 2); // remove . and ..
	
	// remove current dir and leave
	if (!count($children)) {
	  if (rmdir($path)) {
		$this->log('Directory removed - ' . $path);
	  } else {
		$this->log('Directory NOT removed - ' . $path);
	  }
	  return;
	}

	// deal with the dir contents
	foreach ($children as $file) {

	  $_path = $path.$file;

	  if (is_file($_path)) {

		if (unlink($_path)) {
		  $this->log('File removed - ' . $_path);
		} else {
		  $this->log('File NOT removed - ' . $_path);
		}
		continue;
	  }

	  $this->removeDir($_path);
	}

	// remove current dir before heading back
	if (rmdir($path)) {
	  $this->log('Directory removed - ' . $path);
	} else {
	  $this->log('Directory NOT removed - ' . $path);
	}
	return;
  }

  function log($str = false) {

	if ($str !== false)
	  $this->log[] = $str;
	else
	  return implode("\n", $this->log);
	
  }

}

