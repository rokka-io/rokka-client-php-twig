# rokka/twig

A twig extension to use [rokka](https://rokka.io) related methods in your twig templates.

This extension provides filters and functions to translate rokka hashes or image file references into rokka URLs. Some
filters operate on rokka URLs to add stack options.

When working with file references, this extension can upload image files to rokka as necessary. Uploading on the fly
introduces a considerable overhead. If you build a CMS, we recommend to upload images to rokka on creation or in a
background process, and work with the rokka hash in the template, to avoid the first rendering taking a performance hit.
The main use case for uploading on the fly is static site generators where you pre-render the pages and then deploy the
rendered code.

## Installation

Note: If you use the [Rokka Symfony Bundle](https://github.com/rokka-io/rokka-client-bundle), the twig extension is
automatically installed and registered.

```
composer require rokka/twig`
```

Add the following code to your Twig_Environment setup:

```
$twig->addExtension(new \Rokka\Twig\Extension\RokkaExtension('your_rokka_organistaion', 'your_api_key'));

```

With these two steps, the rokka filters and functions become available.

## Usage

You specify an image file that is available locally (or through a image locator, see below). The twig extension checks
if it knows the rokka hash for that image, and if it does not, uploads the image to rokka and stores the hash. Then
it generates the requested URL with the rokka hash.

### Storing and reading hashes

By default, the plugin stores a json file next to each image file. If you want to overwrite this behaviour (for example
to store hashes in a database), you need to extend the `\Rokka\Client\TemplateHelper\AbstractCallbacks` to implement the
desired behaviour and provide an instance of that class to the `RokkaExtension` constructor.

## Non standard image file locations

You can write your own class to load the source images, for example when they are not in the local file system.
Implement the `Rokka\Twig\Resolver\ResolverInterface` to implement your image loading logic and provide an instance of
that class to the `RokkaExtension` constructor.

## Twig filters

The filters are applied to either the path to an image, an SplFileInfo object, a rokka hash or a
`Rokka\Client\LocalImage\AbstractLocalImage` instance.

The filters need the **stack** that you want to use. Make sure that you defined the stack in rokka before using it.

You can force a **format** to have rokka convert your image to the desired format.

Most filters accept an seo string and the seo language. The language is used to transliterate the seo string into a URL
safe string (e.g. for german, change ä to ae or for french change é to e).

### rokka_stack_url: create an image URL for rokka with the desired stack

`rokka_stack_url(stack, format = "jpg", seo = null, seoLanguage = "de")`

Example:

    {{ '/path/to/image' | rokka_stack_url('preview', seo='my-seo-image-url-fragment', seoLanguage = 'fr') }}

Example output: `https://my-org.rokka.io/preview/123abcde/my-seo-image-url-fragment.jpg`

### rokka_original_size_url: create an image URL for rokka that outputs the original size

`rokka_original_size_url(format = "jpg", seo = null, seoLanguage = "de")`

The image will be in its original size, but optimized, compressed and delivered through the CDN of rokka.

### rokka_resize_url: create an image URL for rokka with the dynamic stack to scale the image

`rokka_resize_url(width, height = null, format = "jpg", seo = null, seoLanguage = "de")`

The dynamic stack will make sure that the image is no wider than `width` and no higher than `height`.

### rokka_resizecrop_url: create an image URL for rokka with the dynamic stack to scale and crop the image

`rokka_resizecrop_url(width, height, format = "jpg", seo = null, seoLanguage = "de")`

Similar to resize, but makes sure that the image always has the dimensions width and height. If the aspect ratio of the
image does not fit, the longer side is cropped to fit into the dimensions.

### rokka_add_options: change a rokka URL to add options

`rokka_add_options(options)`

This allows you to configure a stack on the fly and set [stack options and variables](https://rokka.io/documentation/references/stacks.html#expressions-in-stack-operation-options).

Example:

    {{ '/path/to/image' | rokka_stack_url('preview') | rokka_add_options('options-jpg.quality-80') }}
    {{ '/path/to/image' | rokka_stack_url('preview') | rokka_add_options('resize-width-200') }}
    {{ '/path/to/image' | rokka_stack_url('preview') | rokka_add_options('resize-width-200--options-jpg.quality-80') }}

### rokka_src_attributes: change a rokka URL into a css fragment for retina images for css

`rokka_src_attributes(multipliers = [2])`

Example:

    {{ '/path/to/image' | rokka_stack_url('large') | rokka_src_attributes }}

Example output: `background-image:url('https://liip.rokka.io/stack/imagehash.jpg'); background-image: -webkit-image-set(url('https://liip.rokka.io/stack/imagehash.jpg') 1x, url('https://liip.rokka.io/stack/options-dpr-2/imagehash.jpg') 2x);`

### rokka_background_image_style: change a rokka URL into the HTML image tag fragments for retina

`rokka_background_image_style(multipliers = [2])`

Example:

    {{ '/path/to/image' | rokka_stack_url('large') | rokka_background_image_style }}

Example output: `src="https://org.rokka.org/stack/imagehash.jpg" srcset="https://org.rokka.org/stack/options-dpr-2/imagehash.jpg"`

## Twig functions

`rokka_generate_url(hash, stack, format = "jpg", seo = null, seoLanguage = "de")`

Output: A rokka URL for the specified image hash and stack, with the "seo"-filename, if specified.

# Development

## Running PHP-CS-Fixer

```
curl http://cs.sensiolabs.org/download/php-cs-fixer-v2.phar > /tmp/php-cs-fixer.phar
php /tmp/php-cs-fixer.phar  fix -v --diff --using-cache=yes src/
```
