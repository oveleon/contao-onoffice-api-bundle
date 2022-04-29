<?php

namespace Oveleon\ContaoOnofficeApiBundle;

interface OptionsInterface
{
    function get(): array;

    function validate(array $param, bool $includeRequestParameter): array;
}
