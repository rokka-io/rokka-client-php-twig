<?php

namespace Rokka\Twig\Extension;

use Rokka\Client\TemplateHelper;
use Rokka\Client\UriHelper;
use Twig_Filter;

class RokkaRuntimeExtension
{

    private $rokka = null;
    /**
     * @var string
     */
    private $webDir;

    public function __construct(string $org, string $key, string $webDir, TemplateHelperCallbacksAbstract $callbacks = null, $publicRokkaDomain = null)
    {
        $this->rokka = new TemplateHelper($org, $key, $callbacks, $publicRokkaDomain);
        $this->webDir = $webDir;
    }

    public function getStackUrl($image, $stack, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
        return $this->rokka->getStackUrl($image, $stack, $format, $seo, $seoLanguage);
    }

    public function getResizeUrl($image, $width, $height = null, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
        $image = realpath($this->webDir . $image);
        return $this->rokka->getResizeUrl($image, $width, $height, $format, $seo, $seoLanguage);
    }

    public function getResizeCropUrl($image, $width, $height, $format = 'jpg', $seo = null, $seoLanguage = 'de')
    {
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

}