<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAddMissingParamAnnotationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesOrderFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\MethodChainingNewlineFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__.'/tests',
    ])
    ->withParallel()
    ->withSets([SetList::PSR_12, SetList::CLEAN_CODE, SetList::STRICT])
    ->withRules([
        DeclareStrictTypesFixer::class,
        OrderedClassElementsFixer::class,
    ])
    ->withConfiguredRule(VisibilityRequiredFixer::class, [
        'elements' => [
            'const',
            'property',
            'method',
        ],
    ])
    ->withConfiguredRule(ArraySyntaxFixer::class, ['syntax' => 'short'])
    ->withConfiguredRule(ConcatSpaceFixer::class, ['spacing' => 'none'])
    ->withConfiguredRule(PhpdocAddMissingParamAnnotationFixer::class, ['only_untyped' => false])
    ->withConfiguredRule(PhpdocTypesOrderFixer::class, [
        'null_adjustment' => 'always_last',
        'sort_algorithm' => 'none',
    ])
    ->withConfiguredRule(YodaStyleFixer::class, [
        'equal' => false,
        'identical' => false,
        'always_move_variable' => false,
    ])
    ->withConfiguredRule(OrderedImportsFixer::class, [
        'imports_order' => [
            'class',
            'function',
            'const',
        ],
    ])
    ->withConfiguredRule(BlankLineBeforeStatementFixer::class, [
        'statements' => [
            'case',
            'continue',
            'declare',
            'default',
            'do',
            'exit',
            'for',
            'foreach',
            'goto',
            'if',
            'include',
            'include_once',
            'require',
            'require_once',
            'return',
            'switch',
            'throw',
            'try',
            'while',
            'yield',
    ]])
    ->withSkip([MethodChainingNewlineFixer::class, LineLengthFixer::class])
;
