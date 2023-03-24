<?php declare(strict_types=1);

use PhpCsFixer\Fixer\CastNotation\ModernizeTypesCastingFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\FopenFlagsFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\NullableTypeDeclarationForDefaultNullValueFixer;
use PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\OperatorLinebreakFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocOrderFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertInternalTypeFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockShortWillReturnFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestCaseStaticMethodCallsFixer;
use PhpCsFixer\Fixer\ReturnNotation\NoUselessReturnFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\CompactNullableTypehintFixer;
use PhpCsFixerCustomFixers\Fixer\NoImportFromGlobalNamespaceFixer;
use PhpCsFixerCustomFixers\Fixer\NoSuperfluousConcatenationFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessParenthesisFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessStrlenFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocTypesCommaSpacesFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceAfterStatementFixer;
use Symplify\CodingStandard\Fixer\Spacing\StandaloneLineConstructorParamFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->sets([
        SetList::CLEAN_CODE,
        SetList::ARRAY,
        SetList::CONTROL_STRUCTURES,
        SetList::STRICT,
        SetList::PSR_12,
    ]);

    $ecsConfig->rules([
        ModernizeTypesCastingFixer::class,
        FopenFlagsFixer::class,
        NativeConstantInvocationFixer::class,
        NullableTypeDeclarationForDefaultNullValueFixer::class,
        VoidReturnFixer::class,
        OperatorLinebreakFixer::class,
        PhpdocLineSpanFixer::class,
        PhpdocOrderFixer::class,
        PhpUnitConstructFixer::class,
        PhpUnitDedicateAssertInternalTypeFixer::class,
        PhpUnitMockFixer::class,
        PhpUnitMockShortWillReturnFixer::class,
        PhpUnitTestCaseStaticMethodCallsFixer::class,
        NoUselessReturnFixer::class,
        DeclareStrictTypesFixer::class,
        BlankLineBeforeStatementFixer::class,
        CompactNullableTypehintFixer::class,
        NoImportFromGlobalNamespaceFixer::class,
        NoSuperfluousConcatenationFixer::class,
        NoUselessCommentFixer::class,
        SingleSpaceAfterStatementFixer::class,
        NoUselessParenthesisFixer::class,
        NoUselessStrlenFixer::class,
        PhpdocTypesCommaSpacesFixer::class,
        StandaloneLineConstructorParamFixer::class,
    ]);

    $ecsConfig->ruleWithConfiguration(HeaderCommentFixer::class, ['header' => '(c) KruseMedien GmbH <info@krusemedien.com>
For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.', 'separate' => 'bottom', 'location' => 'after_declare_strict', 'comment_type' => 'comment']);
    $ecsConfig->ruleWithConfiguration(ClassAttributesSeparationFixer::class, ['elements' => ['property' => 'one', 'method' => 'one']]);
    $ecsConfig->ruleWithConfiguration(MethodArgumentSpaceFixer::class, ['on_multiline' => 'ensure_fully_multiline']);
    $ecsConfig->ruleWithConfiguration(NativeFunctionInvocationFixer::class, [
        'include' => [NativeFunctionInvocationFixer::SET_COMPILER_OPTIMIZED],
        'scope' => 'namespaced',
        'strict' => false,
    ]);
    $ecsConfig->ruleWithConfiguration(ConcatSpaceFixer::class, ['spacing' => 'one']);
    $ecsConfig->ruleWithConfiguration(GeneralPhpdocAnnotationRemoveFixer::class, ['annotations' => ['copyright', 'category']]);
    $ecsConfig->ruleWithConfiguration(NoSuperfluousPhpdocTagsFixer::class, ['allow_unused_params' => true, 'allow_mixed' => true]);
    $ecsConfig->ruleWithConfiguration(PhpUnitDedicateAssertFixer::class, ['target' => 'newest']);
    $ecsConfig->ruleWithConfiguration(SingleQuoteFixer::class, ['strings_containing_single_quote_chars' => true]);
    // workaround for https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/issues/5495
    $ecsConfig->ruleWithConfiguration(BinaryOperatorSpacesFixer::class, [
        'operators' => [
            '|' => null,
            '&' => null,
        ],
    ]);

    $parameters = $ecsConfig->parameters();
    $parameters->set(Option::CACHE_DIRECTORY, $_SERVER['SHOPWARE_TOOL_CACHE_ECS'] ?? 'var/cache/cs_fixer');
    $parameters->set(Option::CACHE_NAMESPACE, 'KrmePluginReplaceThis');

    $ecsConfig->parallel();

    $ecsConfig->skip([

    ]);
};