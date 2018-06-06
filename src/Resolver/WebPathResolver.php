<?php

namespace Rokka\Twig\Resolver;

use Rokka\Client\LocalImage\AbstractLocalImage;
use Rokka\Client\TemplateHelper;

class WebPathResolver implements ResolverInterface
{
    /**
     * @var string
     */
    private $webDir;

    public function __construct(string $webDir)
    {
        $this->webDir = $webDir;
    }

    /**
     * @param AbstractLocalImage|string|\SplFileInfo $image
     * @param TemplateHelper                         $templateHelper
     *
     * @return AbstractLocalImage
     */
    public function resolve($image, $templateHelper)
    {
        if (is_string($image)) {
            return $templateHelper->getImageObject(realpath($this->webDir.$image));
        }

        return $templateHelper->getImageObject($image);
    }
}
