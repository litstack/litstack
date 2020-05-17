<?php

namespace Fjord\Auth\Middleware;

use Closure;
use Throwable;
use Illuminate\Support\Carbon;
use Fjord\Auth\Models\FjordSession;
use Fjord\Support\Facades\FjordLang;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        try {
            parent::authenticate($request, $guards);
        } catch (AuthenticationException $e) {

            // Delete fjord_session from db when user is logged out.
            FjordSession::where('session_id', Session::getId())->delete();

            throw $e;
        }

        $this->storeFjordSession($request);
        $this->setUserLocale();
    }

    protected function setUserLocale()
    {
        if (fjord_user()->locale === null) {
            fjord_user()->locale = FjordLang::getBrowserLocale();
            fjord_user()->save();
        }
    }

    /**
     * Store Fjord session.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function storeFjordSession($request)
    {
        $query = [
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'ip_address' => $request->ip(),
        ];
        if (FjordSession::where($query)->exists()) {
            FjordSession::where($query)->update(
                [
                    'user_agent' => $request->server('HTTP_USER_AGENT'),
                    'ip_address' => $request->ip(),
                    'session_id' => Session::getId(),
                    'fjord_user_id' => fjord_user()->id,
                    'last_activity' => Carbon::now()
                ],
            );
        } else {
            FjordSession::updateOrCreate(
                [
                    'session_id' => Session::getId(),
                ],
                [
                    'user_agent' => $request->server('HTTP_USER_AGENT'),
                    'ip_address' => $request->ip(),
                    'fjord_user_id' => fjord_user()->id,
                    'last_activity' => Carbon::now()
                ],
            );
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('fjord.login');
        }
    }

    /**
     * Store session location after response is sent.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function terminate($request, $response)
    {
        $session = FjordSession::where('session_id', Session::getId())->first();

        if (!$session) {
            return;
        }

        if (!is_array($session->payload)) {
            $session->payload = [];
        }

        // Only if location hasn't been set before.
        if (array_key_exists('location', $session->payload)) {
            return;
        }

        $location = $this->fetchLocation();

        if (!$location) {
            return;
        }

        $session->update([
            'payload' => ['location' => [
                'city' => $location['city'] ?? '',
                'country' => $location['country'] ?? '',
            ]]
        ]);
    }

    /**
     * Fetch ip location from ipinfo.io.
     *
     * @return array|false
     */
    public function fetchLocation()
    {
        try {
            $response = (new \GuzzleHttp\Client())->request('GET', 'https://ipinfo.io/json');
            return json_decode($response->getBody(), true);
        } catch (Throwable $e) {
            return false;
        }
    }
}
