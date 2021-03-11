<?php

namespace Rokka\Twig\Resolver;

use Rokka\Client\LocalImage\AbstractLocalImage;
use Rokka\Client\TemplateHelper;

interface ResolverInterface
{
    /**
     * @param AbstractLocalImage|string|\SplFileInfo $image
     *
     * @return AbstractLocalImage
     */
    public function resolve($image, TemplateHelper $templateHelper);
}
