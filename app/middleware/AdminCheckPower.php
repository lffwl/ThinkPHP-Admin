<?php
declare (strict_types=1);

namespace app\middleware;

use app\power\server\Power;

class AdminCheckPower
{
    public function handle($request, \Closure $next)
    {
        $Power = new Power();
        $Power->checkAdminPower(app('http')->getName() . '/' . $request->controller() . '/' . $request->action());

        return $next($request);
    }
}
