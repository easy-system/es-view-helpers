<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\ViewHelpers;

use Es\Mvc\ViewHelpersInterface;
use Es\Services\ServiceLocator;

/**
 * The Collection of view helpers. Provides helpers on demand.
 */
class ViewHelpers extends ServiceLocator implements ViewHelpersInterface
{
    /**
     * The class of exception, which should be raised if the requested helper
     * is not found.
     */
    const NOT_FOUND_EXCEPTION = 'Es\ViewHelpers\Exception\ViewHelperNotFoundException';

    /**
     * The message of exception, that thrown when unable to find the requested
     * helper.
     *
     * @var string
     */
    const NOT_FOUND_MESSAGE = 'Not found; the View Helper "%s" is unknown.';

    /**
     * The message of exception, that thrown when unable to build the requested
     * helper.
     *
     * @var string
     */
    const BUILD_FAILURE_MESSAGE = 'Failed to create the View Helper "%s".';

    /**
     * The message of exception, that thrown when added of invalid
     * helper specification.
     *
     * @var string
     */
    const INVALID_ARGUMENT_MESSAGE = 'Invalid specification of View Helper "%s"; expects string, "%s" given.';

    /**
     * Gets the helper and sets its options.
     *
     * @param string $name    The helper name
     * @param array  $options Optional; the helper options
     *
     * @return mixed The requested helper
     */
    public function getHelper($name, array $options = [])
    {
        $helper = $this->get($name);
        if (! empty($options) && is_callable([$helper, 'setOptions'])) {
            $helper->setOptions($options);
        }

        return $helper;
    }

    /**
     * Merges with other view helpers.
     *
     * @param \Es\Mvc\ViewHelpersInterface $source The data source
     *
     * @return self
     */
    public function merge(ViewHelpersInterface $source)
    {
        $this->registry  = array_merge($this->registry, $source->getRegistry());
        $this->instances = array_merge($this->instances, $source->getInstances());

        return $this;
    }
}
