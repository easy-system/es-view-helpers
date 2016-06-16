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

use Es\ViewHelpers\Exception\ViewHelperNotFoundException;
use Es\ViewHelpers\ViewHelpers;

class ViewHelpersTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once 'FakeHelper.php';
    }

    public function testMergeRegistry()
    {
        $targetConfig = [
            'foo' => 'foo',
            'bar' => 'foo',
        ];
        $target = new ViewHelpers();
        $target->add($targetConfig);

        $sourceConfig = [
            'bar' => 'bar',
            'baz' => 'baz',
        ];
        $source = new ViewHelpers();
        $source->add($sourceConfig);

        $return = $target->merge($source);
        $this->assertSame($return, $target);

        $expected = [
            'foo' => $targetConfig['foo'],
            'bar' => $sourceConfig['bar'],
            'baz' => $sourceConfig['baz'],
        ];
        $this->assertSame($expected, $target->getRegistry());
    }

    public function testMergeInstances()
    {
        $targetConfig = [
            'foo' => new \stdClass(),
            'bar' => new \stdClass(),
        ];
        $target = new ViewHelpers();
        foreach ($targetConfig as $key => $item) {
            $target->set($key, $item);
        }

        $sourceConfig = [
            'bar' => new \stdClass(),
            'baz' => new \stdClass(),
        ];
        $source = new ViewHelpers();
        foreach ($sourceConfig as $key => $item) {
            $source->set($key, $item);
        }

        $return = $target->merge($source);
        $this->assertSame($return, $target);

        $expected = [
            'foo' => $targetConfig['foo'],
            'bar' => $sourceConfig['bar'],
            'baz' => $sourceConfig['baz'],
        ];
        $this->assertSame($expected, $target->getInstances());
    }

    public function testGetHelper()
    {
        $options = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $helper  = $this->getMock(FakeHelper::CLASS);
        $helpers = new ViewHelpers();
        $helpers->set('foo', $helper);

        $helper
            ->expects($this->once())
            ->method('setOptions')
            ->with($this->identicalTo($options));

        $return = $helpers->getHelper('foo', $options);
        $this->assertSame($return, $helper);
    }

    public function testExteptionClassWhenHelperNotFound()
    {
        $plugins = new ViewHelpers();
        $this->setExpectedException(ViewHelperNotFoundException::CLASS);
        $plugins->get('foo');
    }
}
