<?php

declare(strict_types=1);

namespace App\Router;

use Nette;

use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {

        $router = new RouteList;
        $router
            ->addRoute('', 'Home:home')
            ->addRoute('about', 'Home:about')
            ->addRoute('contacts', 'Home:contacts')
            ->addRoute('parliament', 'Pages:parliament')
            ->addRoute('passport', 'Pages:passport')
            ->addRoute('news', 'News:index')

            ->addRoute('sign/out', 'Sign:out')
            ->addRoute('sign/in', 'Sign:in')
            ->addRoute('sign/up', 'Sign:up');

        return $router;
    }
}
