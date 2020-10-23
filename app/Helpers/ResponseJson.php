<?php
/**
 * Created by PhpStorm.
 * User: ductho1201
 * Date: 12/19/2018
 * Time: 9:41 AM
 */

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ResponseJson
{
    public function __construct()
    {
    }

    /**
     * @param array $params
     * @return JsonResponse
     */
    public static function responseWithParams(
        $params = [
            'data' => null,
            'successStatus' => true,
            'param' => null,
            'message' => [],
            'statusCode' => null,
            'isSystemError' => false
        ]
    ) {
        //initiate params
        $params['data'] = (isset($params['data'])) ? $params['data'] : null;
        $params['successStatus'] = (isset($params['successStatus'])) ? $params['successStatus'] : true;
        $params['param'] = (isset($params['param'])) ? $params['param'] : null;
        $params['message'] = (isset($params['message'])) ? $params['message'] : [];
        $params['statusCode'] = (isset($params['statusCode'])) ? $params['statusCode'] : null;
        $params['isSystemError'] = (isset($params['isSystemError'])) ? $params['isSystemError'] : false;
        return self::createResultData($params);
    }

    public static function responseSystemError(
        $params = [
            'data' => null,
            'successStatus' => false,
            'param' => null,
            'message' => [],
            'statusCode' => null,
            'isSystemError' => true,
            'exception' => null
        ]
    ) {
        if (!empty($params['exception'])) {
            self::logException($params['exception']);
        }
        if (config('app.debug')) {
            if ($params['exception']) {
                $params['message'] = 'path: ' . $params['exception']->getFile()
                    . '-- line: ' . $params['exception']->getLine()
                    . '-- content: ' . $params['exception']->getMessage();
            }
        }
        return self::createResultData($params);
    }

    /**
     * @param null $data
     * @param null $message
     * @return JsonResponse
     */
    public static function responseSuccess($data = null, $message = null)
    {
        return self::createResultData([
            'data' => $data,
            'successStatus' => true,
            'param' => [],
            'message' => $message
        ]);
    }

    /**
     * @param null $data
     * @param null $message
     * @return JsonResponse
     */
    public static function responseError($data = null, $message = null)
    {
        return self::createResultData([
            'data' => $data,
            'successStatus' => false,
            'param' => [],
            'message' => $message
        ]);
    }

    public static function systemError($exception)
    {
        return self::responseSystemError([
            'data' => null,
            'successStatus' => false,
            'param' => null,
            'message' => [],
            'statusCode' => null,
            'isSystemError' => true,
            'exception' => $exception
        ]);
    }

    /**
     * @param array $params
     * @return JsonResponse
     */
    private static function createResultData($params = [])
    {
        $result = [
            'data' => $params['data'],
            'success' => $params['successStatus'],
            'param' => $params['param'],
            'status' => isset($params['statusCode']) ? $params['statusCode'] : null,
        ];

        $result['message'] = [trans('error.have_problem_please_report')];
        if (config('app.debug') || empty($params['isSystemError'])) {
            $result['message'] = $params['message'];
        }
        return new JsonResponse($result);
    }

    /**
     * @param $exception
     * @return bool
     */
    private static function logException($exception) {
        $message = 'path: ' . $exception->getFile()
            . '-- line: ' . $exception->getLine()
            . '-- content: ' . $exception->getMessage();
        Log::error($message);
        return true;
    }
}