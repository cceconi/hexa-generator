<?php declare(strict_types=1);

namespace {{namespace}}\UseCase\{{business}};

use Apido\HexaLib\UseCase\AbstractUseCase;
use Apido\HexaLib\UseCase\UseCaseInterface;
use {{namespace}}\Api\{{usecase}}UseCaseInterface;
use {{namespace}}\UseCase\{{business}}\Event\{{usecase}}Event;
use {{namespace}}\UseCase\{{business}}\Message\{{usecase}}Result;
use Psr\Log\LoggerInterface;

final class {{usecase}}UseCase extends AbstractUseCase implements UseCaseInterface, {{usecase}}UseCaseInterface
{
    public function __construct(
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
    }
    
    public function apply({{usecase}}Event $event): void
    {
        $this->handle($event, function ({{usecase}}Event $event): {{usecase}}Result {
            $event->hasPermission();
            return new {{usecase}}Result();
        });
    }
}