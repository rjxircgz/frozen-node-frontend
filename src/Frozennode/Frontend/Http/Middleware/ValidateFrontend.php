<?php namespace Frozennode\Frontend\Http\Middleware;

use Closure;

class ValidateFrontend {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$configFactory = app('frontend_config_factory');

		//get the frontend check closure that should be supplied in the config
		$permission = config('frontend.permission');

		//if this is a simple false value, send the user to the login redirect
		if (!$response = $permission())
		{
			$loginUrl = url(config('frontend.login_path', 'user/login'));
			$redirectKey = config('frontend.login_redirect_key', 'redirect');
			$redirectUri = $request->url();

			return redirect()->guest($loginUrl)->with($redirectKey, $redirectUri);
		}

		//otherwise if this is a response, return that
		else if (is_a($response, 'Illuminate\Http\JsonResponse') || is_a($response, 'Illuminate\Http\Response'))
		{
			return $response;
		}

		//if it's a redirect, send it back with the redirect uri
		else if (is_a($response, 'Illuminate\\Http\\RedirectResponse'))
		{
			$redirectKey = config('frontend.login_redirect_key', 'redirect');
			$redirectUri = $request->url();

			return $response->with($redirectKey, $redirectUri);
		}

		return $next($request);
	}

}