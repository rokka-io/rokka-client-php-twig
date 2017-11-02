<?php

namespace Rokka\Twig\Extension;

use Rokka\Client\TemplateHelper;
use Rokka\Client\UriHelper;
use Twig_Filter;

class RokkaRuntimeExtension
{

    private $rokka = null;

    public function __construct(string $org, string $key, TemplateHelperCallbacksAbstract $callbacks = null, $publicRokkaDomain = null)
    {
        $this->rokka = new TemplateHelper($org, $key, $callbacks, $publicRokkaDomain);
    }

    public function getStackUrl($image, $stack, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
        return $this->rokka->getStackUrl($image, $stack, $format, $seo, $seoLanguage);
    }

    public function getResizeUrl($image, $width, $height = null, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
        return $this->rokka->getResizeUrl($image, $width, $height, $format, $seo, $seoLanguage);
    }

    public function getResizeCropUrl($image, $width, $height, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
        return $this->rokka->getResizeCropUrl($image, $width, $height, $format, $seo, $seoLanguage);
    }

    public function getSrcAttributes(string $url)
    {
        return $this->rokka->getSrcAttributes($url);
    }

    public function getBackgroundImageStyle(string $url)
    {
        return $this->rokka->getBackgroundImageStyle($url);
    }

    public function addOptions(string $url, $options)
    {
        return UriHelper::addOptionsToUriString($url, $options);
    }

    public function generateRokkaUrl($hash, $stack, $format = 'jpg',  $seo = null, $seoLanguage = 'de')
    {
        return $this->rokka->generateRokkaUrl($hash, $stack, $format, $seo, $seoLanguage);
    }

}