<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Alias\MbStrFunctionsFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set('skip', [
        MbStrFunctionsFixer::class => ['src/Stringify.php'],
        'PHP_CodeSniffer\Standards\Generic\Sniffs\NamingConventions\UpperCaseConstantNameSniff.ClassConstantNotUpperCase' => ['src/functions.php'],
    ]);

    $containerConfigurator->import(__DIR__ . '/vendor/lmc/coding-standard/ecs.php');

    $services = $containerConfigurator->services();

    $services->set(NoSuperfluousPhpdocTagsFixer::class)
        ->call('configure', [['allow_mixed' => true]]);
};
