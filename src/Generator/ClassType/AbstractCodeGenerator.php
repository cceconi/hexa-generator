<?php

namespace Apido\HexaGenerator\Generator\ClassType;

abstract class AbstractCodeGenerator
{   
    protected string $templatePath;
    protected string $templateName;

    public function __construct(string $templatePath, string $templateName)
    {
        $this->templatePath = $templatePath;
        $this->templateName = $templateName;
    }

    public function generate(string $businessName, string $useCaseName, string $targetDir, string $namespace): void
    {
        if (!is_file($this->templatePath . '/' . $this->templateName)) {
            throw new \RuntimeException('Template file not found: ' . $this->templatePath . '/' . $this->templateName);
        }
        $classContent = file_get_contents($this->templatePath . '/' . $this->templateName);

        $classContent = str_replace('{{namespace}}', $namespace, $classContent);
        $classContent = str_replace('{{business}}', $businessName, $classContent);
        $classContent = str_replace('{{usecase}}', $useCaseName, $classContent);
        $this->writeFile($businessName, $classContent, $useCaseName, $targetDir);
    }

    abstract protected function writeFile(string $businessName, string $classContent, string $useCaseName, string $targetDir): void;
}
