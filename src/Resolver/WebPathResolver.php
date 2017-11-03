<?php
namespace Rokka\Twig\Resolver;


use Rokka\Client\LocalImage\LocalImageAbstract;
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

    public function resolve($image): LocalImageAbstract {
        if (is_string($image)) {
            return TemplateHelper::getImageObject(realpath($this->webDir . $image));
        }
        return TemplateHelper::getImageObject($image);
    }
}