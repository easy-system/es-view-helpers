<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\ViewHelpers\Test\Listener;

use Es\Modules\ModulesEvent;
use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\System\SystemConfig;
use Es\ViewHelpers\Listener\ConfigureHelpersListener;
use Es\ViewHelpers\ViewHelpers;

class ConfigureHelpersListenerTest extends \PHPUnit_Framework_TestCase
{
    use ServicesTrait;

    public function testGetHelpers()
    {
        $helpers  = new ViewHelpers();
        $services = new Services();
        $services->set('ViewHelpers', $helpers);

        $this->setServices($services);
        $listener = new ConfigureHelpersListener();
        $this->assertSame($helpers, $listener->getHelpers());
    }

    public function testSetHelpers()
    {
        $services = new Services();
        $this->setServices($services);

        $helpers  = new ViewHelpers();
        $listener = new ConfigureHelpersListener();
        $listener->setHelpers($helpers);
        $this->assertSame($helpers, $services->get('ViewHelpers'));
    }

    public function testInvoke()
    {
        $config        = new SystemConfig();
        $helpersConfig = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $config['view_helpers'] = $helpersConfig;

        $helpers = $this->getMock(ViewHelpers::CLASS, ['add']);

        $listener = new ConfigureHelpersListener();
        $listener->setConfig($config);
        $listener->setHelpers($helpers);

        $helpers
            ->expects($this->once())
            ->method('add')
            ->with($this->identicalTo($helpersConfig));

        $listener(new ModulesEvent());
    }
}
