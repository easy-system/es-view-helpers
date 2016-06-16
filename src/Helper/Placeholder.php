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

use ArrayAccess;
use Es\Container\AbstractContainer;
use Es\Container\ArrayAccess\ArrayAccessTrait;
use Es\Container\Property\PropertyTrait;
use Es\Container\Property\PropertyInterface;
use InvalidArgumentException;
use RuntimeException;

/**
 * The Placeholder.
 */
class Placeholder extends AbstractContainer implements ArrayAccess, PropertyInterface
{
    use ArrayAccessTrait, PropertyTrait;

    /**#@+
     * @const string The capture type constant
     */
    const SET     = 'Set';
    const APPEND  = 'Append';
    const PREPEND = 'Prepend';
    /**#@-*/

    /**
     * The placeholders.
     *
     * @var array
     */
    protected static $placeholders = [];

    /**
     * The default parameters.
     *
     * @var array
     */
    protected $defaultParams = [];

    /**
     * The type of capture.
     *
     * @var string
     */
    protected $captureType = self::APPEND;

    /**
     * The wrapper.
     *
     * @var string
     */
    protected $wrapper = '%s';

    /**
     * The prefix.
     *
     * @var string
     */
    protected $prefix = '';

    /**
     * The postfix.
     *
     * @var string
     */
    protected $postfix = '';

    /**
     * The separator.
     *
     * @var string
     */
    protected $separator = '';

    /**
     * The indent.
     *
     * @var string
     */
    protected $indent = '';

    /**
     * Is capture started?
     *
     * @var bool
     */
    protected $captureStarted = false;

    /**
     * Sets the default parameters.
     *
     * @param array $params The default parameters
     *
     * @return self
     */
    public function setDefaultParams(array $params)
    {
        $this->defaultParams = $params;

        return $this;
    }

    /**
     * Gets the default parameters.
     *
     * @return array
     */
    public function getDefaultParams()
    {
        return $this->defaultParams;
    }

    /**
     * Sets the type of capture.
     *
     * @param string $type Any of the capture type constants
     *
     * @throws \InvalidArgumentException If invalid capture type provided
     *
     * @return self
     */
    public function setCaptureType($type)
    {
        if ($type === static::SET
            || $type === static::APPEND
            || $type === static::PREPEND
        ) {
            $this->captureType = $type;

            return $this;
        }

        throw new InvalidArgumentException(sprintf(
            'Invalid capture type provided; must be any of the capture '
            . 'type constants, "%s" received.',
            is_scalar($type) ? $type : gettype($type)
        ));
    }

    /**
     * Gets the type of capture.
     *
     * @return string Any of the capture type constants
     */
    public function getCaptureType()
    {
        return $this->captureType;
    }

    /**
     * Sets the wrapper for __toString() serialization.
     *
     * @link http://php.net/manual/en/function.vsprintf.php
     * @link http://php.net/manual/en/function.sprintf.php
     *
     * @param string $wrapper The wrapper of parameters
     *
     * @return self
     */
    public function setWrapper($wrapper)
    {
        $this->wrapper = (string) $wrapper;

        return $this;
    }

    /**
     * Gets the wrapper for __toString() serialization.
     *
     * @link http://php.net/manual/en/function.vsprintf.php
     * @link http://php.net/manual/en/function.sprintf.php
     *
     * @return string The wrapper of parameters
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }

    /**
     * Sets the prefix for __toString() serialization.
     *
     * @param string $prefix The prefix
     *
     * @return self
     */
    public function setPrefix($prefix)
    {
        $this->prefix = (string) $prefix;

        return $this;
    }

    /**
     * Gets the prefix for __toString() serialization.
     *
     * @return string The prefix
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Sets the postfix for __toString() serialization.
     *
     * @param string $postfix The postfix
     *
     * @return self
     */
    public function setPostfix($postfix)
    {
        $this->postfix = $postfix;

        return $this;
    }

    /**
     * Gets the postfix for __toString() serialization.
     *
     * @return string The postfix
     */
    public function getPostfix()
    {
        return $this->postfix;
    }

    /**
     * Sets the separator for __toString() serialization.
     *
     * @param string $separator The separator
     *
     * @return self
     */
    public function setSeparator($separator)
    {
        $this->separator = (string) $separator;

        return $this;
    }

    /**
     * Gets the separator for __toString() serialization.
     *
     * @return string The separator
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * Sets the indendent for __toString() serialization.
     *
     * @param string|int $indent The indent as string or number of spaces
     *
     * @return self
     */
    public function setIndent($indent)
    {
        $this->indent = $this->getWhitespace($indent);

        return $this;
    }

    /**
     * Gets the indent for __toString() serialization.
     *
     * @return string The indent
     */
    public function getIndent()
    {
        return $this->indent;
    }

    /**
     * Exchanges a data.
     *
     * @param array $source The new array to exchange with the current container
     */
    public function exchange(array $source)
    {
        $this->container = $source;
    }

    /**
     * Sets the value.
     *
     * @param mixed $value The value to set
     *
     * @return self
     */
    public function set($value)
    {
        $this->container = [$value];

        return $this;
    }

    /**
     * Appends the value.
     *
     * @param mixed $value The value to append
     *
     * @return self
     */
    public function append($value)
    {
        $this->container[] = $value;

        return $this;
    }

    /**
     * Prepends the value.
     *
     * @param mixed $value The value to prepend
     *
     * @return self
     */
    public function prepend($value)
    {
        array_unshift($this->container, $value);

        return $this;
    }

    /**
     * Attaches the value.
     *
     * @param mixed       $value   The value
     * @param null|string $capture Optional; any of the capture type constants
     *
     * @throws \InvalidArgumentException If invalid capture type provided
     *
     * @return self
     */
    public function attach($value, $capture = null)
    {
        if (null === $capture) {
            $capture = $this->captureType;
        }

        switch ($capture) {
            case self::APPEND:
                $this->append($value);
                break;
            case self::PREPEND:
                $this->prepend($value);
                break;
            case self::SET:
                $this->set($value);
                break;
            default:
                throw new InvalidArgumentException(sprintf(
                    'Invalid capture type provided; must be any of the capture '
                    . 'type constants, "%s" received.',
                    is_scalar($capture) ? $capture : gettype($capture)
                ));
        }

        return $this;
    }

    /**
     * Is capture started?
     *
     * @return bool Returns true on success, false otherwise
     */
    public function isCaptureStarted()
    {
        return $this->captureStarted;
    }

    /**
     * Starts the capture.
     *
     * @param null|string $type Optional; any of the capture type constants
     *
     * @throws \RuntimeException If capture is already started
     *
     * @return self
     */
    public function captureStart($type = null)
    {
        if ($this->captureStarted) {
            throw new RuntimeException('Capture is already started.');
        }
        if (null !== $type) {
            $this->setCaptureType($type);
        }
        $this->captureStarted = true;

        ob_start();

        return $this;
    }

    /**
     * Ends the capture.
     *
     * @param array $params Optional; the capture parameters
     *
     * @throws \RuntimeException If the capture has not been started
     *
     * @return self
     */
    public function captureEnd(array $params = [])
    {
        if (! $this->captureStarted) {
            throw new RuntimeException('Capture has not been started.');
        }
        $params['capture'] = ob_get_clean();
        $this->attach($params);
        $this->captureStarted = false;

        return $this;
    }

    /**
     * Gets the string representation of indent.
     *
     * @param string|int $indent The indent as string or number of spaces
     *
     * @return string The string representation of indent
     */
    public function getWhitespace($indent)
    {
        if (is_int($indent)) {
            return str_repeat(' ', $indent);
        }

        return (string) $indent;
    }

    /**
     * Renders the placeholder.
     *
     * @param null|string|int $indent Optional; the indent
     *
     * @return string The string representation of placeholder
     */
    public function toString($indent = null)
    {
        $indent = ($indent !== null)
                ? $this->getWhitespace($indent)
                : $this->getIndent();

        $return    = $this->prefix;
        $keys      = array_keys($this->container);
        $lastIndex = end($keys);
        foreach ($this->container as $index => $item) {
            $item = array_merge($this->defaultParams, (array) $item);
            $return .= vsprintf($this->wrapper, $item);
            if ($index !== $lastIndex) {
                $return .= $this->separator;
            }
        }
        $return .= $this->postfix;

        return preg_replace("/(\r\n?|\n)[ ]*/", '$1' . $indent, $return);
    }

    /**
     * Gets the placeholder by name.
     *
     * @param string $name Optional; the name of placeholder
     *
     * @return self Returns the instance with specified name
     */
    public function __invoke($name = 'default')
    {
        $name = (string) $name;
        if (! isset(self::$placeholders[$name])) {
            self::$placeholders[$name] = new static();
        }

        return self::$placeholders[$name];
    }

    /**
     * Gets the string representation of placeholder.
     *
     * @return string The string representation of placeholder
     */
    public function __toString()
    {
        return $this->toString();
    }
}
