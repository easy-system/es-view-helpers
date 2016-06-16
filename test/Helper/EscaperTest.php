<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Vew\Test\Helper;

use Es\ViewHelpers\Helper\Escaper;

class EscaperTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $escaper = new Escaper();
        $this->assertTrue($escaper->hasStrategy('html'));
    }

    public function testSetStrategy()
    {
        $escaper = new Escaper();
        $escaper->setStrategy('html', 'htmlentities');
        $this->assertSame('htmlentities', $escaper->getStrategy('html'));
    }

    public function testGetStrategyRaiseExceptionIfStrategyIsUnknown()
    {
        $escaper = new Escaper();
        $this->setExpectedException('InvalidArgumentException');
        $escaper->getStrategy('unknown-strategy');
    }

    public function testHasStrategy()
    {
        $escaper = new Escaper();
        $escaper->setStrategy('foo', function () {});
        $this->assertTrue($escaper->hasStrategy('foo'));
        $this->assertFalse($escaper->hasStrategy('bar'));
    }

    public function testSetEncoding()
    {
        $escaper = new Escaper();
        $escaper->setEncoding('ISO-8859-1');
        $this->assertSame('ISO-8859-1', $escaper->getEncoding());
    }

    public function testGetEncoding()
    {
        $escaper = new Escaper();
        $this->assertSame('UTF-8', $escaper->getEncoding());
    }

    public function escapeHtmlDataProvider()
    {
        return [
            ['<b>Foo</b>', null, '&lt;b&gt;Foo&lt;/b&gt;'],
            ['"Foo"', ENT_NOQUOTES, '"Foo"'],
        ];
    }

    /**
     * @dataProvider escapeHtmlDataProvider
     */
    public function testEscapeHtml($string, $flags, $expected)
    {
        $escaper = new Escaper();
        $this->assertSame($expected, $escaper->escapeHtml($string, $flags));
    }

    public function testInvokeCallStrategy()
    {
        $strategy = function () {
            return func_get_args();
        };

        $escaper = new Escaper();
        $escaper->setStrategy('foo', $strategy);
        $expected = ['string', 'bar', 'baz'];
        $this->assertSame($expected, $escaper('string', 'foo', ['bar', 'baz']));
    }

    public function testInvokeReturnsStrategy()
    {
        $strategy = function () {};
        $escaper = new Escaper();
        $escaper->setStrategy('foo', $strategy);
        $this->assertSame($strategy, $escaper(null, 'foo'));
    }
}
