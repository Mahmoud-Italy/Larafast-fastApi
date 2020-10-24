<?php


namespace Larafast\Fastapi\Controller\Actions;


use Closure;
use Exception;
use Illuminate\Http\JsonResponse;

trait Store
{
    /**
     * @param string $model
     * @param array $validated
     * @param null $reponseSuccess
     * @param null $reponseError
     * @return JsonResponse|mixed
     */
    public function ApiStore(string $model, array $validated = [], $reponseSuccess = null, $reponseError = null)
    {
        try {
            $created = $model::create($validated);
            if ($reponseSuccess instanceof Closure) {
                return $reponseSuccess($created);
            }
            return response()->json(['message' => ' Created Successfully '], 200);
        } catch (Exception $e) {
            if ($reponseError instanceof Closure) {
                return $reponseError($e);
            }
            return response()->json(['message' => 'Unable to create entry, ' . (config('app.debug',true)?$e->getMessage():' Debug is False ')], 500);
        }
    }

}
