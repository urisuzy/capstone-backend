<?php

namespace App\Traits;

trait ApiResponser
{
  public function successResponse($result, $code = 200)
  {
    return response()->json([
      'code' => $code,
      'success' => true,
      'result' => $result
    ], $code);
  }

  public function errorResponse($message, $code = 422)
  {
    return response()->json([
      'code' => $code,
      'success' => true,
      'message' => $message
    ], $code);
  }
}
