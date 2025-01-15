<?php declare(strict_types=1);

namespace {{namespace}}\Api\{{business}};

use {{namespace}}\UseCase\{{business}}\Event\{{usecase}}Event;

interface {{usecase}}UseCaseInterface
{
    public function apply({{usecase}}Event $event): void;
}