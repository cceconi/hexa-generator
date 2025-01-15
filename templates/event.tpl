<?php declare(strict_types=1);

namespace {{namespace}}\UseCase\{{business}}\Event;

use Apido\HexaLib\Event\AbstractEvent;
use Apido\HexaLib\Event\EventInterface;
use Apido\HexaLib\Role\AbstractRole;
use Apido\HexaLib\Role\GuestRole;
use Apido\HexaLib\Role\AuthenticatedRole;
use {{namespace}}\UseCase\{{business}}\Message\{{usecase}}Payload;
use {{namespace}}\UseCase\{{business}}\Message\{{usecase}}Result;

class {{usecase}}Event extends AbstractEvent implements EventInterface
{
    public function getPayload(): {{usecase}}Payload
    {
        return $this->payload;
    }

    public function getResult(): {{usecase}}Result
    {
        return $this->result;
    }

    protected function getPermissions(): array
    {
        return [
            GuestRole::class => AbstractRole::FORBIDDEN,
            AuthenticatedRole::class => AbstractRole::ALLOW
        ];
    }
}