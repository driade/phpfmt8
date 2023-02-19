<?php namespace {$inPhar = true;} 
# Copyright (c) 2015, phpfmt and its authors
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
#
# 1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
#
# 2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
#
# 3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
#
# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

namespace {
	if (version_compare(phpversion(), '5.6.0', '<')) {
		fwrite(STDERR, "PHP needs to be a minimum version of PHP 5.6.0\n");
		exit(255);
	}
}



	


namespace Symfony\Component\Console\Formatter{


interface OutputFormatterInterface
{
    
    public function setDecorated($decorated);

    
    public function isDecorated();

    
    public function setStyle($name, OutputFormatterStyleInterface $style);

    
    public function hasStyle($name);

    
    public function getStyle($name);

    
    public function format($message);
}

}

	


namespace Symfony\Component\Console\Helper{


interface HelperInterface
{
    
    public function setHelperSet(HelperSet $helperSet = null);

    
    public function getHelperSet();

    
    public function getName();
}

}

	


namespace Symfony\Component\Console\Helper{

use Symfony\Component\Console\Formatter\OutputFormatterInterface;


abstract class Helper implements HelperInterface
{
    protected $helperSet = null;

    
    public function setHelperSet(HelperSet $helperSet = null)
    {
        $this->helperSet = $helperSet;
    }

    
    public function getHelperSet()
    {
        return $this->helperSet;
    }

    
    public static function strlen($string)
    {
        if (!function_exists('mb_strwidth')) {
            return strlen($string);
        }

        if (false === $encoding = mb_detect_encoding($string)) {
            return strlen($string);
        }

        return mb_strwidth($string, $encoding);
    }

    public static function formatTime($secs)
    {
        static $timeFormats = array(
            array(0, '< 1 sec'),
            array(2, '1 sec'),
            array(59, 'secs', 1),
            array(60, '1 min'),
            array(3600, 'mins', 60),
            array(5400, '1 hr'),
            array(86400, 'hrs', 3600),
            array(129600, '1 day'),
            array(604800, 'days', 86400),
        );

        foreach ($timeFormats as $format) {
            if ($secs >= $format[0]) {
                continue;
            }

            if (2 == count($format)) {
                return $format[1];
            }

            return ceil($secs / $format[2]).' '.$format[1];
        }
    }

    public static function formatMemory($memory)
    {
        if ($memory >= 1024 * 1024 * 1024) {
            return sprintf('%.1f GiB', $memory / 1024 / 1024 / 1024);
        }

        if ($memory >= 1024 * 1024) {
            return sprintf('%.1f MiB', $memory / 1024 / 1024);
        }

        if ($memory >= 1024) {
            return sprintf('%d KiB', $memory / 1024);
        }

        return sprintf('%d B', $memory);
    }

    public static function strlenWithoutDecoration(OutputFormatterInterface $formatter, $string)
    {
        $isDecorated = $formatter->isDecorated();
        $formatter->setDecorated(false);
                $string = $formatter->format($string);
                $string = preg_replace("/\033\[[^m]*m/", '', $string);
        $formatter->setDecorated($isDecorated);

        return self::strlen($string);
    }
}

}

	


namespace Symfony\Component\Console\Formatter{


class OutputFormatterStyleStack
{
    
    private $styles;

    
    private $emptyStyle;

    
    public function __construct(OutputFormatterStyleInterface $emptyStyle = null)
    {
        $this->emptyStyle = $emptyStyle ?: new OutputFormatterStyle();
        $this->reset();
    }

    
    public function reset()
    {
        $this->styles = array();
    }

    
    public function push(OutputFormatterStyleInterface $style)
    {
        $this->styles[] = $style;
    }

    
    public function pop(OutputFormatterStyleInterface $style = null)
    {
        if (empty($this->styles)) {
            return $this->emptyStyle;
        }

        if (null === $style) {
            return array_pop($this->styles);
        }

        foreach (array_reverse($this->styles, true) as $index => $stackedStyle) {
            if ($style->apply('') === $stackedStyle->apply('')) {
                $this->styles = array_slice($this->styles, 0, $index);

                return $stackedStyle;
            }
        }

        throw new \InvalidArgumentException('Incorrectly nested style tag found.');
    }

    
    public function getCurrent()
    {
        if (empty($this->styles)) {
            return $this->emptyStyle;
        }

        return $this->styles[count($this->styles) - 1];
    }

    
    public function setEmptyStyle(OutputFormatterStyleInterface $emptyStyle)
    {
        $this->emptyStyle = $emptyStyle;

        return $this;
    }

    
    public function getEmptyStyle()
    {
        return $this->emptyStyle;
    }
}

}

	


namespace Symfony\Component\Console\Formatter{


interface OutputFormatterStyleInterface
{
    
    public function setForeground($color = null);

    
    public function setBackground($color = null);

    
    public function setOption($option);

    
    public function unsetOption($option);

    
    public function setOptions(array $options);

    
    public function apply($text);
}

}

	


namespace Symfony\Component\Console\Formatter{


class OutputFormatterStyle implements OutputFormatterStyleInterface
{
    private static $availableForegroundColors = array(
        'black' => array('set' => 30, 'unset' => 39),
        'red' => array('set' => 31, 'unset' => 39),
        'green' => array('set' => 32, 'unset' => 39),
        'yellow' => array('set' => 33, 'unset' => 39),
        'blue' => array('set' => 34, 'unset' => 39),
        'magenta' => array('set' => 35, 'unset' => 39),
        'cyan' => array('set' => 36, 'unset' => 39),
        'white' => array('set' => 37, 'unset' => 39),
        'default' => array('set' => 39, 'unset' => 39),
    );
    private static $availableBackgroundColors = array(
        'black' => array('set' => 40, 'unset' => 49),
        'red' => array('set' => 41, 'unset' => 49),
        'green' => array('set' => 42, 'unset' => 49),
        'yellow' => array('set' => 43, 'unset' => 49),
        'blue' => array('set' => 44, 'unset' => 49),
        'magenta' => array('set' => 45, 'unset' => 49),
        'cyan' => array('set' => 46, 'unset' => 49),
        'white' => array('set' => 47, 'unset' => 49),
        'default' => array('set' => 49, 'unset' => 49),
    );
    private static $availableOptions = array(
        'bold' => array('set' => 1, 'unset' => 22),
        'underscore' => array('set' => 4, 'unset' => 24),
        'blink' => array('set' => 5, 'unset' => 25),
        'reverse' => array('set' => 7, 'unset' => 27),
        'conceal' => array('set' => 8, 'unset' => 28),
    );

    private $foreground;
    private $background;
    private $options = array();

    
    public function __construct($foreground = null, $background = null, array $options = array())
    {
        if (null !== $foreground) {
            $this->setForeground($foreground);
        }
        if (null !== $background) {
            $this->setBackground($background);
        }
        if (count($options)) {
            $this->setOptions($options);
        }
    }

    
    public function setForeground($color = null)
    {
        if (null === $color) {
            $this->foreground = null;

            return;
        }

        if (!isset(static::$availableForegroundColors[$color])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid foreground color specified: "%s". Expected one of (%s)',
                $color,
                implode(', ', array_keys(static::$availableForegroundColors))
            ));
        }

        $this->foreground = static::$availableForegroundColors[$color];
    }

    
    public function setBackground($color = null)
    {
        if (null === $color) {
            $this->background = null;

            return;
        }

        if (!isset(static::$availableBackgroundColors[$color])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid background color specified: "%s". Expected one of (%s)',
                $color,
                implode(', ', array_keys(static::$availableBackgroundColors))
            ));
        }

        $this->background = static::$availableBackgroundColors[$color];
    }

    
    public function setOption($option)
    {
        if (!isset(static::$availableOptions[$option])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid option specified: "%s". Expected one of (%s)',
                $option,
                implode(', ', array_keys(static::$availableOptions))
            ));
        }

        if (!in_array(static::$availableOptions[$option], $this->options)) {
            $this->options[] = static::$availableOptions[$option];
        }
    }

    
    public function unsetOption($option)
    {
        if (!isset(static::$availableOptions[$option])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid option specified: "%s". Expected one of (%s)',
                $option,
                implode(', ', array_keys(static::$availableOptions))
            ));
        }

        $pos = array_search(static::$availableOptions[$option], $this->options);
        if (false !== $pos) {
            unset($this->options[$pos]);
        }
    }

    
    public function setOptions(array $options)
    {
        $this->options = array();

        foreach ($options as $option) {
            $this->setOption($option);
        }
    }

    
    public function apply($text)
    {
        $setCodes = array();
        $unsetCodes = array();

        if (null !== $this->foreground) {
            $setCodes[] = $this->foreground['set'];
            $unsetCodes[] = $this->foreground['unset'];
        }
        if (null !== $this->background) {
            $setCodes[] = $this->background['set'];
            $unsetCodes[] = $this->background['unset'];
        }
        if (count($this->options)) {
            foreach ($this->options as $option) {
                $setCodes[] = $option['set'];
                $unsetCodes[] = $option['unset'];
            }
        }

        if (0 === count($setCodes)) {
            return $text;
        }

        return sprintf("\033[%sm%s\033[%sm", implode(';', $setCodes), $text, implode(';', $unsetCodes));
    }
}

}

	


namespace Symfony\Component\Console\Formatter{


class OutputFormatter implements OutputFormatterInterface
{
    private $decorated;
    private $styles = array();
    private $styleStack;

    
    public static function escape($text)
    {
        return preg_replace('/([^\\\\]?)</', '$1\\<', $text);
    }

    
    public function __construct($decorated = false, array $styles = array())
    {
        $this->decorated = (bool) $decorated;

        $this->setStyle('error', new OutputFormatterStyle('white', 'red'));
        $this->setStyle('info', new OutputFormatterStyle('green'));
        $this->setStyle('comment', new OutputFormatterStyle('yellow'));
        $this->setStyle('question', new OutputFormatterStyle('black', 'cyan'));

        foreach ($styles as $name => $style) {
            $this->setStyle($name, $style);
        }

        $this->styleStack = new OutputFormatterStyleStack();
    }

    
    public function setDecorated($decorated)
    {
        $this->decorated = (bool) $decorated;
    }

    
    public function isDecorated()
    {
        return $this->decorated;
    }

    
    public function setStyle($name, OutputFormatterStyleInterface $style)
    {
        $this->styles[strtolower($name)] = $style;
    }

    
    public function hasStyle($name)
    {
        return isset($this->styles[strtolower($name)]);
    }

    
    public function getStyle($name)
    {
        if (!$this->hasStyle($name)) {
            throw new \InvalidArgumentException(sprintf('Undefined style: %s', $name));
        }

        return $this->styles[strtolower($name)];
    }

    
    public function format($message)
    {
        $message = (string) $message;
        $offset = 0;
        $output = '';
        $tagRegex = '[a-z][a-z0-9_=;-]*';
        preg_match_all("#<(($tagRegex) | /($tagRegex)?)>#ix", $message, $matches, PREG_OFFSET_CAPTURE);
        foreach ($matches[0] as $i => $match) {
            $pos = $match[1];
            $text = $match[0];

            if (0 != $pos && '\\' == $message[$pos - 1]) {
                continue;
            }

                        $output .= $this->applyCurrentStyle(substr($message, $offset, $pos - $offset));
            $offset = $pos + strlen($text);

                        if ($open = '/' != $text[1]) {
                $tag = $matches[1][$i][0];
            } else {
                $tag = isset($matches[3][$i][0]) ? $matches[3][$i][0] : '';
            }

            if (!$open && !$tag) {
                                $this->styleStack->pop();
            } elseif (false === $style = $this->createStyleFromString(strtolower($tag))) {
                $output .= $this->applyCurrentStyle($text);
            } elseif ($open) {
                $this->styleStack->push($style);
            } else {
                $this->styleStack->pop($style);
            }
        }

        $output .= $this->applyCurrentStyle(substr($message, $offset));

        return str_replace('\\<', '<', $output);
    }

    
    public function getStyleStack()
    {
        return $this->styleStack;
    }

    
    private function createStyleFromString($string)
    {
        if (isset($this->styles[$string])) {
            return $this->styles[$string];
        }

        if (!preg_match_all('/([^=]+)=([^;]+)(;|$)/', strtolower($string), $matches, PREG_SET_ORDER)) {
            return false;
        }

        $style = new OutputFormatterStyle();
        foreach ($matches as $match) {
            array_shift($match);

            if ('fg' == $match[0]) {
                $style->setForeground($match[1]);
            } elseif ('bg' == $match[0]) {
                $style->setBackground($match[1]);
            } else {
                try {
                    $style->setOption($match[1]);
                } catch (\InvalidArgumentException $e) {
                    return false;
                }
            }
        }

        return $style;
    }

    
    private function applyCurrentStyle($text)
    {
        return $this->isDecorated() && strlen($text) > 0 ? $this->styleStack->getCurrent()->apply($text) : $text;
    }
}

}

	


namespace Symfony\Component\Console\Output{

use Symfony\Component\Console\Formatter\OutputFormatterInterface;


interface OutputInterface
{
    const VERBOSITY_QUIET = 0;
    const VERBOSITY_NORMAL = 1;
    const VERBOSITY_VERBOSE = 2;
    const VERBOSITY_VERY_VERBOSE = 3;
    const VERBOSITY_DEBUG = 4;

    const OUTPUT_NORMAL = 0;
    const OUTPUT_RAW = 1;
    const OUTPUT_PLAIN = 2;

    
    public function write($messages, $newline = false, $type = self::OUTPUT_NORMAL);

    
    public function writeln($messages, $type = self::OUTPUT_NORMAL);

    
    public function setVerbosity($level);

    
    public function getVerbosity();

    
    public function setDecorated($decorated);

    
    public function isDecorated();

    
    public function setFormatter(OutputFormatterInterface $formatter);

    
    public function getFormatter();
}

}

	


namespace Symfony\Component\Console\Output{


interface ConsoleOutputInterface extends OutputInterface
{
    
    public function getErrorOutput();

    
    public function setErrorOutput(OutputInterface $error);
}

}

	


namespace Symfony\Component\Console\Output{

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Formatter\OutputFormatter;


abstract class Output implements OutputInterface
{
    private $verbosity;
    private $formatter;

    
    public function __construct($verbosity = self::VERBOSITY_NORMAL, $decorated = false, OutputFormatterInterface $formatter = null)
    {
        $this->verbosity = null === $verbosity ? self::VERBOSITY_NORMAL : $verbosity;
        $this->formatter = $formatter ?: new OutputFormatter();
        $this->formatter->setDecorated($decorated);
    }

    
    public function setFormatter(OutputFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    
    public function getFormatter()
    {
        return $this->formatter;
    }

    
    public function setDecorated($decorated)
    {
        $this->formatter->setDecorated($decorated);
    }

    
    public function isDecorated()
    {
        return $this->formatter->isDecorated();
    }

    
    public function setVerbosity($level)
    {
        $this->verbosity = (int) $level;
    }

    
    public function getVerbosity()
    {
        return $this->verbosity;
    }

    public function isQuiet()
    {
        return self::VERBOSITY_QUIET === $this->verbosity;
    }

    public function isVerbose()
    {
        return self::VERBOSITY_VERBOSE <= $this->verbosity;
    }

    public function isVeryVerbose()
    {
        return self::VERBOSITY_VERY_VERBOSE <= $this->verbosity;
    }

    public function isDebug()
    {
        return self::VERBOSITY_DEBUG <= $this->verbosity;
    }

    
    public function writeln($messages, $type = self::OUTPUT_NORMAL)
    {
        $this->write($messages, true, $type);
    }

    
    public function write($messages, $newline = false, $type = self::OUTPUT_NORMAL)
    {
        if (self::VERBOSITY_QUIET === $this->verbosity) {
            return;
        }

        $messages = (array) $messages;

        foreach ($messages as $message) {
            switch ($type) {
                case OutputInterface::OUTPUT_NORMAL:
                    $message = $this->formatter->format($message);
                    break;
                case OutputInterface::OUTPUT_RAW:
                    break;
                case OutputInterface::OUTPUT_PLAIN:
                    $message = strip_tags($this->formatter->format($message));
                    break;
                default:
                    throw new \InvalidArgumentException(sprintf('Unknown output type given (%s)', $type));
            }

            $this->doWrite($message, $newline);
        }
    }

    
    abstract protected function doWrite($message, $newline);
}

}

	


namespace Symfony\Component\Console\Output{

use Symfony\Component\Console\Formatter\OutputFormatterInterface;


class StreamOutput extends Output
{
    private $stream;

    
    public function __construct($stream, $verbosity = self::VERBOSITY_NORMAL, $decorated = null, OutputFormatterInterface $formatter = null)
    {
        if (!is_resource($stream) || 'stream' !== get_resource_type($stream)) {
            throw new \InvalidArgumentException('The StreamOutput class needs a stream as its first argument.');
        }

        $this->stream = $stream;

        if (null === $decorated) {
            $decorated = $this->hasColorSupport();
        }

        parent::__construct($verbosity, $decorated, $formatter);
    }

    
    public function getStream()
    {
        return $this->stream;
    }

    
    protected function doWrite($message, $newline)
    {
        if (false === @fwrite($this->stream, $message.($newline ? PHP_EOL : ''))) {
                        throw new \RuntimeException('Unable to write output.');
        }

        fflush($this->stream);
    }

    
    protected function hasColorSupport()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI');
        }

        return function_exists('posix_isatty') && @posix_isatty($this->stream);
    }
}

}

	


namespace Symfony\Component\Console\Output{

use Symfony\Component\Console\Formatter\OutputFormatterInterface;


class ConsoleOutput extends StreamOutput implements ConsoleOutputInterface
{
    
    private $stderr;

    
    public function __construct($verbosity = self::VERBOSITY_NORMAL, $decorated = null, OutputFormatterInterface $formatter = null)
    {
        $outputStream = $this->hasStdoutSupport() ? 'php://stdout' : 'php://output';
        $errorStream = $this->hasStderrSupport() ? 'php://stderr' : 'php://output';

        parent::__construct(fopen($outputStream, 'w'), $verbosity, $decorated, $formatter);

        $this->stderr = new StreamOutput(fopen($errorStream, 'w'), $verbosity, $decorated, $this->getFormatter());
    }

    
    public function setDecorated($decorated)
    {
        parent::setDecorated($decorated);
        $this->stderr->setDecorated($decorated);
    }

    
    public function setFormatter(OutputFormatterInterface $formatter)
    {
        parent::setFormatter($formatter);
        $this->stderr->setFormatter($formatter);
    }

    
    public function setVerbosity($level)
    {
        parent::setVerbosity($level);
        $this->stderr->setVerbosity($level);
    }

    
    public function getErrorOutput()
    {
        return $this->stderr;
    }

    
    public function setErrorOutput(OutputInterface $error)
    {
        $this->stderr = $error;
    }

    
    protected function hasStdoutSupport()
    {
        return false === $this->isRunningOS400();
    }

    
    protected function hasStderrSupport()
    {
        return false === $this->isRunningOS400();
    }

    
    private function isRunningOS400()
    {
        return 'OS400' === php_uname('s');
    }
}

}

	


namespace Symfony\Component\Console\Helper{

use Symfony\Component\Console\Output\OutputInterface;


class ProgressBar
{
        private $barWidth = 28;
    private $barChar;
    private $emptyBarChar = '-';
    private $progressChar = '>';
    private $format = null;
    private $redrawFreq = 1;

    
    private $output;
    private $step = 0;
    private $max;
    private $startTime;
    private $stepWidth;
    private $percent = 0.0;
    private $lastMessagesLength = 0;
    private $formatLineCount;
    private $messages;
    private $overwrite = true;

    private static $formatters;
    private static $formats;

    
    public function __construct(OutputInterface $output, $max = 0)
    {
        $this->output = $output;
        $this->setMaxSteps($max);

        if (!$this->output->isDecorated()) {
                        $this->overwrite = false;

            if ($this->max > 10) {
                                $this->setRedrawFrequency($max / 10);
            }
        }

        $this->setFormat($this->determineBestFormat());

        $this->startTime = time();
    }

    
    public static function setPlaceholderFormatterDefinition($name, $callable)
    {
        if (!self::$formatters) {
            self::$formatters = self::initPlaceholderFormatters();
        }

        self::$formatters[$name] = $callable;
    }

    
    public static function getPlaceholderFormatterDefinition($name)
    {
        if (!self::$formatters) {
            self::$formatters = self::initPlaceholderFormatters();
        }

        return isset(self::$formatters[$name]) ? self::$formatters[$name] : null;
    }

    
    public static function setFormatDefinition($name, $format)
    {
        if (!self::$formats) {
            self::$formats = self::initFormats();
        }

        self::$formats[$name] = $format;
    }

    
    public static function getFormatDefinition($name)
    {
        if (!self::$formats) {
            self::$formats = self::initFormats();
        }

        return isset(self::$formats[$name]) ? self::$formats[$name] : null;
    }

    public function setMessage($message, $name = 'message')
    {
        $this->messages[$name] = $message;
    }

    public function getMessage($name = 'message')
    {
        return $this->messages[$name];
    }

    
    public function getStartTime()
    {
        return $this->startTime;
    }

    
    public function getMaxSteps()
    {
        return $this->max;
    }

    
    public function getStep()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 2.6 and will be removed in 3.0. Use the getProgress() method instead.', E_USER_DEPRECATED);

        return $this->getProgress();
    }

    
    public function getProgress()
    {
        return $this->step;
    }

    
    public function getStepWidth()
    {
        return $this->stepWidth;
    }

    
    public function getProgressPercent()
    {
        return $this->percent;
    }

    
    public function setBarWidth($size)
    {
        $this->barWidth = (int) $size;
    }

    
    public function getBarWidth()
    {
        return $this->barWidth;
    }

    
    public function setBarCharacter($char)
    {
        $this->barChar = $char;
    }

    
    public function getBarCharacter()
    {
        if (null === $this->barChar) {
            return $this->max ? '=' : $this->emptyBarChar;
        }

        return $this->barChar;
    }

    
    public function setEmptyBarCharacter($char)
    {
        $this->emptyBarChar = $char;
    }

    
    public function getEmptyBarCharacter()
    {
        return $this->emptyBarChar;
    }

    
    public function setProgressCharacter($char)
    {
        $this->progressChar = $char;
    }

    
    public function getProgressCharacter()
    {
        return $this->progressChar;
    }

    
    public function setFormat($format)
    {
                if (!$this->max && null !== self::getFormatDefinition($format.'_nomax')) {
            $this->format = self::getFormatDefinition($format.'_nomax');
        } elseif (null !== self::getFormatDefinition($format)) {
            $this->format = self::getFormatDefinition($format);
        } else {
            $this->format = $format;
        }

        $this->formatLineCount = substr_count($this->format, "\n");
    }

    
    public function setRedrawFrequency($freq)
    {
        $this->redrawFreq = (int) $freq;
    }

    
    public function start($max = null)
    {
        $this->startTime = time();
        $this->step = 0;
        $this->percent = 0.0;

        if (null !== $max) {
            $this->setMaxSteps($max);
        }

        $this->display();
    }

    
    public function advance($step = 1)
    {
        $this->setProgress($this->step + $step);
    }

    
    public function setCurrent($step)
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 2.6 and will be removed in 3.0. Use the setProgress() method instead.', E_USER_DEPRECATED);

        $this->setProgress($step);
    }

    
    public function setOverwrite($overwrite)
    {
        $this->overwrite = (bool) $overwrite;
    }

    
    public function setProgress($step)
    {
        $step = (int) $step;
        if ($step < $this->step) {
            throw new \LogicException('You can\'t regress the progress bar.');
        }

        if ($this->max && $step > $this->max) {
            $this->max = $step;
        }

        $prevPeriod = (int) ($this->step / $this->redrawFreq);
        $currPeriod = (int) ($step / $this->redrawFreq);
        $this->step = $step;
        $this->percent = $this->max ? (float) $this->step / $this->max : 0;
        if ($prevPeriod !== $currPeriod || $this->max === $step) {
            $this->display();
        }
    }

    
    public function finish()
    {
        if (!$this->max) {
            $this->max = $this->step;
        }

        if ($this->step === $this->max && !$this->overwrite) {
                        return;
        }

        $this->setProgress($this->max);
    }

    
    public function display()
    {
        if (OutputInterface::VERBOSITY_QUIET === $this->output->getVerbosity()) {
            return;
        }

                $self = $this;
        $output = $this->output;
        $messages = $this->messages;
        $this->overwrite(preg_replace_callback("{%([a-z\-_]+)(?:\:([^%]+))?%}i", function ($matches) use ($self, $output, $messages) {
            if ($formatter = $self::getPlaceholderFormatterDefinition($matches[1])) {
                $text = call_user_func($formatter, $self, $output);
            } elseif (isset($messages[$matches[1]])) {
                $text = $messages[$matches[1]];
            } else {
                return $matches[0];
            }

            if (isset($matches[2])) {
                $text = sprintf('%'.$matches[2], $text);
            }

            return $text;
        }, $this->format));
    }

    
    public function clear()
    {
        if (!$this->overwrite) {
            return;
        }

        $this->overwrite(str_repeat("\n", $this->formatLineCount));
    }

    
    private function setMaxSteps($max)
    {
        $this->max = max(0, (int) $max);
        $this->stepWidth = $this->max ? Helper::strlen($this->max) : 4;
    }

    
    private function overwrite($message)
    {
        $lines = explode("\n", $message);

                if (null !== $this->lastMessagesLength) {
            foreach ($lines as $i => $line) {
                if ($this->lastMessagesLength > Helper::strlenWithoutDecoration($this->output->getFormatter(), $line)) {
                    $lines[$i] = str_pad($line, $this->lastMessagesLength, "\x20", STR_PAD_RIGHT);
                }
            }
        }

        if ($this->overwrite) {
                        $this->output->write("\x0D");
        } elseif ($this->step > 0) {
                        $this->output->writeln('');
        }

        if ($this->formatLineCount) {
            $this->output->write(sprintf("\033[%dA", $this->formatLineCount));
        }
        $this->output->write(implode("\n", $lines));

        $this->lastMessagesLength = 0;
        foreach ($lines as $line) {
            $len = Helper::strlenWithoutDecoration($this->output->getFormatter(), $line);
            if ($len > $this->lastMessagesLength) {
                $this->lastMessagesLength = $len;
            }
        }
    }

    private function determineBestFormat()
    {
        switch ($this->output->getVerbosity()) {
                        case OutputInterface::VERBOSITY_VERBOSE:
                return $this->max ? 'verbose' : 'verbose_nomax';
            case OutputInterface::VERBOSITY_VERY_VERBOSE:
                return $this->max ? 'very_verbose' : 'very_verbose_nomax';
            case OutputInterface::VERBOSITY_DEBUG:
                return $this->max ? 'debug' : 'debug_nomax';
            default:
                return $this->max ? 'normal' : 'normal_nomax';
        }
    }

    private static function initPlaceholderFormatters()
    {
        return array(
            'bar' => function (ProgressBar $bar, OutputInterface $output) {
                $completeBars = floor($bar->getMaxSteps() > 0 ? $bar->getProgressPercent() * $bar->getBarWidth() : $bar->getProgress() % $bar->getBarWidth());
                $display = str_repeat($bar->getBarCharacter(), $completeBars);
                if ($completeBars < $bar->getBarWidth()) {
                    $emptyBars = $bar->getBarWidth() - $completeBars - Helper::strlenWithoutDecoration($output->getFormatter(), $bar->getProgressCharacter());
                    $display .= $bar->getProgressCharacter().str_repeat($bar->getEmptyBarCharacter(), $emptyBars);
                }

                return $display;
            },
            'elapsed' => function (ProgressBar $bar) {
                return Helper::formatTime(time() - $bar->getStartTime());
            },
            'remaining' => function (ProgressBar $bar) {
                if (!$bar->getMaxSteps()) {
                    throw new \LogicException('Unable to display the remaining time if the maximum number of steps is not set.');
                }

                if (!$bar->getProgress()) {
                    $remaining = 0;
                } else {
                    $remaining = round((time() - $bar->getStartTime()) / $bar->getProgress() * ($bar->getMaxSteps() - $bar->getProgress()));
                }

                return Helper::formatTime($remaining);
            },
            'estimated' => function (ProgressBar $bar) {
                if (!$bar->getMaxSteps()) {
                    throw new \LogicException('Unable to display the estimated time if the maximum number of steps is not set.');
                }

                if (!$bar->getProgress()) {
                    $estimated = 0;
                } else {
                    $estimated = round((time() - $bar->getStartTime()) / $bar->getProgress() * $bar->getMaxSteps());
                }

                return Helper::formatTime($estimated);
            },
            'memory' => function (ProgressBar $bar) {
                return Helper::formatMemory(memory_get_usage(true));
            },
            'current' => function (ProgressBar $bar) {
                return str_pad($bar->getProgress(), $bar->getStepWidth(), ' ', STR_PAD_LEFT);
            },
            'max' => function (ProgressBar $bar) {
                return $bar->getMaxSteps();
            },
            'percent' => function (ProgressBar $bar) {
                return floor($bar->getProgressPercent() * 100);
            },
        );
    }

    private static function initFormats()
    {
        return array(
            'normal' => ' %current%/%max% [%bar%] %percent:3s%%',
            'normal_nomax' => ' %current% [%bar%]',

            'verbose' => ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%',
            'verbose_nomax' => ' %current% [%bar%] %elapsed:6s%',

            'very_verbose' => ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%',
            'very_verbose_nomax' => ' %current% [%bar%] %elapsed:6s%',

            'debug' => ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%',
            'debug_nomax' => ' %current% [%bar%] %elapsed:6s% %memory:6s%',
        );
    }
}

}



namespace {
	$concurrent = function_exists('pcntl_fork');
	if ($concurrent) {
		
define('PHP_INT_LENGTH', strlen(sprintf('%u', PHP_INT_MAX)));
function cofunc(callable $fn) {
	$pid = pcntl_fork();
	if (-1 == $pid) {
		trigger_error('could not fork', E_ERROR);
		return;
	}
	if ($pid) {
		return;
	}
	pcntl_signal(SIGCHLD, SIG_IGN);
	$params = [];
	if (func_num_args() > 1) {
		$params = array_slice(func_get_args(), 1);
	}
	call_user_func_array($fn, $params);
	die();
}

class CSP_Channel {
	const CLOSED = '-1';
	private $ipc;
	private $ipc_fn;
	private $key;
	private $closed = false;
	private $msg_count = 0;
	public function __construct() {
		$this->ipc_fn = tempnam(sys_get_temp_dir(), 'csp.' . uniqid('chn', true));
		$this->key = ftok($this->ipc_fn, 'A');
		$this->ipc = msg_get_queue($this->key, 0666);
		msg_set_queue($this->ipc, $cfg = [
			'msg_qbytes' => (1 * PHP_INT_LENGTH),
		]);

	}

	public function msg_count() {
		return $this->msg_count;
	}

	public function close() {
		$this->closed = true;
		do {
			$this->out();
			--$this->msg_count;
		} while ($this->msg_count >= 0);
		msg_remove_queue($this->ipc);
		file_exists($this->ipc_fn) && @unlink($this->ipc_fn);
	}

	public function in($msg) {
		if ($this->closed || !msg_queue_exists($this->key)) {
			return;
		}
		$shm = new Message();
		$shm->store($msg);
		$error = 0;
		@msg_send($this->ipc, 1, $shm->key(), false, true, $error);
		++$this->msg_count;
	}

	public function non_blocking_in($msg) {
		if ($this->closed || !msg_queue_exists($this->key)) {
			return self::CLOSED;
		}
		$shm = new Message();
		$shm->store($msg);
		$error = 0;
		@msg_send($this->ipc, 1, $shm->key(), false, false, $error);
		if (MSG_EAGAIN === $error) {
			$shmAbortedMessage = new Message($shm->key());
			$shmAbortedMessage->destroy();
			return false;
		}
		++$this->msg_count;
		$first_loop = true;
		do {
			$data = msg_stat_queue($this->ipc);
			if (!$first_loop && 0 == $data['msg_qnum']) {
				break;
			}
			$first_loop = false;
		} while (true);
		return true;
	}

	public function out() {
		if ($this->closed || !msg_queue_exists($this->key)) {
			return;
		}
		$msgtype = null;
		$ipcmsg = null;
		$error = null;
		msg_receive($this->ipc, 1, $msgtype, (1 * PHP_INT_LENGTH) + 1, $ipcmsg, false, 0, $error);
		--$this->msg_count;
		$shm = new Message($ipcmsg);
		$ret = $shm->fetch();
		return $ret;
	}

	public function non_blocking_out() {
		if ($this->closed || !msg_queue_exists($this->key)) {
			return [self::CLOSED, null];
		}
		$msgtype = null;
		$ipcmsg = null;
		$error = null;
		msg_receive($this->ipc, 1, $msgtype, (1 * PHP_INT_LENGTH) + 1, $ipcmsg, false, MSG_IPC_NOWAIT, $error);
		if (MSG_ENOMSG === $error) {
			return [false, null];
		}
		--$this->msg_count;
		$shm = new Message($ipcmsg);
		$ret = $shm->fetch();
		return [true, $ret];
	}
}
class Message {
	private $key;
	private $shm;
	public function __construct($key = null) {
		if (null === $key) {
			$key = ftok(tempnam(sys_get_temp_dir(), 'csp.' . uniqid('shm', true)), 'C');
		}
		$this->shm = shm_attach($key);
		if (false === $this->shm) {
			trigger_error('Unable to attach shared memory segment for channel', E_ERROR);
		}
		$this->key = $key;
	}

	public function store($msg) {
		shm_put_var($this->shm, 1, $msg);
		shm_detach($this->shm);
	}

	public function key() {
		return sprintf('%0' . PHP_INT_LENGTH . 'd', (int) $this->key);
	}

	public function fetch() {
		$ret = shm_get_var($this->shm, 1);
		$this->destroy();
		return $ret;

	}

	public function destroy() {
		if (shm_has_var($this->shm, 1)) {
			shm_remove_var($this->shm, 1);
		}
		shm_remove($this->shm);
	}
}

function make_channel() {
	return new CSP_Channel();
}


function select_channel(array $actions) {
	while (true) {
		foreach ($actions as $action) {
			if ('default' == $action[0]) {
				call_user_func_array($action[1]);
				break 2;
			} elseif (is_callable($action[1])) {
				$chn = &$action[0];
				$callback = &$action[1];

				list($ok, $result) = $chn->non_blocking_out();
				if (true === $ok) {
					call_user_func_array($callback, [$result]);
					break 2;
				}
			} elseif ($action[0] instanceof CSP_Channel) {
				$chn = &$action[0];
				$msg = &$action[1];
				$callback = &$action[2];
				$params = array_slice($action, 3);

				$ok = $chn->non_blocking_in($msg);
				if (CSP_Channel::CLOSED === $ok) {
					throw new Exception('Cannot send to closed channel');
				} elseif (true === $ok) {
					call_user_func($callback);
					break 2;
				}
			} else {
				throw new Exception('Invalid action for CSP select_channel');
			}
		}
	}
}

	}
	
interface Cacher {
	const DEFAULT_CACHE_FILENAME = '.php.tools.cache';

	public function create_db();

	public function is_changed($target, $filename);

	public function upsert($target, $filename, $content);
}

	$enableCache = false;
	if (class_exists('SQLite3')) {
		$enableCache = true;
		

final class Cache implements Cacher {
	private $db;

	private $noop = false;

	public function __construct($filename) {
		if (empty($filename)) {
			$this->noop = true;
			return;
		}

		$startDbCreation = false;
		if (is_dir($filename)) {
			$filename = realpath($filename) . DIRECTORY_SEPARATOR . self::DEFAULT_CACHE_FILENAME;
		}
		if (!file_exists($filename)) {
			$startDbCreation = true;
		}

		$this->setDb(new SQLite3($filename));
		$this->db->busyTimeout(1000);
		if ($startDbCreation) {
			$this->create_db();
		}
	}

	public function __destruct() {
		if ($this->noop) {
			return;
		}
		$this->db->close();
	}

	public function create_db() {
		if ($this->noop) {
			return;
		}
		$this->db->exec('CREATE TABLE cache (target TEXT, filename TEXT, hash TEXT, unique(target, filename));');
	}

	public function is_changed($target, $filename) {
		$content = file_get_contents($filename);
		if ($this->noop) {
			return $content;
		}
		$row = $this->db->querySingle('SELECT hash FROM cache WHERE target = "' . SQLite3::escapeString($target) . '" AND filename = "' . SQLite3::escapeString($filename) . '"', true);
		if (empty($row)) {
			return $content;
		}
		if ($this->calculateHash($content) != $row['hash']) {
			return $content;
		}
		return false;
	}

	public function upsert($target, $filename, $content) {
		if ($this->noop) {
			return;
		}
		$hash = $this->calculateHash($content);
		$this->db->exec('REPLACE INTO cache VALUES ("' . SQLite3::escapeString($target) . '","' . SQLite3::escapeString($filename) . '", "' . SQLite3::escapeString($hash) . '")');
	}

	private function calculateHash($content) {
		return sprintf('%u', crc32($content));
	}

	private function setDb($db) {
		$this->db = $db;
	}
}

	} else {
		

final class Cache implements Cacher {
	public function create_db() {}
	public function is_changed($target, $filename) {
		return file_get_contents($filename);
	}

	public function upsert($target, $filename, $content) {}
}

	}

	define('VERSION', '19.6.4');
	
function extractFromArgv($argv, $item) {
	return array_values(
		array_filter($argv,
			function ($v) use ($item) {
				return substr($v, 0, strlen('--' . $item)) !== '--' . $item;
			}
		)
	);
}

function extractFromArgvShort($argv, $item) {
	return array_values(
		array_filter($argv,
			function ($v) use ($item) {
				return substr($v, 0, strlen('-' . $item)) !== '-' . $item;
			}
		)
	);
}

function lint($file) {
	$output = null;
	$ret = null;
	exec('php -l ' . escapeshellarg($file), $output, $ret);
	return 0 === $ret;
}

function tabwriter(array $lines) {
	$colsize = [];
	foreach ($lines as $line) {
		foreach ($line as $idx => $text) {
			$cs = &$colsize[$idx];
			$len = strlen($text);
			$cs = max($cs, $len);
		}
	}

	$final = '';
	foreach ($lines as $line) {
		$out = '';
		foreach ($line as $idx => $text) {
			$cs = &$colsize[$idx];
			$out .= str_pad($text, $cs) . ' ';
		}
		$final .= rtrim($out) . PHP_EOL;
	}

	return $final;
}
	
function selfupdate($argv, $inPhar) {
	$opts = [
		'http' => [
			'method' => 'GET',
			'header' => "User-agent: phpfmt fmt.phar selfupdate\r\n",
		],
	];

	$context = stream_context_create($opts);

		$releases = json_decode(file_get_contents('https://api.github.com/repos/phpfmt/fmt/tags', false, $context), true);
	$commit = json_decode(file_get_contents($releases[0]['commit']['url'], false, $context), true);
	$files = json_decode(file_get_contents($commit['commit']['tree']['url'], false, $context), true);
	foreach ($files['tree'] as $file) {
		if ('fmt.phar' == $file['path']) {
			$phar_file = base64_decode(json_decode(file_get_contents($file['url'], false, $context), true)['content']);
		}
		if ('fmt.phar.sha1' == $file['path']) {
			$phar_sha1 = base64_decode(json_decode(file_get_contents($file['url'], false, $context), true)['content']);
		}
	}
	if (!isset($phar_sha1) || !isset($phar_file)) {
		fwrite(STDERR, 'Could not autoupdate - not release found' . PHP_EOL);
		exit(255);
	}
	if ($inPhar && !file_exists($argv[0])) {
		$argv[0] = dirname(Phar::running(false)) . DIRECTORY_SEPARATOR . $argv[0];
	}
	if (sha1_file($argv[0]) != $phar_sha1) {
		copy($argv[0], $argv[0] . '~');
		file_put_contents($argv[0], $phar_file);
		chmod($argv[0], 0777 & ~umask());
		fwrite(STDERR, 'Updated successfully' . PHP_EOL);
		exit(0);
	}
	fwrite(STDERR, 'Up-to-date!' . PHP_EOL);
	exit(0);
}


	
define('ST_AT', '@');
define('ST_BRACKET_CLOSE', ']');
define('ST_BRACKET_OPEN', '[');
define('ST_COLON', ':');
define('ST_COMMA', ',');
define('ST_CONCAT', '.');
define('ST_CURLY_CLOSE', '}');
define('ST_CURLY_OPEN', '{');
define('ST_DIVIDE', '/');
define('ST_DOLLAR', '$');
define('ST_EQUAL', '=');
define('ST_EXCLAMATION', '!');
define('ST_IS_GREATER', '>');
define('ST_IS_SMALLER', '<');
define('ST_MINUS', '-');
define('ST_MODULUS', '%');
define('ST_PARENTHESES_CLOSE', ')');
define('ST_PARENTHESES_OPEN', '(');
define('ST_PLUS', '+');
define('ST_QUESTION', '?');
define('ST_QUOTE', '"');
define('ST_REFERENCE', '&');
define('ST_SEMI_COLON', ';');
define('ST_TIMES', '*');
define('ST_BITWISE_OR', '|');
define('ST_BITWISE_XOR', '^');
if (!defined('T_POW')) {
	define('T_POW', '**');
}
if (!defined('T_POW_EQUAL')) {
	define('T_POW_EQUAL', '**=');
}
if (!defined('T_YIELD')) {
	define('T_YIELD', 'yield');
}
if (!defined('T_FINALLY')) {
	define('T_FINALLY', 'finally');
}
if (!defined('T_SPACESHIP')) {
	define('T_SPACESHIP', '<=>');
}
if (!defined('T_COALESCE')) {
	define('T_COALESCE', '??');
}

define('ST_PARENTHESES_BLOCK', 'ST_PARENTHESES_BLOCK');
define('ST_BRACKET_BLOCK', 'ST_BRACKET_BLOCK');
define('ST_CURLY_BLOCK', 'ST_CURLY_BLOCK');
	
abstract class FormatterPass {
	protected $cache = [];

	protected $code = '';

		protected $ignoreFutileTokens = [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT];

	protected $indent = 0;

	protected $indentChar = "\t";

	protected $newLine = "\n";

		protected $ptr = 0;

	protected $tkns = [];

	protected $useCache = false;

	private $memo = [null, null];

	private $memoUseful = [null, null];

	abstract public function candidate($source, $foundTokens);

	abstract public function format($source);

	protected function alignPlaceholders($origPlaceholder, $contextCounter) {
		for ($j = 0; $j <= $contextCounter; ++$j) {
			$placeholder = sprintf($origPlaceholder, $j);
			if (false === strpos($this->code, $placeholder)) {
				continue;
			}
			if (1 === substr_count($this->code, $placeholder)) {
				$this->code = str_replace($placeholder, '', $this->code);
				continue;
			}
			$lines = explode($this->newLine, $this->code);
			$linesWithPlaceholder = [];
			$blockCount = 0;

			foreach ($lines as $idx => $line) {
				if (false !== strpos($line, $placeholder)) {
					$linesWithPlaceholder[$blockCount][] = $idx;
					continue;
				}
				++$blockCount;
				$linesWithPlaceholder[$blockCount] = [];
			}

			$i = 0;
			foreach ($linesWithPlaceholder as $group) {
				++$i;
				$farthest = 0;
				foreach ($group as $idx) {
					$farthest = max($farthest, strpos($lines[$idx], $placeholder));
				}
				foreach ($group as $idx) {
					$line = $lines[$idx];
					$current = strpos($line, $placeholder);
					$delta = abs($farthest - $current);
					if ($delta > 0) {
						$line = str_replace($placeholder, str_repeat(' ', $delta) . $placeholder, $line);
						$lines[$idx] = $line;
					}
				}
			}
			$this->code = str_replace($placeholder, '', implode($this->newLine, $lines));
		}
	}

	protected function appendCode($code = '') {
		$this->code .= $code;
	}

	protected function getCrlf() {
		return $this->newLine;
	}

	protected function getCrlfIndent() {
		return $this->getCrlf() . $this->getIndent();
	}

	protected function getIndent($increment = 0) {
		return str_repeat($this->indentChar, $this->indent + $increment);
	}

	protected function getSpace($true = true) {
		return $true ? ' ' : '';
	}

	protected function getToken($token) {
		$ret = [$token, $token];
		if (isset($token[1])) {
			$ret = $token;
		}
		return $ret;
	}

	protected function hasLn($text) {
		return (false !== strpos($text, $this->newLine));
	}

	protected function hasLnAfter() {
		$id = null;
		$text = null;
		list($id, $text) = $this->inspectToken();
		return T_WHITESPACE === $id && $this->hasLn($text);
	}

	protected function hasLnBefore() {
		$id = null;
		$text = null;
		list($id, $text) = $this->inspectToken(-1);
		return T_WHITESPACE === $id && $this->hasLn($text);
	}

	protected function hasLnLeftToken() {
		list(, $text) = $this->getToken($this->leftToken());
		return $this->hasLn($text);
	}

	protected function hasLnRightToken() {
		list(, $text) = $this->getToken($this->rightToken());
		return $this->hasLn($text);
	}

	protected function inspectToken($delta = 1) {
		if (!isset($this->tkns[$this->ptr + $delta])) {
			return [null, null];
		}
		return $this->getToken($this->tkns[$this->ptr + $delta]);
	}

	protected function isShortArray() {
		return !$this->leftTokenIs([
			ST_BRACKET_CLOSE,
			ST_CURLY_CLOSE,
			ST_PARENTHESES_CLOSE,
			ST_QUOTE,
			T_CONSTANT_ENCAPSED_STRING,
			T_STRING,
			T_VARIABLE,
		]);
	}

	protected function leftMemoTokenIs($token) {
		return $this->resolveFoundToken($this->memo[0], $token);
	}

	protected function leftMemoUsefulTokenIs($token, $debug = false) {
		return $this->resolveFoundToken($this->memoUseful[0], $token);
	}

	protected function leftToken($ignoreList = []) {
		$i = $this->leftTokenIdx($ignoreList);

		return $this->tkns[$i];
	}

	protected function leftTokenIdx($ignoreList = []) {
		$ignoreList = $this->resolveIgnoreList($ignoreList);

		$i = $this->walkLeft($this->tkns, $this->ptr, $ignoreList);

		return $i;
	}

	protected function leftTokenIs($token, $ignoreList = []) {
		return $this->tokenIs('left', $token, $ignoreList);
	}

	protected function leftTokenSubsetAtIdx($tkns, $idx, $ignoreList = []) {
		$ignoreList = $this->resolveIgnoreList($ignoreList);
		$idx = $this->walkLeft($tkns, $idx, $ignoreList);

		return $idx;
	}

	protected function leftTokenSubsetIsAtIdx($tkns, $idx, $token, $ignoreList = []) {
		$idx = $this->leftTokenSubsetAtIdx($tkns, $idx, $ignoreList);

		return $this->resolveTokenMatch($tkns, $idx, $token);
	}

	protected function leftUsefulToken() {
		return $this->leftToken($this->ignoreFutileTokens);
	}

	protected function leftUsefulTokenIdx() {
		return $this->leftTokenIdx($this->ignoreFutileTokens);
	}

	protected function leftUsefulTokenIs($token) {
		return $this->leftTokenIs($token, $this->ignoreFutileTokens);
	}

	protected function memoPtr() {
		$t = $this->tkns[$this->ptr][0];

		if (T_WHITESPACE !== $t) {
			$this->memo[0] = $this->memo[1];
			$this->memo[1] = $t;
		}

		if (T_WHITESPACE !== $t && T_COMMENT !== $t && T_DOC_COMMENT !== $t) {
			$this->memoUseful[0] = $this->memoUseful[1];
			$this->memoUseful[1] = $t;
		}
	}

	protected function peekAndCountUntilAny($tkns, $ptr, $tknids) {
		$tknids = array_flip($tknids);
		$tknsSize = sizeof($tkns);
		$countTokens = [];
		$id = null;
		for ($i = $ptr; $i < $tknsSize; ++$i) {
			$token = $tkns[$i];
			list($id) = $this->getToken($token);
			if (T_WHITESPACE == $id || T_COMMENT == $id || T_DOC_COMMENT == $id) {
				continue;
			}
			if (!isset($countTokens[$id])) {
				$countTokens[$id] = 0;
			}
			++$countTokens[$id];
			if (isset($tknids[$id])) {
				break;
			}
		}
		return [$id, $countTokens];
	}

	protected function printAndStopAt($tknids) {
		if (is_scalar($tknids)) {
			$tknids = [$tknids];
		}
		$tknids = array_flip($tknids);
		$touchedLn = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->cache = [];
			if (!$touchedLn && T_WHITESPACE == $id && $this->hasLn($text)) {
				$touchedLn = true;
			}
			if (isset($tknids[$id])) {
				return [$id, $text, $touchedLn];
			}
			$this->appendCode($text);
		}
	}

	protected function printAndStopAtEndOfParamBlock() {
		$count = 1;
		$paramCount = 1;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->cache = [];

			if (ST_COMMA == $id && 1 == $count) {
				++$paramCount;
			}
			if (ST_BRACKET_OPEN == $id) {
				$this->appendCode($text);
				$this->printBlock(ST_BRACKET_OPEN, ST_BRACKET_CLOSE);
				continue;
			}
			if (ST_CURLY_OPEN == $id || T_CURLY_OPEN == $id || T_DOLLAR_OPEN_CURLY_BRACES == $id) {
				$this->appendCode($text);
				$this->printCurlyBlock();
				continue;
			}
			if (ST_PARENTHESES_OPEN == $id) {
				++$count;
			}
			if (ST_PARENTHESES_CLOSE == $id) {
				--$count;
			}
			if (0 == $count) {
				prev($this->tkns);
				break;
			}
			$this->appendCode($text);
		}
		return $paramCount;
	}

	protected function printBlock($start, $end) {
		$count = 1;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->cache = [];
			$this->appendCode($text);

			if ($start == $id) {
				++$count;
			}
			if ($end == $id) {
				--$count;
			}
			if (0 == $count) {
				break;
			}
		}
	}

	protected function printCurlyBlock() {
		$count = 1;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->cache = [];
			$this->appendCode($text);

			if (ST_CURLY_OPEN == $id) {
				++$count;
			}
			if (T_CURLY_OPEN == $id) {
				++$count;
			}
			if (T_DOLLAR_OPEN_CURLY_BRACES == $id) {
				++$count;
			}
			if (ST_CURLY_CLOSE == $id) {
				--$count;
			}
			if (0 == $count) {
				break;
			}
		}
	}

	protected function printUntil($tknid) {
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->cache = [];
			$this->appendCode($text);
			if ($tknid == $id) {
				break;
			}
		}
	}

	protected function printUntilAny($tknids) {
		$tknids = array_flip($tknids);
		$whitespaceNewLine = false;
		$id = null;
		if (isset($tknids[$this->newLine])) {
			$whitespaceNewLine = true;
		}
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->cache = [];
			$this->appendCode($text);
			if ($whitespaceNewLine && T_WHITESPACE == $id && $this->hasLn($text)) {
				break;
			}
			if (isset($tknids[$id])) {
				break;
			}
		}
		return $id;
	}

	protected function printUntilTheEndOfString() {
		$this->printUntil(ST_QUOTE);
	}

	protected function refInsert(&$tkns, &$ptr, $item) {
		array_splice($tkns, $ptr, 0, [$item]);
		++$ptr;
	}

	protected function refSkipBlocks($tkns, &$ptr) {
		for ($sizeOfTkns = sizeof($tkns); $ptr < $sizeOfTkns; ++$ptr) {
			$id = $tkns[$ptr][0];

			if (T_CLOSE_TAG == $id) {
				return;
			}

			if (T_DO == $id) {
				$this->refWalkUsefulUntil($tkns, $ptr, ST_CURLY_OPEN);
				$this->refWalkCurlyBlock($tkns, $ptr);
				$this->refWalkUsefulUntil($tkns, $ptr, ST_PARENTHESES_OPEN);
				$this->refWalkBlock($tkns, $ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				continue;
			}

			if (T_WHILE == $id) {
				$this->refWalkUsefulUntil($tkns, $ptr, ST_PARENTHESES_OPEN);
				$this->refWalkBlock($tkns, $ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				if ($this->rightTokenSubsetIsAtIdx(
					$tkns,
					$ptr,
					ST_CURLY_OPEN,
					$this->ignoreFutileTokens
				)) {
					$this->refWalkUsefulUntil($tkns, $ptr, ST_CURLY_OPEN);
					$this->refWalkCurlyBlock($tkns, $ptr);
					return;
				}
			}

			if (T_FOR == $id || T_FOREACH == $id || T_SWITCH == $id) {
				$this->refWalkUsefulUntil($tkns, $ptr, ST_PARENTHESES_OPEN);
				$this->refWalkBlock($tkns, $ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				$this->refWalkUsefulUntil($tkns, $ptr, ST_CURLY_OPEN);
				$this->refWalkCurlyBlock($tkns, $ptr);
				return;
			}

			if (T_TRY == $id) {
				$this->refWalkUsefulUntil($tkns, $ptr, ST_CURLY_OPEN);
				$this->refWalkCurlyBlock($tkns, $ptr);
				while (
					$this->rightTokenSubsetIsAtIdx(
						$tkns,
						$ptr,
						T_CATCH,
						$this->ignoreFutileTokens
					)
				) {
					$this->refWalkUsefulUntil($tkns, $ptr, ST_PARENTHESES_OPEN);
					$this->refWalkBlock($tkns, $ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
					$this->refWalkUsefulUntil($tkns, $ptr, ST_CURLY_OPEN);
					$this->refWalkCurlyBlock($tkns, $ptr);
				}
				if ($this->rightTokenSubsetIsAtIdx(
					$tkns,
					$ptr,
					T_FINALLY,
					$this->ignoreFutileTokens
				)) {
					$this->refWalkUsefulUntil($tkns, $ptr, T_FINALLY);
					$this->refWalkUsefulUntil($tkns, $ptr, ST_CURLY_OPEN);
					$this->refWalkCurlyBlock($tkns, $ptr);
				}
				return;
			}

			if (T_IF == $id) {
				$this->refWalkUsefulUntil($tkns, $ptr, ST_PARENTHESES_OPEN);
				$this->refWalkBlock($tkns, $ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				$this->refWalkUsefulUntil($tkns, $ptr, ST_CURLY_OPEN);
				$this->refWalkCurlyBlock($tkns, $ptr);
				while (true) {
					if (
						$this->rightTokenSubsetIsAtIdx(
							$tkns,
							$ptr,
							T_ELSEIF,
							$this->ignoreFutileTokens
						)
					) {
						$this->refWalkUsefulUntil($tkns, $ptr, ST_PARENTHESES_OPEN);
						$this->refWalkBlock($tkns, $ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
						$this->refWalkUsefulUntil($tkns, $ptr, ST_CURLY_OPEN);
						$this->refWalkCurlyBlock($tkns, $ptr);
						continue;
					} elseif (
						$this->rightTokenSubsetIsAtIdx(
							$tkns,
							$ptr,
							T_ELSE,
							$this->ignoreFutileTokens
						)
					) {
						$this->refWalkUsefulUntil($tkns, $ptr, ST_CURLY_OPEN);
						$this->refWalkCurlyBlock($tkns, $ptr);
						break;
					}
					break;
				}
				return;
			}

			if (
				ST_CURLY_OPEN == $id ||
				T_CURLY_OPEN == $id ||
				T_DOLLAR_OPEN_CURLY_BRACES == $id
			) {
				$this->refWalkCurlyBlock($tkns, $ptr);
				continue;
			}

			if (ST_PARENTHESES_OPEN == $id) {
				$this->refWalkBlock($tkns, $ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				continue;
			}

			if (ST_BRACKET_OPEN == $id) {
				$this->refWalkBlock($tkns, $ptr, ST_BRACKET_OPEN, ST_BRACKET_CLOSE);
				continue;
			}

			if (ST_SEMI_COLON == $id) {
				return;
			}
		}
		--$ptr;
	}

	protected function refSkipIfTokenIsAny($tkns, &$ptr, $skipIds) {
		$skipIds = array_flip($skipIds);
		++$ptr;
		for ($sizeOfTkns = sizeof($tkns); $ptr < $sizeOfTkns; ++$ptr) {
			$id = $tkns[$ptr][0];
			if (!isset($skipIds[$id])) {
				break;
			}
		}
	}

	protected function refWalkBackUsefulUntil($tkns, &$ptr, array $expectedId) {
		$expectedId = array_flip($expectedId);
		do {
			$ptr = $this->walkLeft($tkns, $ptr, $this->ignoreFutileTokens);
		} while (isset($expectedId[$tkns[$ptr][0]]));
	}

	protected function refWalkBlock($tkns, &$ptr, $start, $end) {
		$count = 0;
		for ($sizeOfTkns = sizeof($tkns); $ptr < $sizeOfTkns; ++$ptr) {
			$id = $tkns[$ptr][0];
			if ($start == $id) {
				++$count;
			}
			if ($end == $id) {
				--$count;
			}
			if (0 == $count) {
				break;
			}
		}
	}

	protected function refWalkBlockReverse($tkns, &$ptr, $start, $end) {
		$count = 0;
		for (; $ptr >= 0; --$ptr) {
			$id = $tkns[$ptr][0];
			if ($start == $id) {
				--$count;
			}
			if ($end == $id) {
				++$count;
			}
			if (0 == $count) {
				break;
			}
		}
	}

	protected function refWalkCurlyBlock($tkns, &$ptr) {
		$count = 0;
		for ($sizeOfTkns = sizeof($tkns); $ptr < $sizeOfTkns; ++$ptr) {
			$id = $tkns[$ptr][0];
			if (ST_CURLY_OPEN == $id) {
				++$count;
			}
			if (T_CURLY_OPEN == $id) {
				++$count;
			}
			if (T_DOLLAR_OPEN_CURLY_BRACES == $id) {
				++$count;
			}
			if (ST_CURLY_CLOSE == $id) {
				--$count;
			}
			if (0 == $count) {
				break;
			}
		}
	}

	protected function refWalkCurlyBlockReverse($tkns, &$ptr) {
		$count = 0;
		for (; $ptr >= 0; --$ptr) {
			$id = $tkns[$ptr][0];
			if (ST_CURLY_OPEN == $id) {
				--$count;
			}
			if (T_CURLY_OPEN == $id) {
				--$count;
			}
			if (T_DOLLAR_OPEN_CURLY_BRACES == $id) {
				--$count;
			}
			if (ST_CURLY_CLOSE == $id) {
				++$count;
			}
			if (0 == $count) {
				break;
			}
		}
	}

	protected function refWalkUsefulUntil($tkns, &$ptr, $expectedId) {
		do {
			$ptr = $this->walkRight($tkns, $ptr, $this->ignoreFutileTokens);
		} while ($expectedId != $tkns[$ptr][0]);
	}

	protected function refWalkUsefulUntilReverse($tkns, &$ptr, $expectedId) {
		do {
			$ptr = $this->walkLeft($tkns, $ptr, $this->ignoreFutileTokens);
		} while ($ptr >= 0 && $expectedId != $tkns[$ptr][0]);
	}

	protected function render($tkns = null) {
		if (null == $tkns) {
			$tkns = $this->tkns;
		}

		$tkns = array_filter($tkns);
		$str = '';
		foreach ($tkns as $token) {
			list(, $text) = $this->getToken($token);
			$str .= $text;
		}
		return $str;
	}

	protected function renderLight($tkns = null) {
		if (null == $tkns) {
			$tkns = $this->tkns;
		}
		$str = '';
		foreach ($tkns as $token) {
			$str .= $token[1];
		}
		return $str;
	}

	protected function rightToken($ignoreList = []) {
		$i = $this->rightTokenIdx($ignoreList);

		return $this->tkns[$i];
	}

	protected function rightTokenIdx($ignoreList = []) {
		$ignoreList = $this->resolveIgnoreList($ignoreList);

		$i = $this->walkRight($this->tkns, $this->ptr, $ignoreList);

		return $i;
	}

	protected function rightTokenIs($token, $ignoreList = []) {
		return $this->tokenIs('right', $token, $ignoreList);
	}

	protected function rightTokenSubsetAtIdx($tkns, $idx, $ignoreList = []) {
		$ignoreList = $this->resolveIgnoreList($ignoreList);
		$idx = $this->walkRight($tkns, $idx, $ignoreList);

		return $idx;
	}

	protected function rightTokenSubsetIsAtIdx($tkns, $idx, $token, $ignoreList = []) {
		$idx = $this->rightTokenSubsetAtIdx($tkns, $idx, $ignoreList);

		return $this->resolveTokenMatch($tkns, $idx, $token);
	}

	protected function rightUsefulToken() {
		return $this->rightToken($this->ignoreFutileTokens);
	}

	protected function rightUsefulTokenIdx() {
		return $this->rightTokenIdx($this->ignoreFutileTokens);
	}

	protected function rightUsefulTokenIs($token) {
		return $this->rightTokenIs($token, $this->ignoreFutileTokens);
	}

	protected function rtrimAndAppendCode($code = '') {
		$this->code = rtrim($this->code) . $code;
	}

	protected function rtrimLnAndAppendCode($code = '') {
		$this->code = rtrim($this->code, "\t ") . $code;
	}

	protected function scanAndReplace(&$tkns, &$ptr, $start, $end, $call, $lookFor) {
		$lookFor = array_flip($lookFor);
		$placeholder = '<?php' . ' /*\x2 PHPOPEN \x3*/';
		$tmp = '';
		$tknCount = 1;
		$foundPotentialTokens = false;
		while (list($ptr, $token) = each($tkns)) {
			list($id, $text) = $this->getToken($token);
			if (isset($lookFor[$id])) {
				$foundPotentialTokens = true;
			}
			if ($start == $id) {
				++$tknCount;
			}
			if ($end == $id) {
				--$tknCount;
			}
			$tkns[$ptr] = null;
			if (0 == $tknCount) {
				break;
			}
			$tmp .= $text;
		}
		if ($foundPotentialTokens) {
			return $start . str_replace($placeholder, '', $this->{$call}($placeholder . $tmp)) . $end;
		}
		return $start . $tmp . $end;
	}

	protected function scanAndReplaceCurly(&$tkns, &$ptr, $start, $call, $lookFor) {
		$lookFor = array_flip($lookFor);
		$placeholder = '<?php' . ' /*\x2 PHPOPEN \x3*/';
		$tmp = '';
		$tknCount = 1;
		$foundPotentialTokens = false;
		while (list($ptr, $token) = each($tkns)) {
			list($id, $text) = $this->getToken($token);
			if (isset($lookFor[$id])) {
				$foundPotentialTokens = true;
			}
			if (ST_CURLY_OPEN == $id) {
				if (empty($start)) {
					$start = ST_CURLY_OPEN;
				}
				++$tknCount;
			}
			if (T_CURLY_OPEN == $id) {
				if (empty($start)) {
					$start = ST_CURLY_OPEN;
				}
				++$tknCount;
			}
			if (T_DOLLAR_OPEN_CURLY_BRACES == $id) {
				if (empty($start)) {
					$start = ST_DOLLAR . ST_CURLY_OPEN;
				}
				++$tknCount;
			}
			if (ST_CURLY_CLOSE == $id) {
				--$tknCount;
			}
			$tkns[$ptr] = null;
			if (0 == $tknCount) {
				break;
			}
			$tmp .= $text;
		}
		if ($foundPotentialTokens) {
			return $start . str_replace($placeholder, '', $this->{$call}($placeholder . $tmp)) . ST_CURLY_CLOSE;
		}
		return $start . $tmp . ST_CURLY_CLOSE;
	}

	protected function setIndent($increment) {
		$this->indent += $increment;
		if ($this->indent < 0) {
			$this->indent = 0;
		}
	}

	protected function siblings($tkns, $ptr) {
		$ignoreList = $this->resolveIgnoreList([T_WHITESPACE]);
		$left = $this->walkLeft($tkns, $ptr, $ignoreList);
		$right = $this->walkRight($tkns, $ptr, $ignoreList);
		return [$left, $right];
	}

	protected function substrCountTrailing($haystack, $needle) {
		return strlen(rtrim($haystack, " \t")) - strlen(rtrim($haystack, " \t" . $needle));
	}

	protected function tokenIs($direction, $token, $ignoreList = []) {
		if ('left' != $direction) {
			$direction = 'right';
		}
		if (!$this->useCache) {
			return $this->{$direction . 'tokenSubsetIsAtIdx'}($this->tkns, $this->ptr, $token, $ignoreList);
		}

		$key = $this->calculateCacheKey($direction, $ignoreList);
		if (isset($this->cache[$key])) {
			return $this->resolveTokenMatch($this->tkns, $this->cache[$key], $token);
		}

		$this->cache[$key] = $this->{$direction . 'tokenSubsetAtIdx'}($this->tkns, $this->ptr, $ignoreList);

		return $this->resolveTokenMatch($this->tkns, $this->cache[$key], $token);
	}

	protected function walkAndAccumulateCurlyBlock(&$tkns) {
		$count = 1;
		$ret = '';
		while (list($index, $token) = each($tkns)) {
			list($id, $text) = $this->getToken($token);
			$ret .= $text;

			if (ST_CURLY_OPEN == $id) {
				++$count;
			}
			if (T_CURLY_OPEN == $id) {
				++$count;
			}
			if (T_DOLLAR_OPEN_CURLY_BRACES == $id) {
				++$count;
			}
			if (ST_CURLY_CLOSE == $id) {
				--$count;
			}
			if (0 == $count) {
				break;
			}
		}
		return $ret;
	}

	protected function walkAndAccumulateStopAt(&$tkns, $tknid) {
		$ret = '';
		while (list($index, $token) = each($tkns)) {
			list($id, $text) = $this->getToken($token);
			if ($tknid == $id) {
				prev($tkns);
				break;
			}
			$ret .= $text;
		}
		return $ret;
	}

	protected function walkAndAccumulateStopAtAny(&$tkns, $tknids) {
		$tknids = array_flip($tknids);
		$ret = '';
		$id = null;
		while (list($index, $token) = each($tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			if (isset($tknids[$id])) {
				prev($tkns);
				break;
			}
			$ret .= $text;
		}
		return [$ret, $id];
	}

	protected function walkAndAccumulateUntil(&$tkns, $tknid) {
		$ret = '';
		while (list($index, $token) = each($tkns)) {
			list($id, $text) = $this->getToken($token);
			$ret .= $text;
			if ($tknid == $id) {
				break;
			}
		}
		return $ret;
	}

	protected function walkAndAccumulateUntilAny(&$tkns, $tknids) {
		$tknids = array_flip($tknids);
		$ret = '';
		while (list(, $token) = each($tkns)) {
			list($id, $text) = $this->getToken($token);
			$ret .= $text;
			if (isset($tknids[$id])) {
				break;
			}
		}
		return [$ret, $id];
	}

	protected function walkUntil($tknid) {
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			if ($id == $tknid) {
				return [$id, $text];
			}
		}
	}

	protected function walkUsefulRightUntil($tkns, $idx, $tokens) {
		$ignoreList = $this->resolveIgnoreList($this->ignoreFutileTokens);
		$tokens = array_flip($tokens);

		while ($idx > 0 && isset($tkns[$idx])) {
			$idx = $this->walkRight($tkns, $idx, $ignoreList);
			if (isset($tokens[$tkns[$idx][0]])) {
				return $idx;
			}
		}

		return;
	}

	private function calculateCacheKey($direction, $ignoreList) {
		return $direction . "\x2" . implode('', $ignoreList);
	}

	private function resolveFoundToken($foundToken, $token) {
		if ($foundToken === $token) {
			return true;
		} elseif (is_array($token) && isset($foundToken[1]) && in_array($foundToken[0], $token)) {
			return true;
		} elseif (is_array($token) && !isset($foundToken[1]) && in_array($foundToken, $token)) {
			return true;
		} elseif (isset($foundToken[1]) && $foundToken[0] == $token) {
			return true;
		}

		return false;
	}

	private function resolveIgnoreList($ignoreList = []) {
		if (!empty($ignoreList)) {
			return array_flip($ignoreList);
		}
		return [T_WHITESPACE => true];
	}

	private function resolveTokenMatch($tkns, $idx, $token) {
		if (!isset($tkns[$idx])) {
			return false;
		}

		$foundToken = $tkns[$idx];
		return $this->resolveFoundToken($foundToken, $token);
	}

	private function walkLeft($tkns, $idx, $ignoreList) {
		$i = $idx;
		while (--$i >= 0 && isset($ignoreList[$tkns[$i][0]]));
		return $i;
	}

	private function walkRight($tkns, $idx, $ignoreList) {
		$i = $idx;
		$tknsSize = sizeof($tkns) - 1;
		while (++$i < $tknsSize && isset($ignoreList[$tkns[$i][0]]));
		return $i;
	}
}

	
abstract class AdditionalPass extends FormatterPass {
	abstract public function getDescription();

	abstract public function getExample();
}

	

abstract class BaseCodeFormatter {
	protected $passes = [
		'StripSpaces' => false,

		'ReplaceBooleanAndOr' => false,
		'EliminateDuplicatedEmptyLines' => false,

		'RTrim' => false,
		'WordWrap' => false,

		'ConvertOpenTagWithEcho' => false,
		'RestoreComments' => false,
		'UpgradeToPreg' => false,
		'DocBlockToComment' => false,
		'LongArray' => false,

		'StripExtraCommaInArray' => false,
		'NoSpaceAfterPHPDocBlocks' => false,
		'RemoveUseLeadingSlash' => false,
		'ShortArray' => false,
		'MergeElseIf' => false,
		'SplitElseIf' => false,
		'AutoPreincrement' => false,
		'MildAutoPreincrement' => false,

		'CakePHPStyle' => false,

		'StripNewlineAfterClassOpen' => false,
		'StripNewlineAfterCurlyOpen' => false,

		'SortUseNameSpace' => false,
		'SpaceAroundExclamationMark' => false,

		'TightConcat' => false,

		'PSR2IndentWithSpace' => false,
		'AlignPHPCode' => false,
		'AllmanStyleBraces' => false,
		'NamespaceMergeWithOpenTag' => false,
		'MergeNamespaceWithOpenTag' => false,

		'LeftAlignComment' => false,

		'PSR2AlignObjOp' => false,
		'PSR2EmptyFunction' => false,
		'PSR2SingleEmptyLineAndStripClosingTag' => false,
		'PSR2ModifierVisibilityStaticOrder' => false,
		'PSR2CurlyOpenNextLine' => false,
		'PSR2LnAfterNamespace' => false,
		'PSR2KeywordsLowerCase' => false,

		'PSR1MethodNames' => false,
		'PSR1ClassNames' => false,

		'PSR1ClassConstants' => false,
		'PSR1BOMMark' => false,

		'EliminateDuplicatedEmptyLines' => false,
		'IndentTernaryConditions' => false,
		'ReindentComments' => false,
		'ReindentEqual' => false,
		'Reindent' => false,
		'ReindentAndAlignObjOps' => false,
		'ReindentObjOps' => false,

		'AlignDoubleSlashComments' => false,
		'AlignTypehint' => false,
		'AlignGroupDoubleArrow' => false,
		'AlignDoubleArrow' => false,
		'AlignEquals' => false,
		'AlignConstVisibilityEquals' => false,

		'ReindentSwitchBlocks' => false,
		'ReindentColonBlocks' => false,

		'SplitCurlyCloseAndTokens' => false,
		'ResizeSpaces' => false,

		'StripSpaceWithinControlStructures' => false,

		'StripExtraCommaInList' => false,
		'YodaComparisons' => false,

		'MergeDoubleArrowAndArray' => false,
		'MergeCurlyCloseAndDoWhile' => false,
		'MergeParenCloseWithCurlyOpen' => false,
		'NormalizeLnAndLtrimLines' => false,
		'ExtraCommaInArray' => false,
		'SmartLnAfterCurlyOpen' => false,
		'AddMissingCurlyBraces' => false,
		'OnlyOrderUseClauses' => false,
		'OrderAndRemoveUseClauses' => false,
		'AutoImportPass' => false,
		'ConstructorPass' => false,
		'SettersAndGettersPass' => false,
		'NormalizeIsNotEquals' => false,
		'RemoveIncludeParentheses' => false,
		'TwoCommandsInSameLine' => false,

		'SpaceBetweenMethods' => false,
		'GeneratePHPDoc' => false,
		'ReturnNull' => false,
		'AddMissingParentheses' => false,
		'WrongConstructorName' => false,
		'JoinToImplode' => false,
		'EncapsulateNamespaces' => false,
		'PrettyPrintDocBlocks' => false,
		'StrictBehavior' => false,
		'StrictComparison' => false,
		'ReplaceIsNull' => false,
		'DoubleToSingleQuote' => false,
		'LeftWordWrap' => false,
		'ClassToSelf' => false,
		'ClassToStatic' => false,
		'PSR2MultilineFunctionParams' => false,
		'SpaceAroundControlStructures' => false,

		'OrderMethodAndVisibility' => false,
		'OrderMethod' => false,
		'OrganizeClass' => false,
		'AutoSemicolon' => false,
		'PSR1OpenTags' => false,
		'PHPDocTypesToFunctionTypehint' => false,
		'RemoveSemicolonAfterCurly' => false,
		'NewLineBeforeReturn' => false,
		'EchoToPrint' => false,
		'TrimSpaceBeforeSemicolon' => false,
		'StripNewlineWithinClassBody' => false,
	];

	private $hasAfterExecutedPass = false;

	private $hasAfterFormat = false;

	private $hasBeforeFormat = false;

	private $hasBeforePass = false;

	private $shortcircuit = [
		'AlignDoubleArrow' => ['AlignGroupDoubleArrow'],
		'AlignGroupDoubleArrow' => ['AlignDoubleArrow'],
		'AllmanStyleBraces' => ['PSR2CurlyOpenNextLine'],
		'OnlyOrderUseClauses' => ['OrderAndRemoveUseClauses'],
		'OrderAndRemoveUseClauses' => ['OnlyOrderUseClauses'],
		'OrganizeClass' => ['ReindentComments', 'RestoreComments'],
		'ReindentAndAlignObjOps' => ['ReindentObjOps'],
		'ReindentComments' => ['OrganizeClass', 'RestoreComments'],
		'ReindentObjOps' => ['ReindentAndAlignObjOps'],
		'RestoreComments' => ['OrganizeClass', 'ReindentComments'],

		'PSR1OpenTags' => ['ReindentComments'],
		'PSR1BOMMark' => ['ReindentComments'],
		'PSR1ClassConstants' => ['ReindentComments'],
		'PSR1ClassNames' => ['ReindentComments'],
		'PSR1MethodNames' => ['ReindentComments'],
		'PSR2KeywordsLowerCase' => ['ReindentComments'],
		'PSR2IndentWithSpace' => ['ReindentComments'],
		'PSR2LnAfterNamespace' => ['ReindentComments'],
		'PSR2CurlyOpenNextLine' => ['ReindentComments'],
		'PSR2ModifierVisibilityStaticOrder' => ['ReindentComments'],
		'PSR2SingleEmptyLineAndStripClosingTag' => ['ReindentComments'],
	];

	private $shortcircuits = [];

	public function __construct() {
		$this->passes['AddMissingCurlyBraces'] = new AddMissingCurlyBraces();
		$this->passes['EliminateDuplicatedEmptyLines'] = new EliminateDuplicatedEmptyLines();
		$this->passes['ExtraCommaInArray'] = new ExtraCommaInArray();
		$this->passes['LeftAlignComment'] = new LeftAlignComment();
		$this->passes['MergeCurlyCloseAndDoWhile'] = new MergeCurlyCloseAndDoWhile();
		$this->passes['MergeDoubleArrowAndArray'] = new MergeDoubleArrowAndArray();
		$this->passes['MergeParenCloseWithCurlyOpen'] = new MergeParenCloseWithCurlyOpen();
		$this->passes['NormalizeIsNotEquals'] = new NormalizeIsNotEquals();
		$this->passes['NormalizeLnAndLtrimLines'] = new NormalizeLnAndLtrimLines();
		$this->passes['OrderAndRemoveUseClauses'] = new OrderAndRemoveUseClauses();
		$this->passes['Reindent'] = new Reindent();
		$this->passes['ReindentColonBlocks'] = new ReindentColonBlocks();
		$this->passes['ReindentComments'] = new ReindentComments();
		$this->passes['ReindentEqual'] = new ReindentEqual();
		$this->passes['ReindentObjOps'] = new ReindentObjOps();
		$this->passes['RemoveIncludeParentheses'] = new RemoveIncludeParentheses();
		$this->passes['ResizeSpaces'] = new ResizeSpaces();
		$this->passes['RTrim'] = new RTrim();
		$this->passes['SplitCurlyCloseAndTokens'] = new SplitCurlyCloseAndTokens();
		$this->passes['StripExtraCommaInList'] = new StripExtraCommaInList();
		$this->passes['TwoCommandsInSameLine'] = new TwoCommandsInSameLine();

		$this->hasAfterExecutedPass = method_exists($this, 'afterExecutedPass');
		$this->hasAfterFormat = method_exists($this, 'afterFormat');
		$this->hasBeforePass = method_exists($this, 'beforePass');
		$this->hasBeforeFormat = method_exists($this, 'beforeFormat');
	}

	public function disablePass($pass) {
		$this->passes[$pass] = null;
	}

	public function enablePass($pass) {
		$args = func_get_args();
		if (!isset($args[1])) {
			$args[1] = null;
		}

		if (!class_exists($pass)) {
			$passName = sprintf('ExternalPass%s', $pass);
			$passes = array_reverse($this->passes, true);
			$passes[$passName] = new ExternalPass($pass);
			$this->passes = array_reverse($passes, true);
			return;
		}

		if (isset($this->shortcircuits[$pass])) {
			return;
		}

		$this->passes[$pass] = new $pass($args[1]);

		$scPasses = &$this->shortcircuit[$pass];
		if (isset($scPasses)) {
			foreach ($scPasses as $scPass) {
				$this->disablePass($scPass);
				$this->shortcircuits[$scPass] = $pass;
			}
		}
	}

	public function forcePass($pass) {
		$this->shortcircuits = [];
		$args = func_get_args();
		return call_user_func_array([$this, 'enablePass'], $args);
	}

	public function formatCode($source = '') {
		$passes = array_map(
			function ($pass) {
				return clone $pass;
			},
			array_filter($this->passes)
		);
		list($foundTokens, $commentStack) = $this->getFoundTokens($source);
		$this->hasBeforeFormat && $this->beforeFormat($source);
		while (($pass = array_pop($passes))) {
			$this->hasBeforePass && $this->beforePass($source, $pass);
			if ($pass->candidate($source, $foundTokens)) {
				if (isset($pass->commentStack)) {
					$pass->commentStack = $commentStack;
				}
				$source = $pass->format($source);
				$this->hasAfterExecutedPass && $this->afterExecutedPass($source, $pass);
			}
		}
		$this->hasAfterFormat && $this->afterFormat($source);
		return $source;
	}

	public function getPassesNames() {
		return array_keys(array_filter($this->passes));
	}

	protected function getToken($token) {
		$ret = [$token, $token];
		if (isset($token[1])) {
			$ret = $token;
		}
		return $ret;
	}

	private function getFoundTokens($source) {
		$foundTokens = [];
		$commentStack = [];
		$tkns = token_get_all($source);
		foreach ($tkns as $token) {
			list($id, $text) = $this->getToken($token);
			$foundTokens[$id] = $id;
			if (T_COMMENT === $id) {
				$commentStack[] = [$id, $text];
			}
		}
		return [$foundTokens, $commentStack];
	}
}

	if ('1' === getenv('FMTDEBUG') || 'step' === getenv('FMTDEBUG')) {
		

final class CodeFormatter extends BaseCodeFormatter {
	public function afterExecutedPass($source, $className) {
		$cn = get_class($className);
		echo $cn, PHP_EOL;
		echo $source, PHP_EOL;
		echo $cn, PHP_EOL;
		echo '----', PHP_EOL;
		if ('step' == getenv('FMTDEBUG')) {
			readline();
		}
	}
}

	} elseif ('profile' === getenv('FMTDEBUG')) {
		

final class CodeFormatter extends BaseCodeFormatter {
	private $currentTiming = null;

	private $timings = [];

	public function afterExecutedPass($source, $className) {
		$cn = get_class($className);
		$this->timings[$cn] = microtime(true) - $this->currentTiming;
		echo $cn, ':', (memory_get_usage() / 1024 / 1024), "\t", (memory_get_peak_usage() / 1024 / 1024), PHP_EOL;
	}

	public function afterFormat($source) {
		asort($this->timings, SORT_NUMERIC);
		$total = array_sum($this->timings);

		$lines = [];
		foreach ($this->timings as $pass => $timing) {
			$lines[] = [$pass, $timing, str_pad(round($timing / $total * 100, 3) . '%', 8, ' ', STR_PAD_LEFT)];
		}
		echo tabwriter($lines);
	}

	public function beforeFormat($source) {
		echo 'before:', (memory_get_usage() / 1024 / 1024), "\t", (memory_get_peak_usage() / 1024 / 1024), PHP_EOL;
	}

	public function beforePass($source, $className) {
		$this->currentTiming = microtime(true);
	}
}

	} else {
		

final class CodeFormatter extends BaseCodeFormatter {
}

	}

	
final class AddMissingCurlyBraces extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (
			isset($foundTokens[T_ELSE]) ||
			isset($foundTokens[T_WHILE]) ||
			isset($foundTokens[T_FOR]) ||
			isset($foundTokens[T_FOREACH]) ||
			isset($foundTokens[T_ELSEIF]) ||
			isset($foundTokens[T_IF])
		) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
										$touchedCurlyClose = false;
		$hasCurlyOnLeft = false; 
		for ($index = sizeof($this->tkns) - 1; 0 <= $index; --$index) {
			$token = $this->tkns[$index];
			list($id) = $this->getToken($token);
			$this->ptr = $index;

			$hasCurlyOnLeft = false;

			switch ($id) {
			case T_ELSE:
				if ($this->rightTokenSubsetIsAtIdx($this->tkns, $this->ptr, [ST_CURLY_OPEN, ST_COLON, T_IF], $this->ignoreFutileTokens)) {
					break;
				}
				$this->insertCurlyBraces();
				break;

			case ST_CURLY_CLOSE:
				$touchedCurlyClose = true;
				break;

			case T_WHILE:
				if ($touchedCurlyClose) {
					$touchedCurlyClose = false;
					$hasCurlyOnLeft = true;
				}

			case T_FOR:
			case T_FOREACH:
			case T_ELSEIF:
			case T_IF:
				$this->refWalkUsefulUntil($this->tkns, $this->ptr, ST_PARENTHESES_OPEN);
				$this->refWalkBlock($this->tkns, $this->ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				if (
					($hasCurlyOnLeft && $this->rightTokenSubsetIsAtIdx($this->tkns, $this->ptr, [ST_SEMI_COLON], $this->ignoreFutileTokens)) ||
					$this->rightTokenSubsetIsAtIdx($this->tkns, $this->ptr, [ST_CURLY_OPEN, ST_COLON, ST_SEMI_COLON], $this->ignoreFutileTokens)
				) {
					break;
				}
				$this->insertCurlyBraces();
				break;
			}
		}
		return $this->render($this->tkns);
	}

	private function addSemicolon() {
		if (T_CLOSE_TAG == $this->tkns[$this->ptr][0]) {
			return $this->refInsert($this->tkns, $this->ptr, [ST_SEMI_COLON, ST_SEMI_COLON]);
		}
		++$this->ptr;
	}

	private function insertCurlyBraces() {
		$this->refSkipIfTokenIsAny($this->tkns, $this->ptr, [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]);
		$this->refInsert($this->tkns, $this->ptr, [ST_CURLY_OPEN, ST_CURLY_OPEN]);
		$this->refInsert($this->tkns, $this->ptr, [T_WHITESPACE, $this->newLine]);
		$this->refSkipBlocks($this->tkns, $this->ptr);
		$this->addSemicolon();
		$this->refInsert($this->tkns, $this->ptr, [T_WHITESPACE, $this->newLine]);
		$this->refInsert($this->tkns, $this->ptr, [ST_CURLY_CLOSE, ST_CURLY_CLOSE]);
		$this->refInsert($this->tkns, $this->ptr, [T_WHITESPACE, $this->newLine]);
	}
}

	

final class AutoImportPass extends FormatterPass {
	const AUTOIMPORT_PLACEHOLDER = "/*\x2 AUTOIMPORT \x3*/";

	const OPENER_PLACEHOLDER = "<?php /*\x2 AUTOIMPORTNS \x3*/";

	private $oracle = null;

	public function __construct($oracleFn) {
		$this->oracle = new SQLite3($oracleFn);
	}

	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source = '') {
		$namespaceCount = 0;
		$tokens = token_get_all($source);
		while (list(, $token) = each($tokens)) {
			list($id, $text) = $this->getToken($token);
			if (T_NAMESPACE == $id && !$this->rightUsefulTokenIs(T_NS_SEPARATOR)) {
				++$namespaceCount;
			}
		}
		if ($namespaceCount <= 1) {
			return $this->singleNamespace($source);
		}

		$return = '';
		reset($tokens);
		while (list($index, $token) = each($tokens)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_NAMESPACE:
				$return .= $text;
				if ($this->rightUsefulTokenIs(T_NS_SEPARATOR)) {
					break;
				}
				while (list($index, $token) = each($tokens)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					$return .= $text;
					if (ST_CURLY_OPEN == $id) {
						break;
					}
				}
				$namespaceBlock = '';
				$curlyCount = 1;
				while (list($index, $token) = each($tokens)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					$namespaceBlock .= $text;
					if (ST_CURLY_OPEN == $id) {
						++$curlyCount;
					} elseif (ST_CURLY_CLOSE == $id) {
						--$curlyCount;
					}

					if (0 == $curlyCount) {
						break;
					}
				}
				$return .= str_replace(
					self::OPENER_PLACEHOLDER,
					'',
					$this->singleNamespace(self::OPENER_PLACEHOLDER . $namespaceBlock)
				);
				break;
			default:
				$return .= $text;
			}
		}

		return $return;
	}

	private function calculateAlias($use) {
		if (false !== stripos($use, ' as ')) {
			return substr(strstr($use, ' as '), strlen(' as '), -1);
		}
		return basename(str_replace('\\', '/', trim(substr($use, strlen('use'), -1))));
	}

	private function singleNamespace($source) {
		$classList = [];
		$results = $this->oracle->query('SELECT class FROM classes ORDER BY class');
		while (($row = $results->fetchArray())) {
			$className = $row['class'];
			$classNameParts = explode('\\', $className);
			$baseClassName = '';
			while (($cnp = array_pop($classNameParts))) {
				$baseClassName = $cnp . $baseClassName;
				$classList[strtolower($baseClassName)][ltrim(str_replace('\\\\', '\\', '\\' . $className) . ' as ' . $baseClassName, '\\')] = ltrim(str_replace('\\\\', '\\', '\\' . $className) . ' as ' . $baseClassName, '\\');
			}
		}

		$tokens = token_get_all($source);
		$aliasCount = [];
		$namespaceName = '';
		while (list($index, $token) = each($tokens)) {
			list($id, $text) = $this->getToken($token);
			if (T_NAMESPACE == $id && !$this->rightUsefulTokenIs(T_NS_SEPARATOR)) {
				while (list($index, $token) = each($tokens)) {
					list($id, $text) = $this->getToken($token);
					if (T_NS_SEPARATOR == $id || T_STRING == $id) {
						$namespaceName .= $text;
					}
					if (ST_SEMI_COLON == $id || ST_CURLY_OPEN == $id) {
						break;
					}
				}
			}
			if (T_USE == $id || T_NAMESPACE == $id || T_FUNCTION == $id || T_DOUBLE_COLON == $id || T_OBJECT_OPERATOR == $id) {
				while (list($index, $token) = each($tokens)) {
					list($id, $text) = $this->getToken($token);
					if (ST_SEMI_COLON == $id || ST_PARENTHESES_OPEN == $id || ST_CURLY_OPEN == $id) {
						break;
					}
				}
			}
			if (T_CLASS == $id) {
				while (list($index, $token) = each($tokens)) {
					list($id, $text) = $this->getToken($token);
					if (T_EXTENDS == $id || T_IMPLEMENTS == $id || ST_CURLY_OPEN == $id) {
						break;
					}
				}
			}

			$lowerText = strtolower($text);
			if (T_STRING === $id && ($this->leftTokenSubsetIsAtIdx($tokens, $index, T_NEW) || $this->rightTokenSubsetIsAtIdx($tokens, $index, T_DOUBLE_COLON))) {
				if (!isset($aliasCount[$lowerText])) {
					$aliasCount[$lowerText] = 0;
				}
				++$aliasCount[$lowerText];
			}
		}
		$autoImportCandidates = array_intersect_key($classList, $aliasCount);

		$tokens = token_get_all($source);
		$touchedNamespace = false;
		$touchedFunction = false;
		$return = '';
		while (list(, $token) = each($tokens)) {
			list($id, $text) = $this->getToken($token);

			if (T_NAMESPACE == $id && !$this->rightUsefulTokenIs(T_NS_SEPARATOR)) {
				$touchedNamespace = true;
			}
			if (T_FUNCTION == $id) {
				$touchedFunction = true;
			}
			if (!$touchedFunction && $touchedNamespace && (T_FINAL == $id || T_STATIC == $id || T_USE == $id || T_CLASS == $id || T_INTERFACE == $id || T_TRAIT == $id)) {
				$return .= self::AUTOIMPORT_PLACEHOLDER . $this->newLine;
				$return .= $text;

				break;
			}
			$return .= $text;
		}
		while (list(, $token) = each($tokens)) {
			list(, $text) = $this->getToken($token);
			$return .= $text;
		}

		$usedAlias = $this->usedAliasList($source);
		$replacement = '';
		foreach ($autoImportCandidates as $alias => $candidates) {
			if (isset($usedAlias[$alias])) {
				continue;
			}
			usort($candidates, function ($a, $b) use ($namespaceName) {
				return similar_text($a, $namespaceName) < similar_text($b, $namespaceName);
			});
			$replacement .= 'use ' . implode(';' . $this->newLine . '//use ', $candidates) . ';' . $this->newLine;
		}

		$return = str_replace(self::AUTOIMPORT_PLACEHOLDER . $this->newLine, $replacement, $return);
		return $return;
	}

	private function usedAliasList($source) {
		$tokens = token_get_all($source);
		$useStack = [];
		$newTokens = [];
		$nextTokens = [];
		$touchedNamespace = false;
		while (list(, $popToken) = each($tokens)) {
			$nextTokens[] = $popToken;
			while (($token = array_shift($nextTokens))) {
				list($id, $text) = $this->getToken($token);
				if (T_NAMESPACE == $id && !$this->rightUsefulTokenIs(T_NS_SEPARATOR)) {
					$touchedNamespace = true;
				}
				if (T_USE === $id) {
					$useItem = $text;
					while (list(, $token) = each($tokens)) {
						list($id, $text) = $this->getToken($token);
						if (ST_SEMI_COLON === $id) {
							$useItem .= $text;
							break;
						} elseif (ST_COMMA === $id) {
							$useItem .= ST_SEMI_COLON . $this->newLine;
							$nextTokens[] = [T_USE, 'use'];
							break;
						}
						$useItem .= $text;
					}
					$useStack[] = $useItem;
					$token = new SurrogateToken();
				}
				if (T_FINAL === $id || T_ABSTRACT === $id || T_INTERFACE === $id || T_CLASS === $id || T_FUNCTION === $id || T_TRAIT === $id || T_VARIABLE === $id) {
					if (sizeof($useStack) > 0) {
						$newTokens[] = $this->newLine;
						$newTokens[] = $this->newLine;
					}
					$newTokens[] = $token;
					break 2;
				} elseif ($touchedNamespace && (T_DOC_COMMENT === $id || T_COMMENT === $id)) {
					if (sizeof($useStack) > 0) {
						$newTokens[] = $this->newLine;
					}
					$newTokens[] = $token;
					break 2;
				}
				$newTokens[] = $token;
			}
		}

		natcasesort($useStack);
		$aliasList = [];
		$aliasCount = [];
		foreach ($useStack as $use) {
			$alias = $this->calculateAlias($use);
			$alias = strtolower($alias);
			$aliasList[$alias] = strtolower($use);
			$aliasCount[$alias] = 0;
		}
		foreach ($newTokens as $token) {
			if (!($token instanceof SurrogateToken)) {
				list($id, $text) = $this->getToken($token);
				$lowerText = strtolower($text);
				if (T_STRING === $id && isset($aliasList[$lowerText])) {
					++$aliasCount[$lowerText];
				}
			}
		}
		while (list($index, $token) = each($tokens)) {
			list($id, $text) = $this->getToken($token);
			$lowerText = strtolower($text);
			if (T_STRING === $id && isset($aliasList[$lowerText]) && ($this->leftTokenSubsetIsAtIdx($tokens, $index, T_NEW) || $this->rightTokenSubsetIsAtIdx($tokens, $index, T_DOUBLE_COLON))) {
				++$aliasCount[$lowerText];
			} elseif (T_DOC_COMMENT === $id) {
				foreach ($aliasList as $alias => $use) {
					if (false !== stripos($text, $alias)) {
						++$aliasCount[$alias];
					}
				}
			}
		}
		return $aliasCount;
	}
}
	
final class ConstructorPass extends FormatterPass {
	const TYPE_CAMEL_CASE = 'camel';

	const TYPE_GOLANG = 'golang';

	const TYPE_SNAKE_CASE = 'snake';

	
	private $type;

	public function __construct($type = self::TYPE_CAMEL_CASE) {
		$this->type = self::TYPE_CAMEL_CASE;
		if (self::TYPE_CAMEL_CASE == $type || self::TYPE_SNAKE_CASE == $type || self::TYPE_GOLANG == $type) {
			$this->type = $type;
		}
	}

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_CLASS])) {
			return true;
		}
		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

								$classAttributes = [];
		$functionList = [];
		$touchedVisibility = false;
		$touchedFunction = false;
		$curlyCount = null;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_CLASS:
				$classAttributes = [];
				$functionList = [];
				$touchedVisibility = false;
				$touchedFunction = false;
				$curlyCount = null;
				$this->appendCode($text);
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					if (ST_CURLY_OPEN == $id) {
						++$curlyCount;
					}
					if (ST_CURLY_CLOSE == $id) {
						--$curlyCount;
					}
					if (0 === $curlyCount) {
						break;
					}
					$this->appendCode($text);
					if (T_PUBLIC == $id) {
						$touchedVisibility = T_PUBLIC;
					} elseif (T_PRIVATE == $id) {
						$touchedVisibility = T_PRIVATE;
					} elseif (T_PROTECTED == $id) {
						$touchedVisibility = T_PROTECTED;
					}
					if (
						T_VARIABLE == $id &&
						(
							T_PUBLIC == $touchedVisibility ||
							T_PRIVATE == $touchedVisibility ||
							T_PROTECTED == $touchedVisibility
						)
					) {
						$classAttributes[] = $text;
						$touchedVisibility = null;
					} elseif (T_FUNCTION == $id) {
						$touchedFunction = true;
					} elseif ($touchedFunction && T_STRING == $id) {
						$functionList[] = $text;
						$touchedVisibility = null;
						$touchedFunction = false;
					}
				}
				$functionList = array_combine($functionList, $functionList);
				if (!isset($functionList['__construct'])) {
					$this->appendCode('function __construct(' . implode(', ', $classAttributes) . '){' . $this->newLine);
					foreach ($classAttributes as $var) {
						$this->appendCode($this->generate($var));
					}
					$this->appendCode('}' . $this->newLine);
				}

				$this->appendCode($text);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}

	private function generate($var) {
		switch ($this->type) {
		case self::TYPE_SNAKE_CASE:
			$ret = $this->generateSnakeCase($var);
			break;
		case self::TYPE_GOLANG:
			$ret = $this->generateGolang($var);
			break;
		case self::TYPE_CAMEL_CASE:
		default:
			$ret = $this->generateCamelCase($var);
			break;
		}
		return $ret;
	}

	private function generateCamelCase($var) {
		$str = '$this->set' . ucfirst(str_replace('$', '', $var)) . '(' . $var . ');' . $this->newLine;
		return $str;
	}

	private function generateGolang($var) {
		$str = '$this->Set' . ucfirst(str_replace('$', '', $var)) . '(' . $var . ');' . $this->newLine;
		return $str;
	}

	private function generateSnakeCase($var) {
		$str = '$this->set_' . (str_replace('$', '', $var)) . '(' . $var . ');' . $this->newLine;
		return $str;
	}
}
	
final class EliminateDuplicatedEmptyLines extends FormatterPass {
	const EMPTY_LINE = "\x2 EMPTYLINE \x3";

	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_WHITESPACE:
			case T_COMMENT:
			case T_OPEN_TAG:
				if ($this->hasLn($text) || (T_COMMENT == $id && '//' == substr($text, 0, 2))) {
					$text = str_replace($this->newLine, self::EMPTY_LINE . $this->newLine, $text);
				}

				$this->appendCode($text);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}

		$ret = $this->code;
		$count = 0;
		do {
			$ret = str_replace(
				self::EMPTY_LINE . $this->newLine . self::EMPTY_LINE . $this->newLine . self::EMPTY_LINE . $this->newLine,
				self::EMPTY_LINE . $this->newLine . self::EMPTY_LINE . $this->newLine,
				$ret,
				$count
			);
		} while ($count > 0);
		$ret = str_replace(self::EMPTY_LINE, '', $ret);

		list($id) = $this->getToken(end($this->tkns));
		if (T_WHITESPACE === $id) {
			$ret = rtrim($ret) . $this->newLine;
		}

		return $ret;
	}
}
	
class ExternalPass {
	private $passName = '';

	public function __construct($passName) {
		$this->passName = $passName;
	}

	public function candidate() {
		return !empty($this->passName);
	}

	public function format($source) {
		$descriptorspec = [
			0 => ['pipe', 'r'], 			1 => ['pipe', 'w'], 			2 => ['pipe', 'w'], 		];

		$cwd = getcwd();
		$env = [];
		$argv = $_SERVER['argv'];
		$pipes = null;

		$external = str_replace('fmt.', 'fmt-external.', $cwd . DIRECTORY_SEPARATOR . $argv[0]);

		$cmd = $_SERVER['_'] . ' ' . $external . ' --pass=' . $this->passName;
		$process = proc_open(
			$cmd,
			$descriptorspec,
			$pipes,
			$cwd,
			$env
		);
		if (!is_resource($process)) {
			fclose($pipes[0]);
			fclose($pipes[1]);
			fclose($pipes[2]);
			proc_close($process);
			return $source;
		}
		fwrite($pipes[0], $source);
		fclose($pipes[0]);

		$source = stream_get_contents($pipes[1]);
		fclose($pipes[1]);

		fclose($pipes[2]);
		proc_close($process);
		return $source;
	}
}

	
final class ExtraCommaInArray extends FormatterPass {
	const ST_SHORT_ARRAY_OPEN = 'SHORT_ARRAY_OPEN';

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_ARRAY]) || isset($foundTokens[ST_BRACKET_OPEN])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);

										$contextStack = [];
		$touchedBracketOpen = false;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_BRACKET_OPEN:
				$touchedBracketOpen = true;
				$found = ST_BRACKET_OPEN;
				if ($this->isShortArray()) {
					$found = self::ST_SHORT_ARRAY_OPEN;
				}
				$contextStack[] = $found;
				break;

			case ST_BRACKET_CLOSE:
				if (isset($contextStack[0]) && !$this->leftTokenIs(ST_BRACKET_OPEN)) {
					if (self::ST_SHORT_ARRAY_OPEN == end($contextStack) && ($this->hasLnLeftToken() || $this->hasLnBefore()) && !$this->leftUsefulTokenIs(ST_COMMA)) {
						$prevTokenIdx = $this->leftUsefulTokenIdx();
						list($tknId, $tknText) = $this->getToken($this->tkns[$prevTokenIdx]);
						if (T_END_HEREDOC != $tknId && ST_BRACKET_OPEN != $tknId) {
							$this->tkns[$prevTokenIdx] = [$tknId, $tknText . ','];
						}
					} elseif (self::ST_SHORT_ARRAY_OPEN == end($contextStack) && !($this->hasLnLeftToken() || $this->hasLnBefore()) && $this->leftUsefulTokenIs(ST_COMMA)) {
						$prevTokenIdx = $this->leftUsefulTokenIdx();
						list($tknId, $tknText) = $this->getToken($this->tkns[$prevTokenIdx]);
						$this->tkns[$prevTokenIdx] = [$tknId, rtrim($tknText, ',')];
					}
					array_pop($contextStack);
					break;
				}
				$touchedBracketOpen = false;
				break;

			case ST_PARENTHESES_OPEN:
				$found = ST_PARENTHESES_OPEN;
				if ($this->leftUsefulTokenIs(T_STRING)) {
					$found = T_STRING;
				} elseif ($this->leftUsefulTokenIs(T_ARRAY)) {
					$found = T_ARRAY;
				}
				$contextStack[] = $found;
				break;

			case ST_PARENTHESES_CLOSE:
				if (isset($contextStack[0])) {
					if (T_ARRAY == end($contextStack) && ($this->hasLnLeftToken() || $this->hasLnBefore()) && !$this->leftUsefulTokenIs(ST_COMMA)) {
						$prevTokenIdx = $this->leftUsefulTokenIdx();
						list($tknId, $tknText) = $this->getToken($this->tkns[$prevTokenIdx]);
						if (T_END_HEREDOC != $tknId && ST_PARENTHESES_OPEN != $tknId) {
							$this->tkns[$prevTokenIdx] = [$tknId, $tknText . ','];
						}
					} elseif (T_ARRAY == end($contextStack) && !($this->hasLnLeftToken() || $this->hasLnBefore()) && $this->leftUsefulTokenIs(ST_COMMA)) {
						$prevTokenIdx = $this->leftUsefulTokenIdx();
						list($tknId, $tknText) = $this->getToken($this->tkns[$prevTokenIdx]);
						$this->tkns[$prevTokenIdx] = [$tknId, rtrim($tknText, ',')];
					}
					array_pop($contextStack);
				}
				break;

			default:
				$touchedBracketOpen = false;
				break;
			}
			$this->tkns[$this->ptr] = [$id, $text];
		}
		return $this->renderLight();
	}
}
	
final class LeftAlignComment extends FormatterPass {
	const NON_INDENTABLE_COMMENT = "/*\x2 COMMENT \x3*/";

	public function candidate($source, $foundTokens) {
		if (
			isset($foundTokens[T_COMMENT]) ||
			isset($foundTokens[T_DOC_COMMENT])
		) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$touchedNonIndentableComment = false;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			if (self::NON_INDENTABLE_COMMENT === $text) {
				$touchedNonIndentableComment = true;
				continue;
			}
			switch ($id) {
			case T_COMMENT:
			case T_DOC_COMMENT:
				if ($touchedNonIndentableComment) {
					$touchedNonIndentableComment = false;
					$lines = explode($this->newLine, $text);
					$lines = array_map(function ($v) {
						$v = ltrim($v);
						if ('*' === substr($v, 0, 1)) {
							$v = ' ' . $v;
						}
						return $v;
					}, $lines);
					$this->appendCode(implode($this->newLine, $lines));
					break;
				}
				$this->appendCode($text);
				break;

			case T_WHITESPACE:
				list(, $nextText) = $this->inspectToken(1);
				if (self::NON_INDENTABLE_COMMENT === $nextText && substr_count($text, "\n") >= 2) {
					$text = substr($text, 0, strrpos($text, "\n") + 1);
					$this->appendCode($text);
					break;
				} elseif (self::NON_INDENTABLE_COMMENT === $nextText && substr_count($text, "\n") === 1) {
					$text = substr($text, 0, strrpos($text, "\n") + 1);
					$this->appendCode($text);
					break;
				}
				$this->appendCode($text);
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}

	
final class MergeCurlyCloseAndDoWhile extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_WHILE], $foundTokens[T_DO])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_WHILE:
				$str = $text;
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					$str .= $text;
					if (
						ST_CURLY_OPEN == $id ||
						ST_COLON == $id ||
						(ST_SEMI_COLON == $id && (ST_SEMI_COLON == $ptId || ST_CURLY_OPEN == $ptId || T_COMMENT == $ptId || T_DOC_COMMENT == $ptId))
					) {
						$this->appendCode($str);
						break;
					} elseif (ST_SEMI_COLON == $id && !(ST_SEMI_COLON == $ptId || ST_CURLY_OPEN == $ptId || T_COMMENT == $ptId || T_DOC_COMMENT == $ptId)) {
						$this->rtrimAndAppendCode($str);
						break;
					}
				}
				break;

			case T_WHITESPACE:
				$this->appendCode($text);
				break;

			default:
				$ptId = $id;
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}

	
final class MergeDoubleArrowAndArray extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_ARRAY], $foundTokens[T_DOUBLE_ARROW])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$touchedDoubleArrow = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			if (T_DOUBLE_ARROW == $id) {
				$touchedDoubleArrow = true;
				$this->appendCode($text);
				continue;
			}

			if ($touchedDoubleArrow) {
				if (
					T_WHITESPACE == $id ||
					T_DOC_COMMENT == $id ||
					T_COMMENT == $id
				) {
					$this->appendCode($text);
					continue;
				}
				if (T_ARRAY === $id) {
					$this->rtrimAndAppendCode($text);
					$touchedDoubleArrow = false;
					continue;
				}
				$touchedDoubleArrow = false;
			}

			$this->appendCode($text);
		}
		return $this->code;
	}
}
	
final class MergeParenCloseWithCurlyOpen extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[ST_CURLY_OPEN]) || isset($foundTokens[T_ELSE]) || isset($foundTokens[T_ELSEIF])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

						$touchedElseStringParenClose = false;
		$touchedCurlyClose = false;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_STRING:
			case ST_PARENTHESES_CLOSE:
				$touchedElseStringParenClose = true;
				$this->appendCode($text);
				break;

			case ST_CURLY_CLOSE:
				$touchedCurlyClose = true;
				$this->appendCode($text);
				break;

			case ST_CURLY_OPEN:
				if ($touchedElseStringParenClose) {
					$touchedElseStringParenClose = false;
					$this->code = rtrim($this->code);
				}
				$this->appendCode($text);
				break;

			case T_ELSE:
				$touchedElseStringParenClose = true;
			case T_ELSEIF:
				if ($touchedCurlyClose) {
					$this->code = rtrim($this->code);
					$touchedCurlyClose = false;
				}
				$this->appendCode($text);
				break;

			case T_WHITESPACE:
				$this->appendCode($text);
				break;

			default:
				$touchedElseStringParenClose = false;
				$touchedCurlyClose = false;
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}

	
final class NormalizeIsNotEquals extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_IS_NOT_EQUAL])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			if (T_IS_NOT_EQUAL == $id) {
				$text = str_replace('<>', '!=', $text) . $this->getSpace();
			}
			$this->appendCode($text);
		}

		return $this->code;
	}
}

	
final class NormalizeLnAndLtrimLines extends FormatterPass {
	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$source = str_replace(["\r\n", "\n\r", "\r", "\n"], $this->newLine, $source);
		$source = preg_replace('/\h+$/mu', '', $source);

		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_INLINE_HTML:
				$this->appendCode($text);
				break;
			case ST_QUOTE:
				$this->appendCode($text);
				$this->printUntilTheEndOfString();
				break;
			case T_START_HEREDOC:
				$this->appendCode($text);
				$this->printUntil(T_END_HEREDOC);
				break;

			case T_COMMENT:
			case T_DOC_COMMENT:
				list($prevId, $prevText) = $this->inspectToken(-1);

				if (T_WHITESPACE === $prevId && ("\n" === $prevText || "\n\n" == substr($prevText, -2, 2))) {
					$this->appendCode(LeftAlignComment::NON_INDENTABLE_COMMENT);
				}

				$lines = explode($this->newLine, $text);
				$newText = '';
				foreach ($lines as $v) {
					$v = ltrim($v);
					if ('*' === substr($v, 0, 1)) {
						$v = ' ' . $v;
					}
					$newText .= $this->newLine . $v;
				}

				$this->appendCode(ltrim($newText));
				break;

			case T_CONSTANT_ENCAPSED_STRING:
				$this->appendCode($text);
				break;
			default:
				if ($this->hasLn($text)) {
					$trailingNewLine = $this->substrCountTrailing($text, $this->newLine);
					if ($trailingNewLine > 0) {
						$text = trim($text) . str_repeat($this->newLine, $trailingNewLine);
					}
				}
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}
}

	
final class Reindent extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (
			isset($foundTokens[ST_CURLY_OPEN]) ||
			isset($foundTokens[ST_PARENTHESES_OPEN]) ||
			isset($foundTokens[ST_BRACKET_OPEN])
		) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$this->useCache = true;

						$foundStack = [];

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->cache = [];

			if (
				(
					T_WHITESPACE === $id ||
					(T_COMMENT === $id && '//' == substr($text, 0, 2))
				) && $this->hasLn($text)
			) {
				$bottomFoundStack = end($foundStack);
				if (isset($bottomFoundStack['implicit']) && $bottomFoundStack['implicit']) {
					$idx = sizeof($foundStack) - 1;
					$foundStack[$idx]['implicit'] = false;
					$this->setIndent(+1);
				}
			}
			switch ($id) {
			case ST_QUOTE:
				$this->appendCode($text);
				$this->printUntilTheEndOfString();
				break;

			case T_CLOSE_TAG:
				$this->appendCode($text);
				$this->printUntilAny([T_OPEN_TAG, T_OPEN_TAG_WITH_ECHO]);
				break;

			case T_START_HEREDOC:
				$this->appendCode($text);
				$this->printUntil(T_END_HEREDOC);
				break;

			case T_CONSTANT_ENCAPSED_STRING:
			case T_ENCAPSED_AND_WHITESPACE:
			case T_STRING_VARNAME:
			case T_NUM_STRING:
				$this->appendCode($text);
				break;

			case T_DOLLAR_OPEN_CURLY_BRACES:
			case T_CURLY_OPEN:
			case ST_CURLY_OPEN:
			case ST_PARENTHESES_OPEN:
			case ST_BRACKET_OPEN:
				$indentToken = [
					'id' => $id,
					'implicit' => true,
				];
				$this->appendCode($text);
				if ($this->hasLnAfter()) {
					$indentToken['implicit'] = false;
					$this->setIndent(+1);
				}
				$foundStack[] = $indentToken;
				break;

			case ST_CURLY_CLOSE:
			case ST_PARENTHESES_CLOSE:
			case ST_BRACKET_CLOSE:
				$poppedID = array_pop($foundStack);
				if (false === $poppedID['implicit']) {
					$this->setIndent(-1);
				}
				$this->appendCode($text);
				break;

			case T_DOC_COMMENT:
				$text = str_replace($this->newLine, $this->newLine . $this->getIndent(), $text);
				$this->appendCode($text);
				break;

			case T_COMMENT:
			case T_WHITESPACE:
				if (
					$this->hasLn($text) &&
					$this->rightTokenIs([T_COMMENT, T_DOC_COMMENT]) &&
					$this->rightUsefulTokenIs([T_CASE, T_DEFAULT])
				) {
					$this->setIndent(-1);
					$this->appendCode(str_replace($this->newLine, $this->newLine . $this->getIndent(), $text));
					$this->setIndent(+1);
					break;
				}

			default:
				$hasLn = $this->hasLn($text);
				if ($hasLn) {
					$isNextCurlyParenBracketClose = $this->rightTokenIs([T_CASE, T_DEFAULT, ST_CURLY_CLOSE, ST_PARENTHESES_CLOSE, ST_BRACKET_CLOSE]);
					if (!$isNextCurlyParenBracketClose) {
						$text = str_replace($this->newLine, $this->newLine . $this->getIndent(), $text);
					} elseif ($isNextCurlyParenBracketClose) {
						$this->setIndent(-1);
						$text = str_replace($this->newLine, $this->newLine . $this->getIndent(), $text);
						$this->setIndent(+1);
					}
				}
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}

	
final class ReindentColonBlocks extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_ENDIF]) || isset($foundTokens[T_ENDWHILE]) || isset($foundTokens[T_ENDFOREACH]) || isset($foundTokens[T_ENDFOR])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			if (
				T_ENDIF == $id || T_ELSEIF == $id ||
				T_ENDFOR == $id || T_ENDFOREACH == $id || T_ENDWHILE == $id ||
				(T_ELSE == $id && !$this->rightUsefulTokenIs(ST_CURLY_OPEN))
			) {
				$this->setIndent(-1);
			}
			switch ($id) {
			case T_ENDFOR:
			case T_ENDFOREACH:
			case T_ENDWHILE:
			case T_ENDIF:
				$this->appendCode($text);
				break;

			case T_ELSE:
				$this->appendCode($text);
				$this->indentBlock();
				break;

			case T_FOR:
			case T_FOREACH:
			case T_WHILE:
			case T_ELSEIF:
			case T_IF:
				$this->appendCode($text);
				$this->printUntil(ST_PARENTHESES_OPEN);
				$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				$this->indentBlock();
				break;

			case T_START_HEREDOC:
				$this->appendCode($text);
				$this->printUntil(T_END_HEREDOC);
				break;

			default:
				$hasLn = $this->hasLn($text);
				if ($hasLn) {
					if ($this->rightTokenIs([T_ENDIF, T_ELSE, T_ELSEIF, T_ENDFOR, T_ENDFOREACH, T_ENDWHILE])) {
						$this->setIndent(-1);
						$text = str_replace($this->newLine, $this->newLine . $this->getIndent(), $text);
						$this->setIndent(+1);
					} else {
						$text = str_replace($this->newLine, $this->newLine . $this->getIndent(), $text);
					}
				}
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}

	private function indentBlock() {
		$foundId = $this->printUntilAny([ST_COLON, ST_SEMI_COLON, ST_CURLY_OPEN]);
		if (ST_COLON === $foundId && !$this->rightTokenIs([T_CLOSE_TAG])) {
			$this->setIndent(+1);
		}
	}
}
	
final class ReindentComments extends FormatterPass {
	public $commentStack = [];

	
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_COMMENT])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		reset($this->commentStack);
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->tkns[$this->ptr] = [$id, $text];
			if (T_COMMENT == $id) {
				if (LeftAlignComment::NON_INDENTABLE_COMMENT == $text) {
					continue;
				}

				$oldComment = current($this->commentStack);
				next($this->commentStack);
				if (substr($text, 0, 2) != '/*') {
					continue;
				}

				list($ptId, $ptText) = $this->inspectToken(-1);
				if (T_WHITESPACE != $ptId) {
					continue;
				}

				$indent = substr(strrchr($ptText, 10), 1);
				$indentLevel = strlen($indent);
				$innerIndentLevel = $indentLevel + 1;
				$innerIndent = str_repeat($this->indentChar, $innerIndentLevel);

				$lines = explode($this->newLine, $oldComment[1]);
				$forceIndentation = false;
				$leftMostIndentation = -1;
				foreach ($lines as $idx => $line) {
					if (trim($line) == '') {
						continue;
					}
					if (substr($line, 0, 2) == '/*') {
						continue;
					}
					if (substr($line, -2, 2) == '*/') {
						continue;
					}

					if (substr($line, 0, $innerIndentLevel) != $innerIndent) {
						$forceIndentation = true;
					}

					if (!$forceIndentation) {
						continue;
					}

					$lenLine = strlen($line);
					for ($i = 0; $i < $lenLine; ++$i) {
						if ("\t" != $line[$i]) {
							break;
						}
					}
					if (-1 == $leftMostIndentation) {
						$leftMostIndentation = $i;
					}
					$leftMostIndentation = min($leftMostIndentation, $i);
				}

				if ($forceIndentation) {
					foreach ($lines as $idx => $line) {
						if (trim($line) == '') {
							continue;
						}
						if (substr($line, 0, 2) == '/*') {
							continue;
						}
						if (substr($line, -2, 2) == '*/') {
							$lines[$idx] = str_repeat($this->indentChar, $indentLevel) . '*/';
							continue;
						}
						$lines[$idx] = $innerIndent . substr($line, $leftMostIndentation);
					}
				}
				$this->tkns[$this->ptr] = [T_COMMENT, implode($this->newLine, $lines)];
			}
		}

		return $this->renderLight($this->tkns);
	}
}
	
final class ReindentEqual extends FormatterPass {
	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		for ($index = sizeof($this->tkns) - 1; 0 <= $index; --$index) {
			$token = $this->tkns[$index];
			list($id) = $this->getToken($token);
			$this->ptr = $index;

			if (ST_SEMI_COLON == $id) {
				--$index;
				$this->scanUntilEqual($index);
			}
		}

		return $this->render($this->tkns);
	}

	private function scanUntilEqual($index) {
		for ($index; 0 <= $index; --$index) {
			$token = $this->tkns[$index];
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			switch ($id) {
			case ST_QUOTE:
				$this->refWalkUsefulUntilReverse($this->tkns, $index, ST_QUOTE);
				break;

			case T_OPEN_TAG:
				$this->refWalkUsefulUntilReverse($this->tkns, $index, T_CLOSE_TAG);
				break;

			case T_END_HEREDOC:
				$this->refWalkUsefulUntilReverse($this->tkns, $index, T_START_HEREDOC);
				break;

			case ST_CURLY_CLOSE:
				$this->refWalkCurlyBlockReverse($this->tkns, $index);
				break;

			case ST_PARENTHESES_CLOSE:
				$this->refWalkBlockReverse($this->tkns, $index, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				break;

			case ST_BRACKET_CLOSE:
				$this->refWalkBlockReverse($this->tkns, $index, ST_BRACKET_OPEN, ST_BRACKET_CLOSE);
				break;

			case T_STRING:
				if ($this->rightUsefulTokenIs(ST_PARENTHESES_OPEN) && !$this->leftUsefulTokenIs(ST_EQUAL)) {
					return;
				}

			case ST_CONCAT:
			case ST_DIVIDE:
			case ST_MINUS:
			case ST_PLUS:
			case ST_TIMES:
			case T_BOOLEAN_AND:
			case T_BOOLEAN_OR:
			case T_CONSTANT_ENCAPSED_STRING:
			case T_DNUMBER:
			case T_IS_EQUAL:
			case T_IS_GREATER_OR_EQUAL:
			case T_IS_IDENTICAL:
			case T_IS_NOT_EQUAL:
			case T_IS_NOT_IDENTICAL:
			case T_IS_SMALLER_OR_EQUAL:
			case T_IS_SMALLER_OR_EQUAL:
			case T_LNUMBER:
			case T_LOGICAL_AND:
			case T_LOGICAL_OR:
			case T_LOGICAL_XOR:
			case T_POW:
			case T_SPACESHIP:
			case T_VARIABLE:
				break;

			case T_WHITESPACE:
				if (
					$this->hasLn($text)
					&&
					!
					(
						$this->rightUsefulTokenIs([ST_SEMI_COLON])
						||
						$this->leftUsefulTokenIs([
							ST_BRACKET_OPEN,
							ST_COLON,
							ST_CURLY_CLOSE,
							ST_CURLY_OPEN,
							ST_PARENTHESES_OPEN,
							ST_SEMI_COLON,
							T_END_HEREDOC,
							T_OBJECT_OPERATOR,
							T_OPEN_TAG,
						])
						||
						$this->leftTokenIs([
							T_COMMENT,
							T_DOC_COMMENT,
						])
					)
				) {
					$text .= $this->indentChar;
					$this->tkns[$index] = [$id, $text];
				}
				break;

			default:
				return;
			}
		}
	}
}

	
final class ReindentObjOps extends FormatterPass {
	const ALIGN_WITH_INDENT = 1;

	public function candidate($source, $foundTokens) {
		if (
			isset($foundTokens[T_OBJECT_OPERATOR]) ||
			isset($foundTokens[T_DOUBLE_COLON])
		) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		$levelCounter = 0;
		$levelEntranceCounter = [];
		$contextCounter = [];
		$touchCounter = [];
		$alignType = [];
		$printedPlaceholder = [];
		$maxContextCounter = [];
		$touchedParenOpen = false;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_QUOTE:
				$this->appendCode($text);
				$this->printUntilTheEndOfString();
				break;
			case T_CLOSE_TAG:
				$this->appendCode($text);
				$this->printUntil(T_OPEN_TAG);
				break;
			case T_START_HEREDOC:
				$this->appendCode($text);
				$this->printUntil(T_END_HEREDOC);
				break;

			case T_WHILE:
			case T_IF:
			case T_FOR:
			case T_FOREACH:
			case T_SWITCH:
				$this->appendCode($text);
				$this->printUntil(ST_PARENTHESES_OPEN);
				$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				break;

			case T_NEW:
				$this->appendCode($text);
				if ($touchedParenOpen) {
					$touchedParenOpen = false;
					$foundToken = $this->printUntilAny([ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE, ST_COMMA]);
					if (ST_PARENTHESES_OPEN == $foundToken) {
						$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
						$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
						$this->printUntilAny([ST_PARENTHESES_CLOSE, ST_COMMA]);
					}
				}
				break;

			case T_FUNCTION:
				$this->appendCode($text);
				break;

			case T_VARIABLE:
			case T_STRING:
				$this->appendCode($text);
				if (!isset($levelEntranceCounter[$levelCounter])) {
					$levelEntranceCounter[$levelCounter] = 0;
				}
				if (!isset($contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]])) {
					$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$alignType[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$printedPlaceholder[$levelCounter][$levelEntranceCounter[$levelCounter]][$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]] = 0;
				}
				break;

			case ST_PARENTHESES_OPEN:
			case ST_BRACKET_OPEN:
				$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
				$this->appendCode($text);
				break;

			case ST_PARENTHESES_CLOSE:
			case ST_BRACKET_CLOSE:
				--$levelCounter;
				$this->appendCode($text);
				break;

			case T_DOUBLE_COLON:
			case T_OBJECT_OPERATOR:
				if (!isset($touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]) || 0 == $touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]) {
					if (!isset($touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]])) {
						$touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					}
					++$touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]];
					if ($this->hasLnBefore()) {
						$alignType[$levelCounter][$levelEntranceCounter[$levelCounter]] = self::ALIGN_WITH_INDENT;
						$this->appendCode($this->getIndent(+1) . $text);
						$foundToken = $this->printUntilAny([ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE, ST_SEMI_COLON, $this->newLine]);
						if (ST_SEMI_COLON == $foundToken) {
							$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
						} elseif (ST_PARENTHESES_OPEN == $foundToken || ST_PARENTHESES_CLOSE == $foundToken) {
							$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
							$this->indentParenthesesContent();
						}
						break;
					}
				} elseif ($this->hasLnBefore() || $this->hasLnLeftToken()) {
					++$touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]];
					$this->appendCode($this->getIndent(+1) . $text);
					$foundToken = $this->printUntilAny([ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE, ST_SEMI_COLON, $this->newLine]);
					if (ST_SEMI_COLON == $foundToken) {
						$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
					} elseif (ST_PARENTHESES_OPEN == $foundToken || ST_PARENTHESES_CLOSE == $foundToken) {
						$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
						$this->indentParenthesesContent();
					}
					break;
				}
				$this->appendCode($text);
				break;

			case T_COMMENT:
			case T_DOC_COMMENT:
				if (
					isset($alignType[$levelCounter]) &&
					isset($levelEntranceCounter[$levelCounter]) &&
					isset($alignType[$levelCounter][$levelEntranceCounter[$levelCounter]]) &&
					($this->hasLnBefore() || $this->hasLnLeftToken()) &&
					self::ALIGN_WITH_INDENT == $alignType[$levelCounter][$levelEntranceCounter[$levelCounter]]
				) {
					$this->appendCode($this->getIndent(+1));
				}
				$this->appendCode($text);
				if ($this->leftUsefulTokenIs([T_OBJECT_OPERATOR, T_DOUBLE_COLON]) && $this->hasLn($text)) {
					$this->appendCode($this->getIndent(+1));
				}
				break;

			case ST_COMMA:
			case ST_SEMI_COLON:
				if (!isset($levelEntranceCounter[$levelCounter])) {
					$levelEntranceCounter[$levelCounter] = 0;
				}
				++$levelEntranceCounter[$levelCounter];
				$this->appendCode($text);
				break;

			case T_WHITESPACE:
				$this->appendCode($text);
				if ($this->leftUsefulTokenIs([T_OBJECT_OPERATOR, T_DOUBLE_COLON]) && $this->hasLn($text)) {
					$this->appendCode($this->getIndent(+1));
				}
				break;

			default:
				$touchedParenOpen = false;
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}

	protected function incrementCounters(
		&$levelCounter,
		&$levelEntranceCounter,
		&$contextCounter,
		&$maxContextCounter,
		&$touchCounter,
		&$alignType,
		&$printedPlaceholder
	) {
		++$levelCounter;
		if (!isset($levelEntranceCounter[$levelCounter])) {
			$levelEntranceCounter[$levelCounter] = 0;
		}
		++$levelEntranceCounter[$levelCounter];
		if (!isset($contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]])) {
			$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
			$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
			$touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
			$alignType[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
			$printedPlaceholder[$levelCounter][$levelEntranceCounter[$levelCounter]][$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]] = 0;
		}
		++$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]];
		$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = max($maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]], $contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]);
	}

	protected function indentParenthesesContent() {
		$count = 0;
		$sizeofTokens = sizeof($this->tkns);
		for ($i = $this->ptr; $i < $sizeofTokens; ++$i) {
			$token = &$this->tkns[$i];
			list($id, $text) = $this->getToken($token);
			if (
				(T_WHITESPACE == $id || T_DOC_COMMENT == $id || T_COMMENT == $id)
				&& $this->hasLn($text)
			) {
				$token[1] = $text . $this->getIndent(+1);
				continue;
			}
			if (ST_PARENTHESES_OPEN == $id) {
				++$count;
			}
			if (ST_PARENTHESES_CLOSE == $id) {
				--$count;
			}
			if (0 == $count) {
				break;
			}
		}
	}

	protected function injectPlaceholderParenthesesContent($placeholder) {
		$count = 0;
		$sizeofTokens = sizeof($this->tkns);
		for ($i = $this->ptr; $i < $sizeofTokens; ++$i) {
			$token = &$this->tkns[$i];
			list($id, $text) = $this->getToken($token);
			if ((T_WHITESPACE == $id || T_DOC_COMMENT == $id || T_COMMENT == $id)
				&& $this->hasLn($text)) {
				$token[1] = str_replace($this->newLine, $this->newLine . $placeholder, $text);
				continue;
			}
			if (ST_PARENTHESES_OPEN == $id) {
				++$count;
			}
			if (ST_PARENTHESES_CLOSE == $id) {
				--$count;
			}
			if (0 == $count) {
				break;
			}
		}
	}

	private function hasLnInBlock($tkns, $ptr, $start, $end) {
		$sizeOfTkns = sizeof($tkns);
		$count = 0;
		for ($i = $ptr; $i < $sizeOfTkns; ++$i) {
			$token = $tkns[$i];
			list($id, $text) = $this->getToken($token);
			if ($start == $id) {
				++$count;
			}
			if ($end == $id) {
				--$count;
			}
			if (0 == $count) {
				break;
			}
			if ($this->hasLn($text)) {
				return true;
			}
		}
		return false;
	}
}

	
final class ResizeSpaces extends FormatterPass {
	public function candidate($source, $foundTokens) {
		$tkns = token_get_all($source);

		$this->tkns = [];
		foreach ($tkns as $token) {
			if (T_WHITESPACE === $token[0] && !$this->hasLn($token[1])) {
				continue;
			}
			$this->tkns[] = $token;
		}

		return true;
	}

	public function format($source) {
		$this->code = '';
		$this->useCache = true;

		$inTernaryOperator = 0;
		$shortTernaryOperator = false;
		$touchedFunction = false;
		$touchedUse = false;
		$touchedGroupedUse = false;
		$hasEchoAfterOpenTag = false;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->memoPtr();
			$this->cache = [];

			switch ($id) {
			case T_START_HEREDOC:
				$this->appendCode($text);
				$this->printUntil(ST_SEMI_COLON);
				break;

			case T_CALLABLE:
				$this->appendCode($text . ' ');
				break;

			case '+':
			case '-':
				if (
					$this->leftMemoUsefulTokenIs([T_INC, T_DEC, T_LNUMBER, T_DNUMBER, T_VARIABLE, ST_PARENTHESES_CLOSE, T_STRING, T_ARRAY, T_ARRAY_CAST, T_BOOL_CAST, T_DOUBLE_CAST, T_INT_CAST, T_OBJECT_CAST, T_STRING_CAST, T_UNSET_CAST, ST_BRACKET_CLOSE])
					&&
					$this->rightUsefulTokenIs([T_INC, T_DEC, T_LNUMBER, T_DNUMBER, T_VARIABLE, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE, T_STRING, T_ARRAY, T_ARRAY_CAST, T_BOOL_CAST, T_DOUBLE_CAST, T_INT_CAST, T_OBJECT_CAST, T_STRING_CAST, T_UNSET_CAST, ST_BRACKET_CLOSE, ST_BRACKET_OPEN])
				) {
					$this->appendCode(' ' . $text . ' ');
					break;
				}
				$this->appendCode($text);
				break;

			case '*':
				$this->rtrimLnAndappendCode(' ' . $text);
				$this->appendCode(' ');
				break;

			case '%':
			case '/':
			case T_POW:
			case ST_QUESTION:
			case ST_CONCAT:
				if (ST_QUESTION == $id) {
					++$inTernaryOperator;
					$shortTernaryOperator = $this->rightTokenIs(ST_COLON);
				}

				list($prevId) = $this->inspectToken(-1);
				list($nextId) = $this->inspectToken(+1);
				if (
					T_WHITESPACE === $prevId &&
					T_WHITESPACE !== $nextId
				) {
					$this->appendCode($text . $this->getSpace(!$this->rightTokenIs(ST_COLON)));
					break;
				} elseif (
					T_WHITESPACE !== $prevId &&
					T_WHITESPACE === $nextId
				) {
					$this->appendCode(' ' . $text);
					break;
				} elseif (
					T_WHITESPACE !== $prevId &&
					T_WHITESPACE !== $nextId
				) {
					$this->appendCode(' ' . $text . $this->getSpace(!$this->rightTokenIs(ST_COLON)));
					break;
				}
				$this->appendCode($text);
				break;

			case ST_COLON:
				list($prevId) = $this->inspectToken(-1);
				list($nextId, $nextText) = $this->inspectToken(+1);

				if (
					$inTernaryOperator > 0 &&
					T_WHITESPACE === $prevId &&
					T_WHITESPACE !== $nextId
				) {
					$this->appendCode($text . ' ');
					--$inTernaryOperator;
					break;
				} elseif (
					$inTernaryOperator > 0 &&
					T_WHITESPACE !== $prevId &&
					T_WHITESPACE === $nextId
				) {
					$this->appendCode($this->getSpace(!$shortTernaryOperator) . $text);
					--$inTernaryOperator;
					break;
				} elseif (
					$inTernaryOperator > 0 &&
					T_WHITESPACE !== $prevId &&
					T_WHITESPACE !== $nextId
				) {
					$this->appendCode($this->getSpace(!$shortTernaryOperator) . $text . ' ');
					--$inTernaryOperator;
					break;
				} elseif (0 == $inTernaryOperator && $this->leftMemoUsefulTokenIs(ST_PARENTHESES_CLOSE)) {
					$this->appendCode($text . ' ');
					break;
				} elseif (
					(
						T_WHITESPACE != $nextId
						||
						(T_WHITESPACE == $nextId && !$this->hasLn($nextText))
					)
					&& $this->rightUsefulTokenIs(T_CLOSE_TAG)
				) {
					$this->appendCode($text . ' ');
					break;
				} elseif (T_COMMENT == $nextId) {
					$this->appendCode($text . ' ');
					break;
				}
				$this->appendCode($text);
				break;

			case T_PRINT:
				$this->appendCode($text . $this->getSpace(!$this->rightTokenIs([ST_PARENTHESES_OPEN])));
				break;

			case T_VARIABLE:
				if ($this->leftMemoUsefulTokenIs([T_ARRAY, T_STRING])) {
					$this->appendCode(' ');
				}
				$this->appendCode($text);
				break;

			case ST_CURLY_OPEN:
				$touchedFunction = false;
				if (!$touchedUse && $this->leftMemoUsefulTokenIs([T_VARIABLE, T_STRING]) && $this->rightUsefulTokenIs([T_VARIABLE, T_STRING])) {
					$this->appendCode($text);
					break;
				} elseif ($this->leftMemoUsefulTokenIs([T_STRING, T_DO, T_FINALLY, ST_PARENTHESES_CLOSE]) && !$this->hasLnLeftToken()) {
					$this->rtrimAndAppendCode(
						' ' .
						$text .
						$this->getSpace($this->rightTokenIs(T_COMMENT))
					);
					break;
				} elseif ($this->rightTokenIs(ST_CURLY_CLOSE) || ($this->leftMemoTokenIs([T_OBJECT_OPERATOR, ST_DOLLAR]) && $this->rightTokenIs([T_VARIABLE]))) {
					$this->appendCode($text);
					break;
				} elseif (!$this->leftMemoTokenIs(T_DOUBLE_COLON) && $this->rightTokenIs([T_VARIABLE, T_INC, T_DEC, T_COMMENT])) {
					$this->appendCode($text . ' ');
					break;
				} elseif ($this->leftMemoUsefulTokenIs(T_NS_SEPARATOR)) {
					$touchedGroupedUse = true;
					$this->appendCode($text . ' ');
					break;
				}
				$this->appendCode($text);
				break;

			case ST_SEMI_COLON:
				$touchedUse = false;
				if ($this->rightTokenIs([T_VARIABLE, T_INC, T_DEC, T_LNUMBER, T_DNUMBER, T_COMMENT, T_DOC_COMMENT, T_STRING, ST_PARENTHESES_OPEN])) {
					$this->appendCode($text . ' ');
					break;
				}
				$this->appendCode($text);
				$this->appendCode($this->getSpace($this->rightTokenIs(T_COMMENT) && !$this->hasLnAfter()));
				break;

			case ST_PARENTHESES_OPEN:
				if ($this->leftMemoUsefulTokenIs([T_WHILE, T_CATCH]) && !$this->hasLnLeftToken()) {
					$this->rtrimAndAppendCode(' ');
				}
				$this->appendCode($text);
				$this->appendCode($this->getSpace($this->rightTokenIs(T_COMMENT) && !$this->hasLnAfter()));
				break;

			case ST_PARENTHESES_CLOSE:
				$this->appendCode($text . $this->getSpace($this->rightTokenIs([T_COMMENT, T_DOC_COMMENT])));
				break;

			case T_USE:
				$touchedUse = true;
				$this->appendCode($this->getSpace($this->leftMemoTokenIs(ST_PARENTHESES_CLOSE)) . $text . ' ');
				break;

			case T_NAMESPACE:
				$this->appendCode($text . $this->getSpace(!$this->rightTokenIs([ST_SEMI_COLON, T_NS_SEPARATOR, T_DOUBLE_COLON])));
				break;

			case T_ECHO:
				if ($this->leftMemoUsefulTokenIs(T_OPEN_TAG)) {
					$hasEchoAfterOpenTag = false;
				}
				$this->appendCode($text . $this->getSpace(!$this->rightTokenIs(ST_SEMI_COLON)));
				break;
			case T_RETURN:
			case T_YIELD:
			case T_VAR:
			case T_NEW:
			case T_CONST:
			case T_FINAL:
			case T_CASE:
			case T_BREAK:
				$this->appendCode($text . $this->getSpace(!$this->rightTokenIs(ST_SEMI_COLON)));
				break;

			case T_WHILE:
				if ($this->leftMemoTokenIs(ST_CURLY_CLOSE) && !$this->hasLnBefore()) {
					$this->appendCode(' ' . $text . ' ');
					break;
				}
			case T_DOUBLE_ARROW:
				if (T_DOUBLE_ARROW == $id && $this->leftMemoTokenIs([T_CONSTANT_ENCAPSED_STRING, T_STRING, T_VARIABLE, T_LNUMBER, T_DNUMBER, ST_PARENTHESES_CLOSE, ST_BRACKET_CLOSE, ST_CURLY_CLOSE, ST_QUOTE])) {
					$this->rtrimAndAppendCode(' ' . $text . ' ');
					break;
				}
			case T_STATIC:
				$this->appendCode($text . $this->getSpace(!$this->rightTokenIs([ST_SEMI_COLON, T_DOUBLE_COLON, ST_PARENTHESES_OPEN])));
				break;

			case T_FUNCTION:
				$touchedFunction = true;
				$this->appendCode($text . $this->getSpace(!$this->rightTokenIs(ST_SEMI_COLON)));
				break;

			case T_PUBLIC:
			case T_PRIVATE:
			case T_PROTECTED:
			case T_TRAIT:
			case T_INTERFACE:
			case T_THROW:
			case T_GLOBAL:
			case T_ABSTRACT:
			case T_INCLUDE:
			case T_REQUIRE:
			case T_INCLUDE_ONCE:
			case T_REQUIRE_ONCE:
			case T_DECLARE:
			case T_IF:
			case T_FOR:
			case T_FOREACH:
			case T_SWITCH:
			case T_TRY:
			case ST_COMMA:
				$this->appendCode($text . ' ');
				break;

			case T_CLONE:
			case T_CONTINUE:
				$this->appendCode($text . $this->getSpace(!$this->rightTokenIs(ST_SEMI_COLON)));
				break;

			case T_CLASS:
				$this->appendCode(
					$text .
					$this->getSpace(
						!($this->leftMemoUsefulTokenIs(T_NEW) && $this->rightTokenIs([ST_PARENTHESES_OPEN, T_EXTENDS, T_IMPLEMENTS])) &&
						!$this->leftMemoTokenIs(T_DOUBLE_COLON) &&
						!$this->rightTokenIs(ST_SEMI_COLON) ||
						$this->rightTokenIs(T_DOUBLE_ARROW)
					)
				);
				break;

			case T_EXTENDS:
			case T_IMPLEMENTS:
			case T_INSTANCEOF:
			case T_INSTEADOF:
			case T_AS:
			case T_COALESCE:
				$this->rtrimAndAppendCode(' ' . $text . ' ');
				break;

			case T_LOGICAL_AND:
			case T_LOGICAL_OR:
			case T_LOGICAL_XOR:
			case T_AND_EQUAL:
			case T_BOOLEAN_AND:
			case T_BOOLEAN_OR:
			case T_CONCAT_EQUAL:
			case T_DIV_EQUAL:
			case T_IS_EQUAL:
			case T_IS_GREATER_OR_EQUAL:
			case T_IS_IDENTICAL:
			case T_IS_NOT_EQUAL:
			case T_IS_NOT_IDENTICAL:
			case T_IS_SMALLER_OR_EQUAL:
			case T_SPACESHIP:
			case T_MINUS_EQUAL:
			case T_MOD_EQUAL:
			case T_MUL_EQUAL:
			case T_OR_EQUAL:
			case T_PLUS_EQUAL:
			case T_SL:
			case T_SL_EQUAL:
			case T_SR:
			case T_SR_EQUAL:
			case T_XOR_EQUAL:
			case ST_IS_GREATER:
			case ST_IS_SMALLER:
			case ST_EQUAL:
				$this->appendCode($this->getSpace(!$this->hasLnBefore()) . $text . ' ');
				break;

			case T_CATCH:
			case T_FINALLY:
				if ($this->hasLnLeftToken()) {
					$this->appendCode(' ' . $text . ' ');
					break;
				}
				$this->rtrimAndAppendCode(' ' . $text . ' ');
				break;

			case T_ELSEIF:
				if (!$this->leftMemoTokenIs(ST_CURLY_CLOSE)) {
					$this->appendCode($text . ' ');
					break;
				}
				$this->appendCode(' ' . $text . ' ');
				break;

			case T_ELSE:
				if (!$this->leftMemoUsefulTokenIs(ST_CURLY_CLOSE)) {
					$this->appendCode($text);
					break;
				}
				$this->appendCode($this->getSpace(!$this->leftMemoTokenIs([T_COMMENT, T_DOC_COMMENT])) . $text . ' ');
				break;

			case T_ARRAY_CAST:
			case T_BOOL_CAST:
			case T_DOUBLE_CAST:
			case T_INT_CAST:
			case T_OBJECT_CAST:
			case T_STRING_CAST:
			case T_UNSET_CAST:
			case T_GOTO:
				$this->appendCode(str_replace([' ', "\t"], '', $text) . ' ');
				break;

			case ST_REFERENCE:
				$spaceBefore = !$this->leftMemoUsefulTokenIs([ST_EQUAL, ST_PARENTHESES_OPEN, T_AS, T_DOUBLE_ARROW, ST_COMMA]) && !$this->leftMemoUsefulTokenIs([T_FUNCTION]);
				$spaceAfter = !$touchedFunction && !$this->leftMemoUsefulTokenIs([ST_EQUAL, ST_PARENTHESES_OPEN, T_AS, T_DOUBLE_ARROW, ST_COMMA]);
				$this->appendCode($this->getSpace($spaceBefore) . $text . $this->getSpace($spaceAfter));
				break;

			case ST_BITWISE_OR:
			case ST_BITWISE_XOR:
				$this->appendCode(' ' . $text . ' ');
				break;

			case T_COMMENT:
				if (substr($text, 0, 2) === '//') {
					list($leftId) = $this->inspectToken(-1);
					$this->appendCode($this->getSpace(T_VARIABLE == $leftId || ST_BRACKET_OPEN == $leftId) . $text);
					break;
				} elseif (!$this->hasLn($text) && !$this->hasLnBefore() && !$this->hasLnAfter() && $this->leftUsefulTokenIs(ST_COMMA) && $this->rightUsefulTokenIs(T_VARIABLE)) {
					$this->appendCode($text . ' ');
					break;
				}
				$this->appendCode($text);
				break;

			case ST_CURLY_CLOSE:
				if ($touchedGroupedUse) {
					$touchedGroupedUse = false;
					$this->appendCode($this->getSpace(!$this->hasLnBefore()));
				}
				$this->appendCode($text);
				$this->appendCode($this->getSpace($this->rightTokenIs(T_COMMENT) && !$this->hasLnAfter()));
				break;

			case T_CONSTANT_ENCAPSED_STRING:
				$this->appendCode($text);
				$this->appendCode($this->getSpace($this->rightTokenIs(T_COMMENT) && !$this->hasLnAfter()));
				break;

			case T_CLOSE_TAG:
				$this->appendCode($this->getSpace(!$hasEchoAfterOpenTag && !$this->hasLnBefore()));
				$this->appendCode($text);
				$hasEchoAfterOpenTag = false;
				break;

			case T_OPEN_TAG_WITH_ECHO:
				$hasEchoAfterOpenTag = true;
				$this->appendCode($text);
				break;

			case T_OPEN_TAG:
				$hasEchoAfterOpenTag = true;
				$this->appendCode($text);
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}
}

	
final class RTrim extends FormatterPass {
	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		return preg_replace('/\h+$/mu', '', $source);
	}
}
	
final class SettersAndGettersPass extends FormatterPass {
	const PLACEHOLDER = "/*SETTERSANDGETTERSPLACEHOLDER%s\x3*/";

	const PLACEHOLDER_REGEX = '/(;\n\/\*SETTERSANDGETTERSPLACEHOLDER).*(\*\/)/';

	const TYPE_CAMEL_CASE = 'camel';

	const TYPE_GOLANG = 'golang';

	const TYPE_SNAKE_CASE = 'snake';

	
	private $type;

	public function __construct($type = self::TYPE_CAMEL_CASE) {
		$this->type = self::TYPE_CAMEL_CASE;
		if (self::TYPE_CAMEL_CASE == $type || self::TYPE_SNAKE_CASE == $type || self::TYPE_GOLANG == $type) {
			$this->type = $type;
		}
	}

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_CLASS])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_CLASS:
				$attributes = [
					'private' => [],
					'public' => [],
					'protected' => [],
				];
				$functionList = [];
				$touchedVisibility = false;
				$touchedFunction = false;
				$curlyCount = null;
				$this->appendCode($text);
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					if (ST_CURLY_OPEN == $id) {
						++$curlyCount;
					}
					if (ST_CURLY_CLOSE == $id) {
						--$curlyCount;
					}
					if (0 === $curlyCount) {
						break;
					}
					$this->appendCode($text);
					if (T_PUBLIC == $id) {
						$touchedVisibility = T_PUBLIC;
					} elseif (T_PRIVATE == $id) {
						$touchedVisibility = T_PRIVATE;
					} elseif (T_PROTECTED == $id) {
						$touchedVisibility = T_PROTECTED;
					}
					if (T_VARIABLE == $id && T_PUBLIC == $touchedVisibility) {
						$attributes['public'][] = $text;
						$touchedVisibility = null;
						$this->printPlaceholder($text);
					} elseif (T_VARIABLE == $id && T_PRIVATE == $touchedVisibility) {
						$attributes['private'][] = $text;
						$touchedVisibility = null;
						$this->printPlaceholder($text);
					} elseif (T_VARIABLE == $id && T_PROTECTED == $touchedVisibility) {
						$attributes['protected'][] = $text;
						$touchedVisibility = null;
						$this->printPlaceholder($text);
					} elseif (T_FUNCTION == $id) {
						$touchedFunction = true;
					} elseif ($touchedFunction && T_STRING == $id) {
						$functionList[] = $text;
						$touchedVisibility = null;
						$touchedFunction = false;
					}
				}
				$functionList = array_combine($functionList, $functionList);
				$append = false;
				foreach ($attributes as $visibility => $variables) {
					foreach ($variables as $var) {
						$str = $this->generate($visibility, $var);
						foreach ($functionList as $k => $v) {
							if (false !== stripos($str, $v)) {
								unset($functionList[$k]);
								$append = true;
								continue 2;
							}
						}
						if ($append) {
							$this->appendCode($str);
							continue;
						}
						$this->code = str_replace(sprintf(self::PLACEHOLDER, $var), $str, $this->code);
					}
				}

				$this->appendCode($text);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}
		$this->code = preg_replace(self::PLACEHOLDER_REGEX, ';', $this->code);
		return $this->code;
	}

	private function generate($visibility, $var) {
		switch ($this->type) {
		case self::TYPE_SNAKE_CASE:
			$ret = $this->generateSnakeCase($visibility, $var);
			break;
		case self::TYPE_GOLANG:
			$ret = $this->generateGolang($visibility, $var);
			break;
		case self::TYPE_CAMEL_CASE:
		default:
			$ret = $this->generateCamelCase($visibility, $var);
			break;
		}
		return $ret;
	}

	private function generateCamelCase($visibility, $var) {
		$str = $this->newLine . $visibility . ' function set' . ucfirst(str_replace('$', '', $var)) . '(' . $var . '){' . $this->newLine . '$this->' . str_replace('$', '', $var) . ' = ' . $var . ';' . $this->newLine . '}' . $this->newLine . $this->newLine;
		$str .= $visibility . ' function get' . ucfirst(str_replace('$', '', $var)) . '(){' . $this->newLine . 'return $this->' . str_replace('$', '', $var) . ';' . $this->newLine . '}' . $this->newLine;
		return $str;
	}

	private function generateGolang($visibility, $var) {
		$str = $this->newLine . $visibility . ' function Set' . ucfirst(str_replace('$', '', $var)) . '(' . $var . '){' . $this->newLine . '$this->' . str_replace('$', '', $var) . ' = ' . $var . ';' . $this->newLine . '}' . $this->newLine . $this->newLine;
		$str .= $visibility . ' function ' . ucfirst(str_replace('$', '', $var)) . '(){' . $this->newLine . 'return $this->' . str_replace('$', '', $var) . ';' . $this->newLine . '}' . $this->newLine;
		return $str;
	}

	private function generateSnakeCase($visibility, $var) {
		$str = $this->newLine . $visibility . ' function set_' . (str_replace('$', '', $var)) . '(' . $var . '){' . $this->newLine . '$this->' . str_replace('$', '', $var) . ' = ' . $var . ';' . $this->newLine . '}' . $this->newLine . $this->newLine;
		$str .= $visibility . ' function get_' . (str_replace('$', '', $var)) . '(){' . $this->newLine . 'return $this->' . str_replace('$', '', $var) . ';' . $this->newLine . '}' . $this->newLine;
		return $str;
	}

	private function printPlaceholder($text) {
		$this->skipPlaceholderUntilSemicolon();

		$this->appendCode(';' . $this->newLine . sprintf(self::PLACEHOLDER, $text));
	}

	private function skipPlaceholderUntilSemicolon() {
		if ($this->rightUsefulTokenIs(ST_EQUAL)) {
			return $this->printAndStopAt(ST_SEMI_COLON);
		}
		each($this->tkns);
	}
}
	
class SplitCurlyCloseAndTokens extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (!isset($foundTokens[ST_CURLY_CLOSE])) {
			return false;
		}

		$this->tkns = token_get_all($source);
		while (list($index, $token) = each($this->tkns)) {
			list($id) = $this->getToken($token);
			$this->ptr = $index;

			if (ST_CURLY_CLOSE == $id && !$this->hasLnAfter()) {
				return true;
			}
		}

		return false;
	}

	public function format($source) {
		reset($this->tkns);
		$sizeofTkns = sizeof($this->tkns);

		$this->code = '';
		$blockStack = [];
		$touchedBlock = null;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			switch ($id) {
			case T_DO:
			case T_ELSE:
			case T_ELSEIF:
			case T_FOR:
			case T_FOREACH:
			case T_FUNCTION:
			case T_IF:
			case T_SWITCH:
			case T_WHILE:
			case T_TRY:
			case T_CATCH:
				$touchedBlock = $id;
				$this->appendCode($text);
				break;

			case ST_SEMI_COLON:
			case ST_COLON:
				$touchedBlock = null;
				$this->appendCode($text);
				break;

			case T_CURLY_OPEN:
			case T_DOLLAR_OPEN_CURLY_BRACES:
				$this->appendCode($text);
				$this->printCurlyBlock();
				break;

			case ST_BRACKET_OPEN:
				$this->appendCode($text);
				$this->printBlock(ST_BRACKET_OPEN, ST_BRACKET_CLOSE);
				break;

			case ST_PARENTHESES_OPEN:
				$this->appendCode($text);
				$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				break;

			case ST_CURLY_OPEN:
				$this->appendCode($text);
				if (null !== $touchedBlock) {
					$blockStack[] = $touchedBlock;
					$touchedBlock = null;
					break;
				}
				$this->printCurlyBlock();
				break;

			case ST_CURLY_CLOSE:
				$this->appendCode($text);
				$poppedBlock = array_pop($blockStack);
				if (
					($this->ptr + 1) < $sizeofTkns &&
					(
						T_ELSE == $poppedBlock ||
						T_ELSEIF == $poppedBlock ||
						T_FOR == $poppedBlock ||
						T_FOREACH == $poppedBlock ||
						T_IF == $poppedBlock ||
						T_WHILE == $poppedBlock
					) &&
					!$this->hasLnAfter() &&
					!$this->rightTokenIs([
						ST_BRACKET_OPEN,
						ST_CURLY_CLOSE,
						ST_PARENTHESES_CLOSE,
						ST_PARENTHESES_OPEN,
						T_COMMENT,
						T_DOC_COMMENT,
						T_ELSE,
						T_ELSEIF,
						T_IF,
						T_OBJECT_OPERATOR,
						T_CLOSE_TAG,
					])
				) {
					$this->appendCode($this->newLine);
				}
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}
}

	
final class StripExtraCommaInList extends FormatterPass {
	const EMPTY_LIST = 'ST_EMPTY_LIST';

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_LIST])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);

		$contextStack = [];
		$touchedListArrayString = false;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_STRING:
			case T_ARRAY:
			case T_LIST:
				$touchedListArrayString = true;
				if ($this->rightTokenIs(ST_PARENTHESES_OPEN)) {
					$contextStack[] = $id;
				}
				break;

			case ST_PARENTHESES_OPEN:
				if (isset($contextStack[0]) && T_LIST == end($contextStack) && $this->rightTokenIs(ST_PARENTHESES_CLOSE)) {
					$contextStack[sizeof($contextStack) - 1] = self::EMPTY_LIST;
				} elseif (!$touchedListArrayString) {
					$contextStack[] = ST_PARENTHESES_OPEN;
				}
				break;

			case ST_PARENTHESES_CLOSE:
				if (isset($contextStack[0])) {
					if (T_LIST == end($contextStack) && $this->leftUsefulTokenIs(ST_COMMA)) {
						$prevTokenIdx = $this->leftUsefulTokenIdx();
						$this->tkns[$prevTokenIdx] = null;
					}
					array_pop($contextStack);
				}
				break;

			default:
				$touchedListArrayString = false;
				break;
			}
			$this->tkns[$this->ptr] = [$id, $text];
		}
		return $this->renderLight();
	}
}
	
final class SurrogateToken {
}

	
final class TwoCommandsInSameLine extends FormatterPass {
	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$touchedSemicolon = true;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			switch ($id) {
			case ST_SEMI_COLON:
				if ($this->leftTokenIs(ST_SEMI_COLON)) {
					$touchedSemicolon = false;
					break;
				}
				$touchedSemicolon = true;
				$this->appendCode($text);
				break;

			case T_VARIABLE:
			case T_STRING:
			case T_CONTINUE:
			case T_BREAK:
			case T_ECHO:
			case T_PRINT:
				if ($touchedSemicolon && !$this->hasLnBefore()) {
					$touchedSemicolon = false;
					$this->appendCode($this->newLine);
				}
				$this->appendCode($text);
				break;

			case ST_PARENTHESES_OPEN:
				$this->appendCode($text);
				$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				break;

			case T_WHITESPACE:
				if ($this->hasLn($text)) {
					$touchedSemicolon = false;
				}
				$this->appendCode($text);
				break;

			default:
				$touchedSemicolon = false;
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}
}


	
final class PSR1BOMMark extends FormatterPass {
	const BOM = "\xef\xbb\xbf";

	public function candidate($source, $foundTokens) {
		return substr($source, 0, 3) === self::BOM;
	}

	public function format($source) {
		return substr($source, 3);
	}
}

	
final class PSR1ClassConstants extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_CONST]) || isset($foundTokens[T_STRING])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$ucConst = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_CONST:
				$ucConst = true;
				$this->appendCode($text);
				break;
			case T_STRING:
				if ($ucConst) {
					$text = strtoupper($text);
					$ucConst = false;
				}
				$this->appendCode($text);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}
	
final class PSR1ClassNames extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_CLASS]) || isset($foundTokens[T_STRING])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$foundClass = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_CLASS:
				if (!$this->leftUsefulTokenIs(T_DOUBLE_COLON)) {
					$foundClass = true;
				}

				$this->appendCode($text);
				break;
			case T_STRING:
				if ($foundClass) {
					$count = 0;
					$tmp = ucwords(str_replace(['-', '_'], ' ', strtolower($text), $count));
					if ($count > 0) {
						$text = str_replace(' ', '', $tmp);
					}
					$this->appendCode($text);

					$foundClass = false;
					break;
				}
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}

	
final class PSR1MethodNames extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_FUNCTION]) || isset($foundTokens[T_STRING]) || isset($foundTokens[ST_PARENTHESES_OPEN])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$foundMethod = false;
		$methodReplaceList = [];
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_FUNCTION:
				$foundMethod = true;
				$this->appendCode($text);
				break;
			case T_STRING:
				if ($foundMethod) {
					$count = 0;
					$origText = $text;
					$tmp = ucwords(str_replace(['-', '_'], ' ', strtolower($text), $count));
					if ($count > 0 && '' !== trim($tmp) && '_' !== substr($text, 0, 1)) {
						$text = lcfirst(str_replace(' ', '', $tmp));
					}

					$methodReplaceList[$origText] = $text;
					$this->appendCode($text);

					$foundMethod = false;
					break;
				}
			case ST_PARENTHESES_OPEN:
				$foundMethod = false;
			default:
				$this->appendCode($text);
				break;
			}
		}

		$this->tkns = token_get_all($this->code);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_STRING:
				if (isset($methodReplaceList[$text]) && $this->rightUsefulTokenIs(ST_PARENTHESES_OPEN)) {
					$this->appendCode($methodReplaceList[$text]);
					break;
				}

			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}

	
final class PSR1OpenTags extends FormatterPass {
	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$touchedComment = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_OPEN_TAG:
				if ('<?php' !== $text) {
					$this->appendCode('<?php' . ($this->hasLnAfter() || $this->hasLn($text) || $this->rightUsefulTokenIs(T_NAMESPACE) ? $this->newLine : $this->getSpace()));
					break;
				}
				$this->appendCode($text);
				break;

			case T_CLOSE_TAG:
				if (!$touchedComment && !$this->leftUsefulTokenIs([ST_SEMI_COLON, ST_COLON, ST_CURLY_CLOSE, ST_CURLY_OPEN])) {
					$this->appendCode(ST_SEMI_COLON);
				}
				$touchedComment = false;
				$this->appendCode($text);
				break;

			case T_COMMENT:
			case T_DOC_COMMENT:
				if (
					$this->rightUsefulTokenIs([T_CLOSE_TAG]) &&
					!$this->leftUsefulTokenIs([ST_SEMI_COLON]) &&
					!$this->leftUsefulTokenIs([T_OPEN_TAG])
				) {
					$touchedComment = true;
					$this->rtrimAndappendCode(ST_SEMI_COLON . ' ');
				}
				$this->appendCode($text);
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}

	
final class PSR2AlignObjOp extends FormatterPass {
	const ALIGNABLE_TOKEN = "\x2 OBJOP%d \x3";

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[ST_SEMI_COLON]) || isset($foundTokens[T_ARRAY]) || isset($foundTokens[T_DOUBLE_ARROW]) || isset($foundTokens[T_OBJECT_OPERATOR])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$contextCounter = 0;
		$contextMetaCount = [];
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_SEMI_COLON:
			case T_ARRAY:
			case T_DOUBLE_ARROW:
				++$contextCounter;
				$this->appendCode($text);
				break;

			case T_OBJECT_OPERATOR:
				if (!isset($contextMetaCount[$contextCounter])) {
					$contextMetaCount[$contextCounter] = 0;
				}
				if ($this->hasLnBefore() || 0 == $contextMetaCount[$contextCounter]) {
					$this->appendCode(sprintf(self::ALIGNABLE_TOKEN, $contextCounter) . $text);
					++$contextMetaCount[$contextCounter];
					break;
				}
			default:
				$this->appendCode($text);
				break;
			}
		}

		for ($j = 0; $j <= $contextCounter; ++$j) {
			$placeholder = sprintf(self::ALIGNABLE_TOKEN, $j);
			if (false === strpos($this->code, $placeholder)) {
				continue;
			}
			if (1 === substr_count($this->code, $placeholder)) {
				$this->code = str_replace($placeholder, '', $this->code);
				continue;
			}

			$lines = explode($this->newLine, $this->code);
			$linesWithObjop = [];
			$blockCount = 0;

			foreach ($lines as $idx => $line) {
				if (false !== strpos($line, $placeholder)) {
					$linesWithObjop[$blockCount][] = $idx;
					break;
				}
				++$blockCount;
				$linesWithObjop[$blockCount] = [];
			}

			foreach ($linesWithObjop as $group) {
				$firstline = reset($group);
				$positionFirstline = strpos($lines[$firstline], $placeholder);

				foreach ($group as $idx) {
					if ($idx == $firstline) {
						continue;
					}
					$line = ltrim($lines[$idx]);
					$line = str_replace($placeholder, str_repeat(' ', $positionFirstline) . $placeholder, $line);
					$lines[$idx] = $line;
				}
			}

			$this->code = str_replace($placeholder, '', implode($this->newLine, $lines));
		}
		return $this->code;
	}
}

	
final class PSR2CurlyOpenNextLine extends FormatterPass {
	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_START_HEREDOC:
				$this->appendCode($text);
				$this->printUntil(T_END_HEREDOC);
				break;
			case ST_QUOTE:
				$this->appendCode($text);
				$this->printUntilTheEndOfString();
				break;
			case T_INTERFACE:
			case T_TRAIT:
			case T_CLASS:
				$this->appendCode($text);
				if ($this->leftUsefulTokenIs(T_DOUBLE_COLON)) {
					break;
				}
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					if (ST_CURLY_OPEN === $id) {
						$this->appendCode($this->getCrlfIndent());
						prev($this->tkns);
						break;
					}
					$this->appendCode($text);
				}
				break;
			case T_FUNCTION:
				if (!$this->leftTokenIs([T_DOUBLE_ARROW, T_RETURN, ST_EQUAL, ST_PARENTHESES_OPEN, ST_COMMA]) && $this->rightUsefulTokenIs([T_STRING, ST_REFERENCE])) {
					$this->appendCode($text);
					$touchedLn = false;
					while (list($index, $token) = each($this->tkns)) {
						list($id, $text) = $this->getToken($token);
						$this->ptr = $index;
						if (T_WHITESPACE == $id && $this->hasLn($text)) {
							$touchedLn = true;
						}
						if (ST_CURLY_OPEN === $id && !$touchedLn) {
							$this->appendCode($this->getCrlfIndent());
							prev($this->tkns);
							break;
						} elseif (ST_CURLY_OPEN === $id) {
							prev($this->tkns);
							break;
						} elseif (ST_SEMI_COLON === $id) {
							$this->appendCode($text);
							break;
						}
						$this->appendCode($text);
					}
					break;
				}
				$this->appendCode($text);
				break;
			case ST_CURLY_OPEN:
				$this->appendCode($text);
				$this->setIndent(+1);
				break;
			case ST_CURLY_CLOSE:
				$this->setIndent(-1);
				$this->appendCode($text);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}

	
final class PSR2IndentWithSpace extends FormatterPass {
	private $size = 4;

	public function __construct($size = null) {
		if ($size > 0) {
			$this->size = $size;
		}
	}

	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$spaces = str_repeat(' ', (int) $this->size);
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_COMMENT:
			case T_DOC_COMMENT:
			case T_WHITESPACE:
				$this->appendCode(str_replace($this->indentChar, $spaces, $text));
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}
	
final class PSR2KeywordsLowerCase extends FormatterPass {
	private static $reservedWords = [
		'__halt_compiler' => 1,
		'abstract' => 1, 'and' => 1, 'array' => 1, 'as' => 1,
		'break' => 1,
		'callable' => 1, 'case' => 1, 'catch' => 1, 'class' => 1, 'clone' => 1, 'const' => 1, 'continue' => 1,
		'declare' => 1, 'default' => 1, 'die' => 1, 'do' => 1,
		'echo' => 1, 'else' => 1, 'elseif' => 1, 'empty' => 1, 'enddeclare' => 1, 'endfor' => 1, 'endforeach' => 1, 'endif' => 1, 'endswitch' => 1, 'endwhile' => 1, 'eval' => 1, 'exit' => 1, 'extends' => 1,
		'final' => 1, 'for' => 1, 'foreach' => 1, 'function' => 1,
		'global' => 1, 'goto' => 1,
		'if' => 1, 'implements' => 1, 'include' => 1, 'include_once' => 1, 'instanceof' => 1, 'insteadof' => 1, 'interface' => 1, 'isset' => 1,
		'list' => 1,
		'namespace' => 1, 'new' => 1,
		'or' => 1,
		'print' => 1, 'private' => 1, 'protected' => 1, 'public' => 1,
		'require' => 1, 'require_once' => 1, 'return' => 1,
		'static' => 1, 'switch' => 1,
		'throw' => 1, 'trait' => 1, 'try' => 1,
		'unset' => 1, 'use' => 1, 'var' => 1,
		'while' => 1, 'xor' => 1,
	];

	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			if (
				T_WHITESPACE == $id ||
				T_VARIABLE == $id ||
				T_INLINE_HTML == $id ||
				T_COMMENT == $id ||
				T_DOC_COMMENT == $id ||
				T_CONSTANT_ENCAPSED_STRING == $id
			) {
				$this->appendCode($text);
				continue;
			}

			if (
				T_STRING == $id
				&& $this->leftUsefulTokenIs([T_DOUBLE_COLON, T_OBJECT_OPERATOR])
			) {
				$this->appendCode($text);
				continue;
			}

			if (T_START_HEREDOC == $id) {
				$this->appendCode($text);
				$this->printUntil(ST_SEMI_COLON);
				continue;
			}
			if (ST_QUOTE == $id) {
				$this->appendCode($text);
				$this->printUntilTheEndOfString();
				continue;
			}
			$lcText = strtolower($text);
			if (
				(
					('true' === $lcText || 'false' === $lcText || 'null' === $lcText) &&
					!$this->leftUsefulTokenIs([
						T_NS_SEPARATOR, T_AS, T_CLASS, T_EXTENDS, T_IMPLEMENTS, T_INSTANCEOF, T_INTERFACE, T_NEW, T_NS_SEPARATOR, T_PAAMAYIM_NEKUDOTAYIM, T_USE, T_TRAIT, T_INSTEADOF, T_CONST,
					]) &&
					!$this->rightUsefulTokenIs([
						T_NS_SEPARATOR, T_AS, T_CLASS, T_EXTENDS, T_IMPLEMENTS, T_INSTANCEOF, T_INTERFACE, T_NEW, T_NS_SEPARATOR, T_PAAMAYIM_NEKUDOTAYIM, T_USE, T_TRAIT, T_INSTEADOF, T_CONST,
					])
				) ||
				isset(static::$reservedWords[$lcText])
			) {
				$text = $lcText;
			}
			$this->appendCode($text);
		}

		return $this->code;
	}
}
	
final class PSR2LnAfterNamespace extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_NAMESPACE])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_NAMESPACE:
				if ($this->rightUsefulTokenIs(T_NS_SEPARATOR)) {
					$this->appendCode($text);
					break;
				}
				if ($this->leftTokenIs(ST_CURLY_CLOSE)) {
					$this->appendCode($this->getCrlf());
				}
				$this->appendCode($text);
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					$this->appendCode($text);
					if (ST_SEMI_COLON === $id) {
						list(, $text) = $this->inspectToken();
						if (1 === substr_count($text, $this->newLine)) {
							$this->appendCode($this->newLine);
						}
						break;
					} elseif (ST_CURLY_OPEN === $id) {
						break;
					}
				}
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}
}
	
final class PSR2ModifierVisibilityStaticOrder extends FormatterPass {
	public function candidate($source, $foundTokens) {
		return isset($foundTokens[T_VAR]) ||
		isset($foundTokens[T_PUBLIC]) ||
		isset($foundTokens[T_PRIVATE]) ||
		isset($foundTokens[T_PROTECTED]) ||
		isset($foundTokens[T_FINAL]) ||
		isset($foundTokens[T_ABSTRACT]) ||
		isset($foundTokens[T_STATIC]) ||
		isset($foundTokens[T_CLASS])
		;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		$found = [];
		$visibility = null;
		$finalOrAbstract = null;
		$static = null;
		$skipWhitespaces = false;
		$touchedClassInterfaceTrait = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_START_HEREDOC:
				$this->appendCode($text);
				$this->printUntil(T_END_HEREDOC);
				break;
			case ST_QUOTE:
				$this->appendCode($text);
				$this->printUntilTheEndOfString();
				break;
			case T_CLASS:
				$found[] = T_CLASS;
				$touchedClassInterfaceTrait = true;
				$this->appendCode($text);
				$this->printUntilAny([T_EXTENDS, T_IMPLEMENTS, ST_CURLY_OPEN]);
				break;
			case T_INTERFACE:
				$found[] = T_INTERFACE;
				$touchedClassInterfaceTrait = true;
				$this->appendCode($text);
				break;
			case T_TRAIT:
				$found[] = T_TRAIT;
				$touchedClassInterfaceTrait = true;
				$this->appendCode($text);
				break;
			case ST_CURLY_OPEN:
			case ST_PARENTHESES_OPEN:
				if ($touchedClassInterfaceTrait) {
					$found[] = $text;
				}
				$this->appendCode($text);
				$touchedClassInterfaceTrait = false;
				break;
			case ST_CURLY_CLOSE:
			case ST_PARENTHESES_CLOSE:
				array_pop($found);
				if (1 === sizeof($found)) {
					array_pop($found);
				}
				$this->appendCode($text);
				break;
			case T_WHITESPACE:
				if (!$skipWhitespaces) {
					$this->appendCode($text);
				}
				break;
			case T_VAR:
				$text = 'public';
			case T_PUBLIC:
			case T_PRIVATE:
			case T_PROTECTED:
				$visibility = $text;
				$skipWhitespaces = true;
				break;
			case T_FINAL:
			case T_ABSTRACT:
				if (!$this->rightTokenIs([T_CLASS])) {
					$finalOrAbstract = $text;
					$skipWhitespaces = true;
					break;
				}
				$this->appendCode($text);
				break;
			case T_STATIC:
				if (!is_null($visibility)) {
					$static = $text;
					$skipWhitespaces = true;
					break;
				} elseif (!$this->rightTokenIs([T_VARIABLE, T_DOUBLE_COLON]) && !$this->leftTokenIs([T_NEW])) {
					$static = $text;
					$skipWhitespaces = true;
					break;
				}
				$this->appendCode($text);
				break;
			case T_VARIABLE:
				if (
					null !== $visibility ||
					null !== $finalOrAbstract ||
					null !== $static
				) {
					null !== $finalOrAbstract && $this->appendCode($finalOrAbstract . $this->getSpace());
					null !== $visibility && $this->appendCode($visibility . $this->getSpace());
					null !== $static && $this->appendCode($static . $this->getSpace());
					$finalOrAbstract = null;
					$visibility = null;
					$static = null;
					$skipWhitespaces = false;
				}
				$this->appendCode($text);
				$this->printUntil(ST_SEMI_COLON);
				break;
			case T_FUNCTION:
				$hasFoundClassOrInterface = isset($found[0]) && (ST_CURLY_OPEN == $found[0] || T_CLASS === $found[0] || T_INTERFACE === $found[0] || T_TRAIT === $found[0]) && $this->rightUsefulTokenIs([T_STRING, ST_REFERENCE]);
				if ($hasFoundClassOrInterface && null !== $finalOrAbstract) {
					$this->appendCode($finalOrAbstract . $this->getSpace());
				}
				if ($hasFoundClassOrInterface && null !== $visibility) {
					$this->appendCode($visibility . $this->getSpace());
				} elseif (
					$hasFoundClassOrInterface &&
					!$this->leftTokenIs([T_DOUBLE_ARROW, T_RETURN, ST_EQUAL, ST_COMMA, ST_PARENTHESES_OPEN])
				) {
					$this->appendCode('public' . $this->getSpace());
				}
				if ($hasFoundClassOrInterface && null !== $static) {
					$this->appendCode($static . $this->getSpace());
				}
				$this->appendCode($text);
				$visibility = null;
				$static = null;
				$skipWhitespaces = false;
				if ('abstract' == strtolower($finalOrAbstract)) {
					$finalOrAbstract = null;
					$this->printUntil(ST_SEMI_COLON);
					break;
				}
				$finalOrAbstract = null;
				$this->printUntil(ST_CURLY_OPEN);
				$this->printCurlyBlock();
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}

	
final class PSR2SingleEmptyLineAndStripClosingTag extends FormatterPass {
	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$tokenCount = count($this->tkns) - 1;
		while (list($index, $token) = each($this->tkns)) {
			list($id) = $this->getToken($token);
			$this->ptr = $index;
			if (T_INLINE_HTML == $id && $this->ptr != $tokenCount) {
				return $source;
			}
		}

		list($id, $text) = $this->getToken(end($this->tkns));
		$this->ptr = key($this->tkns);

		if (T_CLOSE_TAG == $id && $this->leftUsefulTokenIs([ST_CURLY_CLOSE, ST_SEMI_COLON])) {
			unset($this->tkns[$this->ptr]);
		} elseif (T_INLINE_HTML == $id && '' == trim($text) && $this->leftTokenIs(T_CLOSE_TAG)) {
			unset($this->tkns[$this->ptr]);
			$ptr = $this->leftTokenIdx([]);
			unset($this->tkns[$ptr]);
		}

		return rtrim($this->render()) . $this->newLine;
	}
}

	
final class PsrDecorator {
	public static function PSR1(CodeFormatter $fmt) {
		$fmt->enablePass('PSR1OpenTags');
		$fmt->enablePass('PSR1BOMMark');
		$fmt->enablePass('PSR1ClassConstants');
		$fmt->disablePass('ReindentComments');
	}

	public static function PSR1Naming(CodeFormatter $fmt) {
		$fmt->enablePass('PSR1ClassNames');
		$fmt->enablePass('PSR1MethodNames');
		$fmt->disablePass('ReindentComments');
	}

	public static function PSR2(CodeFormatter $fmt) {
		$fmt->enablePass('PSR2KeywordsLowerCase');
		$fmt->enablePass('PSR2IndentWithSpace');
		$fmt->enablePass('PSR2LnAfterNamespace');
		$fmt->enablePass('PSR2CurlyOpenNextLine');
		$fmt->enablePass('PSR2ModifierVisibilityStaticOrder');
		$fmt->enablePass('PSR2SingleEmptyLineAndStripClosingTag');
		$fmt->enablePass('ReindentSwitchBlocks');
		$fmt->disablePass('ReindentComments');
		$fmt->disablePass('StripNewlineWithinClassBody');
	}

	public static function decorate(CodeFormatter $fmt) {
		self::PSR1($fmt);
		self::PSR1Naming($fmt);
		self::PSR2($fmt);
	}
}

	
final class AddMissingParentheses extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_NEW])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_NEW:
				$this->appendCode($text);
				list($foundId, $foundText, $touchedLn) = $this->printAndStopAt([
					ST_PARENTHESES_OPEN,
					ST_PARENTHESES_CLOSE,
					T_COMMENT,
					T_DOC_COMMENT,
					ST_SEMI_COLON,
					ST_COMMA,
					ST_BRACKET_CLOSE,
				]);
				if (ST_PARENTHESES_OPEN == $foundId) {
					$this->appendCode($foundText);
					break;
				}
				$this->rtrimAndAppendCode('()');
				if ($touchedLn) {
					$this->appendCode($this->newLine);
				}
				$this->appendCode($foundText);
				break;
			default:
				$this->appendCode($text);
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Add extra parentheses in new instantiations.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
$a = new SomeClass;

$a = new SomeClass();
?>
EOT;
	}
}

	
class AliasToMaster extends AdditionalPass {
	protected static $aliasList = [
		'chop' => 'rtrim',
		'close' => 'closedir',
		'die' => 'exit',
		'doubleval' => 'floatval',
		'fputs' => 'fwrite',
		'ini_alter' => 'ini_set',
		'is_double' => 'is_float',
		'is_integer' => 'is_int',
		'is_long' => 'is_int',
		'is_real' => 'is_float',
		'is_writeable' => 'is_writable',
		'join' => 'implode',
		'key_exists' => 'array_key_exists',
		'magic_quotes_runtime' => 'set_magic_quotes_runtime',
		'pos' => 'current',
		'rewind' => 'rewinddir',
		'show_source' => 'highlight_file',
		'sizeof' => 'count',
		'strchr' => 'strstr',
	];

	private $touchedEmptyNs = false;

	public function candidate($source, $foundTokens) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->checkIfEmptyNS($id);
			switch ($id) {
			case T_STRING:
			case T_EXIT:
				if (isset(static::$aliasList[strtolower($text)])) {
					prev($this->tkns);
					return true;
				}
			}
			$this->appendCode($text);
		}
		return false;
	}

	public function format($source) {
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->checkIfEmptyNS($id);
			if (
				(T_STRING == $id || T_EXIT == $id) &&
				isset(static::$aliasList[strtolower($text)]) &&
				(
					!(
						$this->leftUsefulTokenIs([
							T_NEW,
							T_NS_SEPARATOR,
							T_STRING,
							T_DOUBLE_COLON,
							T_OBJECT_OPERATOR,
							T_FUNCTION,
						]) ||
						$this->rightUsefulTokenIs([
							T_NS_SEPARATOR,
							T_DOUBLE_COLON,
						])
					)
					||
					(
						$this->leftUsefulTokenIs([
							T_NS_SEPARATOR,
						]) &&
						$this->touchedEmptyNs
					)
				)
			) {
				$this->appendCode(static::$aliasList[strtolower($text)]);
				continue;
			}
			$this->appendCode($text);
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Replace function aliases to their masters - only basic syntax alias.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
$a = join(',', $arr);
die("done");

$a = implode(',', $arr);
exit("done");
?>
EOT;
	}

	private function checkIfEmptyNS($id) {
		if (T_NS_SEPARATOR != $id) {
			return;
		}

		$this->touchedEmptyNs = !$this->leftUsefulTokenIs(T_STRING);
	}
}

	
final class AlignConstVisibilityEquals extends AdditionalPass {
	const ALIGNABLE_EQUAL = "\x2 EQUAL%d \x3";

	const OPEN_TAG = "<?php /*\x2 EQUAL OPEN TAG\x3*/";

	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

						$parenCount = 0;
		$bracketCount = 0;
		$contextCounter = 0;
		$touchedVisibilityConst = false;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_PUBLIC:
			case T_PRIVATE:
			case T_PROTECTED:
			case T_CONST:
				$touchedVisibilityConst = true;
				$this->appendCode($text);
				break;

			case ST_SEMI_COLON:
				$touchedVisibilityConst = false;
				$this->appendCode($text);
				break;

			case T_FUNCTION:
				++$contextCounter;
				$this->appendCode($text);
				break;

			case ST_CURLY_OPEN:
				$this->appendCode($text);
				$block = $this->walkAndAccumulateCurlyBlock($this->tkns);
				$aligner = new self();
				$this->appendCode(
					str_replace(self::OPEN_TAG, '', $aligner->format(self::OPEN_TAG . $block))
				);
				break;

			case ST_PARENTHESES_OPEN:
				++$parenCount;
				$this->appendCode($text);
				break;
			case ST_PARENTHESES_CLOSE:
				--$parenCount;
				$this->appendCode($text);
				break;
			case ST_BRACKET_OPEN:
				++$bracketCount;
				$this->appendCode($text);
				break;
			case ST_BRACKET_CLOSE:
				--$bracketCount;
				$this->appendCode($text);
				break;
			case ST_EQUAL:
				if ($touchedVisibilityConst && !$parenCount && !$bracketCount) {
					$this->appendCode(sprintf(self::ALIGNABLE_EQUAL, $contextCounter) . $text);
					break;
				}

			default:
				$this->appendCode($text);
				break;
			}
		}

		$this->alignPlaceholders(self::ALIGNABLE_EQUAL, $contextCounter);

		return $this->code;
	}

	
	public function getDescription() {
		return 'Vertically align "=" of visibility and const blocks.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php

class A {
	public $a = 1;
	public $bb = 22;
	public $ccc = 333;
}

class A {
	public $a   = 1;
	public $bb  = 22;
	public $ccc = 333;
}
?>
EOT;
	}
}
	
class AlignDoubleArrow extends AdditionalPass {
	const ALIGNABLE_EQUAL = "\x2 EQUAL%d.%d.%d \x3";

	
	public function __construct() {
		$this->strposFunc = 'strpos';
		$this->substrCountFunc = 'substr_count';

		if (function_exists('mb_strpos')) {
			$this->strposFunc = 'mb_strpos';
			$this->substrCountFunc = 'mb_substr_count';
		}
	}

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_DOUBLE_ARROW])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		$levelCounter = 0;
		$levelEntranceCounter = [];
		$contextCounter = [];
		$maxContextCounter = [];

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_COMMA:
				if (!$this->hasLnAfter() && !$this->hasLnRightToken()) {
					if (!isset($levelEntranceCounter[$levelCounter])) {
						$levelEntranceCounter[$levelCounter] = 0;
					}
					if (!isset($contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]])) {
						$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
						$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					}
					++$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]];
					$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = max($maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]], $contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]);
				} elseif ($contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] > 1) {
					$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 1;
				}
				$this->appendCode($text);
				break;

			case T_DOUBLE_ARROW:
				if (isset($levelEntranceCounter[$levelCounter], $contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]])) {
					$this->appendCode(
						sprintf(
							self::ALIGNABLE_EQUAL,
							$levelCounter,
							$levelEntranceCounter[$levelCounter],
							$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]
						)
					);
				}

				$this->appendCode($text);
				break;

			case ST_PARENTHESES_OPEN:
			case ST_BRACKET_OPEN:
				++$levelCounter;
				if (!isset($levelEntranceCounter[$levelCounter])) {
					$levelEntranceCounter[$levelCounter] = 0;
				}
				++$levelEntranceCounter[$levelCounter];
				if (!isset($contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]])) {
					$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
				}
				++$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]];
				$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = max($maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]], $contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]);

				$this->appendCode($text);
				break;

			case ST_PARENTHESES_CLOSE:
			case ST_BRACKET_CLOSE:
				--$levelCounter;
				$this->appendCode($text);
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}

		$this->align($maxContextCounter);

		return $this->code;
	}

	
	public function getDescription() {
		return 'Vertically align T_DOUBLE_ARROW (=>).';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
$a = [
	1 => 1,
	22 => 22,
	333 => 333,
];

$a = [
	1   => 1,
	22  => 22,
	333 => 333,
];
?>
EOT;
	}

	protected function align($maxContextCounter) {
		foreach ($maxContextCounter as $level => $entrances) {
			foreach ($entrances as $entrance => $context) {
				for ($j = 0; $j <= $context; ++$j) {
					$placeholder = sprintf(self::ALIGNABLE_EQUAL, $level, $entrance, $j);
					if (false === $this->strpos($this->code, $placeholder)) {
						continue;
					}
					if (1 === $this->substrCount($this->code, $placeholder)) {
						$this->code = str_replace($placeholder, '', $this->code);
						continue;
					}

					$lines = explode($this->newLine, $this->code);
					$linesWithObjop = [];

					foreach ($lines as $idx => $line) {
						if (false !== $this->strpos($line, $placeholder)) {
							$linesWithObjop[] = $idx;
						}
					}

					$farthest = 0;
					foreach ($linesWithObjop as $idx) {
						$farthest = max($farthest, $this->strpos($lines[$idx], $placeholder));
					}
					foreach ($linesWithObjop as $idx) {
						$line = $lines[$idx];
						$current = $this->strpos($line, $placeholder);
						$delta = abs($farthest - $current);
						if ($delta > 0) {
							$line = str_replace($placeholder, str_repeat(' ', $delta) . $placeholder, $line);
							$lines[$idx] = $line;
						}
					}

					$this->code = str_replace($placeholder, '', implode($this->newLine, $lines));
				}
			}
		}
	}

	private function strpos($code, $placeholder) {
		return call_user_func($this->strposFunc, $code, $placeholder);
	}

	private function substrCount($code, $placeholder) {
		return call_user_func($this->substrCountFunc, $code, $placeholder);
	}
}

	
final class AlignDoubleSlashComments extends AdditionalPass {
	const ALIGNABLE_COMMENT = "\x2 COMMENT%d \x3";

	
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_COMMENT])) {
			return true;
		}
		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

						$contextCounter = 0;
		$touchedNonAlignableComment = false;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_COMMENT:
				if (LeftAlignComment::NON_INDENTABLE_COMMENT == $text) {
					$touchedNonAlignableComment = true;
					$this->appendCode($text);
					continue;
				}

				$prefix = '';
				if (substr($text, 0, 2) == '//' && !$touchedNonAlignableComment) {
					$prefix = sprintf(self::ALIGNABLE_COMMENT, $contextCounter);
				}
				$this->appendCode($prefix . $text);

				break;

			case T_WHITESPACE:
				if ($this->hasLn($text)) {
					++$contextCounter;
				}
				$this->appendCode($text);
				break;

			default:
				$touchedNonAlignableComment = false;
				$this->appendCode($text);
				break;
			}
		}

		$this->alignPlaceholders(self::ALIGNABLE_COMMENT, $contextCounter);

		return $this->code;
	}

	
	public function getDescription() {
		return 'Vertically align "//" comments.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
//From:
$a = 1; // Comment 1
$bb = 22;  // Comment 2
$ccc = 333;  // Comment 3

//To:
$a = 1;      // Comment 1
$bb = 22;    // Comment 2
$ccc = 333;  // Comment 3

?>
EOT;
	}
}
	
final class AlignEquals extends AdditionalPass {
	const ALIGNABLE_EQUAL = "\x2 EQUAL%d \x3";

	const OPEN_TAG = "<?php /*\x2 EQUAL OPEN TAG\x3*/";

	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

						$parenCount = 0;
		$bracketCount = 0;
		$contextCounter = 0;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_FUNCTION:
				++$contextCounter;
				$this->appendCode($text);
				break;

			case ST_CURLY_OPEN:
				$this->appendCode($text);
				$block = $this->walkAndAccumulateCurlyBlock($this->tkns);
				$aligner = new self();
				$this->appendCode(
					str_replace(self::OPEN_TAG, '', $aligner->format(self::OPEN_TAG . $block))
				);
				break;

			case ST_PARENTHESES_OPEN:
				++$parenCount;
				$this->appendCode($text);
				break;
			case ST_PARENTHESES_CLOSE:
				--$parenCount;
				$this->appendCode($text);
				break;
			case ST_BRACKET_OPEN:
				++$bracketCount;
				$this->appendCode($text);
				break;
			case ST_BRACKET_CLOSE:
				--$bracketCount;
				$this->appendCode($text);
				break;
			case ST_EQUAL:
				if (!$parenCount && !$bracketCount) {
					$this->appendCode(sprintf(self::ALIGNABLE_EQUAL, $contextCounter) . $text);
					break;
				}

			default:
				$this->appendCode($text);
				break;
			}
		}

		$this->alignPlaceholders(self::ALIGNABLE_EQUAL, $contextCounter);

		return $this->code;
	}

	
	public function getDescription() {
		return 'Vertically align "=".';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
$a = 1;
$bb = 22;
$ccc = 333;

$a   = 1;
$bb  = 22;
$ccc = 333;

?>
EOT;
	}
}
	
final class AlignGroupDoubleArrow extends AlignDoubleArrow {
	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		$levelCounter = 0;
		$levelEntranceCounter = [];
		$contextCounter = [];
		$maxContextCounter = [];

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_COMMA:
				if (!$this->hasLnAfter() && !$this->hasLnRightToken()) {
					if (!isset($levelEntranceCounter[$levelCounter])) {
						$levelEntranceCounter[$levelCounter] = 0;
					}
					if (!isset($contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]])) {
						$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
						$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					}
					++$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]];
					$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = max($maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]], $contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]);
				} elseif ($contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] > 1) {
					$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 1;
				}
				$this->appendCode($text);
				break;

			case T_DOUBLE_ARROW:
				$this->appendCode(
					sprintf(
						self::ALIGNABLE_EQUAL,
						$levelCounter,
						$levelEntranceCounter[$levelCounter],
						$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]
					) . $text
				);
				break;

			case T_WHITESPACE:
				if ($this->hasLn($text) && substr_count($text, $this->newLine) >= 2) {
					++$levelCounter;
					if (!isset($levelEntranceCounter[$levelCounter])) {
						$levelEntranceCounter[$levelCounter] = 0;
					}
					++$levelEntranceCounter[$levelCounter];
					if (!isset($contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]])) {
						$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
						$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					}
					++$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]];
					$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = max($maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]], $contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]);
				}
				$this->appendCode($text);
				break;

			case ST_PARENTHESES_OPEN:
			case ST_BRACKET_OPEN:
				++$levelCounter;
				if (!isset($levelEntranceCounter[$levelCounter])) {
					$levelEntranceCounter[$levelCounter] = 0;
				}
				++$levelEntranceCounter[$levelCounter];
				if (!isset($contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]])) {
					$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
				}
				++$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]];
				$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = max($maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]], $contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]);

				$this->appendCode($text);
				break;

			case ST_PARENTHESES_CLOSE:
			case ST_BRACKET_CLOSE:
				--$levelCounter;
				$this->appendCode($text);
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}
		$this->align($maxContextCounter);

		return $this->code;
	}

	
	public function getDescription() {
		return 'Vertically align T_DOUBLE_ARROW (=>) by line groups.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
$a = [
	1 => 1,
	22 => 22,

	333 => 333,
	4444 => 4444,
];

$a = [
	1  => 1,
	22 => 22,

	333  => 333,
	4444 => 4444,
];
?>
EOT;
	}
}

	
final class AlignPHPCode extends AdditionalPass {
	const PLACEHOLDER_STRING = "\x2 CONSTANT_STRING_%d \x3";

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_INLINE_HTML])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_OPEN_TAG:
				list(, $prevText) = $this->getToken($this->leftToken());

				$prevSpace = substr(strrchr($prevText, $this->newLine), 1);
				$skipPadLeft = false;
				if (rtrim($prevSpace) == $prevSpace) {
					$skipPadLeft = true;
				}
				$prevSpace = preg_replace('/[^\s\t]/', ' ', $prevSpace);

				$placeholders = [];
				$strings = [];
				$stack = $text;
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;

					if (T_CONSTANT_ENCAPSED_STRING == $id || T_ENCAPSED_AND_WHITESPACE == $id) {
						$strings[] = $text;
						$text = sprintf(self::PLACEHOLDER_STRING, $this->ptr);
						$placeholders[] = $text;
					}
					$stack .= $text;

					if (T_CLOSE_TAG == $id) {
						break;
					}
				}

				$tmp = explode($this->newLine, $stack);
				$lastLine = sizeof($tmp) - 2;
				foreach ($tmp as $idx => $line) {
					$before = $prevSpace;
					if ('' === trim($line)) {
						continue;
					}
					$indent = '';
					if (0 != $idx && $idx < $lastLine) {
						$indent = $this->indentChar;
					}
					if ($skipPadLeft) {
						$before = '';
						$skipPadLeft = false;
					}
					$tmp[$idx] = $before . $indent . $line;
				}

				$stack = implode($this->newLine, $tmp);
				$stack = str_replace($placeholders, $strings, $stack);

				$this->code = rtrim($this->code, " \t");
				$this->appendCode($stack);
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Align PHP code within HTML block.';
	}

	
	public function getExample() {
		return <<<'EOT'
<div>
	<?php
		echo $a;
	?>
</div>
EOT;
	}
}

	
final class AlignTypehint extends AdditionalPass {
	const ALIGNABLE_TYPEHINT = "\x2 TYPEHINT%d \x3";

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_FUNCTION])) {
			return true;
		}
		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		$contextCounter = 0;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_FUNCTION:
				$this->appendCode($text);
				$this->printUntil(ST_PARENTHESES_OPEN);
				do {
					list($id, $text) = $this->printAndStopAt([T_VARIABLE, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE]);
					if (ST_PARENTHESES_OPEN == $id) {
						$this->appendCode($text);
						$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
						continue;
					}
					if (ST_PARENTHESES_CLOSE == $id) {
						$this->appendCode($text);
						break;
					}
					$this->appendCode(sprintf(self::ALIGNABLE_TYPEHINT, $contextCounter) . $text);
				} while (true);
				++$contextCounter;
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}

		$this->alignPlaceholders(self::ALIGNABLE_TYPEHINT, $contextCounter);

		return $this->code;
	}

	
	public function getDescription() {
		return 'Vertically align function type hints.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
//From:
function a(
	TypeA $a,
	TypeBB $bb,
	TypeCCC $ccc = array(),
	TypeDDDD $dddd,
	TypeEEEEE $eeeee
){
	noop();
}


//To:
function a(
	TypeA     $a,
	TypeBB    $bb,
	TypeCCC   $ccc = array(),
	TypeDDDD  $dddd,
	TypeEEEEE $eeeee
){
	noop();
}


?>
EOT;
	}
}

	
final class AllmanStyleBraces extends AdditionalPass {
	const OTHER_BLOCK = '';

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[ST_CURLY_OPEN])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$blockStack = [];
		$foundStack = [];
		$currentIndentation = 0;
		$touchedCaseOrDefault = false;
		$touchedSwitch = false;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_CASE:
			case T_DEFAULT:
				$touchedCaseOrDefault = true;
				$this->appendCode($text);
				break;

			case T_BREAK:
				$touchedCaseOrDefault = false;
				$this->appendCode($text);
				break;

			case T_CLASS:
			case T_FUNCTION:
				$currentIndentation = 0;
				$poppedID = end($foundStack);
				if (true === $poppedID['implicit']) {
					list($prevId, $prevText) = $this->inspectToken(-1);
					$currentIndentation = substr_count($prevText, $this->indentChar, strrpos($prevText, "\n"));
				}
				$this->appendCode($text);
				break;

			case ST_CURLY_OPEN:
				$block = self::OTHER_BLOCK;
				if ($touchedSwitch) {
					$touchedSwitch = false;
					$block = T_SWITCH;
				}
				$blockStack[] = $block;

				if ($this->leftUsefulTokenIs([ST_PARENTHESES_CLOSE, T_ELSE, T_FINALLY, T_DO, T_STRING])) {
					if (!$this->hasLnLeftToken()) {
						$this->appendCode($this->getCrlfIndent());
					}
				}
				$indentToken = [
					'id' => $id,
					'implicit' => true,
				];
				$adjustedIndendation = max($currentIndentation - $this->indent, 0);
				if ($touchedCaseOrDefault) {
					++$adjustedIndendation;
				}
				$this->appendCode(str_repeat($this->indentChar, $adjustedIndendation) . $text);
				$currentIndentation = 0;
				if ($this->hasLnAfter()) {
					$indentToken['implicit'] = false;
					$this->setIndent(+1);
				}
				if (
					!$this->hasLnAfter() &&
					!$this->leftUsefulTokenIs([T_OBJECT_OPERATOR, T_DOUBLE_COLON]) &&
					!$this->rightTokenIs([T_COMMENT, T_DOC_COMMENT])
				) {
					$this->setIndent(+1);
					$this->appendCode($this->getCrlfIndent());
					$this->setIndent(-1);
				}
				$foundStack[] = $indentToken;
				break;

			case T_DOLLAR_OPEN_CURLY_BRACES:
			case T_CURLY_OPEN:
				$this->appendCode($text);
				$this->printCurlyBlock();
				break;

			case ST_BRACKET_OPEN:
			case ST_PARENTHESES_OPEN:
				$blockStack[] = self::OTHER_BLOCK;
				$indentToken = [
					'id' => $id,
					'implicit' => true,
				];
				$this->appendCode($text);
				if ($this->hasLnAfter()) {
					$indentToken['implicit'] = false;
					$this->setIndent(+1);
				}
				$foundStack[] = $indentToken;
				break;

			case ST_COMMA:
				$this->appendCode($text);
				$foundIndent = &$foundStack[sizeof($foundStack) - 1];
				if ($this->hasLnAfter() && $foundIndent['implicit']) {
					$foundIndent['implicit'] = false;
					$this->setIndent(+1);
				}
				break;

			case ST_BRACKET_CLOSE:
			case ST_PARENTHESES_CLOSE:
			case ST_CURLY_CLOSE:
				$poppedID = array_pop($foundStack);
				$poppedBlock = array_pop($blockStack);
				if (T_SWITCH == $poppedBlock) {
					$touchedCaseOrDefault = false;
					$this->setIndent(-1);
				} elseif (false === $poppedID['implicit']) {
					$this->setIndent(-1);
				}
				$this->appendCode($text);
				break;

			case T_ELSE:
			case T_ELSEIF:
			case T_FINALLY:
				list($prevId, $prevText) = $this->getToken($this->leftToken());
				if (!$this->hasLn($prevText) && T_OPEN_TAG != $prevId) {
					$this->appendCode($this->getCrlfIndent());
					if ($touchedCaseOrDefault) {
						$this->appendCode($this->indentChar);
					}
				}
				$this->appendCode($text);
				break;

			case T_CATCH:
				if (' ' == substr($this->code, -1, 1)) {
					$this->code = substr($this->code, 0, -1);
				}
				if (!$this->hasLnLeftToken()) {
					$this->appendCode($this->getCrlfIndent());
					if ($touchedCaseOrDefault) {
						$this->appendCode($this->indentChar);
					}
				}
				$this->appendCode($text);
				break;

			case T_SWITCH:
				$touchedSwitch = true;
				$this->appendCode($text);
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Transform all curly braces into Allman-style.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
if ($a) {

}


if ($a)
{

}
?>
EOT;
	}
}

	
class AutoPreincrement extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_INC]) || isset($foundTokens[T_DEC])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		for ($this->ptr = sizeof($this->tkns) - 1; $this->ptr >= 0; --$this->ptr) {
			$token = $this->tkns[$this->ptr];
			$tokenRef = &$this->tkns[$this->ptr];

			$id = $token[0];
			if (!(T_INC == $id || T_DEC == $id)) {
				continue;
			}

			if (
				!$this->leftUsefulTokenIs([
					ST_BRACKET_CLOSE,
					ST_CURLY_CLOSE,
					T_STRING,
					T_VARIABLE,
				])
				||
				!$this->rightUsefulTokenIs([
					ST_SEMI_COLON,
					ST_PARENTHESES_CLOSE,
				])
			) {
				continue;
			}

			$this->findVariableLeftEdge();

			if (
				$this->leftUsefulTokenIs([
					ST_SEMI_COLON,
					ST_CURLY_OPEN,
					ST_CURLY_CLOSE,
					T_OPEN_TAG,
				])
			) {
				$this->refInsert($this->tkns, $this->ptr, $token);
				$tokenRef = null;
			}
		}

		return $this->render();
	}

	
	public function getDescription() {
		return 'Automatically convert postincrement to preincrement.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
$a++;
$b--;
func($a++);

++$a;
--$b;
func($a++);
?>
EOT;
	}

	private function findVariableLeftEdge() {
		$this->skipBlocks();

		$leftIdx = $this->leftUsefulTokenIdx();
		$idLeftToken = $this->tkns[$leftIdx][0];

		if (ST_DOLLAR == $idLeftToken) {
			$this->ptr = $leftIdx;
			$leftIdx = $this->leftUsefulTokenIdx();
			$idLeftToken = $this->tkns[$leftIdx][0];
		}

		if (T_OBJECT_OPERATOR == $idLeftToken) {
			$this->findVariableLeftEdge();
			return;
		}

		if (T_DOUBLE_COLON == $idLeftToken) {
			if (!$this->leftUsefulTokenIs([T_STRING])) {
				$this->findVariableLeftEdge();
				return;
			}

			$this->refWalkBackUsefulUntil($this->tkns, $this->ptr, [T_NS_SEPARATOR, T_STRING]);
			$this->ptr = $this->rightUsefulTokenIdx();
		}

		return;
	}

	private function skipBlocks() {
		do {
			$this->ptr = $this->leftUsefulTokenIdx();
			$id = $this->tkns[$this->ptr][0];

			if (ST_BRACKET_CLOSE == $id) {
				$this->refWalkBlockReverse($this->tkns, $this->ptr, ST_BRACKET_OPEN, ST_BRACKET_CLOSE);
			} elseif (ST_CURLY_CLOSE == $id) {
				$this->refWalkCurlyBlockReverse($this->tkns, $this->ptr);
			} elseif (ST_PARENTHESES_CLOSE == $id) {
				$this->refWalkBlockReverse($this->tkns, $this->ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
			}

			$id = $this->tkns[$this->ptr][0];
		} while (!(ST_DOLLAR == $id || T_VARIABLE == $id));
	}
}
	
final class AutoSemicolon extends AdditionalPass {
	const ST_CLOSURE = 'CLOSURE';

	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$parenStack = [];
		$curlyStack = [];
		$lastParen = null;
		$lastCurly = null;
		$ternary = 0;
		$touchedSingleColon = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_IF:
			case T_SWITCH:
			case T_FOR:
			case T_FOREACH:
				$parenStack[] = $id;
				$this->appendCode($text);
				$this->printUntil(ST_PARENTHESES_OPEN);
				break;
			case ST_PARENTHESES_OPEN:
				$parenStack[] = $id;
				$this->appendCode($text);
				break;

			case ST_PARENTHESES_CLOSE:
				$lastParen = array_pop($parenStack);
				$this->appendCode($text);
				break;

			case T_FUNCTION:
				$foundId = $id;
				if ($this->rightUsefulTokenIs(ST_PARENTHESES_OPEN)) {
					$foundId = self::ST_CLOSURE;
				}
				$curlyStack[] = $foundId;
				$this->appendCode($text);
				$this->printUntil(ST_CURLY_OPEN);
				break;

			case T_CURLY_OPEN:
			case T_DOLLAR_OPEN_CURLY_BRACES:
			case ST_CURLY_OPEN:
				$curlyStack[] = $id;
				$this->appendCode($text);
				break;

			case ST_CURLY_CLOSE:
				$lastCurly = array_pop($curlyStack);
				$this->appendCode($text);
				break;
			case ST_QUESTION:
				++$ternary;
				$this->appendCode($text);
				break;
			case ST_COLON:
				if ($ternary > 0) {
					$touchedSingleColon = true;
				}
				$this->appendCode($text);
				break;

			case T_WHITESPACE:
				if (!$this->hasLn($text)) {
					$this->appendCode($text);
					continue;
				}
				if ($touchedSingleColon && $ternary) {
					$touchedSingleColon = false;
					--$ternary;
				}

				if (
					$this->leftUsefulTokenIs([
						ST_BRACKET_OPEN,
						ST_COLON,
						ST_COMMA,
						ST_CONCAT,
						ST_CURLY_OPEN,
						ST_DIVIDE,
						ST_EQUAL,
						ST_MINUS,
						ST_PARENTHESES_OPEN,
						ST_PLUS,
						ST_SEMI_COLON,
						ST_TIMES,

						T_ABSTRACT,
						T_AND_EQUAL,
						T_ARRAY,
						T_ARRAY_CAST,
						T_AS,
						T_BOOL_CAST,
						T_BOOLEAN_AND,
						T_BOOLEAN_OR,
						T_CALLABLE,
						T_CASE,
						T_CATCH,
						T_CLASS,
						T_CLONE,
						T_CONCAT_EQUAL,
						T_CONST,
						T_DECLARE,
						T_DEFAULT,
						T_DIV_EQUAL,
						T_DO,
						T_DOUBLE_ARROW,
						T_DOUBLE_CAST,
						T_DOUBLE_COLON,
						T_DOUBLE_COLON,
						T_ECHO,
						T_ELLIPSIS,
						T_ELSE,
						T_ELSEIF,
						T_EXTENDS,
						T_FINAL,
						T_FINALLY,
						T_FOR,
						T_FOREACH,
						T_FUNCTION,
						T_GLOBAL,
						T_GOTO,
						T_IF,
						T_IMPLEMENTS,
						T_INC,
						T_INCLUDE,
						T_INCLUDE_ONCE,
						T_INLINE_HTML,
						T_INSTANCEOF,
						T_INSTEADOF,
						T_INT_CAST,
						T_INTERFACE,
						T_IS_EQUAL,
						T_IS_GREATER_OR_EQUAL,
						T_IS_IDENTICAL,
						T_IS_NOT_EQUAL,
						T_IS_NOT_IDENTICAL,
						T_IS_SMALLER_OR_EQUAL,
						T_LOGICAL_AND,
						T_LOGICAL_OR,
						T_LOGICAL_XOR,
						T_MINUS_EQUAL,
						T_MOD_EQUAL,
						T_MUL_EQUAL,
						T_NAMESPACE,
						T_NEW,
						T_NS_SEPARATOR,
						T_OBJECT_CAST,
						T_OBJECT_OPERATOR,
						T_OPEN_TAG,
						T_OR_EQUAL,
						T_PLUS_EQUAL,
						T_POW,
						T_POW_EQUAL,
						T_PRIVATE,
						T_PROTECTED,
						T_PUBLIC,
						T_REQUIRE,
						T_REQUIRE_ONCE,
						T_SL,
						T_SL_EQUAL,
						T_SPACESHIP,
						T_SR,
						T_SR_EQUAL,
						T_START_HEREDOC,
						T_STATIC,
						T_STRING_CAST,
						T_SWITCH,
						T_THROW,
						T_TRAIT,
						T_TRY,
						T_UNSET_CAST,
						T_USE,
						T_VAR,
						T_WHILE,
					]) ||
					$this->leftTokenIs([
						T_COMMENT,
						T_DOC_COMMENT,
					])
				) {
					$this->appendCode($text);
					continue;
				}
				if (
					$this->rightUsefulTokenIs([
						ST_BRACKET_CLOSE,
						ST_BRACKET_OPEN,
						ST_COLON,
						ST_COMMA,
						ST_CONCAT,
						ST_CURLY_OPEN,
						ST_DIVIDE,
						ST_MINUS,
						ST_PARENTHESES_CLOSE,
						ST_PARENTHESES_OPEN,
						ST_PLUS,
						ST_SEMI_COLON,
						ST_TIMES,

						T_BOOLEAN_AND,
						T_BOOLEAN_OR,
						T_IS_EQUAL,
						T_IS_GREATER_OR_EQUAL,
						T_IS_IDENTICAL,
						T_IS_NOT_EQUAL,
						T_IS_NOT_IDENTICAL,
						T_IS_SMALLER_OR_EQUAL,
						T_LOGICAL_AND,
						T_LOGICAL_OR,
						T_LOGICAL_XOR,
						T_OBJECT_OPERATOR,
						T_POW,

						ST_QUESTION,
					]) ||
					$this->rightTokenIs([
						T_COMMENT,
						T_DOC_COMMENT,
					])
				) {
					$this->appendCode($text);
					continue;
				}

				if (
					$this->leftUsefulTokenIs(ST_PARENTHESES_CLOSE) &&
					ST_PARENTHESES_OPEN != $lastParen
				) {
					$this->appendCode($text);
					continue;
				}

				if (
					$this->leftUsefulTokenIs(ST_CURLY_CLOSE) &&
					(
						ST_CURLY_OPEN == $lastCurly
						||
						T_FUNCTION == $lastCurly
					)
				) {
					$this->appendCode($text);
					continue;
				}

				if (0 != $ternary) {
					$this->appendCode($text);
					continue;
				}

				$this->appendCode(ST_SEMI_COLON . $text);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Add semicolons in statements ends.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
$a = new SomeClass()

// To
$a = new SomeClass();
?>
EOT;
	}
}

	
final class CakePHPStyle extends AdditionalPass {
	private $foundTokens;

	public function candidate($source, $foundTokens) {
		$this->foundTokens = $foundTokens;
		return true;
	}

	public function format($source) {
		$fmt = new PSR2ModifierVisibilityStaticOrder();
		if ($fmt->candidate($source, $this->foundTokens)) {
			$source = $fmt->format($source);
		}
		$fmt = new MergeElseIf();
		if ($fmt->candidate($source, $this->foundTokens)) {
			$source = $fmt->format($source);
		}
		$source = $this->addUnderscoresBeforeName($source);
		$source = $this->removeSpaceAfterCasts($source);
		$source = $this->mergeEqualsWithReference($source);
		$source = $this->resizeSpaces($source);
		return $source;
	}

	
	public function getDescription() {
		return 'Applies CakePHP Coding Style';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
namespace A;

class A {
	private $__a;
	protected $_b;
	public $c;

	public function b() {
		if($a) {
			noop();
		} else {
			noop();
		}
	}

	protected function _c() {
		if($a) {
			noop();
		} else {
			noop();
		}
	}
}
?>
EOT;
	}

	private function addUnderscoresBeforeName($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$levelTouched = null;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_PUBLIC:
			case T_PRIVATE:
			case T_PROTECTED:
				$levelTouched = $id;
				$this->appendCode($text);
				break;

			case T_VARIABLE:
				if (null !== $levelTouched && $this->leftUsefulTokenIs([T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC])) {
					$text = str_replace('$_', '$', $text);
					$text = str_replace('$_', '$', $text);
					if (T_PROTECTED == $levelTouched) {
						$text = str_replace('$', '$_', $text);
					} elseif (T_PRIVATE == $levelTouched) {
						$text = str_replace('$', '$__', $text);
					}
				}
				$this->appendCode($text);
				$levelTouched = null;
				break;
			case T_STRING:
				if (
					null !== $levelTouched &&
					$this->leftUsefulTokenIs(T_FUNCTION) &&
					'_' != $text &&
					'__' != $text &&
					'__construct' != $text &&
					'__destruct' != $text &&
					'__call' != $text &&
					'__callStatic' != $text &&
					'__get' != $text &&
					'__set' != $text &&
					'__isset' != $text &&
					'__unset' != $text &&
					'__sleep' != $text &&
					'__wakeup' != $text &&
					'__toString' != $text &&
					'__invoke' != $text &&
					'__set_state' != $text &&
					'__clone' != $text &&
					' __debugInfo' != $text
				) {
					if (substr($text, 0, 2) == '__') {
						$text = substr($text, 2);
					}
					if (substr($text, 0, 1) == '_') {
						$text = substr($text, 1);
					}
					if (T_PROTECTED == $levelTouched) {
						$text = '_' . $text;
					} elseif (T_PRIVATE == $levelTouched) {
						$text = '__' . $text;
					}
				}
				$this->appendCode($text);
				$levelTouched = null;
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	private function mergeEqualsWithReference($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
						case ST_REFERENCE:
				if ($this->leftUsefulTokenIs(ST_EQUAL)) {
					$this->rtrimAndAppendCode($text . $this->getSpace());
					break;
				}

			default:
				$this->appendCode($text);
			}
		}
		return $this->code;
	}

	private function removeSpaceAfterCasts($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_ARRAY_CAST:
			case T_BOOL_CAST:
			case T_DOUBLE_CAST:
			case T_INT_CAST:
			case T_OBJECT_CAST:
			case T_STRING_CAST:
			case T_UNSET_CAST:
			case T_STRING:
			case T_VARIABLE:
			case ST_PARENTHESES_OPEN:
				if (
					$this->leftUsefulTokenIs([
						T_ARRAY_CAST,
						T_BOOL_CAST,
						T_DOUBLE_CAST,
						T_INT_CAST,
						T_OBJECT_CAST,
						T_STRING_CAST,
						T_UNSET_CAST,
					])
				) {
					$this->rtrimAndAppendCode($text);
					break;
				}
				$this->appendCode($text);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}

	private function resizeSpaces($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_COMMENT:
			case T_DOC_COMMENT:
				if (!$this->hasLnBefore() && $this->leftTokenIs(ST_CURLY_OPEN)) {
					$this->rtrimAndAppendCode($this->getSpace() . $text);
					break;
				} elseif ($this->rightUsefulTokenIs(T_CONSTANT_ENCAPSED_STRING)) {
					$this->appendCode($text . $this->getSpace());
					break;
				}
				$this->appendCode($text);
				break;
			case T_CLOSE_TAG:
				if (!$this->hasLnBefore()) {
					$this->rtrimAndAppendCode($this->getSpace() . $text);
					break;
				}
				$this->appendCode($text);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}
}

	
class ClassToSelf extends AdditionalPass {
	const PLACEHOLDER = 'self';

	public function candidate($source, $foundTokens) {
		if (
			isset($foundTokens[T_CLASS]) ||
			isset($foundTokens[T_INTERFACE]) ||
			isset($foundTokens[T_TRAIT])
		) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$tknsLen = sizeof($this->tkns);

		$touchedDoubleColon = false;
		for ($ptr = 0; $ptr < $tknsLen; ++$ptr) {
			$token = $this->tkns[$ptr];
			list($id) = $this->getToken($token);

			if (T_DOUBLE_COLON == $id) {
				$touchedDoubleColon = true;
			}
			if ($touchedDoubleColon && T_CLASS == $id) {
				$touchedDoubleColon = false;
				break;
			}
			if (
				T_CLASS == $id ||
				T_INTERFACE == $id ||
				T_TRAIT == $id
			) {
				$this->refWalkUsefulUntil($this->tkns, $ptr, T_STRING);
				list(, $name) = $this->getToken($this->tkns[$ptr]);

				$this->refWalkUsefulUntil($this->tkns, $ptr, ST_CURLY_OPEN);
				$start = $ptr;
				$this->refWalkCurlyBlock($this->tkns, $ptr);
				$end = ++$ptr;

				$this->convertToPlaceholder($name, $start, $end);
				break;
			}
		}

		return $this->render();
	}

	
	public function getDescription() {
		return '"self" is preferred within class, trait or interface.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
class A {
	const constant = 1;
	function b(){
		A::constant;
	}
}

// To
class A {
	const constant = 1;
	function b(){
		self::constant;
	}
}
?>
EOT;
	}

	private function convertToPlaceholder($name, $start, $end) {
		for ($i = $start; $i < $end; ++$i) {
			list($id, $text) = $this->getToken($this->tkns[$i]);

			if (T_FUNCTION == $id && $this->rightTokenSubsetIsAtIdx($this->tkns, $i, [ST_REFERENCE, ST_PARENTHESES_OPEN])) {
				$this->refWalkUsefulUntil($this->tkns, $i, ST_CURLY_OPEN);
				$this->refWalkCurlyBlock($this->tkns, $i);
				continue;
			}

			if (
				!(T_STRING == $id && strtolower($text) == strtolower($name)) ||
				$this->leftTokenSubsetIsAtIdx($this->tkns, $i, T_NS_SEPARATOR) ||
				$this->rightTokenSubsetIsAtIdx($this->tkns, $i, T_NS_SEPARATOR)
			) {
				continue;
			}

			if (
				$this->leftTokenSubsetIsAtIdx($this->tkns, $i, [T_INSTANCEOF, T_NEW]) ||
				$this->rightTokenSubsetIsAtIdx($this->tkns, $i, T_DOUBLE_COLON)
			) {
				$this->tkns[$i] = [T_STRING, self::PLACEHOLDER];
			}
		}
	}
}

	
final class ClassToStatic extends ClassToSelf {
	const PLACEHOLDER = 'static';

	
	public function getDescription() {
		return '"static" is preferred within class, trait or interface.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
class A {
	const constant = 1;
	function b(){
		A::constant;
	}
}

// To
class A {
	const constant = 1;
	function b(){
		static::constant;
	}
}
?>
EOT;
	}
}

	
final class ConvertOpenTagWithEcho extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_OPEN_TAG_WITH_ECHO])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			if (T_OPEN_TAG_WITH_ECHO == $id) {
				$text = '<?php echo ';
			}

			$this->appendCode($text);
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Convert from "<?=" to "<?php echo ".';
	}

	
	public function getExample() {
		return <<<'EOT'
<?="Hello World"?>

<?php echo "Hello World"?>
EOT;
	}
}

	
final class DocBlockToComment extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_DOC_COMMENT])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$this->useCache = true;

		$touchedOpenTag = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->tkns[$this->ptr] = [$id, $text];
			$this->cache = [];

			if (T_DOC_COMMENT != $id) {
				continue;
			}

			if (!$touchedOpenTag && $this->leftUsefulTokenIs(T_OPEN_TAG)) {
				$touchedOpenTag = true;
				continue;
			}

			if ($this->isStructuralElement()) {
				continue;
			}

			$commentTokenText = &$this->tkns[$this->ptr][1];

			if ($this->rightUsefulTokenIs(T_VARIABLE)) {
				$commentTokenText = $this->updateCommentAgainstVariable($commentTokenText);
				continue;
			}

			if ($this->rightUsefulTokenIs([T_FOREACH, T_LIST])) {
				$commentTokenText = $this->updateCommentAgainstParenthesesBlock($commentTokenText);
				continue;
			}

			if (null === $this->rightUsefulToken() || $this->rightUsefulTokenIs(ST_CURLY_CLOSE)) {
				$commentTokenText = $this->updateComment($commentTokenText);
				continue;
			}

			$commentTokenText = $this->updateComment($commentTokenText);
		}

		return $this->renderLight($this->tkns);
	}

	
	public function getDescription() {
		return 'Replace docblocks with regular comments when used in non structural elements.';
	}

	
	public function getExample() {
		return <<<'EOT'
EOT;
	}

	protected function walkAndNormalizeUntil($tknid) {
		while (list($index, $token) = each($this->tkns)) {
			$this->ptr = $index;
			$this->cache = [];
			if ($token[0] == $tknid) {
				$t = &$this->tkns[$this->ptr];
				$t = $this->getToken($token);
				return $t;
			}
		}
	}

	private function isStructuralElement() {
		return $this->rightUsefulTokenIs([
			T_PRIVATE, T_PROTECTED, T_PUBLIC,
			T_FUNCTION, T_ABSTRACT, T_CONST,
			T_NAMESPACE, T_REQUIRE, T_REQUIRE_ONCE,
			T_INCLUDE, T_INCLUDE_ONCE, T_FINAL,
			T_CLASS, T_INTERFACE, T_TRAIT, T_STATIC,
		]);
	}

	private function updateComment($commentTokenText) {
		return preg_replace('/\/\*\*/', '/*', $commentTokenText, 1);
	}

	private function updateCommentAgainstParenthesesBlock($commentTokenText) {
		$this->walkAndNormalizeUntil(ST_PARENTHESES_OPEN);
		$variables = $this->variableListFromParenthesesBlock($this->tkns, $this->ptr);

		$foundVar = false;
		foreach ($variables as $var) {
			if (false !== strpos($commentTokenText, $var)) {
				$foundVar = true;
				break;
			}
		}
		if (!$foundVar) {
			$commentTokenText = $this->updateComment($commentTokenText);
		}
		return $commentTokenText;
	}

	private function updateCommentAgainstVariable($commentTokenText) {
		list(, $nextText) = $this->rightUsefulToken();
		$this->ptr = $this->rightUsefulTokenIdx();
		$this->cache = [];
		if (!$this->rightUsefulTokenIs(ST_EQUAL) ||
			false === strpos($commentTokenText, $nextText)) {
			$commentTokenText = $this->updateComment($commentTokenText);
		}
		return $commentTokenText;
	}

	private function variableListFromParenthesesBlock($tkns, $ptr) {
		$sizeOfTkns = sizeof($tkns);
		$variableList = [];
		$count = 0;
		for ($i = $ptr; $i < $sizeOfTkns; ++$i) {
			$token = $tkns[$i];
			list($id, $text) = $this->getToken($token);

			if (T_VARIABLE == $id) {
				$variableList[] = $text;
			}
			if (ST_PARENTHESES_OPEN == $id) {
				++$count;
			}
			if (ST_PARENTHESES_CLOSE == $id) {
				--$count;
			}
			if (0 == $count) {
				break;
			}
		}
		return array_unique($variableList);
	}
}
	
final class DoubleToSingleQuote extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_CONSTANT_ENCAPSED_STRING])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			if ($this->hasDoubleQuote($id, $text)) {
				$text = $this->convertToSingleQuote($text);
			}

			$this->appendCode($text);
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Convert from double to single quotes.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
$a = "";

$a = '';
?>
EOT;
	}

	private function convertToSingleQuote($text) {
		$text[0] = '\'';
		$lastByte = strlen($text) - 1;
		$text[$lastByte] = '\'';
		$text = str_replace(['\$', '\"'], ['$', '"'], $text);
		return $text;
	}

	private function hasDoubleQuote($id, $text) {
		return (
			T_CONSTANT_ENCAPSED_STRING == $id &&
			'"' == $text[0] &&
			false === strpos($text, '\'') &&
			!preg_match('/(?<!\\\\)(?:\\\\{2})*\\\\(?!["$\\\\])/', $text)
		);
	}
}

	
final class EchoToPrint extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_ECHO])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		while (list($index, $token) = each($this->tkns)) {
			list($id) = $this->getToken($token);
			$this->ptr = $index;

			if (T_ECHO == $id) {
				$start = $index;
				$end = $this->walkUsefulRightUntil($this->tkns, $index, [ST_SEMI_COLON, T_CLOSE_TAG]);
				$convert = true;
				for ($i = $start; $i < $end; $i++) {
					$tkn = $this->tkns[$i];
					if (ST_PARENTHESES_OPEN === $tkn[0]) {
						$this->refWalkBlock($tkns, $ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
					} elseif (ST_BRACKET_OPEN === $tkn[0]) {
						$this->refWalkBlock($tkns, $ptr, ST_BRACKET_OPEN, ST_BRACKET_CLOSE);
					} elseif (ST_COMMA === $tkn[0]) {
						$convert = false;
						break;
					}
				}
				if ($convert) {
					$this->tkns[$start] = [T_PRINT, 'print'];
				}
			}
		}

		return $this->render();
	}

	
	public function getDescription() {
		return 'Convert from T_ECHO to print.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
echo 1;

print 2;
?>
EOT;
	}

}
	
final class EncapsulateNamespaces extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_NAMESPACE])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_NAMESPACE:
				if ($this->rightUsefulTokenIs(T_NS_SEPARATOR)) {
					break;
				}
				$this->appendCode($text);
				list($foundId, $foundText) = $this->printAndStopAt([ST_CURLY_OPEN, ST_SEMI_COLON]);
				if (ST_CURLY_OPEN == $foundId) {
					$this->appendCode($foundText);
					$this->printCurlyBlock();
				} elseif (ST_SEMI_COLON == $foundId) {
					$this->appendCode(ST_CURLY_OPEN);
					list($foundId, $foundText) = $this->printAndStopAt([T_NAMESPACE, T_CLOSE_TAG]);
					if (T_CLOSE_TAG == $foundId) {
						return $source;
					}
					$this->appendCode($this->getCrlf() . ST_CURLY_CLOSE . $this->getCrlf());
					prev($this->tkns);
					continue;
				}
				break;
			default:
				$this->appendCode($text);
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Encapsulate namespaces with curly braces';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
namespace NS1;
class A {
}
?>
to
<?php
namespace NS1 {
	class A {
	}
}
?>
EOT;
	}
}

	
final class GeneratePHPDoc extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_FUNCTION]) || isset($foundTokens[T_PUBLIC]) || isset($foundTokens[T_PROTECTED]) || isset($foundTokens[T_PRIVATE]) || isset($foundTokens[T_STATIC]) || isset($foundTokens[T_VAR])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$touchedVisibility = false;
		$touchedDocComment = false;
		$visibilityIdx = 0;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_DOC_COMMENT:
				$touchedDocComment = true;
				break;

			case T_CLASS:
				if ($touchedDocComment) {
					$touchedDocComment = false;
				}
				break;

			case T_FINAL:
			case T_ABSTRACT:
			case T_PUBLIC:
			case T_PROTECTED:
			case T_PRIVATE:
			case T_STATIC:
			case T_VAR:
				if (!$this->leftTokenIs([T_FINAL, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC, T_ABSTRACT, T_VAR])) {
					$touchedVisibility = true;
					$visibilityIdx = $this->ptr;
				}

				break;
			case T_VARIABLE:
				if (!$this->leftTokenIs([T_FINAL, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC, T_ABSTRACT, T_VAR])) {
					break;
				}
				if ($touchedDocComment) {
					$touchedDocComment = false;
					break;
				}
				if (!$touchedVisibility) {
					break;
				}
				$origIdx = $visibilityIdx;

				$type = 'mixed';
				if ($this->rightTokenIs([ST_EQUAL])) {
					$this->walkUntil(ST_EQUAL);
					if ($this->rightTokenIs([T_ARRAY, ST_BRACKET_OPEN])) {
						$type = 'array';
					} elseif ($this->rightTokenIs([T_LNUMBER])) {
						$type = 'int';
					} elseif ($this->rightTokenIs([T_DNUMBER])) {
						$type = 'float';
					} elseif ($this->rightTokenIs([T_CONSTANT_ENCAPSED_STRING])) {
						$type = 'string';
					}
				}

				$propToken = &$this->tkns[$origIdx];
				$propToken[1] = $this->renderPropertyDocBlock($type) . $propToken[1];
				$touchedVisibility = false;

				break;
			case T_FUNCTION:
				if ($touchedDocComment) {
					$touchedDocComment = false;
					break;
				}
				$origIdx = $visibilityIdx;
				if (!$touchedVisibility) {
					$origIdx = $this->ptr;
				}
				list($ntId) = $this->getToken($this->rightToken());
				if (T_STRING != $ntId) {
					$this->appendCode($text);
					break;
				}
				$this->walkUntil(ST_PARENTHESES_OPEN);
				$paramStack = [];
				$tmp = ['type' => '', 'name' => ''];
				$count = 1;
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;

					if (ST_PARENTHESES_OPEN == $id) {
						++$count;
					}
					if (ST_PARENTHESES_CLOSE == $id) {
						--$count;
					}
					if (0 == $count) {
						break;
					}
					if (T_STRING == $id || T_NS_SEPARATOR == $id) {
						$tmp['type'] .= $text;
						continue;
					}
					if (T_VARIABLE == $id) {
						if ($this->leftTokenIs([T_ARRAY]) || $this->rightTokenIs([ST_EQUAL]) && $this->walkUntil(ST_EQUAL) && $this->rightTokenIs([T_ARRAY, ST_BRACKET_OPEN])) {
							$tmp['type'] = 'array';
						}
						$tmp['name'] = $text;
						$paramStack[] = $tmp;
						$tmp = ['type' => '', 'name' => ''];
						continue;
					}
				}

				$returnStack = '';
				if (!$this->rightUsefulTokenIs(ST_SEMI_COLON)) {
					$this->walkUntil(ST_CURLY_OPEN);
					$count = 1;
					while (list($index, $token) = each($this->tkns)) {
						list($id, $text) = $this->getToken($token);
						$this->ptr = $index;

						if (ST_CURLY_OPEN == $id) {
							++$count;
						}
						if (ST_CURLY_CLOSE == $id) {
							--$count;
						}
						if (0 == $count) {
							break;
						}
						if (T_RETURN == $id) {
							if ($this->rightTokenIs([T_DNUMBER])) {
								$returnStack = 'float';
							} elseif ($this->rightTokenIs([T_LNUMBER])) {
								$returnStack = 'int';
							} elseif ($this->rightTokenIs([T_VARIABLE])) {
								$returnStack = 'mixed';
							} elseif ($this->rightTokenIs([ST_SEMI_COLON])) {
								$returnStack = 'null';
							}
						}
					}
				}

				$funcToken = &$this->tkns[$origIdx];
				$funcToken[1] = $this->renderFunctionDocBlock($paramStack, $returnStack) . $funcToken[1];
				$touchedVisibility = false;
			}
		}

		return implode('', array_map(function ($token) {
			list(, $text) = $this->getToken($token);
			return $text;
		}, $this->tkns));
	}

	
	public function getDescription() {
		return 'Automatically generates PHPDoc blocks';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
class A {
	function a(Someclass $a) {
		return 1;
	}
}
?>
to
<?php
class A {
	/**
	 * @param Someclass $a
	 * @return int
	 */
	function a(Someclass $a) {
		return 1;
	}
}
?>
EOT;
	}

	private function renderFunctionDocBlock(array $paramStack, $returnStack) {
		if (empty($paramStack) && empty($returnStack)) {
			return '';
		}
		$str = ' /**' . $this->newLine;
		foreach ($paramStack as $param) {
			$str .= rtrim(' * @param ' . $param['type']) . ' ' . $param['name'] . $this->newLine;
		}
		if (!empty($returnStack)) {
			$str .= ' * @return ' . $returnStack . $this->newLine;
		}
		$str .= ' */' . $this->newLine;
		return $str;
	}

	private function renderPropertyDocBlock($type) {
		return sprintf(' /**%s* @var %s%s */%s',
			$this->newLine,
			$type,
			$this->newLine,
			$this->newLine
		);
	}
}

	
final class IndentTernaryConditions extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[ST_QUESTION])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_COLON:
			case ST_QUESTION:
				if ($this->hasLnBefore()) {
					$this->appendCode($this->getIndent(+1));
				}
				$this->appendCode($text);
				break;
			default:
				$this->appendCode($text);
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Applies indentation to ternary conditions.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
$a = ($b)
? $c
: $d
;
?>
to
<?php
$a = ($b)
	? $c
	: $d
;
?>
EOT;
	}
}
	
final class JoinToImplode extends AliasToMaster {
	protected static $aliasList = [
		'join' => 'implode',
	];

	
	public function getDescription() {
		return 'Replace implode() alias (join() -> implode()).';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
$a = join(',', $arr);

$a = implode(',', $arr);
?>
EOT;
	}
}

	
final class LeftWordWrap extends AdditionalPass {
	const PLACEHOLDER_WORDWRAP = "\x2 WORDWRAP \x3";

	private static $length = 80;

	private static $tabSizeInSpace = 8;

	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		$currentLineLength = 0;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			$originalText = $text;
			if (T_WHITESPACE == $id) {
				$text = str_replace(
					$this->indentChar,
					str_repeat(' ', self::$tabSizeInSpace),
					$text
				);
			}
			$textLen = strlen($text);

			$currentLineLength += $textLen;

			if ($this->hasLn($text)) {
				$currentLineLength = $textLen - strrpos($text, $this->newLine);
			}

			if ($currentLineLength > self::$length) {
				$currentLineLength = $textLen - strrpos($text, $this->newLine);
				$this->code = str_replace(self::PLACEHOLDER_WORDWRAP, $this->newLine, $this->code);
			}

			if (T_OBJECT_OPERATOR == $id || T_WHITESPACE == $id) {
				$this->code = str_replace(self::PLACEHOLDER_WORDWRAP, '', $this->code);
				$this->appendCode(self::PLACEHOLDER_WORDWRAP);
			}
			$this->appendCode($originalText);
		}

		$this->code = str_replace(self::PLACEHOLDER_WORDWRAP, '', $this->code);
		return $this->code;
	}

	
	public function getDescription() {
		return 'Word wrap at 80 columns - left justify.';
	}

	
	public function getExample() {
		return '';
	}
}
	
final class LongArray extends AdditionalPass {
	const EMPTY_ARRAY = 'ST_EMPTY_ARRAY';

	const ST_SHORT_ARRAY_OPEN = 'SHORT_ARRAY_OPEN';

	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);

		$contextStack = [];
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_BRACKET_OPEN:
				$found = ST_BRACKET_OPEN;
				if ($this->isShortArray()) {
					$found = self::ST_SHORT_ARRAY_OPEN;
					$id = self::ST_SHORT_ARRAY_OPEN;
					$text = 'array(';
				}
				$contextStack[] = $found;
				break;
			case ST_BRACKET_CLOSE:
				if (isset($contextStack[0]) && !$this->leftTokenIs(ST_BRACKET_OPEN)) {
					if (self::ST_SHORT_ARRAY_OPEN == end($contextStack)) {
						$id = ')';
						$text = ')';
					}
					array_pop($contextStack);
				}
				break;
			case T_STRING:
				if ($this->rightTokenIs(ST_PARENTHESES_OPEN)) {
					$contextStack[] = T_STRING;
				}
				break;
			case T_ARRAY:
				if ($this->rightTokenIs(ST_PARENTHESES_OPEN)) {
					$contextStack[] = T_ARRAY;
				}
				break;
			case ST_PARENTHESES_OPEN:
				if (isset($contextStack[0]) && T_ARRAY == end($contextStack) && $this->rightTokenIs(ST_PARENTHESES_CLOSE)) {
					$contextStack[sizeof($contextStack) - 1] = self::EMPTY_ARRAY;
				} elseif (!$this->leftTokenIs([T_ARRAY, T_STRING])) {
					$contextStack[] = ST_PARENTHESES_OPEN;
				}
				break;
			case ST_PARENTHESES_CLOSE:
				if (isset($contextStack[0])) {
					array_pop($contextStack);
				}
				break;
			}
			$this->tkns[$this->ptr] = [$id, $text];
		}

		return $this->renderLight();
	}

	
	public function getDescription() {
		return 'Convert short to long arrays.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
$a = [$a, $b];

// To
$b = array($b, $c);
?>
EOT;
	}
}
	

final class MergeElseIf extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_ELSE]) || isset($foundTokens[T_ELSEIF])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_IF:
				if ($this->leftTokenIs([T_ELSE]) && !$this->leftTokenIs([T_OPEN_TAG, T_OPEN_TAG_WITH_ECHO])) {
					$this->rtrimAndAppendCode($text);
					break;
				}
				$this->appendCode($text);
				break;
			case T_ELSEIF:
				$this->appendCode('elseif');
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Merge if with else.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
if($a){

} else if($b) {

}

if($a){

} elseif($b) {

}
?>
EOT;
	}
}

	
final class SplitElseIf extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_ELSE]) || isset($foundTokens[T_ELSEIF])) {
			return true;
		}
		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_ELSEIF:
				$this->appendCode('else if');
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}

	
	public function getDescription() {
		return 'Merge if with else.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
if($a){
} elseif($b) {
}
if($a){
} else if($b) {
}
?>
EOT;
	}
}
	
final class MergeNamespaceWithOpenTag extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_NAMESPACE])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_NAMESPACE:
				if ($this->leftTokenIs(T_OPEN_TAG) && !$this->rightUsefulTokenIs(T_NS_SEPARATOR)) {
					$this->rtrimAndAppendCode($this->newLine . $text);
					break 2;
				}

			default:
				$this->appendCode($text);
				break;
			}
		}
		while (list(, $token) = each($this->tkns)) {
			list(, $text) = $this->getToken($token);
			$this->appendCode($text);
		}
		return $this->code;
	}

	
	public function getDescription() {
		return 'Ensure there is no more than one linebreak before namespace';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php

namespace A;
?>
to
<?php
namespace A;
?>
EOT;
	}
}

	
final class MildAutoPreincrement extends AutoPreincrement {
	
	public function getDescription() {
		return 'Automatically convert postincrement to preincrement. (Deprecated pass. Use AutoPreincrement instead).';
	}
}
	
final class NewLineBeforeReturn extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_RETURN])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_RETURN:
				if (!$this->leftUsefulTokenIs([ST_CURLY_OPEN, ST_COLON])) {
					$this->rtrimAndAppendCode($this->newLine . $this->newLine . $text);
					break;
				}
			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Add an empty line before T_RETURN.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
function a(){
	$a = 1;
	return $a;
}

// To
function a(){
	$a = 1;

	return $a;
}
?>
EOT;
	}
}

	
final class NoSpaceAfterPHPDocBlocks extends FormatterPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_DOC_COMMENT])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_WHITESPACE:
				if ($this->hasLn($text) && $this->leftTokenIs(T_DOC_COMMENT)) {
					$text = substr(strrchr($text, 10), 0);
					$this->appendCode($text);
					break;
				}
			default:
				$this->appendCode($text);
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Remove empty lines after PHPDoc blocks.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
/**
 * @param int $myInt
 */

function a($myInt){
}

/**
 * @param int $myInt
 */
function a($myInt){
}
?>
EOT;
	}
}
	
class OrganizeClass extends AdditionalPass {
	const METHOD_REPLACEMENT_PLACEHOLDER = "\x2 METHODPLACEHOLDER \x3";

	const OPENER_PLACEHOLDER = "<?php /*\x2 ORDERMETHOD \x3*/";

	public function candidate($source, $foundTokens) {
		if (
			isset($foundTokens[T_CLASS])
			|| isset($foundTokens[T_TRAIT])
			|| isset($foundTokens[T_INTERFACE])
		) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);

				$return = '';
		$classBlock = '';

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_CLASS:
			case T_INTERFACE:
			case T_TRAIT:
				if ($this->leftUsefulTokenIs(T_DOUBLE_COLON)) {
					$this->appendCode($text);
					break;
				}
				$return = $text;
				$return .= $this->walkAndAccumulateUntil($this->tkns, ST_CURLY_OPEN);
				$classBlock = $this->walkAndAccumulateCurlyBlock($this->tkns);
				$return .= str_replace(
					self::OPENER_PLACEHOLDER,
					'',
					static::orderMethods(self::OPENER_PLACEHOLDER . $classBlock)
				);
				$this->appendCode($return);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Organize class, interface and trait structure.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
class A {
	public function d(){}
	protected function b(){}
	private $a = "";
	private function c(){}
	public function a(){}
	public $b = "";
	const B = 0;
	const A = 0;
}

// To
class A {
	const A = 0;

	const B = 0;

	public $b = "";

	private $a = "";

	public function a(){}

	public function d(){}

	protected function b(){}

	private function c(){}
}
?>
EOT;
	}

	public function orderMethods($source) {
		$tokens = token_get_all($source);

						$attributeList = [];
		$commentStack = [];
		$constList = [];
		$docCommentStack = '';
		$functionList = [];
		$touchedDocComment = false;
		$useStack = '';

		while (list($index, $token) = each($tokens)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_USE:
				if ($touchedDocComment) {
					$touchedDocComment = false;
					$useStack .= $docCommentStack;
				}
				$useStack .= $text;
				list($foundText, $foundId) = $this->walkAndAccumulateUntilAny($tokens, [ST_CURLY_OPEN, ST_SEMI_COLON]);
				$useStack .= $foundText;
				if (ST_CURLY_OPEN == $foundId) {
					$useStack .= $this->walkAndAccumulateCurlyBlock($tokens);
				}
				$useStack .= $this->newLine;
				break;

			case T_COMMENT:
				if (strpos($text, "\x2") === false) {
					if ($this->rightTokenSubsetIsAtIdx($tokens, $this->ptr, [
						T_ABSTRACT,
						T_FUNCTION,
						T_PRIVATE,
						T_PROTECTED,
						T_PUBLIC,
						T_STATIC,
					], $this->ignoreFutileTokens)) {
						if (!$touchedDocComment) {
							$touchedDocComment = true;
							$docCommentStack = ' ';
						}
						$docCommentStack .= $text;
						break;
					}
					$commentStack[] = $text;
				}
				break;

			case T_DOC_COMMENT:
				if (!$touchedDocComment) {
					$touchedDocComment = true;
					$docCommentStack = ' ';
				}
				$docCommentStack .= $text;
				break;

			case T_CONST:
				$stack = '';
				if ($touchedDocComment) {
					$touchedDocComment = false;
					$stack .= $docCommentStack;
				}
				$stack .= $text;
				$constName = $this->walkAndAccumulateUntil($tokens, T_STRING);
				$stack .= $constName;
				$stack .= $this->walkAndAccumulateUntil($tokens, ST_SEMI_COLON);
				$constList[$constName] = $stack;
				break;

			case T_ABSTRACT:
			case T_FUNCTION:
			case T_PRIVATE:
			case T_PROTECTED:
			case T_PUBLIC:
			case T_STATIC:
			case T_VARIABLE:
			case T_FINAL:
				$stack = '';
				if ($touchedDocComment) {
					$touchedDocComment = false;
					$stack .= $docCommentStack;
				}
				$touchedMethod = false;
				$touchedAttribute = false;
				$functionName = '';
				$attributeName = '';
				$visibilityLevel = 0;

				$searchFor = [
					T_ABSTRACT,
					T_FUNCTION,
					T_PRIVATE,
					T_PROTECTED,
					T_PUBLIC,
					T_STATIC,
					T_STRING,
					T_VARIABLE,
					T_FINAL,
				];
				prev($tokens);

				do {
					list($foundText, $foundId) = $this->walkAndAccumulateUntilAny($tokens, $searchFor);
					if (T_PROTECTED == $foundId) {
						$visibilityLevel = 1;
					} elseif (T_PRIVATE == $foundId) {
						$visibilityLevel = 2;
					} elseif (T_FUNCTION == $foundId) {
						$touchedMethod = true;
					} elseif (T_VARIABLE == $foundId) {
						$touchedAttribute = true;
						$attributeName = $foundText;
					} elseif (T_STRING == $foundId && $touchedMethod) {
						$functionName = $foundText;
					}
					$stack .= $foundText;
				} while (empty($functionName) && empty($attributeName));

				if ($touchedMethod) {
					list($foundText, $foundId) = $this->walkAndAccumulateUntilAny($tokens, [ST_CURLY_OPEN, ST_SEMI_COLON]);
					$stack .= $foundText;
					if (ST_CURLY_OPEN == $foundId) {
						$stack .= $this->walkAndAccumulateCurlyBlock($tokens);
					}
					$functionList[$visibilityLevel . ':' . $functionName] = $stack;
				} elseif ($touchedAttribute) {
					$stack .= $this->walkAndAccumulateUntil($tokens, ST_SEMI_COLON);
					$attributeList[$visibilityLevel . ':' . $attributeName] = $stack;
				}
				break;

			default:
				if ($touchedDocComment) {
					$docCommentStack .= $text;
					break;
				}
				break;
			}
		}
		ksort($constList);
		ksort($attributeList);
		ksort($functionList);

		$final = '';
		if (!empty($useStack)) {
			$final .= $useStack . $this->newLine;
		}

		foreach ($commentStack as $text) {
			$final .= ' ' . $text;
			if ($this->substrCountTrailing($text, "\n") === 0) {
				$final .= $this->newLine;
			}
		}

		$final .= $this->newLine;
		foreach ($constList as $text) {
			$final .= $text . $this->newLine . $this->newLine;
		}

		$final .= $this->newLine;
		foreach ($attributeList as $text) {
			$final .= $text . $this->newLine . $this->newLine;
		}

		$final .= $this->newLine;
		foreach ($functionList as $text) {
			$final .= $text . $this->newLine . $this->newLine;
		}

		return $this->newLine . ' ' . trim($final) . $this->newLine . ST_CURLY_CLOSE;
	}
}

	
class OrderAndRemoveUseClauses extends AdditionalPass {
	const BLANK_LINE_AFTER_USE_BLOCK = true;

	const OPENER_PLACEHOLDER = "<?php /*\x2 ORDERBY \x3*/";

	const REMOVE_UNUSED = true;

	const SPLIT_COMMA = true;

	const STRIP_BLANK_LINES = true;

	const TRAIT_BLOCK_OPEN = 'TRAIT_BLOCK_OPEN';

	private $sortFunction = null;

	public function __construct(callable $sortFunction = null) {
		$this->sortFunction = $sortFunction;
		if (null == $sortFunction) {
			$this->sortFunction = function ($useStack) {
				natcasesort($useStack);
				return $useStack;
			};
		}
	}

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_USE])) {
			return true;
		}

		return false;
	}

	public function format($source = '') {
		$source = $this->sortWithinNamespaces($source);

		return $source;
	}

	
	public function getDescription() {
		return 'Order use block and remove unused imports.';
	}

	
	public function getExample() {
		return <<<'EOT'
// From
use C;
use B;

class D {
	function f() {
		new B();
	}
}


// To
use B;

class D {
	function f() {
		new B();
	}
}
EOT;
	}

	protected function sortUseClauses($source, $splitComma, $removeUnused, $stripBlankLines, $blanklineAfterUseBlock) {
		$tokens = token_get_all($source);

										$newTokens = [];
		$useStack = [0 => []];
		$foundComma = false;
		$groupCount = 0;
		$touchedDoubleColon = false;
		$stopTokens = [ST_SEMI_COLON, ST_CURLY_OPEN];
		if ($splitComma) {
			$stopTokens[] = ST_COMMA;
		}
		$aliasList = [];
		$aliasCount = [];
		$unusedImport = [];

		while (list($index, $token) = each($tokens)) {
			list($id, $text) = $this->getToken($token);

			if (T_DOUBLE_COLON == $id) {
				$newTokens[] = $token;
				$touchedDoubleColon = true;
				continue;
			}

			if (
				(T_TRAIT === $id || T_CLASS === $id) &&
				!$touchedDoubleColon
			) {
				$newTokens[] = $token;
				while (list(, $token) = each($tokens)) {
					list($id, $text) = $this->getToken($token);
					$newTokens[] = $token;
				}
				break;
			}

			$touchedDoubleColon = false;

			if (
				!$stripBlankLines &&
				(
					T_WHITESPACE === $id
					||
					(T_COMMENT === $id && '/' == $text[2])
				) && substr_count($text, $this->newLine) >= 2
			) {
				++$groupCount;
				$useStack[$groupCount] = [];
				$newTokens[] = $token;
				continue;
			}

			if (T_USE === $id && $this->rightTokenSubsetIsAtIdx($tokens, $index, [ST_PARENTHESES_OPEN], $this->ignoreFutileTokens)) {
				$newTokens[] = $token;
				continue;
			}

			if (T_USE === $id || $foundComma) {
				list($useTokens, $foundToken) = $this->walkAndAccumulateStopAtAny($tokens, $stopTokens);

				if (ST_SEMI_COLON == $foundToken) {
					$useStack[$groupCount][] = 'use ' . ltrim($useTokens) . ';';
					$newTokens[] = new SurrogateToken();
					next($tokens);

					$foundComma = false;
				} elseif (ST_COMMA == $foundToken) {
					$useStack[$groupCount][] = 'use ' . ltrim($useTokens) . ';';
					$newTokens[] = new SurrogateToken();
					$newTokens[] = [T_WHITESPACE, $this->newLine . $this->newLine];

					$foundComma = true;
				} elseif (ST_CURLY_OPEN == $foundToken) {
					next($tokens);
					$base = $this->newLine . 'use ' . ltrim($useTokens);

					do {
						list($groupText, $groupFoundToken) = $this->walkAndAccumulateStopAtAny($tokens, [ST_COMMA, ST_CURLY_CLOSE]);
						$useStack[$groupCount][] = $base . trim($groupText) . ';';
						$newTokens[] = new SurrogateToken();
						next($tokens);
					} while (ST_COMMA == $groupFoundToken);
					$foundComma = false;
					$this->walkAndAccumulateUntil($tokens, ST_SEMI_COLON);
				}
				continue;
			}

			$newTokens[] = $token;
		}

		if (empty($useStack[0])) {
			return $source;
		}
		foreach ($useStack as $group => $useClauses) {
			$useStack[$group] = call_user_func($this->sortFunction, $useClauses);
		}
		$useStack = call_user_func_array('array_merge', $useStack);

		foreach ($useStack as $use) {
			$alias = $this->calculateAlias($use);
			$alias = str_replace(ST_SEMI_COLON, '', strtolower($alias));
			$aliasList[$alias] = trim(strtolower($use));
			$aliasCount[$alias] = 0;
		}

		$return = '';
		foreach ($newTokens as $idx => $token) {
			if ($token instanceof SurrogateToken) {
				$return .= array_shift($useStack);
				if ($blanklineAfterUseBlock && !isset($useStack[0])) {
					$return .= $this->newLine;
				}
				continue;
			} elseif (T_WHITESPACE == $token[0] && isset($newTokens[$idx - 1], $newTokens[$idx + 1]) && $newTokens[$idx - 1] instanceof SurrogateToken && $newTokens[$idx + 1] instanceof SurrogateToken) {
				if ($stripBlankLines) {
					$return .= $this->newLine;
					continue;
				}

				$return .= $token[1];
				continue;
			}
			list($id, $text) = $this->getToken($token);
			$lowerText = strtolower($text);
			if (T_STRING === $id && isset($aliasList[$lowerText])) {
				++$aliasCount[$lowerText];
			} elseif (T_DOC_COMMENT === $id) {
				foreach ($aliasList as $alias => $use) {
					if (false !== stripos($text, $alias)) {
						++$aliasCount[$alias];
					}
				}
			}
			$return .= $text;
		}

		if ($removeUnused) {
			$unusedImport = array_keys(
				array_filter(
					$aliasCount, function ($v) {
						return 0 === $v;
					}
				)
			);
		}

		foreach ($unusedImport as $v) {
			$return = str_ireplace($aliasList[$v] . $this->newLine, null, $return);
		}

		return $return;
	}

	private function calculateAlias($use) {
		if (false !== stripos($use, ' as ')) {
			return substr(stristr($use, ' as '), strlen(' as '), -1);
		}
		return basename(str_replace('\\', '/', trim(substr($use, strlen('use'), -1))));
	}

	private function sortWithinNamespaces($source) {
		$classRelatedCount = 0;
		$namespaceCount = 0;
		$tokens = token_get_all($source);
		$touchedTUse = false;
		while (list(, $token) = each($tokens)) {
			list($id, $text) = $this->getToken($token);
			if (T_USE === $id) {
				$touchedTUse = true;
			}
			if (
				T_CLASS == $id ||
				T_INTERFACE == $id
			) {
				++$classRelatedCount;
			}
			if (T_NAMESPACE == $id && !$this->rightUsefulTokenIs(T_NS_SEPARATOR)) {
				++$namespaceCount;
			}
		}

		if ($namespaceCount <= 1 && $touchedTUse) {
			return $this->sortUseClauses($source, self::SPLIT_COMMA, self::REMOVE_UNUSED, self::STRIP_BLANK_LINES, self::BLANK_LINE_AFTER_USE_BLOCK && $classRelatedCount > 0);
		}

		$return = '';
		reset($tokens);
		while (list($index, $token) = each($tokens)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_NAMESPACE:
				$return .= $text;
				while (list($index, $token) = each($tokens)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					$return .= $text;
					if (ST_CURLY_OPEN == $id || ST_SEMI_COLON == $id) {
						break;
					}
				}
				$namespaceBlock = '';
				if (ST_CURLY_OPEN === $id) {
					$curlyCount = 1;
					while (list($index, $token) = each($tokens)) {
						list($id, $text) = $this->getToken($token);
						$this->ptr = $index;
						$namespaceBlock .= $text;

						if (ST_CURLY_OPEN == $id) {
							++$curlyCount;
						} elseif (ST_CURLY_CLOSE == $id) {
							--$curlyCount;
						}

						if (0 == $curlyCount) {
							break;
						}
					}
				} elseif (ST_SEMI_COLON === $id) {
					while (list($index, $token) = each($tokens)) {
						list($id, $text) = $this->getToken($token);
						$this->ptr = $index;

						if (T_NAMESPACE == $id && !$this->rightUsefulTokenIs(T_NS_SEPARATOR)) {
							prev($tokens);
							break;
						}

						$namespaceBlock .= $text;
					}
				}

				$return .= str_replace(
					self::OPENER_PLACEHOLDER,
					'',
					$this->sortUseClauses(self::OPENER_PLACEHOLDER . $namespaceBlock, self::SPLIT_COMMA, self::REMOVE_UNUSED, self::STRIP_BLANK_LINES, self::BLANK_LINE_AFTER_USE_BLOCK)
				);

				break;
			default:
				$return .= $text;
			}
		}

		return $return;
	}
}

	
final class OnlyOrderUseClauses extends OrderAndRemoveUseClauses {
	
	public function getDescription() {
		return 'Order use block - do not remove unused imports.';
	}

	
	public function getExample() {
		return <<<'EOT'
// From
use C;
use B;

class D {
	function f() {
		new B();
	}
}


// To
use B;
use C;

class D {
	function f() {
		new B();
	}
}
EOT;
	}

	protected function sortUseClauses($source, $splitComma, $removeUnused, $stripBlankLines, $blanklineAfterUseBlock) {
		$removeUnused = false;
		return parent::sortUseClauses($source, $splitComma, $removeUnused, $stripBlankLines, $blanklineAfterUseBlock);
	}
}

	
final class OrderMethod extends OrganizeClass {
}

	
final class OrderMethodAndVisibility extends OrganizeClass {
}

	class PHPDocTypesToFunctionTypehint extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_FUNCTION])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_FUNCTION:
				$this->appendCode($text);
				if (!$this->rightUsefulTokenIs(T_STRING)) {
					continue;
				}
				if (!$this->leftTokenIs(T_DOC_COMMENT)) {
					continue;
				}

				$foundParams = [];
				$foundReturn = '';
				list(, $docBlock) = $this->leftToken();
				$words = explode(' ', $docBlock);
				while (list(, $word) = each($words)) {
					$word = trim(strtolower($word));
					switch ($word) {
					case '@param':
						$foundType = '';
						$foundName = '';
						while (list(, $word) = each($words)) {
							$word = trim(strtolower($word));
							if ('$' == $word[0]) {
								$foundName = $word;
								break;
							} else {
								$foundType = $word;
							}
						}
						$foundParams[$foundName] = $foundType;
					case '@return':
						while (list(, $word) = each($words)) {
							$word = trim(strtolower($word));
							$foundReturn = $word;
							break;
						}
					}
				}
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					if (ST_CURLY_OPEN == $id && '' != $foundReturn) {
						$text = ':' . $foundReturn . ' ' . $text;
						$this->appendCode($text);
						break;
					}
					if (T_VARIABLE == $id && isset($foundParams[$text])) {
						$text = $foundParams[$text] . ' ' . $text;
					}
					$this->appendCode($text);
				}
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Read variable types from PHPDoc blocks and add them in function signatures.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From:
/**
 * @param int $a
 * @param int $b
 * @return int
 */
function abc($a = 10, $b = 20, $c) {

}

// To:
/**
 * @param int $a
 * @param int $b
 * @return int
 */
function abc(int $a = 10, int $b = 20, $c): int {

}
?>
EOT;
	}
}
	
final class PrettyPrintDocBlocks extends AdditionalPass {
	const EMPTY_LINE = "\x2 EMPTYLINE \x3";

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_DOC_COMMENT])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			if (T_DOC_COMMENT == $id) {
				$text = $this->prettify($text);
			}
			$this->appendCode($text);
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Prettify Doc Blocks';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
/**
 * some description.
 * @param array $b
 * @param LongTypeName $c
 */
function A(array $b, LongTypeName $c) {
}
?>

to
<?php
/**
 * some description.
 * @param array        $b
 * @param LongTypeName $c
 */
function A(array $b, LongTypeName $c) {
}
?>
EOT;
	}

	private function isUTF8($usStr) {
		return (utf8_encode(utf8_decode($usStr)) == $usStr);
	}

	private function prettify($docBlock) {
		$isUTF8 = $this->isUTF8($docBlock);

		if ($isUTF8) {
			$docBlock = utf8_decode($docBlock);
		}

		$groups = [
			'@deprecated' => 1,
			'@link' => 1,
			'@see' => 1,
			'@since' => 1,

			'@author' => 2,
			'@copyright' => 2,
			'@license' => 2,

			'@package' => 3,
			'@subpackage' => 3,

			'@param' => 4,
			'@throws' => 4,
			'@return' => 4,
		];
		$weights = [
			'@package' => 1,
			'@subpackage' => 2,
			'@author' => 3,
			'@copyright' => 4,
			'@license' => 5,
			'@deprecated' => 6,
			'@link' => 7,
			'@see' => 8,
			'@since' => 9,
			'@param' => 10,
			'@throws' => 11,
			'@return' => 12,
		];
		$weightsLen = [
			'@package' => strlen('@package'),
			'@subpackage' => strlen('@subpackage'),
			'@author' => strlen('@author'),
			'@copyright' => strlen('@copyright'),
			'@license' => strlen('@license'),
			'@deprecated' => strlen('@deprecated'),
			'@link' => strlen('@link'),
			'@see' => strlen('@see'),
			'@since' => strlen('@since'),
			'@param' => strlen('@param'),
			'@throws' => strlen('@throws'),
			'@return' => strlen('@return'),
		];

				$docBlock = trim(str_replace(['/**', '*/'], '', $docBlock));
		$lines = explode($this->newLine, $docBlock);
		foreach ($lines as $idx => $v) {
			$v = ltrim($v);
			if ('* ' === substr($v, 0, 2)) {
				$v = substr($v, 2);
			}
			if ('*' === substr($v, 0, 1)) {
				$v = substr($v, 1);
			}
			$lines[$idx] = $v . ':' . $idx;
		}

				usort($lines, function ($a, $b) use ($weights, $weightsLen) {
			$weightA = 0;
			foreach ($weights as $pattern => $weight) {
				if (strtolower(substr(ltrim($a), 0, $weightsLen[$pattern])) == $pattern) {
					$weightA = $weight;
					break;
				}
			}

			$weightB = 0;
			foreach ($weights as $pattern => $weight) {
				if (strtolower(substr(ltrim($b), 0, $weightsLen[$pattern])) == $pattern) {
					$weightB = $weight;
					break;
				}
			}

			if ($weightA == $weightB) {
				$weightA = substr(strrchr($a, ':'), 1);
				$weightB = substr(strrchr($b, ':'), 1);
			}
			return $weightA - $weightB;
		});

		$seqdetect = 0;
				reset($lines);
		while (list($idx, $line) = each($lines)) {
			$weight = substr(strrchr($line, ':'), 1);
			$line = substr($line, 0, -1 * (strlen($line) - strrpos($line, ':')));
			if ($weight != $seqdetect) {
				prev($lines);
				break;
			}

			++$seqdetect;
		}
		while (list($idx, $line) = each($lines)) {
			$weight = substr(strrchr($line, ':'), 1);
			$line = substr($line, 0, -1 * (strlen($line) - strrpos($line, ':')));
			if (empty($line)) {
				unset($lines[$idx]);
			}
		}

				$patterns = [
			'@param' => strlen('@param'),
			'@throws' => strlen('@throws'),
			'@return' => strlen('@return'),
			'@var' => strlen('@var'),
			'@type' => strlen('@type'),
		];
		$patternsColumns = [
			'@param' => 4,
			'@throws' => 2,
			'@return' => 2,
			'@var' => 4,
			'@type' => 4,
		];
		$maxColumn = [];

		foreach ($lines as $idx => $line) {
			foreach ($patterns as $pattern => $len) {
				if (strtolower(substr(ltrim($line), 0, $len)) == $pattern) {
					$words = explode(' ', $line);
					$i = 0;
					foreach ($words as $word) {
						if (!trim($word)) {
							continue;
						}
						$maxColumn[$i] = isset($maxColumn[$i]) ? max($maxColumn[$i], strlen($word)) : strlen($word);
						if (2 == $i) {
							break;
						}
						++$i;
					}
				}
			}
		}

		foreach ($lines as $idx => $line) {
			foreach ($patterns as $pattern => $len) {
				if (strtolower(substr(ltrim($line), 0, $len)) == $pattern) {
					$words = explode(' ', $line);
					$currentLine = '';
					$pad = 0;
					$columnCount = 0;
					$maxColumnCount = $patternsColumns[$pattern];
					foreach ($maxColumn as $rightMost) {
						while ((list(, $word) = each($words))) {
							if (trim($word)) {
								break;
							}
						}

						$currentLine .= $word;
						$pad += $rightMost + 1;
						$currentLine = str_pad($currentLine, $pad);
						++$columnCount;
						if ($columnCount == $maxColumnCount) {
							break;
						}
					}
					while ((list(, $word) = each($words))) {
						if (!trim($word)) {
							continue;
						}
						$currentLine .= $word . ' ';
					}
					$lines[$idx] = rtrim($currentLine);
				}
			}
		}

				$lastGroup = null;
		foreach ($lines as $idx => $line) {
			if ('@' == substr(ltrim($line), 0, 1)) {
				$tag = strtolower(substr($line, 0, strpos($line, ' ')));
				if (isset($groups[$tag]) && $groups[$tag] != $lastGroup) {
					$lines[$idx] = (null !== $lastGroup ? $this->newLine . ' * ' : '') . $line;
					$lastGroup = $groups[$tag];
				}
			}
		}

				$docBlock = '/**' . $this->newLine;
		foreach ($lines as $line) {
			$docBlock .= ' * ' . substr(rtrim($line), 0, strrpos($line, ':')) . $this->newLine;
		}
		$docBlock .= ' */';

		if ($isUTF8) {
			$docBlock = utf8_encode($docBlock);
		}

		return $docBlock;
	}
}
	
final class PSR2EmptyFunction extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_FUNCTION])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_FUNCTION:
				$this->appendCode($text);
				$this->printUntil(ST_PARENTHESES_OPEN);
				$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				$this->printAndStopAt(ST_CURLY_OPEN);
				if ($this->rightTokenIs(ST_CURLY_CLOSE)) {
					$this->rtrimAndAppendCode($this->getSpace() . ST_CURLY_OPEN);
					$this->printAndStopAt(ST_CURLY_CLOSE);
					$this->rtrimAndAppendCode(ST_CURLY_CLOSE);
					break;
				}
				prev($this->tkns);
				break;
			default:
				$this->appendCode($text);
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Merges in the same line of function header the body of empty functions.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// PSR2 Mode - From
function a()
{}

// To
function a() {}
?>
EOT;
	}
}

	
final class PSR2MultilineFunctionParams extends AdditionalPass {
	const LINE_BREAK = "\x2 LN \x3";

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_FUNCTION])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_FUNCTION:
				$this->appendCode($text);
				$this->printUntil(ST_PARENTHESES_OPEN);
				$this->appendCode(self::LINE_BREAK);
				$touchedComma = false;
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;

					if (ST_PARENTHESES_OPEN === $id) {
						$this->appendCode($text);
						$this->printUntil(ST_PARENTHESES_CLOSE);
						continue;
					} elseif (ST_BRACKET_OPEN === $id) {
						$this->appendCode($text);
						$this->printUntil(ST_BRACKET_CLOSE);
						continue;
					} elseif (ST_PARENTHESES_CLOSE === $id) {
						$this->appendCode(self::LINE_BREAK);
						$this->appendCode($text);
						break;
					}
					$this->appendCode($text);

					if (ST_COMMA === $id && !$this->hasLnAfter()) {
						$touchedComma = true;
						$this->appendCode(self::LINE_BREAK);
					}
				}
				$placeholderReplace = $this->newLine;
				if (!$touchedComma) {
					$placeholderReplace = '';
				}
				$this->code = str_replace(self::LINE_BREAK, $placeholderReplace, $this->code);
				break;
			default:
				$this->appendCode($text);
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Break function parameters into multiple lines.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// PSR2 Mode - From
function a($a, $b, $c)
{}

// To
function a(
	$a,
	$b,
	$c
) {}
?>
EOT;
	}
}

	
class ReindentAndAlignObjOps extends AdditionalPass {
	const ALIGNABLE_OBJOP = "\x2 OBJOP%d.%d.%d \x3";

	const ALIGN_WITH_INDENT = 1;

	const ALIGN_WITH_SPACES = 2;

	public function candidate($source, $foundTokens) {
		if (
			isset($foundTokens[T_OBJECT_OPERATOR]) ||
			isset($foundTokens[T_DOUBLE_COLON])
		) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		$levelCounter = 0;
		$levelEntranceCounter = [];
		$contextCounter = [];
		$touchCounter = [];
		$alignType = [];
		$printedPlaceholder = [];
		$maxContextCounter = [];
		$touchedParenOpen = false;
		$touchedFunction = false;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_QUOTE:
				$this->appendCode($text);
				$this->printUntilTheEndOfString();
				break;
			case T_CLOSE_TAG:
				$this->appendCode($text);
				$this->printUntil(T_OPEN_TAG);
				break;
			case T_START_HEREDOC:
				$this->appendCode($text);
				$this->printUntil(T_END_HEREDOC);
				break;

			case T_WHILE:
			case T_IF:
			case T_FOR:
			case T_FOREACH:
			case T_SWITCH:
				$this->appendCode($text);
				$this->printUntil(ST_PARENTHESES_OPEN);
				$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				break;

			case T_NEW:
				$this->appendCode($text);
				if ($touchedParenOpen) {
					$touchedParenOpen = false;
					$foundToken = $this->printUntilAny([ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE, ST_COMMA]);
					if (ST_PARENTHESES_OPEN == $foundToken) {
						$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
						$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
						$this->printUntilAny([ST_PARENTHESES_CLOSE, ST_COMMA]);
					} elseif (ST_PARENTHESES_CLOSE == $foundToken) {
						$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
					}
				}
				break;

			case T_FUNCTION:
				$this->appendCode($text);
				$touchedFunction = true;
				break;

			case T_VARIABLE:
			case T_STRING:
				$this->appendCode($text);
				if (!isset($levelEntranceCounter[$levelCounter])) {
					$levelEntranceCounter[$levelCounter] = 0;
				}
				if (!isset($contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]])) {
					$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$alignType[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$printedPlaceholder[$levelCounter][$levelEntranceCounter[$levelCounter]][$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]] = 0;
				}
				break;

			case ST_CURLY_OPEN:
			case T_CURLY_OPEN:
			case T_DOLLAR_OPEN_CURLY_BRACES:
				$this->appendCode($text);
				$curlyOpenType = $id;
				if ($touchedFunction) {
					$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
					$touchedFunction = false;
					$curlyOpenType = T_FUNCTION;
				}
				$touchedCurlyBlock[] = $curlyOpenType;
				break;

			case ST_CURLY_CLOSE:
				$this->appendCode($text);
				$curlyOpenType = array_pop($touchedCurlyBlock);
				if (T_FUNCTION == $curlyOpenType) {
					--$levelCounter;
				}
				break;

			case ST_PARENTHESES_OPEN:
				$touchedParenOpen = true;
				$this->appendCode($text);
				if (!$this->hasLnInBlock($this->tkns, $this->ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE)) {
					$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
					break;
				}
				$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
				break;

			case ST_BRACKET_OPEN:
				$this->appendCode($text);
				if (!$this->hasLnInBlock($this->tkns, $this->ptr, ST_BRACKET_OPEN, ST_BRACKET_CLOSE)) {
					$this->printBlock(ST_BRACKET_OPEN, ST_BRACKET_CLOSE);
					break;
				}
				$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
				break;

			case ST_PARENTHESES_CLOSE:
			case ST_BRACKET_CLOSE:
				--$levelCounter;
				$this->appendCode($text);
				break;

			case T_DOUBLE_COLON:
				if (!$this->hasLnBefore()) {
					$this->appendCode($text);
					break;
				}
			case T_OBJECT_OPERATOR:
				if ($levelCounter < 0) {
					$levelCounter = 0;
				}
				if (!isset($contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]])) {
					$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$alignType[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
					$printedPlaceholder[$levelCounter][$levelEntranceCounter[$levelCounter]][$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]] = 0;
				}
				if (0 == $touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]) {
					++$touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]];
					if ($this->hasLnBefore()) {
						$alignType[$levelCounter][$levelEntranceCounter[$levelCounter]] = self::ALIGN_WITH_INDENT;
						$this->appendCode($this->getIndent(+1) . $text);
						$foundToken = $this->printUntilAny([ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE, ST_SEMI_COLON, $this->newLine]);
						if (ST_SEMI_COLON == $foundToken) {
							$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
						} elseif (ST_PARENTHESES_OPEN == $foundToken || ST_PARENTHESES_CLOSE == $foundToken) {
							$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
							$this->indentParenthesesContent();
						}
						break;
					}
					$alignType[$levelCounter][$levelEntranceCounter[$levelCounter]] = self::ALIGN_WITH_SPACES;
					if (!isset($printedPlaceholder[$levelCounter][$levelEntranceCounter[$levelCounter]][$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]])) {
						$printedPlaceholder[$levelCounter][$levelEntranceCounter[$levelCounter]][$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]] = 0;
					}
					++$printedPlaceholder[$levelCounter][$levelEntranceCounter[$levelCounter]][$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]];
					$placeholder = sprintf(
						self::ALIGNABLE_OBJOP,
						$levelCounter,
						$levelEntranceCounter[$levelCounter],
						$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]
					);
					$this->appendCode($placeholder . $text);
					$foundToken = $this->printUntilAny([ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE, ST_SEMI_COLON, ST_EQUAL, $this->newLine, ST_COMMA]);
					if (ST_SEMI_COLON == $foundToken || ST_EQUAL == $foundToken || ST_COMMA == $foundToken) {
						$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
					} elseif (ST_PARENTHESES_OPEN == $foundToken) {
						if (!$this->hasLnInBlock($this->tkns, $this->ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE)) {
							$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
							break;
						}
						$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
						$this->injectPlaceholderParenthesesContent($placeholder);
					} elseif (ST_PARENTHESES_CLOSE == $foundToken) {
						$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
						$this->injectPlaceholderParenthesesContent($placeholder);
					}
					break;
				} elseif ($this->hasLnBefore() || $this->hasLnLeftToken()) {
					++$touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]];
					if (self::ALIGN_WITH_SPACES == $alignType[$levelCounter][$levelEntranceCounter[$levelCounter]]) {
						++$printedPlaceholder[$levelCounter][$levelEntranceCounter[$levelCounter]][$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]];
						$placeholder = sprintf(
							self::ALIGNABLE_OBJOP,
							$levelCounter,
							$levelEntranceCounter[$levelCounter],
							$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]
						);
						$this->appendCode($placeholder . $text);
						$foundToken = $this->printUntilAny([ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE, ST_SEMI_COLON, $this->newLine]);
						if (ST_SEMI_COLON == $foundToken) {
							$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
						} elseif (
							ST_PARENTHESES_OPEN == $foundToken &&
							!$this->hasLnInBlock($this->tkns, $this->ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE)
						) {
							$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
							break;
						} elseif (ST_PARENTHESES_OPEN == $foundToken || ST_PARENTHESES_CLOSE == $foundToken) {
							$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
							$this->injectPlaceholderParenthesesContent($placeholder);
						}
						break;
					}
					$this->appendCode($this->getIndent(+1) . $text);
					$foundToken = $this->printUntilAny([ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE, ST_SEMI_COLON, $this->newLine]);
					if (ST_SEMI_COLON == $foundToken) {
						$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
					} elseif (ST_PARENTHESES_OPEN == $foundToken || ST_PARENTHESES_CLOSE == $foundToken) {
						$this->incrementCounters($levelCounter, $levelEntranceCounter, $contextCounter, $maxContextCounter, $touchCounter, $alignType, $printedPlaceholder);
						$this->indentParenthesesContent();
					}
					break;
				}
				$this->appendCode($text);
				break;

			case T_COMMENT:
			case T_DOC_COMMENT:
				if (
					isset($alignType[$levelCounter]) &&
					isset($levelEntranceCounter[$levelCounter]) &&
					isset($alignType[$levelCounter][$levelEntranceCounter[$levelCounter]]) &&
					($this->hasLnBefore() || $this->hasLnLeftToken()) &&
					!$this->leftUsefulTokenIs(ST_CURLY_CLOSE)
				) {
					if (self::ALIGN_WITH_SPACES == $alignType[$levelCounter][$levelEntranceCounter[$levelCounter]]) {
						++$printedPlaceholder[$levelCounter][$levelEntranceCounter[$levelCounter]][$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]];
						$this->appendCode(
							sprintf(
								self::ALIGNABLE_OBJOP,
								$levelCounter,
								$levelEntranceCounter[$levelCounter],
								$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]
							)
						);
					} elseif (self::ALIGN_WITH_INDENT == $alignType[$levelCounter][$levelEntranceCounter[$levelCounter]]) {
						$this->appendCode($this->getIndent(+1));
					}
				}
				$this->appendCode($text);
				if ($this->leftUsefulTokenIs([T_OBJECT_OPERATOR, T_DOUBLE_COLON]) && $this->hasLn($text)) {
					$this->appendCode($this->getIndent(+1));
				}
				break;

			case ST_COMMA:
			case ST_SEMI_COLON:
			case ST_EQUAL:
				if (!isset($levelEntranceCounter[$levelCounter])) {
					$levelEntranceCounter[$levelCounter] = 0;
				}
				++$levelEntranceCounter[$levelCounter];
				$this->appendCode($text);
				break;

			case T_WHITESPACE:
				$this->appendCode($text);
				if ($this->leftUsefulTokenIs([T_OBJECT_OPERATOR, T_DOUBLE_COLON]) && $this->hasLn($text)) {
					$this->appendCode($this->getIndent(+1));
				}
				break;

			default:
				$touchedParenOpen = false;
				$this->appendCode($text);
				break;
			}
		}

		foreach ($maxContextCounter as $level => $entrances) {
			foreach ($entrances as $entrance => $context) {
				for ($j = 0; $j <= $context; ++$j) {
					if (!isset($printedPlaceholder[$level][$entrance][$j])) {
						continue;
					}
					if (0 === $printedPlaceholder[$level][$entrance][$j]) {
						continue;
					}

					$placeholder = sprintf(self::ALIGNABLE_OBJOP, $level, $entrance, $j);
					if (1 === $printedPlaceholder[$level][$entrance][$j]) {
						$this->code = str_replace($placeholder, '', $this->code);
						continue;
					}

					$lines = explode($this->newLine, $this->code);
					$linesWithObjop = [];

					foreach ($lines as $idx => $line) {
						if (false !== strpos($line, $placeholder)) {
							$linesWithObjop[] = $idx;
						}
					}

					$farthest = 0;
					foreach ($linesWithObjop as $idx) {
						$farthest = max($farthest, strpos($lines[$idx], $placeholder . '->'));
					}
					foreach ($linesWithObjop as $idx) {
						$line = $lines[$idx];
						$current = strpos($line, $placeholder);
						$delta = abs($farthest - $current);
						if ($delta > 0) {
							$line = str_replace($placeholder, str_repeat(' ', $delta) . $placeholder, $line);
							$lines[$idx] = $line;
						}
					}

					$this->code = str_replace($placeholder, '', implode($this->newLine, $lines));
				}
			}
		}
		$this->code = preg_replace('/' . str_replace('%d', '.*', preg_quote(self::ALIGNABLE_OBJOP)) . '/', '', $this->code);

		return $this->code;
	}

	
	public function getDescription() {
		return 'Align object operators.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From:
$aaaaa->b
->c;

// To:
$aaaaa->b
      ->c;
?>
EOT;
	}

	protected function incrementCounters(
		&$levelCounter,
		&$levelEntranceCounter,
		&$contextCounter,
		&$maxContextCounter,
		&$touchCounter,
		&$alignType,
		&$printedPlaceholder
	) {
		++$levelCounter;
		if (!isset($levelEntranceCounter[$levelCounter])) {
			$levelEntranceCounter[$levelCounter] = 0;
		}
		++$levelEntranceCounter[$levelCounter];
		if (!isset($contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]])) {
			$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
			$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
			$touchCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
			$alignType[$levelCounter][$levelEntranceCounter[$levelCounter]] = 0;
			$printedPlaceholder[$levelCounter][$levelEntranceCounter[$levelCounter]][$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]] = 0;
		}
		++$contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]];
		$maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]] = max($maxContextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]], $contextCounter[$levelCounter][$levelEntranceCounter[$levelCounter]]);
	}

	protected function indentParenthesesContent() {
		$count = 0;
		$sizeofTokens = sizeof($this->tkns);
		for ($i = $this->ptr; $i < $sizeofTokens; ++$i) {
			$token = &$this->tkns[$i];
			list($id, $text) = $this->getToken($token);
			if (
				(T_WHITESPACE == $id || T_DOC_COMMENT == $id || T_COMMENT == $id)
				&& $this->hasLn($text)
			) {
				$token[1] = $text . $this->getIndent(+1);
				continue;
			}
			if (ST_PARENTHESES_OPEN == $id) {
				++$count;
			}
			if (ST_PARENTHESES_CLOSE == $id) {
				--$count;
			}
			if (0 == $count) {
				break;
			}
		}
	}

	protected function injectPlaceholderParenthesesContent($placeholder) {
		$count = 0;
		$sizeofTokens = sizeof($this->tkns);
		for ($i = $this->ptr; $i < $sizeofTokens; ++$i) {
			$token = &$this->tkns[$i];
			list($id, $text) = $this->getToken($token);
			if ((T_WHITESPACE == $id || T_DOC_COMMENT == $id || T_COMMENT == $id)
				&& $this->hasLn($text)) {
				$token[1] = str_replace($this->newLine, $this->newLine . $placeholder, $text);
				continue;
			}
			if (ST_PARENTHESES_OPEN == $id) {
				++$count;
			}
			if (ST_PARENTHESES_CLOSE == $id) {
				--$count;
			}
			if (0 == $count) {
				break;
			}
		}
	}

	private function hasLnInBlock($tkns, $ptr, $start, $end) {
		$sizeOfTkns = sizeof($tkns);
		$count = 0;
		for ($i = $ptr; $i < $sizeOfTkns; ++$i) {
			$token = $tkns[$i];
			list($id, $text) = $this->getToken($token);
			if ($start == $id) {
				++$count;
			}
			if ($end == $id) {
				--$count;
			}
			if (0 == $count) {
				break;
			}
			if ($this->hasLn($text)) {
				return true;
			}
		}
		return false;
	}
}

	
final class ReindentSwitchBlocks extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_SWITCH])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		$touchedSwitch = false;
		$foundStack = [];

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			switch ($id) {
			case ST_QUOTE:
				$this->appendCode($text);
				$this->printUntilTheEndOfString();
				break;
			case T_CLOSE_TAG:
				$this->appendCode($text);
				$this->printUntil(T_OPEN_TAG);
				break;
			case T_START_HEREDOC:
				$this->appendCode($text);
				$this->printUntil(T_END_HEREDOC);
				break;
			case T_CONSTANT_ENCAPSED_STRING:
				$this->appendCode($text);
				break;

			case T_SWITCH:
				$touchedSwitch = true;
				$this->appendCode($text);
				break;

			case T_DOLLAR_OPEN_CURLY_BRACES:
			case T_CURLY_OPEN:
			case ST_CURLY_OPEN:
				$indentToken = $id;
				$this->appendCode($text);
				if ($touchedSwitch) {
					$touchedSwitch = false;
					$indentToken = T_SWITCH;
					$this->setIndent(+1);
				}
				$foundStack[] = $indentToken;
				break;

			case ST_CURLY_CLOSE:
				$poppedID = array_pop($foundStack);
				if (T_SWITCH === $poppedID) {
					$this->setIndent(-1);
				}
				$this->appendCode($text);
				break;

			default:
				$hasLn = $this->hasLn($text);
				if ($hasLn) {
					$poppedID = end($foundStack);
					if (
						T_SWITCH == $poppedID &&
						$this->rightTokenIs(ST_CURLY_CLOSE)
					) {
						$this->setIndent(-1);
						$text = str_replace($this->newLine, $this->newLine . $this->getIndent(), $text);
						$this->setIndent(+1);
					} else {
						$text = str_replace($this->newLine, $this->newLine . $this->getIndent(), $text);
					}
				}
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Reindent one level deeper the content of switch blocks.';
	}

	
	public function getExample() {
		return <<<EOT
<?php
// From
switch ($a) {
case 1:
	echo 'a';
}

// To
switch ($a) {
	case 1:
		echo 'a';
}
EOT;
	}
}
	
final class RemoveIncludeParentheses extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_INCLUDE]) || isset($foundTokens[T_REQUIRE]) || isset($foundTokens[T_INCLUDE_ONCE]) || isset($foundTokens[T_REQUIRE_ONCE])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$parenCount = 0;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_PARENTHESES_OPEN:
				$this->appendCode($text);
				$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				break;
			case ST_PARENTHESES_CLOSE:
				$parenCount--;
				if ($parenCount > 0) {
					$this->appendCode($text);
				}
				break;
			case T_INCLUDE:
			case T_REQUIRE:
			case T_INCLUDE_ONCE:
			case T_REQUIRE_ONCE:
				$this->appendCode($text . $this->getSpace());
				if (!$this->rightTokenIs(ST_PARENTHESES_OPEN)) {
					break;
				}
				++$parenCount;
				$this->walkUntil(ST_PARENTHESES_OPEN);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Remove parentheses from include declarations.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From:
require_once("file.php");

// To:
require_once "file.php";
?>
EOT;
	}
}

	
final class RemoveSemicolonAfterCurly extends AdditionalPass {
	const LAMBDA_CURLY_OPEN = 'LAMBDA_CURLY_OPEN';

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[ST_CURLY_CLOSE], $foundTokens[ST_SEMI_COLON])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$curlyStack = [];

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {

			case T_NAMESPACE:
			case T_CLASS:
			case T_TRAIT:
			case T_INTERFACE:

			case T_WHILE:
			case T_IF:
			case T_SWITCH:
			case T_FOR:
			case T_FOREACH:
				$touchedFunction = true;
				$this->appendCode($text);
				break;

			case T_FUNCTION:
				$touchedFunction = true;
				if (!$this->rightUsefulTokenIs(T_STRING)) {
					$touchedFunction = false;
				}
				$this->appendCode($text);
				break;

			case ST_CURLY_OPEN:
				$curlyType = ST_CURLY_OPEN;
				if (!$touchedFunction) {
					$curlyType = self::LAMBDA_CURLY_OPEN;
				}
				$curlyStack[] = $curlyType;
				$this->appendCode($text);
				break;

			case ST_CURLY_CLOSE:
				$curlyType = array_pop($curlyStack);
				$this->appendCode($text);

				if (self::LAMBDA_CURLY_OPEN != $curlyType && $this->rightUsefulTokenIs(ST_SEMI_COLON)) {
					$this->walkUntil(ST_SEMI_COLON);
				}
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Remove semicolon after closing curly brace.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From:
function xxx() {
    // code
};

// To:
function xxx() {
    // code
}
?>
EOT;
	}
}

	
final class RemoveUseLeadingSlash extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_NAMESPACE]) || isset($foundTokens[T_TRAIT]) || isset($foundTokens[T_CLASS]) || isset($foundTokens[T_FUNCTION]) || isset($foundTokens[T_NS_SEPARATOR])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$lastTouchedToken = null;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_NAMESPACE:
			case T_TRAIT:
			case T_CLASS:
			case T_FUNCTION:
				$lastTouchedToken = $id;
			case T_NS_SEPARATOR:
				if (T_NAMESPACE == $lastTouchedToken && $this->leftTokenIs([T_USE])) {
					continue;
				}
			default:
				$this->appendCode($text);
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Remove leading slash in T_USE imports.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
namespace NS1;
use \B;
use \D;

new B();
new D();
?>
to
<?php
namespace NS1;
use B;
use D;

new B();
new D();
?>
EOT;
	}
}

	
final class ReplaceBooleanAndOr extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_LOGICAL_AND]) || isset($foundTokens[T_LOGICAL_OR])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			if (T_LOGICAL_AND == $id) {
				$text = '&&';
			} elseif (T_LOGICAL_OR == $id) {
				$text = '||';
			}

			$this->appendCode($text);
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Convert from "and"/"or" to "&&"/"||". Danger! This pass leads to behavior change.';
	}

	
	public function getExample() {
		return <<<'EOT'
if ($a and $b or $c) {...}

if ($a && $b || $c) {...}
EOT;
	}
}

	
final class ReplaceIsNull extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$this->useCache = true;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->cache = [];

			if (T_STRING == $id && 'is_null' == strtolower($text) && !$this->leftUsefulTokenIs([T_OBJECT_OPERATOR, T_DOUBLE_COLON])) {
				$this->appendCode('null');
				$this->printAndStopAt(ST_PARENTHESES_OPEN);
				$this->appendCode('===');
				$this->printAndStopAt(ST_PARENTHESES_CLOSE);
				continue;
			}

			$this->appendCode($text);
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Replace is_null($a) with null === $a.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
is_null($a);
?>
to
<?php
null === $a;
?>
EOT;
	}
}

	
final class RestoreComments extends AdditionalPass {
	public $commentStack = [];

	
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_COMMENT])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		reset($this->commentStack);
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->tkns[$this->ptr] = [$id, $text];
			if (T_COMMENT == $id) {
				$oldComment = current($this->commentStack);
				next($this->commentStack);
				$this->tkns[$this->ptr] = $oldComment;
			}
		}
		return $this->renderLight($this->tkns);
	}

	public function getDescription() {
		return 'Revert any formatting of comments content.';
	}

	public function getExample() {
		return '';
	}
}
	
final class ReturnNull extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_RETURN])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$this->useCache = true;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->cache = [];

			if (ST_PARENTHESES_OPEN == $id && $this->leftTokenIs([T_RETURN])) {
				$parenCount = 1;
				$touchedAnotherValidToken = false;
				$stack = $text;
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					$this->cache = [];
					if (ST_PARENTHESES_OPEN == $id) {
						++$parenCount;
					}
					if (ST_PARENTHESES_CLOSE == $id) {
						--$parenCount;
					}
					$stack .= $text;
					if (0 == $parenCount) {
						break;
					}
					if (
						!(
							(T_STRING == $id && strtolower($text) == 'null') ||
							ST_PARENTHESES_OPEN == $id ||
							ST_PARENTHESES_CLOSE == $id
						)
					) {
						$touchedAnotherValidToken = true;
					}
				}
				if ($touchedAnotherValidToken) {
					$this->appendCode($stack);
				}
				continue;
			}
			if (T_STRING == $id && strtolower($text) == 'null') {
				list($prevId) = $this->getToken($this->leftUsefulToken());
				list($nextId) = $this->getToken($this->rightUsefulToken());
				if (T_RETURN == $prevId && ST_SEMI_COLON == $nextId) {
					continue;
				}
			}

			$this->appendCode($text);
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Simplify empty returns.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
function a(){
	return null;
}
?>
to
<?php
function a(){
	return;
}
?>
EOT;
	}
}

	

final class ShortArray extends AdditionalPass {
	const FOUND_ARRAY = 'array';

	const FOUND_PARENTHESES = 'paren';

	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_ARRAY])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$foundParen = [];
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_ARRAY:
				if ($this->rightTokenIs([ST_PARENTHESES_OPEN])) {
					$foundParen[] = self::FOUND_ARRAY;
					$this->printAndStopAt(ST_PARENTHESES_OPEN);
					$this->appendCode(ST_BRACKET_OPEN);
					break;
				}
			case ST_PARENTHESES_OPEN:
				$foundParen[] = self::FOUND_PARENTHESES;
				$this->appendCode($text);
				break;

			case ST_PARENTHESES_CLOSE:
				$popToken = array_pop($foundParen);
				if (self::FOUND_ARRAY == $popToken) {
					$this->appendCode(ST_BRACKET_CLOSE);
					break;
				}
			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Convert old array into new array. (array() -> [])';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
echo array();
?>
to
<?php
echo [];
?>
EOT;
	}
}

	
final class SmartLnAfterCurlyOpen extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[ST_CURLY_OPEN])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_CURLY_OPEN:
				$this->appendCode($text);
				$curlyCount = 1;
				$stack = '';
				$foundLineBreak = false;
				$hasLnAfter = $this->hasLnAfter();
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					$stack .= $text;
					if (T_START_HEREDOC == $id) {
						$stack .= $this->walkAndAccumulateUntil($this->tkns, T_END_HEREDOC);
						continue;
					}
					if (ST_QUOTE == $id) {
						$stack .= $this->walkAndAccumulateUntil($this->tkns, ST_QUOTE);
						continue;
					}
					if (ST_CURLY_OPEN == $id) {
						++$curlyCount;
					}
					if (ST_CURLY_CLOSE == $id) {
						--$curlyCount;
					}
					if (T_WHITESPACE === $id && $this->hasLn($text)) {
						$foundLineBreak = true;
						break;
					}
					if (0 == $curlyCount) {
						break;
					}
				}
				if ($foundLineBreak && !$hasLnAfter) {
					$this->appendCode($this->newLine);
				}
				$this->appendCode($stack);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}

	
	public function getDescription() {
		return 'Add line break when implicit curly block is added.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
if($a) echo array();
?>
to
<?php
if($a) {
	echo array();
}
?>
EOT;
	}
}

	
final class SortUseNameSpace extends AdditionalPass {
	private $pass = null;

	public function __construct() {
		$sortFunction = function ($useStack) {
			usort($useStack, function ($a, $b) {
				$len = strlen($a) - strlen($b);
				if (0 === $len) {
					return strcmp($a, $b);
				}
				return $len;
			});
			return $useStack;
		};
		$this->pass = new OrderAndRemoveUseClauses($sortFunction);
	}

	public function candidate($source, $foundTokens) {
		return $this->pass->candidate($source, $foundTokens);
	}

	public function format($source) {
		return $this->pass->format($source);
	}

	
	public function getDescription() {
		return 'Organize use clauses by length and alphabetic order.';
	}

	
	public function getExample() {
		return '';
	}
}
	
final class SpaceAroundControlStructures extends AdditionalPass {
	
	public function candidate($source, $foundTokens) {
		if (
			isset($foundTokens[T_IF]) ||
			isset($foundTokens[T_DO]) ||
			isset($foundTokens[T_WHILE]) ||
			isset($foundTokens[T_FOR]) ||
			isset($foundTokens[T_FOREACH]) ||
			isset($foundTokens[T_SWITCH])
		) {
			return true;
		}
		return false;
	}

	
	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$isComment = false;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			switch ($id) {
			case ST_QUOTE:
				$this->appendCode($text);
				$this->printUntilTheEndOfString();
				break;
			case T_CLOSE_TAG:
				$this->appendCode($text);
				$this->printUntil(T_OPEN_TAG);
				break;
			case T_START_HEREDOC:
				$this->appendCode($text);
				$this->printUntil(T_END_HEREDOC);
				break;
			case T_CONSTANT_ENCAPSED_STRING:
				$this->appendCode($text);
				break;
			case T_COMMENT:
				$isComment = false;
				if (
					!$this->leftUsefulTokenIs([T_OPEN_TAG]) &&
					$this->rightTokenIs([
						T_IF,
						T_DO,
						T_FOR,
						T_FOREACH,
						T_SWITCH,
						T_WHILE,
						T_COMMENT,
						T_DOC_COMMENT,
					])
				) {
					$this->appendCode($this->newLine);
					$isComment = true;
				}
				$this->appendCode($text);
				break;
			case T_IF:
			case T_DO:
			case T_FOR:
			case T_FOREACH:
			case T_SWITCH:
				if (!$isComment) {
					$this->appendCode($this->newLine);
				}

				$this->appendCode($text);
				break;
			case T_WHILE:
				if (!$isComment) {
					$this->appendCode($this->newLine);
				}
				$this->appendCode($text);
				$this->printUntil(ST_PARENTHESES_OPEN);
				$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				if ($this->rightUsefulTokenIs(ST_SEMI_COLON)) {
					$this->printUntil(ST_SEMI_COLON);
					$this->appendCode($this->newLine);
				}
				break;
			case ST_CURLY_CLOSE:
				$this->appendCode($text);
				if (!$this->rightTokenIs([T_ENCAPSED_AND_WHITESPACE, ST_QUOTE, ST_COMMA, ST_SEMI_COLON, ST_PARENTHESES_CLOSE])) {
					$this->appendCode($this->newLine);
				}
				break;
			default:
				$this->appendCode($text);
				break;
			}

		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Add space around control structures.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
if ($a) {

}
if ($b) {

}

// To
if ($a) {

}

if ($b) {

}
?>
EOT;
	}
}

	final class SpaceAroundExclamationMark extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[ST_EXCLAMATION])) {
			return true;
		}
		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_EXCLAMATION:
				$this->appendCode(
					$this->getSpace(!$this->leftUsefulTokenIs([
						T_BOOLEAN_AND, T_BOOLEAN_OR,
						T_LOGICAL_AND, T_LOGICAL_OR, T_LOGICAL_XOR,
					]))
					. $text .
					$this->getSpace(!$this->rightUsefulTokenIs([
						T_BOOLEAN_AND, T_BOOLEAN_OR,
						T_LOGICAL_AND, T_LOGICAL_OR, T_LOGICAL_XOR,
					]))
				);
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	public function getDescription() {
		return 'Add spaces around exclamation mark.';
	}

	public function getExample() {
		echo '
<?php
// From:
if (!true) foo();

// To:
if ( ! true) foo();
';
	}
}
	
final class SpaceBetweenMethods extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_FUNCTION])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_FUNCTION:
				$this->appendCode($text);
				$this->printUntil(ST_CURLY_OPEN);
				$this->printCurlyBlock();
				if (!$this->rightTokenIs([ST_CURLY_CLOSE, ST_SEMI_COLON, ST_COMMA, ST_PARENTHESES_CLOSE])) {
					$this->appendCode($this->getCrlf());
				}
				break;
			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Put space between methods.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
class A {
	function b(){

	}
	function c(){

	}
}
?>
to
<?php
class A {
	function b(){

	}

	function c(){

	}

}
?>
EOT;
	}
}

	

final class StrictBehavior extends AdditionalPass {
	private static $functions = [
		'array_keys' => 3,
		'array_search' => 3,
		'base64_decode' => 2,
		'in_array' => 3,
		'mb_detect_encoding' => 3,
	];

	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			if (T_STRING != $id) {
				$this->appendCode($text);
				continue;
			}

			$lcText = strtolower($text);
			$foundKeyword = &self::$functions[$lcText];
			if (!isset($foundKeyword)) {
				$this->appendCode($text);
				continue;
			}

			if ($this->leftUsefulTokenIs([T_DOUBLE_COLON, T_OBJECT_OPERATOR])) {
				$this->appendCode($text);
				continue;
			}

			if (!$this->rightUsefulTokenIs(ST_PARENTHESES_OPEN)) {
				$this->appendCode($text);
				continue;
			}

			$maxParams = $foundKeyword;

			$this->appendCode($text);
			$this->printUntil(ST_PARENTHESES_OPEN);
			$paramCount = $this->printAndStopAtEndOfParamBlock();

			if ($paramCount < $maxParams) {
				for (++$paramCount; $paramCount < $maxParams; ++$paramCount) {
					$this->appendCode(', null');
				}
				$this->appendCode(', true');
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Activate strict option in array_search, base64_decode, in_array, array_keys, mb_detect_encoding. Danger! This pass leads to behavior change.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
array_search($needle, $haystack);
base64_decode($str);
in_array($needle, $haystack);

array_keys($arr);
mb_detect_encoding($arr);

array_keys($arr, [1]);
mb_detect_encoding($arr, 'UTF8');

// To
array_search($needle, $haystack, true);
base64_decode($str, true);
in_array($needle, $haystack, true);

array_keys($arr, null, true);
mb_detect_encoding($arr, null, true);

array_keys($arr, [1], true);
mb_detect_encoding($arr, 'UTF8', true);
?>
EOT;
	}
}
	

final class StrictComparison extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_IS_EQUAL]) || isset($foundTokens[T_IS_NOT_EQUAL])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			if (T_IS_EQUAL == $id) {
				$text = '===';
			} elseif (T_IS_NOT_EQUAL == $id) {
				$text = '!==';
			}

			$this->appendCode($text);
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'All comparisons are converted to strict. Danger! This pass leads to behavior change.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
if($a == $b){}
if($a != $b){}

// To
if($a === $b){}
if($a !== $b){}
?>
EOT;
	}
}
	
final class StripExtraCommaInArray extends AdditionalPass {
	const EMPTY_ARRAY = 'ST_EMPTY_ARRAY';

	const ST_SHORT_ARRAY_OPEN = 'SHORT_ARRAY_OPEN';

	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);

		$contextStack = [];
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_BRACKET_OPEN:
				$found = ST_BRACKET_OPEN;
				if ($this->isShortArray()) {
					$found = self::ST_SHORT_ARRAY_OPEN;
				}
				$contextStack[] = $found;
				break;
			case ST_BRACKET_CLOSE:
				if (isset($contextStack[0]) && !$this->leftTokenIs(ST_BRACKET_OPEN)) {
					if (self::ST_SHORT_ARRAY_OPEN == end($contextStack) && $this->leftUsefulTokenIs(ST_COMMA)) {
						$prevTokenIdx = $this->leftUsefulTokenIdx();
						$this->tkns[$prevTokenIdx] = null;
					}
					array_pop($contextStack);
				}
				break;
			case T_STRING:
				if ($this->rightTokenIs(ST_PARENTHESES_OPEN)) {
					$contextStack[] = T_STRING;
				}
				break;
			case T_ARRAY:
				if ($this->rightTokenIs(ST_PARENTHESES_OPEN)) {
					$contextStack[] = T_ARRAY;
				}
				break;
			case ST_PARENTHESES_OPEN:
				if (isset($contextStack[0]) && T_ARRAY == end($contextStack) && $this->rightTokenIs(ST_PARENTHESES_CLOSE)) {
					$contextStack[sizeof($contextStack) - 1] = self::EMPTY_ARRAY;
				} elseif (!$this->leftTokenIs([T_ARRAY, T_STRING])) {
					$contextStack[] = ST_PARENTHESES_OPEN;
				}
				break;
			case ST_PARENTHESES_CLOSE:
				if (isset($contextStack[0])) {
					if (T_ARRAY == end($contextStack) && $this->leftUsefulTokenIs(ST_COMMA)) {
						$prevTokenIdx = $this->leftUsefulTokenIdx();
						$this->tkns[$prevTokenIdx] = null;
					}
					array_pop($contextStack);
				}
				break;
			}
			$this->tkns[$this->ptr] = [$id, $text];
		}
		return $this->renderLight();
	}

	
	public function getDescription() {
		return 'Remove trailing commas within array blocks';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
$a = [$a, $b, ];
$b = array($b, $c, );

// To
$a = [$a, $b];
$b = array($b, $c);
?>
EOT;
	}
}
	

final class StripNewlineAfterClassOpen extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_CLASS]) || isset($foundTokens[T_TRAIT])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_TRAIT:
			case T_CLASS:
				if ($this->leftUsefulTokenIs(T_DOUBLE_COLON)) {
					$this->appendCode($text);
					break;
				}
				$this->appendCode($text);
				$this->printUntil(ST_CURLY_OPEN);
				list(, $text) = $this->printAndStopAt(T_WHITESPACE);
				if ($this->hasLn($text)) {
					$text = substr(strrchr($text, 10), 0);
				}
				$this->appendCode($text);
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}

	
	public function getDescription() {
		return 'Strip empty lines after class opening curly brace.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
class A {

	protected $a;
}
// To
class A {
	protected $a;
}
?>
EOT;
	}
}
	
final class StripNewlineAfterCurlyOpen extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[ST_CURLY_OPEN])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_CURLY_OPEN:
				$this->appendCode($text);
				list(, $text) = $this->printAndStopAt(T_WHITESPACE);
				if ($this->hasLn($text)) {
					$text = substr(strrchr($text, 10), 0);
				}
				$this->appendCode($text);
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}

	
	public function getDescription() {
		return 'Strip empty lines after opening curly brace.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
for ($a = 0; $a < 10; $a++){

	if($a){

		// do something
	}
}
// To
for ($a = 0; $a < 10; $a++){
	if($a){
		// do something
	}
}
?>
EOT;
	}
}
	

final class StripNewlineWithinClassBody extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_CLASS]) || isset($foundTokens[T_TRAIT])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_TRAIT:
			case T_CLASS:
				if ($this->leftUsefulTokenIs(T_DOUBLE_COLON)) {
					$this->appendCode($text);
					break;
				}
				$this->appendCode($text);
				$this->printUntil(ST_CURLY_OPEN);
				list(, $text) = $this->printAndStopAt(T_WHITESPACE);
				if ($this->hasLn($text)) {
					$text = substr(strrchr($text, 10), 0);
				}
				$this->appendCode($text);

				$count = 1;
				$block = '';
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);

					if (ST_CURLY_OPEN == $id) {
						++$count;
					}
					if (T_CURLY_OPEN == $id) {
						++$count;
					}
					if (T_DOLLAR_OPEN_CURLY_BRACES == $id) {
						++$count;
					}
					if (ST_CURLY_CLOSE == $id) {
						--$count;
					}
					if (0 == $count) {
						break;
					}
					$block .= $text;
				}
				$this->appendCode($block);
				$this->rtrimAndAppendCode($this->newLine . '}');
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}

	
	public function getDescription() {
		return 'Strip empty lines after class opening curly brace.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
class A {

	protected $a;

}
// To
class A {
	protected $a;
}
?>
EOT;
	}
}
	
final class StripSpaces extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_WHITESPACE]) || isset($foundTokens[T_COMMENT])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			if (T_WHITESPACE == $id || T_COMMENT == $id) {
				continue;
			}

			$this->appendCode($text);
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Remove all empty spaces';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
$a = [$a, $b];
$b = array($b, $c);

// To
$a=[$a,$b];$b=array($b,$c);
?>
EOT;
	}
}
	
final class StripSpaceWithinControlStructures extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (
			isset($foundTokens[T_CASE]) ||
			isset($foundTokens[T_DO]) ||
			isset($foundTokens[T_FOR]) ||
			isset($foundTokens[T_FOREACH]) ||
			isset($foundTokens[T_IF]) ||
			isset($foundTokens[T_SWITCH]) ||
			isset($foundTokens[T_WHILE])
		) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$touchedDo = false;

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			switch ($id) {
			case T_IF:
			case T_DO:
			case T_FOR:
			case T_FOREACH:
			case T_SWITCH:
				if (T_DO == $id) {
					$touchedDo = true;
				}
				$this->appendCode($text);
				$this->printUntil(ST_PARENTHESES_OPEN);
				$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);
				$this->printUntil(ST_CURLY_OPEN);

				if ($this->hasLnAfter()) {
					each($this->tkns);
					$this->appendCode($this->newLine);
					continue;
				}

				break;

			case T_WHILE:
				if (!$touchedDo && $this->leftUsefulTokenIs(ST_CURLY_CLOSE)) {
					$this->rtrimAndAppendCode($this->newLine);
				}
				$touchedDo = false;
				$this->appendCode($text);
				$this->printUntil(ST_PARENTHESES_OPEN);
				$this->printBlock(ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE);

				if ($this->rightUsefulTokenIs(ST_CURLY_OPEN)) {
					$this->printUntil(ST_CURLY_OPEN);

					if ($this->hasLnAfter()) {
						each($this->tkns);
						$this->appendCode($this->newLine);
						continue;
					}
				}

				break;

			case T_CASE:
				$this->appendCode($text);
				$this->printUntil(ST_COLON);

				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					if (T_WHITESPACE != $id) {
						break;
					}
					$this->appendCode($text);
				}
				$this->rtrimAndAppendCode($this->newLine . $text);
				break;

			case ST_CURLY_CLOSE:
				if ($this->hasLnBefore()) {
					$this->rtrimAndAppendCode($this->newLine . $text);
					continue;
				}

				$this->appendCode($text);
				break;

			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Strip empty lines within control structures.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
for ($a = 0; $a < 10; $a++){

	if($a){

		// do something
	}

}
// To
for ($a = 0; $a < 10; $a++){
	if($a){
		// do something
	}
}
?>
EOT;
	}
}

	
final class TightConcat extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[ST_CONCAT])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$whitespaces = " \t";
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_CONCAT:
				if (!$this->leftUsefulTokenIs([T_LNUMBER, T_DNUMBER]) && !$this->hasLnBefore()) {
					$this->code = rtrim($this->code, $whitespaces);
				}
				list($nextId, $nextText) = $this->inspectToken(+1);
				if (T_WHITESPACE == $nextId && !$this->hasln($nextText) && !$this->rightUsefulTokenIs([T_LNUMBER, T_DNUMBER])) {
					each($this->tkns);
				}
			default:
				$this->appendCode($text);
				break;
			}
		}
		return $this->code;
	}

	
	public function getDescription() {
		return 'Ensure string concatenation does not have spaces, except when close to numbers.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
$a = 'a' . 'b';
$a = 'a' . 1 . 'b';
// To
$a = 'a'.'b';
$a = 'a'. 1 .'b';
?>
EOT;
	}
}
	
final class TrimSpaceBeforeSemicolon extends AdditionalPass {
	
	public function candidate($source, $foundTokens) {
		return true;
	}

	
	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$isComment = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case ST_SEMI_COLON:
				if (!$this->leftTokenIs([T_COMMENT, T_DOC_COMMENT])) {
					$this->rtrimAndAppendCode($text);
					break;
				}

			default:
				$this->appendCode($text);
				break;
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Remove empty lines before semi-colon.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
// From
echo 1
;

// To
echo 1;
?>
EOT;
	}
}

	

final class UpgradeToPreg extends AdditionalPass {
	private static $conversionTable = [
		'ereg' => [
			'to' => 'preg_match',
			'modifier' => '',
		],
		'ereg_replace' => [
			'to' => 'preg_replace',
			'modifier' => '',
		],
		'eregi' => [
			'to' => 'preg_match',
			'modifier' => 'i',
		],
		'eregi_replace' => [
			'to' => 'preg_replace',
			'modifier' => 'i',
		],
		'split' => [
			'to' => 'preg_split',
			'modifier' => '',
		],
		'spliti' => [
			'to' => 'preg_split',
			'modifier' => 'i',
		],
	];

	private static $delimiters = ['/', '#', '!'];

	public function candidate($source, $foundTokens) {
		return (
			false !== stripos($source, 'ereg') ||
			false !== stripos($source, 'split')
		);
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->tkns[$this->ptr] = [$id, $text];

			if (T_STRING != $id) {
				continue;
			}

			if ($this->leftUsefulTokenIs([T_OBJECT_OPERATOR, T_DOUBLE_COLON])) {
				continue;
			}

			$lctext = strtolower($text);
			if (T_STRING == $id && !isset(self::$conversionTable[$lctext])) {
				continue;
			}

			$funcIdx = $this->ptr;

			$this->walkUntil(ST_PARENTHESES_OPEN);
			if (!$this->rightUsefulTokenIs(T_CONSTANT_ENCAPSED_STRING)) {
				continue;
			}
			$this->walkUntil(T_CONSTANT_ENCAPSED_STRING);

			$patternIdx = $this->ptr;

			list(, $countTokens) = $this->peekAndCountUntilAny($this->tkns, $this->ptr, [ST_COMMA, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE]);
			unset($countTokens[T_CONSTANT_ENCAPSED_STRING], $countTokens[ST_COMMA], $countTokens[ST_PARENTHESES_CLOSE]);
			if (sizeof($countTokens) > 0) {
				continue;
			}

			list(, $pattern) = $this->getToken($this->tkns[$patternIdx]);
			$patternQuote = substr($pattern, 0, 1);
			$pattern = substr($pattern, 1, -1);
			$delim = $this->detectRegexDelim($pattern);
			$newPattern = $delim . addcslashes($pattern, $delim) . $delim . 'D' . self::$conversionTable[$lctext]['modifier'];

						if (false === @preg_match($newPattern, '')) {
				continue;
			}

			$this->tkns[$funcIdx][1] = self::$conversionTable[$lctext]['to'];
			$this->tkns[$patternIdx][1] = $patternQuote . $newPattern . $patternQuote;
		}

		return $this->render($this->tkns);
	}

	
	public function getDescription() {
		return 'Upgrade ereg_* calls to preg_*';
	}

	
	public function getExample() {
		return '<?php
// From:
$var = ereg("[A-Z]", $var);
$var = eregi_replace("[A-Z]", "", $var)
$var = spliti("[A-Z]", $var);
// To:
$var = preg_match("/[A-Z]/Di", $var);
$var = preg_replace("/[A-Z]/Di", "", $var);
$var = preg_split("/[A-Z]/Di", $var);
';
	}

	private function detectRegexDelim($pattern) {
		$delim = [];
		foreach (self::$delimiters as $k => $d) {
			if (false === strpos($pattern, $d)) {
				return $d;
			}

			$delim[$d] = [substr_count($pattern, $d), $k];
		}

		uasort($delim, function ($a, $b) {
			if ($a[0] === $b[0]) {
				if ($a[1] === $b[1]) {
					return 0;
				} elseif ($a[1] < $b[1]) {
					return -1;
				}
				return 1;
			}

			if ($a[0] < $b[0]) {
				return -1;
			}

			return 1;
		});

		return key($delim);
	}
}

	
final class WordWrap extends AdditionalPass {
	const ALIGNABLE_WORDWRAP = "\x2 WORDWRAP \x3";

	private static $length = 80;

	private static $tabSizeInSpace = 8;

	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';

		$currentLineLength = 0;
		$detectedTab = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;

			$originalText = $text;
			if (T_WHITESPACE == $id) {
				if (!$detectedTab && false !== strpos($text, "\t")) {
					$detectedTab = true;
				}
				$text = str_replace(
					$this->indentChar,
					str_repeat(' ', self::$tabSizeInSpace),
					$text
				);
			}
			$textLen = strlen($text);

			$currentLineLength += $textLen;
			if ($this->hasLn($text)) {
				$currentLineLength = $textLen - strrpos($text, $this->newLine);
			}

			if ($currentLineLength > self::$length) {
				$currentLineLength = $textLen - strrpos($text, $this->newLine);
				$this->appendCode($this->newLine . self::ALIGNABLE_WORDWRAP);
			}

			$this->appendCode($originalText);
		}

		if (false === strpos($this->code, self::ALIGNABLE_WORDWRAP)) {
			return $this->code;
		}

		$lines = explode($this->newLine, $this->code);
		foreach ($lines as $idx => $line) {
			if (false !== strpos($line, self::ALIGNABLE_WORDWRAP)) {
				$line = str_replace(self::ALIGNABLE_WORDWRAP, '', $line);
				$line = str_pad($line, self::$length, ' ', STR_PAD_LEFT);
				if ($detectedTab) {
					$line = preg_replace('/\G {' . self::$tabSizeInSpace . '}/', "\t", $line);
				}
				$lines[$idx] = $line;
			}
		}

		return implode($this->newLine, $lines);
	}

	
	public function getDescription() {
		return 'Word wrap at 80 columns.';
	}

	
	public function getExample() {
		return '';
	}
}
	
final class WrongConstructorName extends AdditionalPass {
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_NAMESPACE]) || isset($foundTokens[T_CLASS])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$touchedNamespace = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
			case T_NAMESPACE:
				if (!$this->rightUsefulTokenIs(T_NS_SEPARATOR)) {
					$touchedNamespace = true;
				}
				$this->appendCode($text);
				break;
			case T_CLASS:
				$this->appendCode($text);
				if ($this->leftUsefulTokenIs([T_DOUBLE_COLON])) {
					break;
				}
				if ($touchedNamespace) {
					break;
				}
				$classLocalName = '';
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;
					$this->appendCode($text);
					if (T_STRING == $id) {
						$classLocalName = strtolower($text);
					}
					if (T_EXTENDS == $id || T_IMPLEMENTS == $id || ST_CURLY_OPEN == $id) {
						break;
					}
				}
				$count = 1;
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->getToken($token);
					$this->ptr = $index;

					if (T_STRING == $id && $this->leftUsefulTokenIs([T_FUNCTION]) && strtolower($text) == $classLocalName) {
						$text = '__construct';
					}
					$this->appendCode($text);

					if (ST_CURLY_OPEN == $id) {
						++$count;
					}
					if (ST_CURLY_CLOSE == $id) {
						--$count;
					}
					if (0 == $count) {
						break;
					}
				}
				break;
			default:
				$this->appendCode($text);
			}
		}

		return $this->code;
	}

	
	public function getDescription() {
		return 'Update old constructor names into new ones. http://php.net/manual/en/language.oop5.decon.php';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
class A {
	function A(){

	}
}
?>
to
<?php
class A {
	function __construct(){

	}
}
?>
EOT;
	}
}
	
final class YodaComparisons extends AdditionalPass {
	const CHAIN_FUNC = 'CHAIN_FUNC';

	const CHAIN_LITERAL = 'CHAIN_LITERAL';

	const CHAIN_STRING = 'CHAIN_STRING';

	const CHAIN_VARIABLE = 'CHAIN_VARIABLE';

	const PARENTHESES_BLOCK = 'PARENTHESES_BLOCK';

	public function candidate($source, $foundTokens) {
		if (
			isset($foundTokens[T_IS_EQUAL]) ||
			isset($foundTokens[T_IS_IDENTICAL]) ||
			isset($foundTokens[T_IS_NOT_EQUAL]) ||
			isset($foundTokens[T_IS_NOT_IDENTICAL])
		) {
			return true;
		}

		return false;
	}

	public function format($source) {
		return $this->yodise($source);
	}

	
	public function getDescription() {
		return 'Execute Yoda Comparisons.';
	}

	
	public function getExample() {
		return <<<'EOT'
<?php
if($a == 1){

}
?>
to
<?php
if(1 == $a){

}
?>
EOT;
	}

	protected function yodise($source) {
		$tkns = $this->aggregateVariables($source);
		while (list($ptr, $token) = each($tkns)) {
			if (is_null($token)) {
				continue;
			}
			list($id) = $this->getToken($token);
			switch ($id) {
			case T_IS_EQUAL:
			case T_IS_IDENTICAL:
			case T_IS_NOT_EQUAL:
			case T_IS_NOT_IDENTICAL:
				list($left, $right) = $this->siblings($tkns, $ptr);
				list($leftId) = $tkns[$left];
				list($rightId) = $tkns[$right];
				if ($leftId == $rightId) {
					continue;
				}

				$leftPureVariable = $this->isPureVariable($leftId);
				for ($leftmost = $left; $leftmost >= 0; --$leftmost) {
					list($leftScanId) = $this->getToken($tkns[$leftmost]);
					if ($this->isLowerPrecedence($leftScanId)) {
						++$leftmost;
						break;
					}
					$leftPureVariable &= $this->isPureVariable($leftScanId);
				}

				$rightPureVariable = $this->isPureVariable($rightId);
				for ($rightmost = $right; $rightmost < sizeof($tkns) - 1; ++$rightmost) {
					list($rightScanId) = $this->getToken($tkns[$rightmost]);
					if ($this->isLowerPrecedence($rightScanId)) {
						--$rightmost;
						break;
					}
					$rightPureVariable &= $this->isPureVariable($rightScanId);
				}

				if ($leftPureVariable && !$rightPureVariable) {
					$origLeftTokens = $leftTokens = implode('', array_map(function ($token) {
						return isset($token[1]) ? $token[1] : $token;
					}, array_slice($tkns, $leftmost, $left - $leftmost + 1)));
					$origRightTokens = $rightTokens = implode('', array_map(function ($token) {
						return isset($token[1]) ? $token[1] : $token;
					}, array_slice($tkns, $right, $rightmost - $right + 1)));

					$leftTokens = (substr($origRightTokens, 0, 1) == ' ' ? ' ' : '') . trim($leftTokens) . (substr($origRightTokens, -1, 1) == ' ' ? ' ' : '');
					$rightTokens = (substr($origLeftTokens, 0, 1) == ' ' ? ' ' : '') . trim($rightTokens) . (substr($origLeftTokens, -1, 1) == ' ' ? ' ' : '');

					$tkns[$leftmost] = ['REPLACED', $rightTokens];
					$tkns[$right] = ['REPLACED', $leftTokens];

					if ($leftmost != $left) {
						for ($i = $leftmost + 1; $i <= $left; ++$i) {
							$tkns[$i] = null;
						}
					}
					if ($rightmost != $right) {
						for ($i = $right + 1; $i <= $rightmost; ++$i) {
							$tkns[$i] = null;
						}
					}
				}
			}
		}
		return $this->render($tkns);
	}

	private function aggregateVariables($source) {
		$tkns = token_get_all($source);
		while (list($ptr, $token) = each($tkns)) {
			list($id, $text) = $this->getToken($token);

			if (ST_PARENTHESES_OPEN == $id) {
				$initialPtr = $ptr;
				$tmp = $this->scanAndReplace($tkns, $ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE, 'yodise', [T_IS_EQUAL, T_IS_IDENTICAL, T_IS_NOT_EQUAL, T_IS_NOT_IDENTICAL]);
				$tkns[$initialPtr] = [self::PARENTHESES_BLOCK, $tmp];
				continue;
			}
			if (ST_QUOTE == $id) {
				$stack = $text;
				$initialPtr = $ptr;
				while (list($ptr, $token) = each($tkns)) {
					list($id, $text) = $this->getToken($token);
					$stack .= $text;
					$tkns[$ptr] = null;
					if (ST_QUOTE == $id) {
						break;
					}
				}

				$tkns[$initialPtr] = [self::CHAIN_STRING, $stack];
				continue;
			}

			if (T_STRING == $id || T_VARIABLE == $id || T_NS_SEPARATOR == $id) {
				$initialIndex = $ptr;
				$stack = $text;
				$touchedVariable = false;
				if (T_VARIABLE == $id) {
					$touchedVariable = true;
				}
				if (!$this->rightTokenSubsetIsAtIdx(
					$tkns,
					$ptr,
					[T_STRING, T_VARIABLE, T_NS_SEPARATOR, T_OBJECT_OPERATOR, T_DOUBLE_COLON, ST_CURLY_OPEN, ST_PARENTHESES_OPEN, ST_BRACKET_OPEN, T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES]
				)) {
					continue;
				}
				while (list($ptr, $token) = each($tkns)) {
					list($id, $text) = $this->getToken($token);
					$tkns[$ptr] = null;
					if (ST_CURLY_OPEN == $id) {
						$text = $this->scanAndReplaceCurly($tkns, $ptr, ST_CURLY_OPEN, 'yodise', [T_IS_EQUAL, T_IS_IDENTICAL, T_IS_NOT_EQUAL, T_IS_NOT_IDENTICAL]);
					} elseif (T_CURLY_OPEN == $id) {
						$text = $this->scanAndReplaceCurly($tkns, $ptr, ST_CURLY_OPEN, 'yodise', [T_IS_EQUAL, T_IS_IDENTICAL, T_IS_NOT_EQUAL, T_IS_NOT_IDENTICAL]);
					} elseif (T_DOLLAR_OPEN_CURLY_BRACES == $id) {
						$text = $this->scanAndReplaceCurly($tkns, $ptr, T_DOLLAR . ST_CURLY_OPEN, 'yodise', [T_IS_EQUAL, T_IS_IDENTICAL, T_IS_NOT_EQUAL, T_IS_NOT_IDENTICAL]);
					} elseif (ST_BRACKET_OPEN == $id) {
						$text = $this->scanAndReplace($tkns, $ptr, ST_BRACKET_OPEN, ST_BRACKET_CLOSE, 'yodise', [T_IS_EQUAL, T_IS_IDENTICAL, T_IS_NOT_EQUAL, T_IS_NOT_IDENTICAL]);
					} elseif (ST_PARENTHESES_OPEN == $id) {
						$text = $this->scanAndReplace($tkns, $ptr, ST_PARENTHESES_OPEN, ST_PARENTHESES_CLOSE, 'yodise', [T_IS_EQUAL, T_IS_IDENTICAL, T_IS_NOT_EQUAL, T_IS_NOT_IDENTICAL]);
					}

					$stack .= $text;

					if (!$touchedVariable && T_VARIABLE == $id) {
						$touchedVariable = true;
					}

					if (
						!$this->rightTokenSubsetIsAtIdx(
							$tkns,
							$ptr,
							[T_STRING, T_VARIABLE, T_NS_SEPARATOR, T_OBJECT_OPERATOR, T_DOUBLE_COLON, ST_CURLY_OPEN, ST_PARENTHESES_OPEN, ST_BRACKET_OPEN, T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES]
						)
					) {
						break;
					}
				}
				$chain = [self::CHAIN_LITERAL, $stack];
				if (substr(trim($stack), -1, 1) == ST_PARENTHESES_CLOSE) {
					$chain = [self::CHAIN_FUNC, $stack];
				} elseif ($touchedVariable) {
					$chain = [self::CHAIN_VARIABLE, $stack];
				}
				$tkns[$initialIndex] = $chain;
			}
		}
		$tkns = array_values(array_filter($tkns));
		return $tkns;
	}

	private function isLowerPrecedence($id) {
		switch ($id) {
		case ST_REFERENCE:
		case ST_BITWISE_XOR:
		case ST_BITWISE_OR:
		case T_BOOLEAN_AND:
		case T_BOOLEAN_OR:
		case ST_QUESTION:
		case ST_COLON:
		case ST_EQUAL:
		case T_PLUS_EQUAL:
		case T_MINUS_EQUAL:
		case T_MUL_EQUAL:
		case T_POW_EQUAL:
		case T_DIV_EQUAL:
		case T_CONCAT_EQUAL:
		case T_MOD_EQUAL:
		case T_AND_EQUAL:
		case T_OR_EQUAL:
		case T_XOR_EQUAL:
		case T_SL_EQUAL:
		case T_SR_EQUAL:
		case T_DOUBLE_ARROW:
		case T_LOGICAL_AND:
		case T_LOGICAL_XOR:
		case T_LOGICAL_OR:
		case ST_COMMA:
		case ST_SEMI_COLON:
		case T_RETURN:
		case T_THROW:
		case T_GOTO:
		case T_CASE:
		case T_COMMENT:
		case T_DOC_COMMENT:
		case T_OPEN_TAG:
			return true;
		}
		return false;
	}

	private function isPureVariable($id) {
		return self::CHAIN_VARIABLE == $id || T_VARIABLE == $id || T_INC == $id || T_DEC == $id || ST_EXCLAMATION == $id || T_COMMENT == $id || T_DOC_COMMENT == $id || T_WHITESPACE == $id;
	}
}

	if (!isset($inPhar)) {
		$inPhar = false;
	}
	if (!isset($testEnv)) {
		
function showHelp($argv, $enableCache, $inPhar) {
	echo 'Usage: ' . $argv[0] . ' [-hv] [-o=FILENAME] [--config=FILENAME] ' . ($enableCache ? '[--cache[=FILENAME]] ' : '') . '[options] <target>', PHP_EOL;
	$options = [
		'--cache[=FILENAME]' => 'cache file. Default: ',
		'--cakephp' => 'Apply CakePHP coding style',
		'--config=FILENAME' => 'configuration file. Default: .phpfmt.ini',
		'--constructor=type' => 'analyse classes for attributes and generate constructor - camel, snake, golang',
		'--dry-run' => 'Runs the formatter without atually changing files; returns exit code 1 if changes would have been applied',
		'--enable_auto_align' => 'disable auto align of ST_EQUAL and T_DOUBLE_ARROW',
		'--exclude=pass1,passN,...' => 'disable specific passes',
		'--help-pass' => 'show specific information for one pass',
		'--ignore=PATTERN-1,PATTERN-N,...' => 'ignore file names whose names contain any PATTERN-N',
		'--indent_with_space=SIZE' => 'use spaces instead of tabs for indentation. Default 4',
		'--lint-before' => 'lint files before pretty printing (PHP must be declared in %PATH%/$PATH)',
		'--list' => 'list possible transformations',
		'--list-simple' => 'list possible transformations - greppable',
		'--no-backup' => 'no backup file (original.php~)',
		'--passes=pass1,passN,...' => 'call specific compiler pass',
		'--profile=NAME' => 'use one of profiles present in configuration file',
		'--psr' => 'activate PSR1 and PSR2 styles',
		'--psr1' => 'activate PSR1 style',
		'--psr1-naming' => 'activate PSR1 style - Section 3 and 4.3 - Class and method names case.',
		'--psr2' => 'activate PSR2 style',
		'--setters_and_getters=type' => 'analyse classes for attributes and generate setters and getters - camel, snake, golang',
		'--smart_linebreak_after_curly' => 'convert multistatement blocks into multiline blocks',
		'--visibility_order' => 'fixes visibiliy order for method in classes - PSR-2 4.2',
		'--yoda' => 'yoda-style comparisons',
		'-h, --help' => 'this help message',
		'-o=file' => 'output the formatted code to "file"',
		'-o=-' => 'output the formatted code to standard output',
		'-v' => 'verbose',
	];
	if ($inPhar) {
		$options['--selfupdate'] = 'self-update fmt.phar from Github';
		$options['--version'] = 'version';
	}
	$options['--cache[=FILENAME]'] .= (Cacher::DEFAULT_CACHE_FILENAME);
	if (!$enableCache) {
		unset($options['--cache[=FILENAME]']);
	}
	ksort($options);
	$maxLen = max(array_map(function ($v) {
		return strlen($v);
	}, array_keys($options)));
	foreach ($options as $k => $v) {
		echo '  ', str_pad($k, $maxLen), '  ', $v, PHP_EOL;
	}

	echo PHP_EOL, 'If <target> is "-", it reads from stdin', PHP_EOL;
}

$getoptLongOptions = [
	'cache::',
	'cakephp',
	'config:',
	'constructor:',
	'dry-run',
	'enable_auto_align',
	'exclude:',
	'help',
	'help-pass:',
	'ignore:',
	'indent_with_space::',
	'lint-before',
	'list',
	'list-simple',
	'no-backup',
	'oracleDB::',
	'passes:',
	'php2go',
	'profile:',
	'psr',
	'psr1',
	'psr1-naming',
	'psr2',
	'setters_and_getters:',
	'smart_linebreak_after_curly',
	'visibility_order',
	'yoda',
];
if ($inPhar) {
	$getoptLongOptions[] = 'selfupdate';
	$getoptLongOptions[] = 'version';
}
if (!$enableCache) {
	unset($getoptLongOptions['cache::']);
}
$opts = getopt(
	'ihvo:',
	$getoptLongOptions
);

if (isset($opts['list'])) {
	echo 'Usage: ', $argv[0], ' --help-pass=PASSNAME', PHP_EOL;
	$classes = get_declared_classes();
	$helpLines = [];
	foreach ($classes as $className) {
		if (is_subclass_of($className, 'AdditionalPass')) {
			$pass = new $className();
			$helpLines[] = ["\t- " . $className, $pass->getDescription()];
		}
	}
	echo tabwriter($helpLines);
	die();
}

if (isset($opts['list-simple'])) {
	$classes = get_declared_classes();
	$helpLines = [];
	foreach ($classes as $className) {
		if (is_subclass_of($className, 'AdditionalPass')) {
			$pass = new $className();
			$helpLines[] = [$className, $pass->getDescription()];
		}
	}
	echo tabwriter($helpLines);
	die();
}
if (isset($opts['selfupdate'])) {
	selfupdate($argv, $inPhar);
}
if (isset($opts['version'])) {
	if ($inPhar) {
		echo $argv[0], ' ', VERSION, PHP_EOL;
	}
	exit(0);
}
if (isset($opts['config'])) {
	$argv = extractFromArgv($argv, 'config');

	if ('scan' == $opts['config']) {
		$cfgfn = getcwd() . DIRECTORY_SEPARATOR . '.phpfmt.ini';
		$lastcfgfn = '';
		fwrite(STDERR, 'Scanning for configuration file...');
		while (!is_file($cfgfn) && $lastcfgfn != $cfgfn) {
			$lastcfgfn = $cfgfn;
			$cfgfn = dirname(dirname($cfgfn)) . DIRECTORY_SEPARATOR . '.phpfmt.ini';
		}
		$opts['config'] = $cfgfn;
		if (file_exists($opts['config']) && is_file($opts['config'])) {
			fwrite(STDERR, $opts['config']);
			$iniOpts = parse_ini_file($opts['config'], true);
			if (!empty($iniOpts)) {
				$opts += $iniOpts;
			}
		}
		fwrite(STDERR, PHP_EOL);
	} else {
		if (!file_exists($opts['config']) || !is_file($opts['config'])) {
			fwrite(STDERR, 'Custom configuration not file found' . PHP_EOL);
			exit(255);
		}
		$iniOpts = parse_ini_file($opts['config'], true);
		if (!empty($iniOpts)) {
			$opts += $iniOpts;
		}
	}
} elseif (file_exists(getcwd() . DIRECTORY_SEPARATOR . '.phpfmt.ini') && is_file(getcwd() . DIRECTORY_SEPARATOR . '.phpfmt.ini')) {
	fwrite(STDERR, 'Configuration file found' . PHP_EOL);
	$iniOpts = parse_ini_file(getcwd() . DIRECTORY_SEPARATOR . '.phpfmt.ini', true);
	if (isset($opts['profile'])) {
		$argv = extractFromArgv($argv, 'profile');
		$profile = &$iniOpts[$opts['profile']];
		if (isset($profile)) {
			$iniOpts = $profile;
		}
	}
	$opts = array_merge($iniOpts, $opts);
}
if (isset($opts['h']) || isset($opts['help'])) {
	showHelp($argv, $enableCache, $inPhar);
	exit(0);
}

if (isset($opts['help-pass'])) {
	$optPass = $opts['help-pass'];
	if (class_exists($optPass) && method_exists($optPass, 'getDescription')) {
		$pass = new $optPass();
		echo $argv[0], ': "', $optPass, '" - ', $pass->getDescription(), PHP_EOL, PHP_EOL;
		echo 'Example:', PHP_EOL, $pass->getExample(), PHP_EOL;
	} else {
		echo $argv[0], ': Core pass.';
	}
	die();
}

$cache = null;
$cache_fn = null;
if ($enableCache && isset($opts['cache'])) {
	$argv = extractFromArgv($argv, 'cache');
	$cache_fn = $opts['cache'];
	$cache = new Cache($cache_fn);
	fwrite(STDERR, 'Using cache ...' . PHP_EOL);
} elseif (!$enableCache) {
	$cache = new Cache();
}

$backup = true;
if (isset($opts['no-backup'])) {
	$argv = extractFromArgv($argv, 'no-backup');
	$backup = false;
}

$dryRun = false;
if (isset($opts['dry-run'])) {
	$argv = extractFromArgv($argv, 'dry-run');
	$dryRun = true;
}

$ignore_list = null;
if (isset($opts['ignore'])) {
	$argv = extractFromArgv($argv, 'ignore');
	$ignore_list = array_map(function ($v) {
		return trim($v);
	}, explode(',', $opts['ignore']));
}

$lintBefore = false;
if (isset($opts['lint-before'])) {
	$argv = extractFromArgv($argv, 'lint-before');
	$lintBefore = true;
}

$fmt = new CodeFormatter();
if (isset($opts['setters_and_getters'])) {
	$argv = extractFromArgv($argv, 'setters_and_getters');
	$fmt->enablePass('SettersAndGettersPass', $opts['setters_and_getters']);
}

if (isset($opts['constructor'])) {
	$argv = extractFromArgv($argv, 'constructor');
	$fmt->enablePass('ConstructorPass', $opts['constructor']);
}

if (isset($opts['oracleDB'])) {
	$argv = extractFromArgv($argv, 'oracleDB');

	if ('scan' == $opts['oracleDB']) {
		$oracle = getcwd() . DIRECTORY_SEPARATOR . 'oracle.sqlite';
		$lastoracle = '';
		while (!is_file($oracle) && $lastoracle != $oracle) {
			$lastoracle = $oracle;
			$oracle = dirname(dirname($oracle)) . DIRECTORY_SEPARATOR . 'oracle.sqlite';
		}
		$opts['oracleDB'] = $oracle;
		fwrite(STDERR, PHP_EOL);
	}

	if (file_exists($opts['oracleDB']) && is_file($opts['oracleDB'])) {
		$fmt->enablePass('AutoImportPass', $opts['oracleDB']);
	}
}

if (isset($opts['smart_linebreak_after_curly'])) {
	$fmt->enablePass('SmartLnAfterCurlyOpen');
	$argv = extractFromArgv($argv, 'smart_linebreak_after_curly');
}

if (isset($opts['yoda'])) {
	$fmt->enablePass('YodaComparisons');
	$argv = extractFromArgv($argv, 'yoda');
}

if (isset($opts['enable_auto_align'])) {
	$fmt->enablePass('AlignEquals');
	$fmt->enablePass('AlignDoubleArrow');
	$argv = extractFromArgv($argv, 'enable_auto_align');
}

if (isset($opts['psr'])) {
	PsrDecorator::decorate($fmt);
	$argv = extractFromArgv($argv, 'psr');
}

if (isset($opts['psr1'])) {
	PsrDecorator::PSR1($fmt);
	$argv = extractFromArgv($argv, 'psr1');
}

if (isset($opts['psr1-naming'])) {
	PsrDecorator::PSR1Naming($fmt);
	$argv = extractFromArgv($argv, 'psr1-naming');
}

if (isset($opts['psr2'])) {
	PsrDecorator::PSR2($fmt);
	$argv = extractFromArgv($argv, 'psr2');
}

if (isset($opts['indent_with_space'])) {
	$fmt->enablePass('PSR2IndentWithSpace', $opts['indent_with_space']);
	$argv = extractFromArgv($argv, 'indent_with_space');
}

if ((isset($opts['psr1']) || isset($opts['psr2']) || isset($opts['psr'])) && isset($opts['enable_auto_align'])) {
	$fmt->enablePass('PSR2AlignObjOp');
}

if (isset($opts['visibility_order'])) {
	$fmt->enablePass('PSR2ModifierVisibilityStaticOrder');
	$argv = extractFromArgv($argv, 'visibility_order');
}

if (isset($opts['passes'])) {
	$optPasses = array_map(function ($v) {
		return trim($v);
	}, explode(',', $opts['passes']));
	foreach ($optPasses as $optPass) {
		$fmt->enablePass($optPass);
	}
	$argv = extractFromArgv($argv, 'passes');
}

if (isset($opts['cakephp'])) {
	$fmt->enablePass('CakePHPStyle');
	$argv = extractFromArgv($argv, 'cakephp');
}

if (isset($opts['php2go'])) {
	Php2GoDecorator::decorate($fmt);
	$argv = extractFromArgv($argv, 'php2go');
}

if (isset($opts['exclude'])) {
	$passesNames = explode(',', $opts['exclude']);
	foreach ($passesNames as $passName) {
		$fmt->disablePass(trim($passName));
	}
	$argv = extractFromArgv($argv, 'exclude');
}

if (isset($opts['v'])) {
	$argv = extractFromArgvShort($argv, 'v');
	fwrite(STDERR, 'Used passes: ' . implode(', ', $fmt->getPassesNames()) . PHP_EOL);
}

if (isset($opts['i'])) {
	echo 'php.tools fmt.php interactive mode.', PHP_EOL;
	echo 'no <?php is necessary', PHP_EOL;
	echo 'type a lone "." to finish input.', PHP_EOL;
	echo 'type "quit" to finish.', PHP_EOL;
	while (true) {
		$str = '';
		do {
			$line = readline('> ');
			$str .= $line;
		} while (!('.' == $line || 'quit' == $line));
		if ('quit' == $line) {
			exit(0);
		}
		readline_add_history(substr($str, 0, -1));
		echo $fmt->formatCode('<?php ' . substr($str, 0, -1)), PHP_EOL;
	}
} elseif (isset($opts['o'])) {
	$argv = extractFromArgvShort($argv, 'o');
	if ('-' == $opts['o'] && '-' == $argv[1]) {
		echo $fmt->formatCode(file_get_contents('php://stdin'));
		exit(0);
	}
	if ($inPhar) {
		if (!file_exists($argv[1])) {
			$argv[1] = getcwd() . DIRECTORY_SEPARATOR . $argv[1];
		}
	}
	if (!is_file($argv[1])) {
		fwrite(STDERR, 'File not found: ' . $argv[1] . PHP_EOL);
		exit(255);
	}
	if ('-' == $opts['o']) {
		echo $fmt->formatCode(file_get_contents($argv[1]));
		exit(0);
	}
	$argv = array_values($argv);
	file_put_contents($opts['o'], $fmt->formatCode(file_get_contents($argv[1])));
} elseif (isset($argv[1])) {
	if ('-' == $argv[1]) {
		echo $fmt->formatCode(file_get_contents('php://stdin'));
		exit(0);
	}
	$fileNotFound = false;
	$start = microtime(true);
	fwrite(STDERR, 'Formatting ...' . PHP_EOL);
	$missingFiles = [];
	$fileCount = 0;

	$cacheHitCount = 0;
	$workers = 4;

	$hasFnSeparator = false;

		$filesChanged = false;

	for ($j = 1; $j < $argc; ++$j) {
		$arg = &$argv[$j];
		if (!isset($arg)) {
			continue;
		}
		if ('--' == $arg) {
			$hasFnSeparator = true;
			continue;
		}
		if ($inPhar && !file_exists($arg)) {
			$arg = getcwd() . DIRECTORY_SEPARATOR . $arg;
		}
		if (is_file($arg)) {
			$file = $arg;
			if ($lintBefore && !lint($file)) {
				fwrite(STDERR, 'Error lint:' . $file . PHP_EOL);
				continue;
			}
			++$fileCount;
			fwrite(STDERR, '.');
			$fileContents = file_get_contents($file);
			$formattedCode = $fmt->formatCode($fileContents);
			if ($dryRun) {
				if ($fileContents !== $formattedCode) {
					$filesChanged = true;
				}
			} else {
				file_put_contents($file . '-tmp', $formattedCode);
				$oldchmod = fileperms($file);
				rename($file . '-tmp', $file);
				chmod($file, $oldchmod);
			}
		} elseif (is_dir($arg)) {
			fwrite(STDERR, $arg . PHP_EOL);

			$target_dir = $arg;
			$dir = new RecursiveDirectoryIterator($target_dir);
			$it = new RecursiveIteratorIterator($dir);
			$files = new RegexIterator($it, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

			if ($concurrent) {
				$chn = make_channel();
				$chn_done = make_channel();
				if ($concurrent) {
					fwrite(STDERR, 'Starting ' . $workers . ' workers ...' . PHP_EOL);
				}
				for ($i = 0; $i < $workers; ++$i) {
					cofunc(function ($fmt, $backup, $cache_fn, $chn, $chn_done, $lintBefore, $dryRun) {
						$cache = new Cache($cache_fn);
						$cacheHitCount = 0;
						$cache_miss_count = 0;
						$filesChanged = false;
						while (true) {
							$msg = $chn->out();
							if (null === $msg) {
								break;
							}
							$target_dir = $msg['target_dir'];
							$file = $msg['file'];
							if (empty($file)) {
								continue;
							}
							if ($lintBefore && !lint($file)) {
								fwrite(STDERR, 'Error lint:' . $file . PHP_EOL);
								continue;
							}

							$content = $cache->is_changed($target_dir, $file);
							if (false === $content) {
								++$cacheHitCount;
								continue;
							}

							++$cache_miss_count;
							$fmtCode = $fmt->formatCode($content);
							if (null !== $cache) {
								$cache->upsert($target_dir, $file, $fmtCode);
							}
							if ($dryRun) {
								if ($fmtCode !== $content) {
									$filesChanged = true;
								}
							} else {
								file_put_contents($file . '-tmp', $fmtCode);
								$oldchmod = fileperms($file);
								$backup && rename($file, $file . '~');
								rename($file . '-tmp', $file);
								chmod($file, $oldchmod);
							}
						}
						$chn_done->in([$cacheHitCount, $cache_miss_count, $filesChanged]);
					}, $fmt, $backup, $cache_fn, $chn, $chn_done, $lintBefore, $dryRun);
				}
			}

			$progress = new \Symfony\Component\Console\Helper\ProgressBar(
				new \Symfony\Component\Console\Output\StreamOutput(fopen('php://stderr', 'w')),
				sizeof(iterator_to_array($files))
			);
			$progress->start();
			foreach ($files as $file) {
				$progress->advance();
				$file = $file[0];
				if (null !== $ignore_list) {
					foreach ($ignore_list as $pattern) {
						if (false !== strpos($file, $pattern)) {
							continue 2;
						}
					}
				}

				++$fileCount;
				if ($concurrent) {
					$chn->in([
						'target_dir' => $target_dir,
						'file' => $file,
					]);
				} else {
					if (0 == ($fileCount % 20)) {
						fwrite(STDERR, ' ' . $fileCount . PHP_EOL);
					}
					$content = $cache->is_changed($target_dir, $file);
					if (false === $content) {
						++$fileCount;
						++$cacheHitCount;
						continue;
					}
					if ($lintBefore && !lint($file)) {
						fwrite(STDERR, 'Error lint:' . $file . PHP_EOL);
						continue;
					}
					$fmtCode = $fmt->formatCode($content);
					fwrite(STDERR, '.');
					if (null !== $cache) {
						$cache->upsert($target_dir, $file, $fmtCode);
					}
					if ($dryRun) {
						if ($fmtCode !== $content) {
							$filesChanged = true;
						}
					} else {
						file_put_contents($file . '-tmp', $fmtCode);
						$oldchmod = fileperms($file);
						$backup && rename($file, $file . '~');
						rename($file . '-tmp', $file);
						chmod($file, $oldchmod);
					}
				}
			}
			if ($concurrent) {
				for ($i = 0; $i < $workers; ++$i) {
					$chn->in(null);
				}
				for ($i = 0; $i < $workers; ++$i) {
					list($cache_hit, $cache_miss, $filesChanged) = $chn_done->out();
					$cacheHitCount += $cache_hit;
				}
				$chn_done->close();
				$chn->close();
			}
			$progress->finish();
			fwrite(STDERR, PHP_EOL);

			continue;
		} elseif (
			!is_file($arg) &&
			('--' != substr($arg, 0, 2) || $hasFnSeparator)
		) {
			$fileNotFound = true;
			$missingFiles[] = $arg;
			fwrite(STDERR, '!');
		}
		if (0 == ($fileCount % 20)) {
			fwrite(STDERR, ' ' . $fileCount . PHP_EOL);
		}
	}
	fwrite(STDERR, PHP_EOL);
	if (null !== $cache) {
		fwrite(STDERR, ' ' . $cacheHitCount . ' files untouched (cache hit)' . PHP_EOL);
	}
	fwrite(STDERR, ' ' . $fileCount . ' files total' . PHP_EOL);
	fwrite(STDERR, 'Took ' . round(microtime(true) - $start, 2) . 's' . PHP_EOL);
	if (sizeof($missingFiles)) {
		fwrite(STDERR, 'Files not found: ' . PHP_EOL);
		foreach ($missingFiles as $file) {
			fwrite(STDERR, "\t - " . $file . PHP_EOL);
		}
	}
	if ($dryRun && $filesChanged) {
		exit(1);
	}
	if ($fileNotFound) {
		exit(255);
	}
} else {
	showHelp($argv, $enableCache, $inPhar);
}
exit(0);

	}
}

__HALT_COMPILER();
