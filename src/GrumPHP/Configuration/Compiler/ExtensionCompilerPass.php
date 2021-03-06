<?php

namespace GrumPHP\Configuration\Compiler;

use GrumPHP\Exception\RuntimeException;
use GrumPHP\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ExtensionCompilerPass
 *
 * @package GrumPHP\Configuration\Compiler
 */
class ExtensionCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $extensions = $container->getParameter('extensions');
        $extensions = is_array($extensions) ? $extensions : [];
        foreach ($extensions as $extensionClass) {
            if (!class_exists($extensionClass)) {
                throw new RuntimeException(sprintf('Invalid extension class specified: %s', $extensionClass));
            }

            $extension = new $extensionClass();
            if (!$extension instanceof ExtensionInterface) {
                throw new RuntimeException(sprintf(
                    'Extension class must implement ExtensionInterface. But `%s` is not.',
                    $extensionClass
                ));
            }

            $extension->load($container);
        }
    }
}
