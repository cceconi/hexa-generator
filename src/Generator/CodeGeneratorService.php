<?php

namespace Apido\HexaGenerator\Generator;

use Apido\HexaGenerator\Generator\ClassType\AbstractCodeGenerator;
use Apido\HexaGenerator\Generator\ClassType\EventGenerator;
use Apido\HexaGenerator\Generator\ClassType\PayloadGenerator;
use Apido\HexaGenerator\Generator\ClassType\ResultGenerator;
use Apido\HexaGenerator\Generator\ClassType\RoleGenerator;
use Apido\HexaGenerator\Generator\ClassType\UseCaseGenerator;
use Apido\HexaGenerator\Generator\ClassType\UseCaseInterfaceGenerator;

class CodeGeneratorService
{
    private string $templatePath;

    public function __construct(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    public function prepareOutputDir(string $targetDir): void
    {
        if (!is_dir("$targetDir/Api")) {
            mkdir("$targetDir/Api", 0775, false);
        }
        if (!is_dir("$targetDir/Exception")) {
            mkdir("$targetDir/Exception", 0775, false);
        }
        if (!is_dir("$targetDir/Spi")) {
            mkdir("$targetDir/Spi", 0775, false);
        }
        if (!is_dir("$targetDir/UseCase")) {
            mkdir("$targetDir/UseCase", 0775, false);
        }
        if (!is_dir("$targetDir/Shared/Role")) {
            mkdir("$targetDir/Shared/Role", 0775, true);
        }
    }

    public function generate(string $businessName, string $useCaseName, string $targetDir, string $namespace, string $type): void
    {
        $generator = $this->getCodeGeneratorFromType($type);
        $generator->generate($businessName, $useCaseName, $targetDir, $namespace);
    }

    private function getCodeGeneratorFromType(string $type): AbstractCodeGenerator
    {
        switch ($type) {
            case ClassType::USE_CASE:
                return new UseCaseGenerator($this->templatePath, ClassType::USE_CASE);
            case ClassType::USE_CASE_INTERFACE:
                return new UseCaseInterfaceGenerator($this->templatePath, ClassType::USE_CASE_INTERFACE);
            case ClassType::EVENT:
                return new EventGenerator($this->templatePath, ClassType::EVENT);
            case ClassType::PAYLOAD:
                return new PayloadGenerator($this->templatePath, ClassType::PAYLOAD);
            case ClassType::RESULT:
                return new ResultGenerator($this->templatePath, ClassType::RESULT);
            case ClassType::ROLE:
                return new RoleGenerator($this->templatePath, ClassType::ROLE);
        }
        throw new \InvalidArgumentException('Invalid type');
    }
}
