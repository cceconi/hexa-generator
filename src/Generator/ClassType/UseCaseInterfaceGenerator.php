<?php

namespace Apido\HexaGenerator\Generator\ClassType;

class UseCaseInterfaceGenerator extends AbstractCodeGenerator
{
    protected function writeFile(string $businessName, string $classContent, string $useCaseName, string $targetDir): void
    {
        $useCaseDir = "$targetDir/Api/$businessName";
        if (!is_dir($useCaseDir)) {
            mkdir($useCaseDir, 0775, true);
        }
        $useCaseFile = "{$useCaseDir}/{$useCaseName}UseCaseInterface.php";
        if (!is_file($useCaseFile)) {
            file_put_contents($useCaseFile, $classContent);
        }
    }
}
