<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;

class DummyDummy
{
    public function handle(Request $request, $next)
    {
        dump('heyayayaya');
        $next($request);
    }

}