<?php

namespace App;

use InvalidArgumentException;

class RequestHelper
{
    /**
     * Získá a validuje stránkovací parametry z pole (např. $_GET nebo jiný zdroj)
     * @param array $input
     * @return array [page:int, perPage:int]
     * @throws InvalidArgumentException Pokud parametry nejsou platné
     */
    public static function getPaginationParams(array $input): array
    {
        $pageRaw = filter_var($input['page'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPageRaw = filter_var($input['per_page'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if ($pageRaw === false && isset($input['page'])) {
            throw new InvalidArgumentException('Parametr "page" musí být celé číslo větší nebo rovno 1.');
        }
        if ($perPageRaw === false && isset($input['per_page'])) {
            throw new InvalidArgumentException('Parametr "per_page" musí být celé číslo větší nebo rovno 1.');
        }

        $page = $pageRaw !== null ? (int)$pageRaw : 1;
        $perPage = $perPageRaw !== null ? (int)$perPageRaw : 10;

        return [$page, $perPage];
    }

    /**
     * Z validovaných dat připraví pole pro Paginator
     * @param mixed $data
     * @return array
     * @throws InvalidArgumentException
     */
    public static function validateData(array $data): array
    {
        // Pro jednoduchost předpokládáme, že data jsou pole
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Pole "data" musí být platné pole.');
        }
        return $data;
    }
}
