<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // يمكن إضافة مسارات مستثناة من فحص CSRF هنا إذا لزم الأمر
        // 'api/*',
        // 'webhook/*',
    ];

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        $token = $this->getTokenFromRequest($request);

        return is_string($request->session()->token()) &&
               is_string($token) &&
               hash_equals($request->session()->token(), $token);
    }

    /**
     * Get the CSRF token from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function getTokenFromRequest($request)
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        if (! $token && $header = $request->header('X-XSRF-TOKEN')) {
            $token = $this->encrypter->decrypt($header, static::serialized());
        }

        return $token;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, \Closure $next)
    {
        if (
            $this->isReading($request) ||
            $this->runningUnitTests() ||
            $this->inExceptArray($request) ||
            $this->tokensMatch($request)
        ) {
            return tap($next($request), function ($response) use ($request) {
                if ($this->shouldAddXsrfTokenCookie()) {
                    $this->addCookieToResponse($request, $response);
                }
            });
        }

        // إذا فشل فحص CSRF، نعيد توجيه مع رسالة خطأ واضحة
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'CSRF token mismatch.',
                'errors' => [
                    'csrf' => ['The page has expired due to inactivity. Please refresh and try again.']
                ]
            ], 419);
        }

        return redirect()->back()
            ->withInput($request->except('password', 'password_confirmation'))
            ->withErrors([
                'csrf' => 'انتهت صلاحية الصفحة بسبب عدم النشاط. يرجى تحديث الصفحة والمحاولة مرة أخرى.'
            ]);
    }
}
