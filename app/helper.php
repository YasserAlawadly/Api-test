<?php
if (!function_exists('api_response')) {
    function api_response($status, $message, $data = null, $status_code = 200)
    {
        $response = [
            'status'   =>   $status,
            'message'  =>   $message,
            'data'      =>  $data
        ];
        $pagination =  api_model_set_pagenation($data);
        if ($pagination) $response['pagination'] = $pagination;
        return response()->json($response, $status_code);
    }
}


if (!function_exists('api_model_set_pagenation')) {

    function api_model_set_pagenation($model)
    {
        if (is_object($model)) {
            try {
                $pagnation['total'] = $model->total();
                $pagnation['lastPage'] = $model->lastPage();
                $pagnation['perPage'] = $model->perPage();
                $pagnation['currentPage'] = $model->currentPage();
                return $pagnation;
            } catch (\Exception $e) {
            }
        }
        return null;
    }
}
