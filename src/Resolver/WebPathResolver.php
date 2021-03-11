<?php

namespace Rokka\Twig\Resolver;

use Rokka\Client\LocalImage\AbstractLocalImage;
use Rokka\Client\TemplateHelper;

/**
 * Create the Rokka LocalImage from a file information with the local filesystem.
 */
class WebPathResolver implements ResolverInterface
{
    /**
     * @var string
     */
    private $webDir;

    /**
     * @param string $webDir base path for images specified as path
     */
    public function __construct(string $webDir)
    {
        $this->webDir = $webDir;
    }

    /**
     * @param AbstractLocalImage|string|\SplFileInfo $image
     *
     * @throws \RuntimeException if image is a path and the file is not found at $webDir
     */
    public function resolve($image, TemplateHelper $templateHelper): AbstractLocalImage
    {
        if (\is_string($image)) {
            return $templateHelper->getImageObject($this->realpath($this->webDir.$image));
        }

        return $templateHelper->getImageObject($image);
    }

    /**
     * @throws \RuntimeException if image file is not found
     */
    private function realpath(string $path): string
    {
        $realpath = realpath($path);
        if (false === $realpath) {
            throw new \RuntimeException('Image file not found at '.$path);
        }

        return $realpath;
    }
}
