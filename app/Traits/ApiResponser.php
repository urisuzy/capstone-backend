<?php

namespace App\Traits;

trait ApiResponser
{
  public function successResponse($result, $code = 200)
  {
    return response()->json([
      'code' => $code,
      'error' => false,
      'message' => 'success',
      'result' => $result
    ], $code);
  }

  public function errorResponse($message, $code = 422)
  {
    info($message);
    return response()->json([
      'code' => $code,
      'error' => true,
      'message' => $message
    ], $code);
  }
}
