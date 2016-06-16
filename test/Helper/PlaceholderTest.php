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

use Es\ViewHelpers\Helper\Placeholder;
use ReflectionProperty;

class PlaceholderTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleListFromCapture()
    {
        $helper = new Placeholder();
        $helper->setPrefix('<ul>');
        $helper->setPostfix('</ul>');
        $helper->setWrapper('<li class="%s">%s</li>');

        $helper->captureStart();
        echo 'foo';
        $helper->captureEnd(['bar']);

        $helper->captureStart();
        echo 'bar';
        $helper->captureEnd(['baz']);

        $expected = '<ul><li class="bar">foo</li><li class="baz">bar</li></ul>';
        $this->assertSame($expected, (string) $helper);
    }

    public function testSimpleListFromArray()
    {
        $helper = new Placeholder();
        $helper->setPrefix('<ul>');
        $helper->setPostfix('</ul>');
        $helper->setWrapper('<li class="%s">%s</li>');
        $source = [
            ['foo', 'bar'],
            ['bat', 'baz'],
        ];
        $helper->exchange($source);

        $expected = '<ul><li class="foo">bar</li><li class="bat">baz</li></ul>';
        $this->assertSame($expected, (string) $helper);
    }

    public function testSimpleTableFromCapture()
    {
        $helper = new Placeholder();
        $helper->setPrefix('<table>');
        $helper->setPostfix('</table>');
        $helper->setWrapper('<tr class="%s"><td class="%s">%s</td></tr>');

        $helper->captureStart();
        echo 'foo';
        $helper->captureEnd(['bar', 'bat']);

        $expected = '<table><tr class="bar"><td class="bat">foo</td></tr></table>';
        $this->assertSame($expected, (string) $helper);
    }

    public function testSimpleTableFromArray()
    {
        $helper = new Placeholder();
        $helper->setPrefix('<table>');
        $helper->setPostfix('</table>');
        $helper->setWrapper('<tr class="%s"><td class="%s">%s</td></tr>');
        $source = [
            ['foo', 'bat', 'baz'],
            ['com', 'con', 'cop'],
        ];
        $helper->exchange($source);

        $expected = '<table>'
                 . '<tr class="foo"><td class="bat">baz</td></tr>'
                 . '<tr class="com"><td class="con">cop</td></tr>'
                 . '</table>';

        $this->assertSame($expected, (string) $helper);
    }

    public function testSetDefaultParams()
    {
        $params = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $helper = new Placeholder();
        $return = $helper->setDefaultParams($params);
        $this->assertSame($return, $helper);

        $reflection = new ReflectionProperty($helper, 'defaultParams');
        $reflection->setAccessible(true);
        $this->assertSame($params, $reflection->getValue($helper));
    }

    public function testGetDefaultParams()
    {
        $params = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $helper = new Placeholder();
        $helper->setDefaultParams($params);
        $this->assertSame($params, $helper->getDefaultParams());
    }

    public function testSetCaptureTypeRaiseExceptionIfInvalidCaptureTypeProvided()
    {
        $helper = new Placeholder();
        $this->setExpectedException('InvalidArgumentException');
        $helper->setCaptureType('foo');
    }

    public function testSetCaptureTypeOnSuccess()
    {
        $helper = new Placeholder();
        $return = $helper->setCaptureType(Placeholder::SET);
        $this->assertSame($return, $helper);

        $reflection = new ReflectionProperty($helper, 'captureType');
        $reflection->setAccessible(true);
        $this->assertSame(Placeholder::SET, $reflection->getValue($helper));
    }

    public function testGetCaptureType()
    {
        $helper = new Placeholder();
        $helper->setCaptureType(Placeholder::SET);
        $this->assertSame(Placeholder::SET, $helper->getCaptureType());
    }

    public function testSetWrapper()
    {
        $wrapper = '<span>%s</span>';
        $helper  = new Placeholder();
        $return  = $helper->setWrapper($wrapper);
        $this->assertSame($return, $helper);

        $reflection = new ReflectionProperty($helper, 'wrapper');
        $reflection->setAccessible(true);
        $this->assertSame($wrapper, $reflection->getValue($helper));
    }

    public function testGetWrapper()
    {
        $wrapper = '<span>%s</span>';
        $helper  = new Placeholder();
        $helper->setWrapper($wrapper);
        $this->assertSame($wrapper, $helper->getWrapper());
    }

    public function testSetPrefix()
    {
        $prefix = '<title>';
        $helper = new Placeholder();
        $return = $helper->setPrefix($prefix);
        $this->assertSame($return, $helper);

        $reflection = new ReflectionProperty($helper, 'prefix');
        $reflection->setAccessible(true);
        $this->assertSame($prefix, $reflection->getValue($helper));
    }

    public function testGetPrefix()
    {
        $prefix = '<title>';
        $helper = new Placeholder();
        $helper->setPrefix($prefix);
        $this->assertSame($prefix, $helper->getPrefix());
    }

    public function testSetPostfix()
    {
        $postfix = '</title>';
        $helper  = new Placeholder();
        $return  = $helper->setPostfix($postfix);
        $this->assertSame($return, $helper);

        $reflection = new \ReflectionProperty($helper, 'postfix');
        $reflection->setAccessible(true);
        $this->assertSame($postfix, $reflection->getValue($helper));
    }

    public function testGetPostfix()
    {
        $postfix = '</title>';
        $helper  = new Placeholder();
        $helper->setPostfix($postfix);
        $this->assertSame($postfix, $helper->getPostfix());
    }

    public function testSetSeparator()
    {
        $separator = '-';
        $helper    = new Placeholder();
        $return    = $helper->setSeparator($separator);
        $this->assertSame($return, $helper);

        $reflection = new ReflectionProperty($helper, 'separator');
        $reflection->setAccessible(true);
        $this->assertSame($separator, $reflection->getValue($helper));
    }

    public function testGetSeparator()
    {
        $separator = '-';
        $helper    = new Placeholder();
        $helper->setSeparator($separator);
        $this->assertSame($separator, $helper->getSeparator());
    }

    public function testSetIndentFromString()
    {
        $indent = '  ';
        $helper = new Placeholder();
        $return = $helper->setIndent($indent);
        $this->assertSame($return, $helper);

        $reflection = new ReflectionProperty($helper, 'indent');
        $reflection->setAccessible(true);
        $this->assertSame($indent, $reflection->getValue($helper));
    }

    public function testSetIndentFromNuberOfWhitespaces()
    {
        $indent = 2;
        $helper = new Placeholder();
        $return = $helper->setIndent($indent);
        $this->assertSame($return, $helper);

        $reflection = new ReflectionProperty($helper, 'indent');
        $reflection->setAccessible(true);
        $this->assertSame(str_repeat(' ', $indent), $reflection->getValue($helper));
    }

    public function testGetIndent()
    {
        $indent = '  ';
        $helper = new Placeholder();
        $helper->setIndent($indent);
        $this->assertSame($indent, $helper->getIndent());
    }

    public function testExchange()
    {
        $helper = new Placeholder();
        $source = [
            ['foo', 'bar'],
            ['bat', 'baz'],
        ];
        $helper->exchange($source);

        $reflection = new ReflectionProperty($helper, 'container');
        $reflection->setAccessible(true);
        $this->assertSame($source, $reflection->getValue($helper));
    }

    public function testSet()
    {
        $value  = 'foo';
        $helper = new Placeholder();
        $return = $helper->set($value);
        $this->assertSame($return, $helper);

        $reflection = new ReflectionProperty($helper, 'container');
        $reflection->setAccessible(true);
        $this->assertSame([$value], $reflection->getValue($helper));
    }

    public function testAppend()
    {
        $firstValue  = 'foo';
        $secondValue = 'bar';

        $helper = new Placeholder();
        $return = $helper->append($firstValue);
        $this->assertSame($return, $helper);
        $return = $helper->append($secondValue);
        $this->assertSame($return, $helper);

        $expected   = [$firstValue, $secondValue];
        $reflection = new ReflectionProperty($helper, 'container');
        $reflection->setAccessible(true);
        $this->assertSame($expected, $reflection->getValue($helper));
    }

    public function testPrepend()
    {
        $firstValue  = 'foo';
        $secondValue = 'bar';

        $helper = new Placeholder();
        $return = $helper->prepend($firstValue);
        $this->assertSame($return, $helper);
        $return = $helper->prepend($secondValue);
        $this->assertSame($return, $helper);

        $expected   = [$secondValue, $firstValue];
        $reflection = new ReflectionProperty($helper, 'container');
        $reflection->setAccessible(true);
        $this->assertSame($expected, $reflection->getValue($helper));
    }

    public function attachDataProvider()
    {
        return [
            ['foo', Placeholder::SET,     'set'],
            ['bar', Placeholder::APPEND,  'append'],
            ['baz', Placeholder::PREPEND, 'prepend'],
        ];
    }

    /**
     * @dataProvider attachDataProvider
     */
    public function testAttachAttachsValueFromDirectCapture($value, $captureType, $expectedMethod)
    {
        $helper = $this->getMock(Placeholder::CLASS, ['set', 'append', 'prepend']);
        $helper
            ->expects($this->once())
            ->method($expectedMethod)
            ->with($this->identicalTo($value));

        $helper->attach($value, $captureType);
    }

    /**
     * @dataProvider attachDataProvider
     */
    public function testAttachAttachsValueFromGlobalCapture($value, $captureType, $expectedMethod)
    {
        $helper = $this->getMock(Placeholder::CLASS, ['set', 'append', 'prepend']);
        $helper->setCaptureType($captureType);
        $helper
            ->expects($this->once())
            ->method($expectedMethod)
            ->with($this->identicalTo($value));

        $helper->attach($value);
    }

    public function testAttachRaiseExceptionIfInvalidCaptureTypeProvided()
    {
        $helper = new Placeholder();
        $this->setExpectedException('InvalidArgumentException');
        $helper->attach('foo', 'invalid-capture-type');
    }

    public function testIsCaptureStarted()
    {
        $helper = new Placeholder();
        $this->assertFalse($helper->isCaptureStarted());

        $helper->captureStart();
        $this->assertTrue($helper->isCaptureStarted());

        $helper->captureEnd();
        $this->assertFalse($helper->isCaptureStarted());
    }

    public function testCaptureStartRaiseExceptionIfCaptureIsAlreadyStarted()
    {
        $helper = new Placeholder();
        $helper->captureStart();
        ob_end_clean(); // closes output buffer
        $this->setExpectedException('RuntimeException');
        $helper->captureStart();
    }

    public function testCaptureStartSetsCaptureType()
    {
        $helper = new Placeholder();
        $helper->setCaptureType(Placeholder::SET);
        $helper->captureStart(Placeholder::PREPEND);
        ob_end_clean(); // closes output buffer
        $this->assertSame(Placeholder::PREPEND, $helper->getCaptureType());
    }

    public function testCaptureStartOpenOutputBuffer()
    {
        $helper = new Placeholder();
        $level  = ob_get_level();
        $helper->captureStart();
        $this->assertSame($level + 1, ob_get_level());
        ob_end_clean(); // closes output buffer
    }

    public function testCaptureStartFluidInterface()
    {
        $helper = new Placeholder();
        $return = $helper->captureStart();
        $this->assertSame($return, $helper);
        ob_end_clean(); // closes output buffer
    }

    public function testCaptureEndRaiseExceptionIfCaptureHasNotBeenStarted()
    {
        $helper = new Placeholder();
        $this->setExpectedException('RuntimeException');
        $helper->captureEnd();
    }

    public function testCaptureEndCloseOutputBuffer()
    {
        $helper = new Placeholder();
        $helper->captureStart();
        $level = ob_get_level();
        $helper->captureEnd();
        $this->assertSame($level - 1, ob_get_level());
    }

    public function testCaptureEndAttachsCapture()
    {
        $params = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $capture  = 'Lorem ipsum dolor sit amet';
        $expected = array_merge($params, ['capture' => $capture]);

        $helper = $this->getMock(Placeholder::CLASS, ['attach']);

        $helper
            ->expects($this->once())
            ->method('attach')
            ->with($this->identicalTo($expected));

        $helper->captureStart();
        echo $capture;
        $helper->captureEnd($params);
    }

    public function testCaptureEndFluidInterface()
    {
        $helper = new Placeholder();
        $helper->captureStart();
        $return = $helper->captureEnd();
        $this->assertSame($return, $helper);
    }

    public function testGetWhitespaceFromString()
    {
        $indent = '  ';
        $helper = new Placeholder();
        $this->assertSame($indent, $helper->getWhitespace($indent));
    }

    public function testGetWhitespaceFromNumberOfIndents()
    {
        $indent = 2;
        $helper = new Placeholder();
        $this->assertSame(str_repeat(' ', $indent), $helper->getWhitespace($indent));
    }

    public function testToStringChangeIndentFromDirectIndent()
    {
        $helper = new Placeholder();
        $helper->setWrapper('<span>%s</span>');
        $helper->setSeparator("\n");
        $source = ['foo', 'bar', 'baz'];
        $helper->exchange($source);

        $indent   = 3;
        $expected = "<span>foo</span>\n   <span>bar</span>\n   <span>baz</span>";
        $this->assertSame($expected, $helper->toString($indent));
    }

    public function testToStringChangeIndentFromGlobalIndent()
    {
        $indent = 3;
        $helper = new Placeholder();
        $helper->setIndent($indent);
        $helper->setWrapper('<span>%s</span>');
        $helper->setSeparator("\n");
        $source = ['foo', 'bar', 'baz'];
        $helper->exchange($source);

        $expected = "<span>foo</span>\n   <span>bar</span>\n   <span>baz</span>";
        $this->assertSame($expected, $helper->toString());
    }

    public function testInvoke()
    {
        $helper = new Placeholder();

        $first = $helper('foo');
        $this->assertInstanceOf(Placeholder::CLASS, $first);

        $second = $helper('foo');
        $this->assertInstanceOf(Placeholder::CLASS, $second);
        $this->assertSame($first, $second);

        $third = $helper('bar');
        $this->assertInstanceOf(Placeholder::CLASS, $third);
        $this->assertNotSame($first, $third);

        $fourth = $helper('foo');
        $this->assertInstanceOf(Placeholder::CLASS, $fourth);
        $this->assertSame($first, $fourth);
    }

    public function testStringRepresentation()
    {
        $helper   = $this->getMock(Placeholder::CLASS, ['toString']);
        $expected = 'Lorem ipsum dolor sit amet';
        $helper
            ->expects($this->once())
            ->method('toString')
            ->will($this->returnValue($expected));

        $result = (string) $helper;
        $this->assertSame($result, $expected);
    }
}
