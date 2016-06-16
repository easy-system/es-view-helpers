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

use InvalidArgumentException;

/**
 * Escape strings.
 */
class Escaper
{
    /**
     * The strategies.
     *
     * @var array
     */
    protected $strategies = [];

    /**
     * The encoding.
     *
     * @var string
     */
    protected $encoding = 'UTF-8';

    public function __construct()
    {
        $this->setStrategy('html', [$this, 'escapeHtml']);
    }

    /**
     * Sets the strategy.
     *
     * @param string   $name     The strategy name
     * @param callable $strategy The strategy
     */
    public function setStrategy($name, callable $strategy)
    {
        $this->strategies[(string) $name] = $strategy;
    }

    /**
     * Gets the strategy.
     *
     * @param string $name The name of strategy
     *
     * @throws \InvalidArgumentException If the specified strategy not exists
     *
     * @return callable The strategy
     */
    public function getStrategy($name)
    {
        $name = (string) $name;

        if (! isset($this->strategies[$name])) {
            throw new InvalidArgumentException(sprintf(
                'Unknown escaper strategy "%s".',
                $name
            ));
        }

        return $this->strategies[$name];
    }

    /**
     * Is there a strategy?
     *
     * @param string $name The name of strategy
     *
     * @return bool Returns true on succcess, false otherwise
     */
    public function hasStrategy($name)
    {
        return isset($this->strategies[$name]);
    }

    /**
     * Sets the encoding.
     *
     * @param string $encoding The encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = (string) $encoding;
    }

    /**
     * Gets the encoding.
     *
     * @return string The encoding
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Converts special characters to HTML entities.
     *
     * @param string      $string   The string being converted
     * @param null|int    $flags    Optional; null by default means
     *                              ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE
     *                              A bitmask of options
     * @param null|string $encoding Optional; null by default means the default
     *                              encoding. The encoding used when converting
     *                              characters
     *
     * @return string Returns the converted string
     */
    public function escapeHtml($string, $flags = null, $encoding = null)
    {
        if (null === $flags) {
            $flags = ENT_QUOTES | ENT_HTML5;
        }

        $flags |= ENT_SUBSTITUTE;

        if (null === $encoding) {
            $encoding = $this->encoding;
        }

        return htmlspecialchars((string) $string, $flags, $encoding);
    }

    /**
     * The functor.
     * If the string is null, returns specified strategy.
     * Otherwise calls the specified strategy.
     *
     * @param null|string $string   The string being converted or null
     * @param string      $strategy The strategy name
     * @param array       $params   The parameters of strategy
     *
     * @return callable|string Returns the strategy, if the specified string is
     *                         null or converted string otherwise
     */
    public function __invoke($string = null, $strategy = 'html', array $params = [])
    {
        if (null === $string) {
            return $this->getStrategy($strategy);
        }
        array_unshift($params, $string);

        $escaper = $this->getStrategy($strategy);

        return call_user_func_array($escaper, $params);
    }
}
