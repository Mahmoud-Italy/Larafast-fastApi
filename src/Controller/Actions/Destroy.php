<?php
namespace Larafast\Fastapi\Controller\Actions;

use Closure;

trait Destroy
{

    /**
     * @param $model
     * @param null $reponseSuccess
     * @param null $reponseError
     * @return \Illuminate\Http\JsonResponse
     */
    public function ApiDestroy($model, $reponseSuccess = null, $reponseError = null)
    {
        try {
            $deleted = $model->delete();
            if ($reponseSuccess instanceof Closure) {
                return $reponseSuccess($deleted);
            }
            return response()->json(['message' => 'deleted successfully'], 200);
        } catch (\Exception $e) {
            if ($reponseError instanceof Closure) {
                return $reponseError($e);
            }
            return response()->json(['message' => 'Unable to delete entry, ' . (config('app.debug',true)?$e->getMessage():' Debug is False ')], 500);
        }
    }

}
