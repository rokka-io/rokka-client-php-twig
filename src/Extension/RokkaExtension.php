<?php

namespace Rokka\Twig\Extension;

use Twig_SimpleFilter;
use Twig_SimpleFunction;

class RokkaExtension extends \Twig_Extension
{
    /**
     * @var RokkaRuntimeExtension
     */
    private $rokka;

    public function __construct(string $org, string $key, string $webDir, TemplateHelperCallbacksAbstract $callbacks = null, $publicRokkaDomain = null)
    {
        $this->rokka = new RokkaRuntimeExtension($org, $key, $webDir, $callbacks, $publicRokkaDomain);
    }

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('rokka_stack_url', [$this->rokka, 'getStackUrl']),
            new Twig_SimpleFilter('rokka_resize_url', [$this->rokka, 'getResizeUrl']),
            new Twig_SimpleFilter('rokka_resizecrop_url', [$this->rokka, 'getResizeCropUrl']),
            new Twig_SimpleFilter('rokka_src_attributes', [$this->rokka, 'getSrcAttributes'], ['is_safe' => ['html']]),
            new Twig_SimpleFilter('rokka_background_image_style', [$this->rokka, 'getBackgroundImageStyle'], ['is_safe' => ['html']]),
            new Twig_SimpleFilter('rokka_add_options', [$this->rokka, 'addOptions']),
        ];
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('rokka_generate_url', [$this->rokka, 'generateRokkaUrl']),
        ];
    }

}