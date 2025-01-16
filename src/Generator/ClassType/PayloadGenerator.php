<?php

namespace Apido\HexaGenerator\Generator\ClassType;

class PayloadGenerator extends AbstractCodeGenerator
{
    protected function writeFile(string $businessName, string $classContent, string $useCaseName, string $targetDir): void
    {
        $payloadDir = "$targetDir/UseCase/$businessName/Message";
        if (!is_dir($payloadDir)) {
            mkdir($payloadDir, 0775, true);
        }
        $payloadFile = "{$payloadDir}/{$useCaseName}Payload.php";
        if (!is_file($payloadFile)) {
            file_put_contents($payloadFile, $classContent);
        }
    }
}
