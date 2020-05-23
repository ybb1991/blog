<?php
namespace App\Transformer;

use App\Transformer\Transformer;

class LessonTransformer extends Transformer{

      public function transform($item)
     {
            return [
                  'title'=>$item['title'],
                  'body'=>$item['body'],
                  'free'=>(boolean) $item['free'],
            ];
     }

}