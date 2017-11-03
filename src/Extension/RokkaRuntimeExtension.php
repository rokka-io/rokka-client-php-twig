<?php

namespace Rokka\Twig\Extension;

use Rokka\Client\LocalImage\LocalImageAbstract;
use Rokka\Client\TemplateHelper;
use Rokka\Client\TemplateHelperCallbacksAbstract;
use Rokka\Client\UriHelper;
use Rokka\Twig\Resolver\ResolverInterface;
use Twig_Filter;

class RokkaRuntimeExtension
{

    private $rokka = null;
    private $resolver;

    public function __construct(string $org, string $key, TemplateHelperCallbacksAbstract $callbacks = null, $publicRokkaDomain = null,  ResolverInterface $resolver = null)
    {
        $this->rokka = new TemplateHelper($org, $key, $callbacks, $publicRokkaDomain);
        $this->resolver = $resolver;
    }

    public function getStackUrl($image, $stack, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
        return $this->rokka->getStackUrl($image, $stack, $format, $seo, $seoLanguage);
    }

    public function getResizeUrl($image, $width, $height = null, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
        $image = $this->getImageObject($image);

        return $this->rokka->getResizeUrl($image, $width, $height, $format, $seo, $seoLanguage);
    }

    public function getResizeCropUrl($image, $width, $height, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
        $image = $this->getImageObject($image);
        return $this->rokka->getResizeCropUrl($image, $width, $height, $format, $seo, $seoLanguage);
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

    protected function getImageObject($image) {
        if ($image instanceof LocalImageAbstract) {
            return $image;
        }
        if ($this->resolver) {
            return $this->resolver->resolve($image);
        }
        return $image;
    }
}