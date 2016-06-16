<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\ViewHelpers\Listener;

use Es\Modules\ModulesEvent;
use Es\Mvc\ViewHelpersInterface;
use Es\Services\Provider;
use Es\System\ConfigTrait;

/**
 * Configures the view helpers.
 */
class ConfigureHelpersListener
{
    use ConfigTrait;

    /**
     * Sets the view helpers.
     *
     * @param \Es\Mvc\ViewHelpersInterface $helpers The view helpers
     */
    public function setHelpers(ViewHelpersInterface $helpers)
    {
        Provider::getServices()->set('ViewHelpers', $helpers);
    }

    /**
     * Gets the view helpers.
     *
     * @return \Es\Mvc\ViewHelpersInterface The view helpers
     */
    public function getHelpers()
    {
        return Provider::getServices()->get('ViewHelpers');
    }

    /**
     * Configures the view helpers.
     *
     * @param \Es\Modules\ModulesEvent $event The event of modules
     */
    public function __invoke(ModulesEvent $event)
    {
        $systemConfig = $this->getConfig();
        if (isset($systemConfig['view_helpers'])) {
            $config  = (array) $systemConfig['view_helpers'];
            $helpers = $this->getHelpers();
            $helpers->add($config);
        }
    }
}
