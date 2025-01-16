<?php declare(strict_types=1);

namespace {{namespace}}\UseCase\{{business}}\Message;

use Apido\HexaLib\Message\AbstractResult;

final class {{usecase}}Result extends AbstractResult
{    
    public function __construct()
    {
    }
    
    public function __toString(): string
    {
        return "";
    }

    protected function toArray(): array
    {
        return [];
    }
}
