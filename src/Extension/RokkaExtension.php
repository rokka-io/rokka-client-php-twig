<?php

namespace Rokka\Twig\Extension;

use Rokka\Client\Base as ClientBase;
use Rokka\Client\LocalImage\AbstractLocalImage;
use Rokka\Client\TemplateHelper;
use Rokka\Client\TemplateHelper\AbstractCallbacks;
use Rokka\Client\UriHelper;
use Rokka\Twig\Resolver\ResolverInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class RokkaExtension extends AbstractExtension
{
    /**
     * @var TemplateHelper
     */
    private $rokka;

    /**
     * @var ResolverInterface|null
     */
    private $resolver = null;

    public function __construct(
        string $organization,
        string $apiKey,
        AbstractCallbacks $callbacks = null,
        string $publicRokkaDomain = null,
        ResolverInterface $resolver = null,
        string $rokkaApiHost = ClientBase::DEFAULT_API_BASE_URL
    ) {
        $this->rokka = new TemplateHelper($organization, $apiKey, $callbacks, $publicRokkaDomain, $rokkaApiHost);
        $this->resolver = $resolver;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('rokka_stack_url', [$this, 'getStackUrl']),
            new TwigFilter('rokka_resize_url', [$this, 'getResizeUrl']),
            new TwigFilter('rokka_resizecrop_url', [$this, 'getResizeCropUrl']),
            new TwigFilter('rokka_original_size_url', [$this, 'getOriginalSizeUrl']),
            new TwigFilter('rokka_src_attributes', [$this, 'getSrcAttributes'], ['is_safe' => ['html']]),
            new TwigFilter('rokka_background_image_style', [$this, 'getBackgroundImageStyle'], ['is_safe' => ['html']]),
            new TwigFilter('rokka_add_options', [$this, 'addOptions']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('rokka_generate_url', [$this, 'generateRokkaUrl']),
        ];
    }

    /**
     * @param AbstractLocalImage|string|\SplFileInfo $image
     * @param string                                 $stack       The stack to render the image
     * @param string                                 $format      The desired format of the image (jpg, png, webp, ...)
     * @param string|null                            $seo         if you want a different seo string than the image file name
     * @param string                                 $seoLanguage Optional language to be used for slugifying (eg. 'de' slugifies 'ö' to 'oe')
     */
    public function getStackUrl($image, string $stack, string $format = 'jpg', ?string $seo = null, string $seoLanguage = 'de'): string
    {
        $imageObject = $this->getImageObject($image);

        return $this->getNonEmptyUrl(
            $this->rokka->getStackUrl($imageObject, $stack, $format, $seo, $seoLanguage),
            $image
        );
    }

    /**
     * Scale image so it is no wider than width, and if height is given, no heigher than height.
     *
     * @param AbstractLocalImage|string|\SplFileInfo $image
     * @param int                                    $width       The max width for the image
     * @param int|null                               $height      The max height for the image. If not set, image will always be width wide and the height varies
     * @param string                                 $format      The desired format of the image (jpg, png, webp, ...)
     * @param string|null                            $seo         if you want a different seo string than the image file name
     * @param string                                 $seoLanguage Optional language to be used for slugifying (eg. 'de' slugifies 'ö' to 'oe')
     */
    public function getResizeUrl($image, int $width, ?int $height = null, string $format = 'jpg', ?string $seo = null, string $seoLanguage = 'de'): string
    {
        $imageObject = $this->getImageObject($image);

        return $this->getNonEmptyUrl(
            $this->rokka->getResizeUrl($imageObject, $width, $height, $format, $seo, $seoLanguage),
            $image
        );
    }

    /**
     * Scale the image and crop to be always exactly width x height.
     *
     * @param AbstractLocalImage|string|\SplFileInfo $image
     * @param int                                    $width       The max width for the image
     * @param int                                    $height      the max height for the image
     * @param string                                 $format      The desired format of the image (jpg, png, webp, ...)
     * @param string|null                            $seo         if you want a different seo string than the image file name
     * @param string                                 $seoLanguage Optional language to be used for slugifying (eg. 'de' slugifies 'ö' to 'oe')
     */
    public function getResizeCropUrl($image, int $width, int $height, string $format = 'jpg', string $seo = null, string $seoLanguage = 'de'): string
    {
        $imageObject = $this->getImageObject($image);

        return $this->getNonEmptyUrl(
            $this->rokka->getResizeCropUrl($imageObject, $width, $height, $format, $seo, $seoLanguage),
            $image
        );
    }

    /**
     * @param AbstractLocalImage|string|\SplFileInfo $image
     */
    public function getOriginalSizeUrl($image, string $format = 'jpg', string $seo = null, string $seoLanguage = 'de'): string
    {
        $imageObject = $this->getImageObject($image);

        return $this->getNonEmptyUrl(
            $this->rokka->getOriginalSizeUrl($imageObject, $format, $seo, $seoLanguage),
            $image
        );
    }

    /**
     * @param int[] $multipliers List of multipliers to apply
     */
    public function getSrcAttributes(string $url, array $multipliers = [2]): string
    {
        return $this->rokka->getSrcAttributes($url, $multipliers);
    }

    /**
     * @param int[] $multipliers List of multipliers to apply
     */
    public function getBackgroundImageStyle(string $url, array $multipliers = [2]): string
    {
        return $this->rokka->getBackgroundImageStyle($url, $multipliers);
    }

    /**
     * @param string[]|string $options
     */
    public function addOptions(string $url, $options): string
    {
        return UriHelper::addOptionsToUriString($url, $options);
    }

    public function generateRokkaUrl(string $hash, string $stack, string $format = 'jpg', string $seo = null, string $seoLanguage = 'de'): string
    {
        return $this->rokka->generateRokkaUrl($hash, $stack, $format, $seo, $seoLanguage);
    }

    /**
     * @param AbstractLocalImage|string|\SplFileInfo $originalUrl
     */
    private function getNonEmptyUrl(?string $rokkaUrl, $originalUrl): string
    {
        if (!empty($rokkaUrl)) {
            return $rokkaUrl;
        }

        if (\is_string($originalUrl)) {
            return $originalUrl;
        }

        throw new \RuntimeException('Unable to generate a rokka url from '.var_export($originalUrl, true));
    }

    /**
     * @param AbstractLocalImage|string|\SplFileInfo $image
     *
     * @return AbstractLocalImage|string|\SplFileInfo
     */
    private function getImageObject($image)
    {
        if ($image instanceof AbstractLocalImage) {
            return $image;
        }
        if ($this->resolver) {
            return $this->resolver->resolve($image, $this->rokka);
        }

        return $image;
    }
}
