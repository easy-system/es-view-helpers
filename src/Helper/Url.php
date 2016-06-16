<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\ViewHelpers\Helper;

use Es\Http\Factory\UriHostFactory;
use Es\Http\Factory\UriSchemeFactory;
use Es\Router\RouterInterface;
use Es\Services\Provider;

/**
 * Generates Url.
 */
class Url
{
    /**
     * The host.
     *
     * @var string
     */
    protected $host = '';

    /**
     * Sets router.
     *
     * @param \Es\Router\RouterInterface $router The router
     */
    public function setRouter(RouterInterface $router)
    {
        Provider::getServices()->set('Router', $router);
    }

    /**
     * Gets router.
     *
     * @return \Es\Router\RouterInterface The router
     */
    public function getRouter()
    {
        return Provider::getServices()->get('Router');
    }

    /**
     * Gets url from route.
     *
     * @param string $name   The route name
     * @param array  $params Optional; the route parameters
     *
     * @return string The url
     */
    public function fromRoute($name, array $params = [])
    {
        $router = $this->getRouter();
        $route  = $router->get($name);

        return $route->assemble($params);
    }

    /**
     * Gets the absolute address.
     *
     * @param null|string $relative Optional; the relative address
     *
     * @return string Absolute address
     */
    public function absolute($relative = null)
    {
        $host = $this->getHost();

        if (null !== $relative) {
            $relative = '/' . ltrim($relative, '/');
        }

        return $host . $relative;
    }

    /**
     * Gets the host.
     *
     * @return string The host
     */
    public function getHost()
    {
        if (! $this->host) {
            $this->host = UriSchemeFactory::make() . '://' . UriHostFactory::make();
        }

        return $this->host;
    }

    /**
     * Sets the host.
     *
     * @param string $host The host
     */
    public function setHost($host)
    {
        $this->host = (string) $host;
    }
}
