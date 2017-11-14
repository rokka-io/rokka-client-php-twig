# rokka/twig

A twig extension to easily use [rokka](https://rokka.io) related methods in your twig templates.

Docs are coming, very much work in progress, see the source or open an issue, if questions.

Stable release coming soon.

## Installation

For this develop release, add the following to your composer.json

```
   "rokka/client": "dev-templatehelper as 1.1.0-dev",
   "rokka/twig": "dev-develop as 0.1-dev",
```

and run `composer update`. 

Once we have released rokka/client 1.1.0, the following command will be enough

```
composer require rokka/twig`
``` 

Add the following code to your Twig_Environment setup:
```
$twig->addExtension(new \Rokka\Twig\Extension\RokkaExtension('your_rokka_organistaion', 'your_api_key'));

```

and you should be able to use the following filters and functions.

## Twig filters

`rokka_stack_url(stack, format = "jpg", seo = null, seoLanguage = "de")`

Input: filepath, SplInfoObject or \Rokka\Client\LocalImage\LocalImageAbstract
Output: The rokka URL to the image with `stack` (can also by a dynamic stack) 

`rokka_resize_url(width, height = null, format = "jpg", seo = null, seoLanguage = "de")`

Input: filepath, SplFileInfo or \Rokka\Client\LocalImage\LocalImageAbstract
Output: The rokka URL to the image with a dynamic stack which resizes the image to 
not bigger than `width` and `height`.

`rokka_resizecrop_url(width, height, format = "jpg", seo = null, seoLanguage = "de")`

Input: filepath, SplFileInfo or \Rokka\Client\LocalImage\LocalImageAbstract
Output: The rokka URL to the image with a dynamic stack which resized the image to 
at least  `width` and `height` and crops it then with `width` and `height`

`rokka_original_size_url(format = "jpg", seo = null, seoLanguage = "de")`

Input: filepath, SplFileInfo or \Rokka\Client\LocalImage\LocalImageAbstract
Output: The rokka URL to the image with the original size of the picture. 
But optimized, compressed and delivered by rokka.

`rokka_add_options(options)`

Input: A rokka URL
Output: Adds options to a rokka URL. Eg. you can add stack options to it like `options-jpg.quality-80` or also stack operations options like `resize-width-200`, you can also combine it with `resize-width-200--options-jpg.quality-80`

`rokka_background_image_style(multipliers = [2])`

Input: A rokka URL
Output: returns the correct tags for displaying retina images within an img tag, eg. `src=https://org.rokka.org/stack/imagehash.jpg" srcset="https://org.rokka.org/stack/options-dpr-2/imagehash.jpg"`

`rokka_src_attributes(multipliers = [2])`

Input: A rokka URL
Output: returns the correct tags for displaying retina images within an background css style. img tag, eg. `background-image:url('https://liip.rokka.io/stack/imagehash.jpg'); background-image: -webkit-image-set(url('https://liip.rokka.io/stack/imagehash.jpg') 1x, url('https://liip.rokka.io/stack/options-dpr-2/imagehash.jpg') 2x);`

 
## Twig functions

`rokka_generate_url(hash, stack, format = "jpg", seo = null, seoLanguage = "de")`

Output: A rokka URL with the hash and the stack. And an "seo"-filename, if set.

## Storing and reading hashes

By default, the plugin stores a json file next to each image with the rokka hash, after it uploaded it. If you want to overwrite this behaviour (for example storing it in a database), you have to implement the `\Rokka\Client\TemplateHelperCallbacksAbstract` interface and provide that as 3rd option to the `RokkaExtension` constructor.

## Non standard file locations

You can write your own class to define, where your image is stored (if it's not on a file system for example). Implement the `\Rokka\Client\LocalImage\LocalImageAbstract` interface for that and then provide objects of that to the filters mentioned above 

## Symfony

If you use symfony, we recomment using the [rokka Symfony Bundle](https://github.com/rokka-io/rokka-client-bundle)
