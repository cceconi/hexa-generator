<?php

namespace Apido\HexaGenerator\Command;

use Apido\HexaGenerator\Generator\ClassType;
use Apido\HexaGenerator\Generator\CodeGeneratorService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateUseCaseCommand extends Command
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
        $this->setName('hexa:generate:use-case')
            ->setDescription('Generate all use case classes')
            ->addArgument("business", InputArgument::OPTIONAL, "The business theme of your use case")
            ->addArgument("usecase", InputArgument::OPTIONAL, "The name of the use case")
            ->addArgument("target-dir", InputArgument::OPTIONAL, "The directory where the use case will be generated, must be your Domain directory")
            ->addArgument("namespace", InputArgument::OPTIONAL, "The namespace of your Domain (eg. App\Domain)")
            ->setHelp("This command allows you to generate all classes needed for a use case (use case, event, payload and result classes)");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $businessName = $input->getArgument("business");
        $useCaseName = $input->getArgument("usecase");
        $targetDir = $input->getArgument("target-dir");
        $namespace = $input->getArgument("namespace");
        $io = new SymfonyStyle($input, $output);
        $io->title("Generating use case: $useCaseName");

        $io->section("Input information");

        if (is_null($businessName)) {
            $businessName = $io->ask("Enter your domain", null, function ($businessName): string {
                if (empty($businessName)) {
                    throw new \InvalidArgumentException("Domain name cannot be empty");
                }
                if (!is_string($businessName)) {
                    throw new \InvalidArgumentException("Domain name must be a string");
                }
                return $businessName;
            });
        }

        if (is_null($useCaseName)) {
            $useCaseName = $io->ask("Enter your use case name", null, function ($useCaseName): string {
                if (empty($useCaseName)) {
                    throw new \InvalidArgumentException("Use case name cannot be empty");
                }
                if (!is_string($useCaseName)) {
                    throw new \InvalidArgumentException("Use case name must be a string");
                }
                return $useCaseName;
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

        $io->writeln("Business theme Name: " . $businessName);
        $io->writeln("Use case Name: " . $useCaseName);
        $io->writeln("Target directory: " . realpath($targetDir));
        $io->writeln("Namespace: " . $namespace);

        if(!$io->confirm('Do you want to continue?', false)) {
            $io->warning("Command aborted");
            return Command::SUCCESS;
        }

        $io->section("Generate classes");

        $progressBar = $this->getProgressBar($output);
        $progressBar->setMaxSteps(100);
        $progressBar->setMessage("Prepare output dir...", 'status');
        $progressBar->start();
        $this->codeGenerator->prepareOutputDir($targetDir);
        $progressBar->setMessage("Generating use case classes...", 'status');
        $progressBar->advance(20);
        $this->codeGenerator->generate($businessName, $useCaseName, $targetDir, $namespace, ClassType::USE_CASE);
        $this->codeGenerator->generate($businessName, $useCaseName, $targetDir, $namespace, ClassType::USE_CASE_INTERFACE);
        $progressBar->setMessage("Generating event class...", 'status');
        $progressBar->advance(20);
        $this->codeGenerator->generate($businessName, $useCaseName, $targetDir, $namespace, ClassType::EVENT);
        $progressBar->setMessage("Generating payload class...", 'status');
        $progressBar->advance(20);
        $this->codeGenerator->generate($businessName, $useCaseName, $targetDir, $namespace, ClassType::PAYLOAD);
        $progressBar->setMessage("Generating result class...", 'status');
        $progressBar->advance(20);
        $this->codeGenerator->generate($businessName, $useCaseName, $targetDir, $namespace, ClassType::RESULT);
        $progressBar->setMessage("Generation finished", 'status');
        $progressBar->advance(20);
        $progressBar->finish();
        $io->writeln("");
        $io->writeln("");

        $io->success("Use case classes availables");

        $io->section("Generate roles");

        while($io->confirm('Do you want to generate new roles for your domain user?', false)) {
            $roleName = $io->ask("Enter the role name", null, function ($roleName): string {
                if (empty($roleName)) {
                    throw new \InvalidArgumentException("Role name cannot be empty");
                }
                if (!is_string($roleName)) {
                    throw new \InvalidArgumentException("Role name must be a string");
                }
                return $roleName;
            });

            $this->codeGenerator->generate($businessName, $roleName, $targetDir, $namespace, ClassType::ROLE);
            $io->success("Role class $roleName available");
        }

        $io->info("Thank you for using HexaGenerator");
        return Command::SUCCESS;
    }

    private function getProgressBar(OutputInterface $output): ProgressBar
    {
        $progressBar = new ProgressBar($output);
        $progressBar->setBarCharacter('<fg=green>●</>');
        $progressBar->setEmptyBarCharacter("<fg=red>○</>");
        $progressBar->setProgressCharacter("<fg=yellow>◐</>");
        $progressBar->setFormat(
            "<fg=white;bg=cyan> %status:-39s%</>\n%current%/%max% %bar% %percent:3s%%"
        );
        $progressBar->setRedrawFrequency(1);
        $progressBar->maxSecondsBetweenRedraws(0.1);
        $progressBar->minSecondsBetweenRedraws(0.01);
        $progressBar->setMessage("Starting...", 'status');
        return $progressBar;
    }
}
