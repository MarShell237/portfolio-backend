<?php

namespace App\Helpers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Symfony\Component\HttpFoundation\Response;

class MorphResolver
{
    public static function resolve(string $type, int $id): Model
    {
        $modelClass = Relation::getMorphedModel($type);

        if (! $modelClass) {
            throw new Exception("Invalid Model type [$type]", Response::HTTP_BAD_REQUEST);
        }

        return $modelClass::findOrFail($id);
    }
}
