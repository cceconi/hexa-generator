<?php

namespace Apido\HexaGenerator\Command;

use Apido\HexaGenerator\Generator\ClassType;
use Apido\HexaGenerator\Generator\CodeGeneratorService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateRoleCommand extends Command
{
    private CodeGeneratorService $codeGenerator;

    public function __construct()
    {
        parent::__construct();
        $this->codeGenerator = new CodeGeneratorService(
            __DIR__ . "/../../templates"
        );
    }

    protected function configure()
    {
        $this->setName('hexa:generate:role')
            ->setDescription('Generate a role class')
            ->addArgument("role", InputArgument::OPTIONAL, "The name of the role")
            ->addArgument("target-dir", InputArgument::OPTIONAL, "The directory where the use case will be generated, must be your Domain directory")
            ->addArgument("namespace", InputArgument::OPTIONAL, "The namespace of your Domain (eg. App\Domain)")
            ->setHelp("This command allows you to generate a role class needed for your domain user");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $roleName = $input->getArgument("role");
        $targetDir = $input->getArgument("target-dir");
        $namespace = $input->getArgument("namespace");
        $io = new SymfonyStyle($input, $output);
        $io->title("Generate a role class");

        $io->section("Input information");

        if (is_null($roleName)) {
            $roleName = $io->ask("Enter your role", null, function ($roleName): string {
                if (empty($roleName)) {
                    throw new \InvalidArgumentException("Role name cannot be empty");
                }
                if (!is_string($roleName)) {
                    throw new \InvalidArgumentException("Role name must be a string");
                }
                return $roleName;
            });
        }

        if (is_null($targetDir)) {
            $targetDir = $io->ask("Enter the target directory", ".", function ($targetDir): string {
                if (empty($targetDir)) {
                    throw new \InvalidArgumentException("Target directory cannot be empty");
                }
                if (!is_string($targetDir)) {
                    throw new \InvalidArgumentException("Target directory must be a string");
                }
                if (!is_dir(realpath($targetDir))) {
                    throw new \InvalidArgumentException("Target directory must be a valid directory");
                }
                return $targetDir;
            });
        }

        if (is_null($namespace)) {
            $namespace = $io->ask("Enter the namespace", null, function ($namespace): string {
                if (empty($namespace)) {
                    throw new \InvalidArgumentException("Namespace cannot be empty");
                }
                if (!is_string($namespace)) {
                    throw new \InvalidArgumentException("Namespace must be a string");
                }
                return $namespace;
            });
        }

        $io->writeln("Role Name: " . $roleName);
        $io->writeln("Target directory: " . realpath($targetDir));
        $io->writeln("Namespace: " . $namespace);

        if(!$io->confirm('Do you want to continue?', false)) {
            $io->warning("Command aborted");
            return Command::SUCCESS;
        }

        $io->section("Generate role");

        $this->codeGenerator->prepareOutputDir($targetDir);
        $this->codeGenerator->generate("", $roleName, $targetDir, $namespace, ClassType::ROLE);
        $io->success("Role class $roleName available");

        while($io->confirm('Do you want to generate anothers roles for your domain user?', false)) {
            $roleName = $io->ask("Enter the role name", null, function ($roleName): string {
                if (empty($roleName)) {
                    throw new \InvalidArgumentException("Role name cannot be empty");
                }
                if (!is_string($roleName)) {
                    throw new \InvalidArgumentException("Role name must be a string");
                }
                return $roleName;
            });

            $this->codeGenerator->generate("", $roleName, $targetDir, $namespace, ClassType::ROLE);
            $io->success("Role class $roleName available");
        }

        $io->info("Thank you for using HexaGenerator");
        return Command::SUCCESS;
    }
}
