<?php


namespace Rokka\Twig\Resolver;


use Rokka\Client\LocalImage\LocalImageAbstract;

interface ResolverInterface
{
    public function resolve($image): LocalImageAbstract;

}