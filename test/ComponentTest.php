<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\ViewHelpers\Test;

use Es\ViewHelpers\Component;

class ComponentTest extends \PHPUnit_Framework_TestCase
{
    protected $requiredServices = [
        'ViewHelpers',
    ];

    protected $requiredHelpers = [
        'escape',
        'partial',
        'placeholder',
        'url',
    ];

    public function testGetVersion()
    {
        $component = new Component();
        $version   = $component->getVersion();
        $this->assertInternalType('string', $version);
        $this->assertRegExp('#\d+.\d+.\d+#', $version);
    }

    public function testGetServicesConfig()
    {
        $component = new Component();
        $config    = $component->getServicesConfig();
        $this->assertInternalType('array', $config);
        foreach ($this->requiredServices as $item) {
            $this->assertArrayHasKey($item, $config);
        }
    }

    public function testGetListenersConfig()
    {
        $component = new Component();
        $config    = $component->getListenersConfig();
        $this->assertInternalType('array', $config);
    }

    public function testGetEventsConfig()
    {
        $component = new Component();
        $config    = $component->getEventsConfig();
        $this->assertInternalType('array', $config);
    }

    public function testGetSystemConfig()
    {
        $component = new Component();
        $config    = $component->getSystemConfig();
        $this->assertInternalType('array', $config);
        $this->assertArrayHasKey('view_helpers', $config);
        $helpers = $config['view_helpers'];
        foreach ($this->requiredHelpers as $item) {
            $this->assertArrayHasKey($item, $helpers);
        }
    }
}
