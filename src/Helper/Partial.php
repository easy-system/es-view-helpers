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

use Es\Events\EventsTrait;
use Es\View\ViewEvent;
use Es\View\ViewModel;

/**
 * Helper for rendering a template using its own template engine, its own
 * variable scope and (optional) its own module.
 */
class Partial
{
    use EventsTrait;

    /**
     * Renders a template fragment using its own template engine, its own
     * variable scope and (optional) its own module.
     *
     * @param string      $template The name of template
     * @param array       $values   Optional; the variables to populate in the view
     * @param null|string $module   Optional; the module namespace
     *
     * @return string The result of rendering
     */
    public function __invoke($template, array $values = [], $module = null)
    {
        $model = new ViewModel($values);
        $model->setTemplate($template);
        if (! empty($module)) {
            $model->setModule($module);
        }

        $events = $this->getEvents();
        $event  = new ViewEvent($model);
        $events->trigger($event);

        return $event->getResult();
    }
}
