<?php

declare(strict_types=1);

namespace App\Common\Helpers;

final class Helpers
{
    public static function extractDataAttributes(
        array $request_data
    ): array {
        return data_get(
            $request_data,
            key: 'data.attributes'
        );
    }

    public static function extractIncludedAttributes(
        array $request_data
    ): array {
        return data_get(
            $request_data,
            key: 'attributes'
        );
    }
}
