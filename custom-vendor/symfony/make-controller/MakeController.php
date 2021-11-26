<?php

/*
 * This file is part of the Symfony MakerBundle package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\MakerBundle\Maker;

use Doctrine\Common\Annotations\Annotation;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;

/**
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Ryan Weaver <weaverryan@gmail.com>
 */
final class MakeController extends AbstractMaker
{
    const AVAILABLE_CONTEXT = ['Fixer', 'Customer', 'Admin'];

    public static function getCommandName(): string
    {
        return 'make:controller';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates a new controller class';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->addArgument('context', InputArgument::REQUIRED, sprintf('Choose a context (e.g. <fg=yellow>%s</>)', implode(', ', self::AVAILABLE_CONTEXT)))
            ->addArgument('controller-class', InputArgument::REQUIRED, sprintf('Choose a name for your controller class (e.g. <fg=yellow>%sController</>)', Str::asClassName(Str::getRandomTerm())))
            ->addOption('no-template', null, InputOption::VALUE_NONE, 'Use this option to disable template generation')
            ->setHelp(file_get_contents(__DIR__ . '/../Resources/help/MakeController.txt'));

        $inputConf->setArgumentAsNonInteractive('context');
        $inputConf->setArgumentAsNonInteractive('controller-class');
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        $contextArgument = $command->getDefinition()->getArgument('context');
        $contextQuestion = new Question($contextArgument->getDescription());
        $contextQuestion->setAutocompleterValues(self::AVAILABLE_CONTEXT);

        $controllerArgument = $command->getDefinition()->getArgument('controller-class');
        $controllerQuestion = new Question($controllerArgument->getDescription());

        do {
            isset($contextValue) && $io->error(sprintf('Invalid context "%s".', $contextValue));
            $contextValue = $io->askQuestion($contextQuestion);
        } while (!in_array($contextValue, self::AVAILABLE_CONTEXT));
        $input->setArgument('context', $contextValue);

        $controllerValue = $io->askQuestion($controllerQuestion);
        $input->setArgument('controller-class', $controllerValue);
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $controllerClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('controller-class'),
            'Controller\\' . $input->getArgument('context') . '\\',
            'Controller'
        );

        $noTemplate = $input->getOption('no-template');
        $templateName = strtolower($input->getArgument('context')) . '/' . Str::asFilePath($controllerClassNameDetails->getRelativeNameWithoutSuffix()) . '/index.html.twig';
        $controllerPath = $generator->generateController(
            $controllerClassNameDetails->getFullName(),
            'controller/Controller.tpl.php',
            [
                'route_path' => Str::asRoutePath($controllerClassNameDetails->getRelativeNameWithoutSuffix()),
                'route_name' => Str::asRouteName($controllerClassNameDetails->getRelativeNameWithoutSuffix()),
                'with_template' => $this->isTwigInstalled() && !$noTemplate,
                'template_name' => $templateName,
            ]
        );

        if ($this->isTwigInstalled() && !$noTemplate) {
            $generator->generateTemplate(
                $templateName,
                'controller/twig_template.tpl.php',
                [
                    'controller_path' => $controllerPath,
                    'root_directory' => $generator->getRootDirectory(),
                    'class_name' => $controllerClassNameDetails->getShortName(),
                ]
            );
        }

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
        $io->text('Next: Open your new controller class and add some pages!');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(
            Annotation::class,
            'doctrine/annotations'
        );
    }

    private function isTwigInstalled()
    {
        return class_exists(TwigBundle::class);
    }
}
