<?php

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

if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN' && !ini_get('short_open_tag')) {
    unset($argv[0]);
    $ret = 0;
    passthru($_SERVER['_'] . ' -dshort_open_tag=1 ' . __DIR__ . DIRECTORY_SEPARATOR . 'run_all_tests.php ' . implode(' ', $argv) . ' 2>&1', $ret);
    exit($ret);
}

# Copyright (c) 2014, Carlos C
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

$shortTagEnabled = ini_get('short_open_tag');
$opt = getopt('v', ['verbose', 'coverage', 'coveralls', 'testNumber:', 'stop', 'baseline']);
$isCoverage = isset($opt['coverage']) || isset($opt['coveralls']);
$isCoveralls = isset($opt['coveralls']);
if ($isCoverage) {
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Exception.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Report/HTML/Renderer.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Report/Node.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Report/Node/Iterator.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Util.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Report/HTML/Renderer/File.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Report/HTML/Renderer/Directory.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Report/HTML/Renderer/Dashboard.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Report/Node/File.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Report/Node/Directory.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Report/Factory.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Report/HTML.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Report/Clover.php';
    require 'vendor/phpunit/php-token-stream/src/Token/Stream.php';
    require 'vendor/sebastian/version/src/Version.php';
    require 'vendor/symfony/yaml/Yaml.php';
    require 'vendor/phpunit/php-text-template/src/Template.php';
    require 'vendor/phpunit/php-token-stream/src/Token.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Driver.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Driver/Xdebug.php';
    require 'vendor/sebastian/environment/src/Runtime.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage.php';
    require 'vendor/phpunit/php-file-iterator/src/Iterator.php';
    require 'vendor/phpunit/php-file-iterator/src/Factory.php';
    require 'vendor/phpunit/php-file-iterator/src/Facade.php';
    require 'vendor/phpunit/php-code-coverage/src/CodeCoverage/Filter.php';

    $filter = new PHP_CodeCoverage_Filter();
    $filter->addFileToBlacklist('fmt.php');
    $filter->addFileToBlacklist('fmt.src.php');
    $filter->addFileToBlacklist('fmt-external.php');
    $filter->addFileToBlacklist('fmt-external.src.php');
    $filter->addFileToBlacklist('run_all_tests.php');
    $filter->addDirectoryToBlacklist('vendor');
    $coverage = new PHP_CodeCoverage(null, $filter);
}

$testNumber = '';
if (isset($opt['testNumber'])) {
    if (is_numeric($opt['testNumber'])) {
        $testNumber = sprintf('%03d', (int) $opt['testNumber']);
    } else {
        $testNumber = sprintf('%s', $opt['testNumber']);
    }
}

$bogomips = null;
if (isset($opt['baseline'])) {
    echo 'Calculating baseline... ';
    $bogomips = bogomips();
    echo 'done', PHP_EOL;
}

echo 'Starting timer...', PHP_EOL;
$start = microtime(true);
$testEnv = true;
ob_start();
include realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR. 'fmt.stub.php');
ob_end_clean();

echo 'Running tests...' . PHP_EOL;
$brokenTests = [];
$skippedTests = [];

$cases = glob(__DIR__ . DIRECTORY_SEPARATOR . 'Original' . DIRECTORY_SEPARATOR . $testNumber . '*.in');
$count = 0;
$bailOut = false;
foreach ($cases as $caseIn) {
    ++$count;
    $isCoverage && $coverage->start($caseIn);
    $fmt = new CodeFormatter();
    $caseOut = str_replace('.in', '.out', $caseIn);
    $content = file_get_contents($caseIn);
    $tokens = token_get_all($content);
    $specialPasses = false;
    foreach ($tokens as $token) {
        list($id, $text) = getToken($token);
        if (!$shortTagEnabled && (T_INLINE_HTML == $id) && false !== strpos($text, '//skipShortTag')) {
            $skippedTests[] = $caseIn;
            echo 'S';
            continue 2;
        } elseif (T_COMMENT == $id && '//version:' == substr($text, 0, 10)) {
            $version = str_replace('//version:', '', $text);
            if (version_compare(PHP_VERSION, $version, '<')) {
                $skippedTests[] = $caseIn;
                echo 'S';
                continue 2;
            }
        } elseif (T_COMMENT == $id && '//passes:' == substr($text, 0, 9)) {
            $passes = explode(',', str_replace('//passes:', '', $text));
            $specialPasses = true;
            foreach ($passes as $pass) {
                $pass = trim($pass);
                if (false !== strpos($pass, '|')) {
                    $pass = explode('|', $pass);
                    $fmt->forcePass($pass[0], $pass[1]);
                } else {
                    if ('default' != strtolower($pass)) {
                        $fmt->forcePass($pass);
                    } else {
                        $fmt->forcePass('AlignEquals');
                        $fmt->forcePass('AlignDoubleArrow');
                        $fmt->forcePass('ReindentAndAlignObjOps');
                        $fmt->forcePass('ReindentSwitchBlocks');
                    }
                }
            }
        }
    }
    if (!$specialPasses) {
        $fmt->forcePass('AlignEquals');
        $fmt->forcePass('AlignDoubleArrow');
        $fmt->forcePass('ReindentAndAlignObjOps');
        $fmt->forcePass('ReindentSwitchBlocks');
    }

    $got = $fmt->formatCode($content);
    
    $expected = '';
    if (file_exists($caseOut)) {
        $expected = file_get_contents($caseOut);
    }
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $expected = str_replace(PHP_EOL, "\n", $expected);
    }

    if ($got != $expected) {

        echo bin2hex($got);
        echo bin2hex($expected);
        exit(1);
        
        $brokenTests[$caseOut] = $got;
        if (isset($opt['stop'])) {
            $bailOut = true;
            break;
        }
        echo '!';
    } else {
        echo '.';
    }
    stopAtStep();
    $isCoverage && $coverage->stop();
}

$cases = glob(__DIR__ . DIRECTORY_SEPARATOR . 'PSR' . DIRECTORY_SEPARATOR . $testNumber . '*.in');

if (!$bailOut) {
    foreach ($cases as $caseIn) {
        ++$count;
        $isCoverage && $coverage->start($caseIn);
        $fmt = new CodeFormatter();
        $caseOut = str_replace('.in', '.out', $caseIn);
        $content = file_get_contents($caseIn);
        $tokens = token_get_all($content);
        $specialPasses = false;
        foreach ($tokens as $token) {
            list($id, $text) = getToken($token);
            if (T_COMMENT == $id && '//version:' == substr($text, 0, 10)) {
                $version = str_replace('//version:', '', $text);
                if (version_compare(PHP_VERSION, $version, '<')) {
                    $skippedTests[] = $caseIn;
                    echo 'S';
                    continue 2;
                }
            } elseif (!$shortTagEnabled && (T_INLINE_HTML == $id) && false !== strpos($text, '//skipShortTag')) {
                $skippedTests[] = $caseIn;
                echo 'S';
                continue 2;
            } elseif (T_COMMENT == $id && '//passes:' == substr($text, 0, 9)) {
                $passes = explode(',', str_replace('//passes:', '', $text));
                $specialPasses = true;
                foreach ($passes as $pass) {
                    $pass = trim($pass);
                    if ('default' == strtolower($pass)) {
                        $fmt->forcePass('AlignEquals');
                        $fmt->forcePass('AlignDoubleArrow');
                        $fmt->forcePass('ReindentAndAlignObjOps');
                        $fmt->forcePass('ReindentSwitchBlocks');
                        PsrDecorator::decorate($fmt);
                    } else {
                        $fmt->forcePass($pass);
                    }
                }
            }
        }
        if (!$specialPasses) {
            $fmt->forcePass('AlignEquals');
            $fmt->forcePass('AlignDoubleArrow');
            $fmt->forcePass('ReindentAndAlignObjOps');
            $fmt->forcePass('ReindentSwitchBlocks');
            PsrDecorator::decorate($fmt);
        }

        $got = $fmt->formatCode($content);
        $expected = '';
        if (file_exists($caseOut)) {
            $expected = file_get_contents($caseOut);
        }
        if ($got != $expected) {
            $brokenTests[$caseOut] = $got;
            if (isset($opt['stop'])) {
                $bailOut = true;
                break;
            }
            echo '!';
        } else {
            echo '.';
        }
        stopAtStep();
        $isCoverage && $coverage->stop();
    }
}

echo PHP_EOL;
echo 'Tests:', $count . PHP_EOL;
echo 'Broken:', sizeof($brokenTests) . PHP_EOL;
if (isset($opt['v']) || isset($opt['verbose'])) {
    foreach ($brokenTests as $caseOut => $test) {
        file_put_contents($caseOut . '-got', $test);
        passthru('diff -u ' . $caseOut . ' ' . $caseOut . '-got 2>&1');
        unlink($caseOut . '-got');
    }
}

echo 'Took ', (microtime(true) - $start);
if (!is_null($bogomips)) {
    echo ' at ', $bogomips, ' bogomips';
}
echo PHP_EOL;

if ($isCoverage && !$isCoveralls) {
    $writer = new PHP_CodeCoverage_Report_HTML();
    $writer->process($coverage, './cover/');
}
if ($isCoveralls) {
    $writer = new PHP_CodeCoverage_Report_Clover();
    $writer->process($coverage, './clover.xml');
    $report = $coverage->getReport();
    foreach ($report as $item) {
        if (!$item instanceof PHP_CodeCoverage_Report_Node_File) {
            continue;
        }

        $file = file($item->getPath());
        $touchedLines = [];
        $cts = $item->getClassesAndTraits();
        foreach ($cts as $ct => $attr) {
            foreach ($attr['methods'] as $method => $mAttr) {
                if (0 == $mAttr['executableLines'] || $mAttr['executableLines'] > 0 && 0 == $mAttr['coverage']) {
                    continue;
                }
                $touchedLines[$attr['startLine']] = $attr['startLine'];
                $touchedLines[$attr['endLine']] = $attr['endLine'];
                $touchedLines[$mAttr['startLine']] = $mAttr['startLine'];
                $touchedLines[$mAttr['endLine']] = $mAttr['endLine'];
                foreach ($item->getCoverageData() as $line => $tests) {
                    if (empty($tests)) {
                        continue;
                    }
                    $touchedLines[$line] = $line;
                }
            }
        }
        if (empty($touchedLines)) {
            continue;
        }

        $newFile = [];
        sort($touchedLines, SORT_NUMERIC);
        foreach ($touchedLines as $line) {
            $newFile[] = $file[$line - 1];
        }

        echo implode('', $newFile), PHP_EOL;
    }

}
if (sizeof($brokenTests) > 0) {
    echo 'run run_all_tests.php -v to see the error diffs', PHP_EOL;
    exit(1);
}

if (sizeof($skippedTests) > 0) {
    echo 'Skipped tests:', PHP_EOL, implode(PHP_EOL, $skippedTests), PHP_EOL;
}
exit(0);

function getToken($token){
    if (is_string($token)) {
        return [$token, $token];
    } else {
        return $token;
    }
}

function bogomips() {
    // Please consider using http://pecl.php.net/package/hrtime
    // Wall clock is susceptible to changes in OS date/time, eg. NTP induced
    for ($loops = 1; $loops > 0; $loops <<= 1) {
        $start = time();
        delay($loops);
        $end = time() - $start;

        if ($end > 1) {
            $bogomips = $loops / $end / 500000;
            return sprintf('%0.2f', $bogomips);
        }
    }

    return;
}

function delay($loops) {
    for ($i = 0; $i < $loops; ++$i);
}

function stopAtStep() {
    if ('1' === getenv('FMTDEBUG') || 'profile' === getenv('FMTDEBUG')) {
        readline();
    }
}