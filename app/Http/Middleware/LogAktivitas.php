<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class LogAktivitas
{
    public function handle(Request $request, Closure $next)
    {
        // Lanjutkan request
        $response = $next($request);

        // Log aktivitas hanya untuk pengguna yang sudah login
        if (Auth::check()) {
            $this->logRequest($request);
        }

        return $response;
    }

    protected function logRequest(Request $request)
    {
        $route = $request->route();
        
        if ($route) {
            $aktivitas = $this->generateAktivitas($route);
            $deskripsi = $this->generateDeskripsi($request);

            LogAktivitas::catat($aktivitas, $deskripsi);
        }
    }

    protected function generateAktivitas($route)
    {
        $routeName = $route->getName() ?? 'undefined';
        $method = request()->method();

        return "{$method} {$routeName}";
    }

    protected function generateDeskripsi(Request $request)
    {
        $user = Auth::user();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        return "Pengguna: {$user->name} | IP: {$ipAddress} | Browser: {$userAgent}";
    }
}
