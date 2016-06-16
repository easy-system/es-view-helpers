<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\ViewHelpers\Exception;

use Es\Exception\ExceptionInterface;
use Exception;

class ViewHelperNotFoundException extends Exception implements ExceptionInterface
{
}
