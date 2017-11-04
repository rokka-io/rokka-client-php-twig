<?php

namespace Rokka\Twig\Extension;

use Rokka\Client\Base as ClientBase;
use Rokka\Client\LocalImage\LocalImageAbstract;
use Rokka\Client\TemplateHelper;
use Rokka\Client\TemplateHelperCallbacksAbstract;
use Rokka\Client\UriHelper;
use Rokka\Twig\Resolver\ResolverInterface;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class RokkaExtension extends \Twig_Extension
{
    /**
     * @var RokkaRuntimeExtension
     */
    private $rokka;

    /**
     * @var null|ResolverInterface
     */
    private $resolver = null;

    public function __construct(
        string $organization,
        string $apiKey,
        TemplateHelperCallbacksAbstract $callbacks = null,
        string $publicRokkaDomain = null,
        ResolverInterface $resolver = null,
        string $rokkaApiHost = ClientBase::DEFAULT_API_BASE_URL
    ) {
        $this->rokka = new TemplateHelper($organization, $apiKey, $callbacks, $publicRokkaDomain, $rokkaApiHost);
        $this->resolver = $resolver;
    }

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('rokka_stack_url', [$this, 'getStackUrl']),
            new Twig_SimpleFilter('rokka_resize_url', [$this, 'getResizeUrl']),
            new Twig_SimpleFilter('rokka_resizecrop_url', [$this, 'getResizeCropUrl']),
            new Twig_SimpleFilter('rokka_original_size_url', [$this, 'getOriginalSizeUrl']),
            new Twig_SimpleFilter('rokka_src_attributes', [$this, 'getSrcAttributes'], ['is_safe' => ['html']]),
            new Twig_SimpleFilter('rokka_background_image_style', [$this, 'getBackgroundImageStyle'], ['is_safe' => ['html']]),
            new Twig_SimpleFilter('rokka_add_options', [$this, 'addOptions']),
        ];
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('rokka_generate_url', [$this, 'generateRokkaUrl']),
        ];
    }

    public function getStackUrl($image, $stack, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
        $imageObject = $this->getImageObject($image);
        return $this->getNonEmptyUrl(
            $this->rokka->getStackUrl($imageObject, $stack, $format, $seo, $seoLanguage),
            $image
        );
    }

    public function getResizeUrl($image, $width, $height = null, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
        $imageObject = $this->getImageObject($image);
        return $this->getNonEmptyUrl(
            $this->rokka->getResizeUrl($imageObject, $width, $height, $format, $seo, $seoLanguage),
            $image
        );
    }

    public function getResizeCropUrl($image, $width, $height, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
        $imageObject = $this->getImageObject($image);
        return $this->getNonEmptyUrl(
            $this->rokka->getResizeCropUrl($imageObject, $width, $height, $format, $seo, $seoLanguage),
            $image
        );
    }

    public function getOriginalSizeUrl($image, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
        $imageObject = $this->getImageObject($image);
        return $this->getNonEmptyUrl(
            $this->rokka->getOriginalSizeUrl($imageObject, $format, $seo, $seoLanguage),
            $image
        );
    }


    public function getSrcAttributes(string $url, $multipliers = [2])
    {
        return $this->rokka->getSrcAttributes($url, $multipliers);
    }

    public function getBackgroundImageStyle(string $url, $multipliers = [2])
    {
        return $this->rokka->getBackgroundImageStyle($url, $multipliers);
    }

    public function addOptions(string $url, $options)
    {
        return UriHelper::addOptionsToUriString($url, $options);
    }

    public function generateRokkaUrl($hash, $stack, $format = 'jpg',  $seo = null, $seoLanguage = 'de')
    {
        return $this->rokka->generateRokkaUrl($hash, $stack, $format, $seo, $seoLanguage);
    }

    protected function getNonEmptyUrl($rokkaUrl, $originalUrl) {
        if (!empty($rokkaUrl)) {
            return $rokkaUrl;
        }
        return $originalUrl;
    }

    protected function getImageObject($image)
    {
        if ($image instanceof LocalImageAbstract) {
            return $image;
        }
        if ($this->resolver) {
            return $this->resolver->resolve($image);
        }
        return $image;
    }

}