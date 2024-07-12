<?php

namespace App\Http\Middleware;

use App\Models\SettingAction;
use App\Models\SettingCont;
use App\Models\SettingPermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class Permit
{
    public function handle(Request $request, Closure $next)
    {
        $userRole = $request->user()->role->name;                    // admin--user--guest
        $action = Route::getCurrentRoute()->getActionName();         // Controller with Method from route
        $route = str_replace('App\Http\Controllers\\', '', $action); // controllerName
        $route = explode('@', $route);                               // methodName

        if ($userRole === 'admin')
            return $next($request);

        // controller check
        $controller = SettingCont::where(['name' => $route[0]])->first();
        // if controller not defined
        if (!$controller) {
            return response()->json(['status' => false, 'message' => 'Access denied']);
        }
        // check method
        $act = SettingAction::where(['name' => $route[1]])->where(['conts_id' => $controller->id])->first();
        // if method not defined
        if (!$act) {
            return response()->json(['status' => false, 'message' => 'Access denied action']);
        }
        //check permission
        $permission = SettingPermission::where(['role_id' => $request->user()->role->id])
            ->where(['conts_id' => $controller->id])
            ->where(['action_id' => $act->id])->first();

        if ($permission) {
            return $next($request);
        }
        return response()->json(['status' => false, 'message' => 'Access denied'], 403);
    }
}
