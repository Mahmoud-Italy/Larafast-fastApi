<?php


namespace Larafast\Fastapi\Controller\Actions;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;

trait Update
{
    /**
     * @param $model
     * @param array $validated
     * @param null $reponseSuccess
     * @param null $reponseError
     * @return JsonResponse|mixed
     */
    public function ApiUpdate($model, array $validated = [], $reponseSuccess = null, $reponseError = null)
    {
        try {
            $updated = $model->update($validated);
            if ($reponseSuccess instanceof Closure) {
                return $reponseSuccess($model,$updated);
            }
            return response()->json(['message' => ' Updated Successfully '], 200);
        } catch (Exception $e) {
            if ($reponseError instanceof Closure) {
                return $reponseError($e);
            }
            return response()->json(['message' => 'Unable to update entry, ' . (config('app.debug',true)?$e->getMessage():' Debug is False ')], 500);
        }
    }

}
