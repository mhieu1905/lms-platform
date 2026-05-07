<?php 
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogService 
{
    public static function log($action, $model = null, $modelId = null, $message = null, $requestData = [])
    {
        $logMessage = strtoupper($action);

        if ($model && $modelId) {
            $logMessage .= " [$model:$modelId]";
        }

        if ($message) {
            $logMessage .= ' - ' . self::toJson($message);
        }

        Log::info($logMessage, [
            'request_data' => self::toJson($requestData),
            'user_id' => Auth::id(),
            'user_agent' => request()->header('User-Agent'),
            'ip' => request()->ip(), 
        ]);
    }

    private static function toJson($data) 
    {
        if (is_array($data) || is_object($data)) {
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return json_last_error() == JSON_ERROR_NONE ? $json : '[Invalid JSON: ' . json_last_error() . ']';
        }
        return $data;
    }
}
?>