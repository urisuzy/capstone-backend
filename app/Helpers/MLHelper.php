<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class MLHelper
{
  public static function convertLocalToML(array $tourismIds): array
  {
    return DB::table('tourism_temporaries')->whereIn('place_id', $tourismIds)->pluck('tempat')->toArray();
  }

  public static function convertMLToLocal(array $tempat): array
  {
    $arr = [];
    foreach ($tempat as $id) {
      $temp = DB::table('tourism_temporaries')->where('tempat', $id)->first();
      if ($temp) $arr[] = $temp->place_id;
    }
    return $arr;
  }
}
