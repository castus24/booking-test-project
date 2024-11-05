<?php

namespace App\Http\Middleware;

use App\Enums\LangEnum;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return mixed|Response
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->hasHeader("Accept-Language")) {
            $language = strtolower($request->header('Accept-Language'));

            if (!in_array($language, LangEnum::asArray())) {
                throw new Exception('Choose one of available languages: En or Ru');
            }

            App::setLocale($language);
        }

        return $next($request);
    }
}
