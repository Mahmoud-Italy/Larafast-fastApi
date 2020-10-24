<?php


namespace Larafast\Fastapi\Controller\Actions;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait Show
{
    public function ApiShow($model, $parameter, $reponseSuccess = null, $reponseError = null)
    {
        if ($data = (new $model)->resolveRouteBinding($parameter)) {
            return $reponseSuccess instanceof Closure ? $reponseSuccess($data) : $data;
        }

        if ($reponseError instanceof Closure) {
            return $reponseError(new ModelNotFoundException());
        }

       return abort(404);
    }

}
