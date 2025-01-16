<?php

namespace Apido\HexaGenerator\Generator\ClassType;

class RoleGenerator extends AbstractCodeGenerator
{
    protected function writeFile(string $businessName, string $classContent, string $useCaseName, string $targetDir): void
    {
        $resultDir = "$targetDir/Shared/Role";
        if (!is_dir($resultDir)) {
            mkdir($resultDir, 0775, true);
        }
        $resultFile = "{$resultDir}/{$useCaseName}Role.php";
        if (!is_file($resultFile)) {
            file_put_contents($resultFile, $classContent);
        }
    }
}
