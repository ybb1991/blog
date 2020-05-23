<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Transformer\LessonTransformer;

class LessonsController extends ApiController
{
    protected $lessonTransform;

    // public function __construct(LessonTransformer $lessonTransform)
    // {
    //     $this->lessonTransform = $lessonTransform;
    // }
    public function index()
    {
        /**
         * lesson::all();
         * 没有提示信息
         * 直接展示我们的数据结构
         * 没有错误信息
         */

        $lessons = Lesson::all();
        return Response()->json([
             'status'       => 'success',
             'status_code'  => 200,
             'data'         => $lessons,
        ]);
    }

    public function show($id)
    {
        $lesson = Lesson::find($id);

        if(!$lesson){
            return $this->setStatusCode(403)->responseNotFound();
        }

        return Response()->json([
            'status'       => 'success',
            'status_code'  => 200,
            'data'         => $lesson,
       ]);
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

}
