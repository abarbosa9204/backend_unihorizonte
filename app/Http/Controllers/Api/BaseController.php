<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use TCG\Voyager\Models\Setting;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function settings()
    {
        $settingsArray = [];
        $environment = DB::table('settings')->select(['display_name', 'value'])->where(['display_name' => 'ENVIRONMENT'])->take(1)->get();
        if (count($environment) > 0) {
            $settingsArray['app_env'] = $environment[0]->value;
        } else {
            $settingsArray['app_env'] = 'development';
        }

        $settings = DB::table('settings')->select(['display_name', 'value', 'details'])->where('details', $settingsArray['app_env'])->get();
        foreach ($settings as $value) {
            if ($value->display_name != 'ENVIRONMENT') {
                $settingsArray[$value->display_name] = $value->value;
            }
        }
        return $settingsArray;
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = Response::HTTP_NOT_FOUND)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
