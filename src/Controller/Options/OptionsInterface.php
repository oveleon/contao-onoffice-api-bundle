<?php

namespace Oveleon\ContaoOnofficeApiBundle\Controller\Options;

interface OptionsInterface
{
    function get(): array;

    function add(array $data): void;

    function setMode(int $mode): void;

    function isValid(string $key): bool;

    function validate(array $param, bool $includeRequestParameter): array;
}
