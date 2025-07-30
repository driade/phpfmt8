# [PHPFmt](https://github.com/driade/phpfmt8) for Sublime Text 4

[![PHPFmt](https://github.com/driade/phpfmt8/actions/workflows/github.yml/badge.svg)](https://github.com/driade/phpfmt8/actions/workflows/github.yml)

PHPFmt is a PHP code formatter tailored for Sublime Text 4 with full support for PHP 8.x. It's a continuation of the [phpfmt_stable](https://github.com/nanch/phpfmt_stable) project, with added PHP 8 compatibility and numerous bug fixes.

## Features

- **PHP 8 Support**: Seamlessly format PHP >= 5.6 code.
- **Rich Formatting Options**: Includes PSR-1, PSR-2, and WordPress coding standards, among others.
- **Customization**: Supports a wide array of transformations and formatting tweaks.
- **Easy Integration**: Simple setup and configuration within Sublime Text.

## Installation

### Requirements

- PHP 5.6 or newer installed on your system.
- Sublime Text 4.

### Steps

1. Open Sublime Text and press `Ctrl+Shift+P`.
2. Select `Package Control: Install Package`.
3. Search for `phpfmt` and install it.

## Configuration

### Windows

Edit the configuration file at `%AppData%\Sublime Text\Packages\phpfmt\phpfmt.sublime-settings` and set the `php_bin` path:

```json
{
    "php_bin": "c:/PHP/php.exe"
}
```

### macOS and Linux

Edit `phpfmt.sublime-settings` and set the `php_bin` path:

```json
{
    "php_bin": "/usr/local/bin/php"
}
```

You may find an example configuration file in https://github.com/driade/phpfmt8/blob/master/driade.sublime-settings , where you can see how to configure the extension.

## Usage

PHPFmt provides a variety of commands accessible via the command palette (`Ctrl+Shift+P` or `Cmd+Shift+P`):

- `phpfmt: format now`
- `phpfmt: toggle format on save`
- `phpfmt: toggle PSR2`

... and more.


## Currently Supported Transformations:

 * AddMissingParentheses             Add extra parentheses in new instantiations.
 * AliasToMaster                     Replace function aliases to their masters - only basic syntax alias.
 * AlignConstVisibilityEquals        Vertically align "=" of visibility and const blocks.
 * AlignDoubleArrow                  Vertically align T_DOUBLE_ARROW (=>).
 * AlignDoubleSlashComments          Vertically align "//" comments.
 * AlignEquals                       Vertically align "=".
 * AlignSuperEquals                  Vertically align "=", ".=", "&=", ">>=", etc.
 * AlignGroupDoubleArrow             Vertically align T_DOUBLE_ARROW (=>) by line groups.
 * AlignPHPCode                      Align PHP code within HTML block.
 * AlignTypehint                     Vertically align function type hints.
 * AllmanStyleBraces                 Transform all curly braces into Allman-style.
 * AutoPreincrement                  Automatically convert postincrement to preincrement.
 * AutoSemicolon                     Add semicolons in statements ends.
 * CakePHPStyle                      Applies CakePHP Coding Style
 * ClassToSelf                       "self" is preferred within class, trait or interface.
 * ClassToStatic                     "static" is preferred within class, trait or interface.
 * ConvertOpenTagWithEcho            Convert from "<?=" to "<?php echo ".
 * DocBlockToComment                 Replace docblocks with regular comments when used in non structural elements.
 * DoubleToSingleQuote               Convert from double to single quotes.
 * EchoToPrint                       Convert from T_ECHO to print.
 * EncapsulateNamespaces             Encapsulate namespaces with curly braces
 * GeneratePHPDoc                    Automatically generates PHPDoc blocks
 * IndentTernaryConditions           Applies indentation to ternary conditions.
 * IndentPipeOperator                Applies indentation to the pipe operator (|>).
 * JoinToImplode                     Replace implode() alias (join() -> implode()).
 * LeftWordWrap                      Word wrap at 80 columns - left justify.
 * LongArray                         Convert short to long arrays.
 * MergeElseIf                       Merge if with else.
 * SplitElseIf                       Split if with else.
 * MergeNamespaceWithOpenTag         Ensure there is no more than one linebreak before namespace
 * MildAutoPreincrement              Automatically convert postincrement to preincrement. (Deprecated pass. Use AutoPreincrement instead).
 * NewLineBeforeReturn               Add an empty line before T_RETURN.
 * OrganizeClass                     Organize class, interface and trait structure.
 * OrderAndRemoveUseClauses          Order use block and remove unused imports.
 * OnlyOrderUseClauses               Order use block - do not remove unused imports.
 * OrderMethod                       Organize class, interface and trait structure.
 * OrderMethodAndVisibility          Organize class, interface and trait structure.
 * PHPDocTypesToFunctionTypehint     Read variable types from PHPDoc blocks and add them in function signatures.
 * PrettyPrintDocBlocks              Prettify Doc Blocks
 * PSR2EmptyFunction                 Merges in the same line of function header the body of empty functions.
 * PSR2MultilineFunctionParams       Break function parameters into multiple lines.
 * ReindentAndAlignObjOps            Align object operators.
 * ReindentSwitchBlocks              Reindent one level deeper the content of switch blocks.
 * ReindentEnumBlocks                Reindent one level deeper the content of enum blocks.
 * RemoveIncludeParentheses          Remove parentheses from include declarations.
 * RemoveSemicolonAfterCurly         Remove semicolon after closing curly brace.
 * RemoveUseLeadingSlash             Remove leading slash in T_USE imports.
 * ReplaceBooleanAndOr               Convert from "and"/"or" to "&&"/"||". Danger! This pass leads to behavior change.
 * ReplaceIsNull                     Replace is_null($a) with null === $a.
 * RestoreComments                   Revert any formatting of comments content.
 * ReturnNull                        Simplify empty returns.
 * ShortArray                        Convert old array into new array. (array() -> [])
 * SmartLnAfterCurlyOpen             Add line break when implicit curly block is added.
 * SortUseNameSpace                  Organize use clauses by length and alphabetic order.
 * SpaceAroundControlStructures      Add space around control structures.
 * SpaceAfterExclamationMark         Add space after exclamation mark.
 * SpaceAroundParentheses            Add spaces inside parentheses.
 * SpaceAroundExclamationMark        Add spaces around exclamation mark.
 * SpaceBetweenMethods               Put space between methods.
 * StrictBehavior                    Activate strict option in array_search, base64_decode, in_array, array_keys, mb_detect_encoding. Danger! This pass leads to behavior change.
 * StrictComparison                  All comparisons are converted to strict. Danger! This pass leads to behavior change.
 * StripExtraCommaInArray            Remove trailing commas within array blocks
 * StripNewlineAfterClassOpen        Strip empty lines after class opening curly brace.
 * StripNewlineAfterCurlyOpen        Strip empty lines after opening curly brace.
 * StripNewlineWithinClassBody       Strip empty lines after class opening curly brace.
 * StripSpaces                       Remove all empty spaces
 * StripSpaceWithinControlStructures Strip empty lines within control structures.
 * TightConcat                       Ensure string concatenation does not have spaces, except when close to numbers.
 * TrimSpaceBeforeSemicolon          Remove empty lines before semi-colon.
 * UpgradeToPreg                     Upgrade ereg_* calls to preg_*
 * WordWrap                          Word wrap at 80 columns.
 * WrongConstructorName              Update old constructor names into new ones. http://php.net/manual/en/language.oop5.decon.php
 * YodaComparisons                   Execute Yoda Comparisons.

## What does it do?

<table>
<tr>
<td>Before</td>
<td>After</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
for($i = 0; $i &lt; 10; $i++)
{
if($i%2==0)
echo "Flipflop";
}
</code></pre>
</td>
<td>
<pre><code>&lt;?php
for ($i = 0; $i &lt; 10; $i++) {
  if ($i%2 == 0) {
    echo "Flipflop";
  }
}
</code></pre>
</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
$a = 10;
$otherVar = 20;
$third = 30;
</code></pre>
</td>
<td>
<pre><code>&lt;?php
$a        = 10;
$otherVar = 20;
$third    = 30;
</code></pre>
<i>This can be enabled with the option "enable_auto_align"</i>
</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
namespace NS\Something;
use \OtherNS\C;
use \OtherNS\B;
use \OtherNS\A;
use \OtherNS\D;

$a = new A();
$b = new C();
$d = new D();
</code></pre>
</td>
<td>
<pre><code>&lt;?php
namespace NS\Something;

use \OtherNS\A;
use \OtherNS\C;
use \OtherNS\D;

$a = new A();
$b = new C();
$d = new D();
</code></pre>
<i>note how it sorts the use clauses, and removes unused ones</i>
</td>
</tr>
</table>

### What does it do? - PSR version

<table>
<tr>
<td>Before</td>
<td>After</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
for($i = 0; $i &lt; 10; $i++)
{
if($i%2==0)
echo "Flipflop";
}
</code></pre>
</td>
<td>
<pre><code>&lt;?php
for ($i = 0; $i &lt; 10; $i++) {
    if ($i%2 == 0) {
        echo "Flipflop";
    }
}
</code></pre>
<i>Note the identation of 4 spaces.</i>
</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
class A {
function a(){
return 10;
}
}
</code></pre>
</td>
<td>
<pre><code>&lt;?php
class A
{
    public function a()
    {
        return 10;
    }
}
</code></pre>
<i>Note the braces position, and the visibility adjustment in the method a().</i>
</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
namespace NS\Something;
use \OtherNS\C;
use \OtherNS\B;
use \OtherNS\A;
use \OtherNS\D;

$a = new A();
$b = new C();
$d = new D();
</code></pre>
</td>
<td>
<pre><code>&lt;?php
namespace NS\Something;

use \OtherNS\A;
use \OtherNS\C;
use \OtherNS\D;

$a = new A();
$b = new C();
$d = new D();
</code></pre>
<i>note how it sorts the use clauses, and removes unused ones</i>
</td>
</tr>
</table>

## Troubleshooting

Ensure PHP is accessible from the command line. If issues arise, open an issue [here](https://github.com/driade/phpfmt8/issues).


## Contributing

Contributions are welcome! Please submit pull requests or issues with detailed information and code samples.

## VSCode

If you're using Visual Studio Code, please consider installing  [vscode-phpfmt](https://marketplace.visualstudio.com/items?itemName=kokororin.vscode-phpfmt) extension by @kokororin

This extension leverages the same phpfmt8 engine, providing seamless PHP formatting directly within VS Code.

### Acknowledgements

- GoSublime - for the method to update the formatted buffer
- Google's diff match patch - http://code.google.com/p/google-diff-match-patch/
