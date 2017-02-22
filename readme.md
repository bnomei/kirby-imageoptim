# Kirby Imageoptim

![GitHub release](https://img.shields.io/github/release/bnomei/kirby-imageoptim.svg?maxAge=1800) ![License](https://img.shields.io/github/license/mashape/apistatus.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-2.3%2B-red.svg)

Kirby CMS file method to optimize images using [ImageOptim PHP API](https://github.com/ImageOptim/php-imageoptim-api) within your template code. Optimized image is refreshed if file is changed or calling code requests different parameters. It is saved to the `/thumbs` folder (`kirby()->roots()->thumbs()`).

Note: ImageOptim API will only be called on webserver. On localhost the kirby thumbs api will be used to avoid the timeconsuming [upload api call](https://github.com/ImageOptim/php-imageoptim-api#imagefrompathfilepath--local-source-image).

## Requirements

- [**Kirby**](https://getkirby.com/) 2.3+
- [ImageOptim API key](https://imageoptim.com/api/register) (trial available). This plugin uses v1.3.1.
- `allow_url_fopen` PHP setting must be enabled for the API to work. Check with `ini_get('allow_url_fopen')`. Please be aware of the potential security risks caused by allow_url_fopen!

## Installation

### [Kirby CLI](https://github.com/getkirby/cli)

```
kirby plugin:install bnomei/kirby-imageoptim
```

### Git Submodule

```
$ git submodule add https://github.com/bnomei/kirby-imageoptim.git site/plugins/kirby-imageoptim
```

### Copy and Paste

1. [Download](https://github.com/bnomei/kirby-imageoptim/archive/master.zip) the contents of this repository as ZIP-file.
2. Rename the extracted folder to `kirby-imageoptim` and copy it into the `site/plugins/` directory in your Kirby project.

## Usage

In your `site/config.php` activate the plugin and set the [ImageOptim API key](https://imageoptim.com/api/register).

```
c::set('plugin.imageoptim', true); // default is false
c::set('plugin.imageoptim.apikey', 'YOUR_API_KEY_HERE');
```

The plugin adds a `$myFile->imageoptim()` function to [$file objects](https://getkirby.com/docs/cheatsheet#file).

```
<?php 
	// get any image/file object
	$myFile = $page->file('image.jpg');

	// get url (on your webserver) for optimized thumb
	$url = $myFile->imageoptim();

	// echo the url as image
	// https://getkirby.com/docs/toolkit/api#brick
	$img = brick('img')
		->attr('src', $url)
		->attr('alt', $myFile->filename());
	echo $img;

?>
```

Changing width, height and/or fitting is also supported. Modifying dpr and quality setting as well.

```
<?php 
	// fit to 400px width
	$url = $myFile->imageoptim(400);

	// fit to 400px width and 300px height
	$url = $myFile->imageoptim(400, 300);
	
	// crop to 800x600px dimension
	$url = $myFile->imageoptim(800, 600, 'crop');

	// fit to 400px width and 300px height at 2x dpr
	$url = $myFile->imageoptim(400, 300, 'fit', 2);

	// fit to 400px width and 300px height at 2x dpr and 'high' quality
	$url = $myFile->imageoptim(400, 300, 'fit', 2, 'high'); 

?>
```

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby-imageoptim/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
