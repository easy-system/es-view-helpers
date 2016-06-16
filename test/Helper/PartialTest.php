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

use Es\Events\Events;
use Es\View\ViewEvent;
use Es\View\ViewModel;
use Es\ViewHelpers\Helper\Partial;

class PartialTest extends \PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        $values = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $template = 'foo/bar';
        $module   = 'Foo';

        $events = $this->getMock(Events::CLASS);
        $helper = new Partial();
        $helper->setEvents($events);

        $events
            ->expects($this->once())
            ->method('trigger')
            ->with($this->callback(function ($event) use ($template, $values, $module) {
                if (! $event instanceof ViewEvent) {
                    return false;
                }
                $model = $event->getContext();
                if (! $model instanceof ViewModel) {
                    return false;
                }
                if ($template !== $model->getTemplate()) {
                    return false;
                }
                if ($values !== $model->getVariables()) {
                    return false;
                }
                if ($module !== $model->getModule()) {
                    return false;
                }

                return true;
            }));

        $helper($template, $values, $module);
    }
}
