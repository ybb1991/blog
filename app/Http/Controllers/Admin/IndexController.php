<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public function index()
    {
        $builder = User::where('id', '>', 5);
        $bindings = $builder->getBindings();
        $sql = str_replace('?', '%s', $builder->toSql());
        $sql = sprintf($sql, ...$bindings);
        dd($sql);
        // dd(app('Illuminate\Config\Repository'));
        dd(config('database.default'));
        dd(app('billing')->charge());
        return view('home');
    }
}
