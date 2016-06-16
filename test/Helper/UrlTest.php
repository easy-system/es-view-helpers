<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\ViewHelpers\Test\Helper;

use Es\Http\Factory\UriHostFactory;
use Es\Http\Factory\UriSchemeFactory;
use Es\Router\Route;
use Es\Router\Router;
use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\ViewHelpers\Helper\Url;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    use ServicesTrait;

    public function testGetRouter()
    {
        $router   = new Router();
        $services = new Services();
        $services->set('Router', $router);

        $this->setServices($services);
        $helper = new Url();
        $this->assertSame($router, $helper->getRouter());
    }

    public function testSetRouter()
    {
        $services = new Services();
        $this->setServices($services);

        $helper = new Url();
        $router = new Router();
        $helper->setRouter($router);
        $this->assertSame($router, $services->get('Router'));
    }

    public function testFromRoute()
    {
        $router = $this->getMock(Router::CLASS, ['get']);
        $route  = $this->getMockBuilder(Route::CLASS)
            ->disableOriginalConstructor()
            ->setMethods(['assemble'])
            ->getMock();

        $helper = $this->getMock(Url::CLASS, ['getRouter']);

        $routeName   = 'foo';
        $routeParams = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $helper
            ->expects($this->once())
            ->method('getRouter')
            ->will($this->returnValue($router));

        $router
            ->expects($this->once())
            ->method('get')
            ->with($this->identicalTo($routeName))
            ->will($this->returnValue($route));

        $route
            ->expects($this->once())
            ->method('assemble')
            ->with($this->identicalTo($routeParams));

        $helper->fromRoute($routeName, $routeParams);
    }

    public function testSetHost()
    {
        $helper = new Url();
        $host   = 'http://foo.com';

        $helper->setHost($host);
        $this->assertSame($host, $helper->getHost());
    }

    public function testGetHost()
    {
        UriSchemeFactory::setForcedScheme('https');
        UriHostFactory::setForcedHost('foo.bar');

        $helper = new Url();

        $expected = 'https://foo.bar';
        $this->assertSame($expected, $helper->getHost());
    }

    public function testAbsolute()
    {
        $helper = new Url();
        $helper->setHost('http://bar.baz');

        $expected = 'http://bar.baz/woo.css';
        $this->assertSame($expected, $helper->absolute('woo.css'));
    }
}
