<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Validator\DependencyInjection;

use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class AddConstraintValidatorsPass implements CompilerPassInterface
{
    private $validatorFactoryServiceId;
    private $constraintValidatorTag;

    public function __construct($validatorFactoryServiceId = 'validator.validator_factory', $constraintValidatorTag = 'validator.constraint_validator')
    {
        $this->validatorFactoryServiceId = $validatorFactoryServiceId;
        $this->constraintValidatorTag = $constraintValidatorTag;
    }

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->validatorFactoryServiceId)) {
            return;
        }

        $validators = array();
        foreach ($container->findTaggedServiceIds($this->constraintValidatorTag) as $id => $attributes) {
            $definition = $container->getDefinition($id);

            if ($definition->isAbstract()) {
                continue;
            }

            if (isset($attributes[0]['alias'])) {
                $validators[$attributes[0]['alias']] = new ServiceClosureArgument(new Reference($id));
            }

            $validators[$definition->getClass()] = new ServiceClosureArgument(new Reference($id));
        }

        $container
            ->getDefinition('validator.validator_factory')
            ->replaceArgument(0, (new Definition(ServiceLocator::class, array($validators)))->addTag('container.service_locator'))
        ;
    }
}
