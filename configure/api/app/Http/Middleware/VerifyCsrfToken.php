<?php
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use App\Constants\ErrorCodeConst;

class VerifyCsrfToken extends Middleware
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        if ($this->isReading($request) || $this->runningUnitTests() ||
            $this->inExceptArray($request) || $this->tokensMatch($request)) {
            return $this->addCookieToResponse($request, $next($request));
        }

        $ary_url_title = ErrorCodeConst::MAPPING_URL_TITLE;
        $uri = $request->route()->uri();

        if (isset($ary_url_title[$uri])) {
            return response()->view('error', [
                'msg' => ErrorCodeConst::ERR_CODE_16,
                'title' => $ary_url_title[$uri]
            ]);
        } else {
            return response()->view('error', [
                'msg' => ErrorCodeConst::ERR_UNDEFINED_ROUTE,
                'title' => ErrorCodeConst::ERR_TITLE_00
            ]);
        }
    }

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '*/api/*',
    ];
    //
}
