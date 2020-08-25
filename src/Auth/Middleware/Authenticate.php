<?php

namespace Ignite\Auth\Middleware;

use Ignite\Auth\Models\LitSession;
use Ignite\Support\Facades\LitLang;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Throwable;

class Authenticate extends Middleware
{
    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param \Illuminate\Http\Request $request
     * @param array                    $guards
     *
     * @throws \Illuminate\Auth\AuthenticationException
     *
     * @return void
     */
    protected function authenticate($request, array $guards)
    {
        try {
            parent::authenticate($request, $guards);
        } catch (AuthenticationException $e) {
            // Delete lit_session from db when user is logged out.
            LitSession::where('session_id', Session::getId())->delete();

            throw $e;
        }

        $this->storeLitSession($request);
        $this->setUserLocale();
    }

    protected function setUserLocale()
    {
        if (lit_user()->locale === null) {
            lit_user()->locale = LitLang::getBrowserLocale();
            lit_user()->save();
        }
    }

    /**
     * Store Lit session.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function storeLitSession($request)
    {
        $query = [
            'user_agent' => $request->server('HTTP_USER_AGENT'),
            'ip_address' => $request->ip(),
        ];
        if (LitSession::where($query)->exists()) {
            LitSession::where($query)->update(
                [
                    'user_agent'    => $request->server('HTTP_USER_AGENT'),
                    'ip_address'    => $request->ip(),
                    'session_id'    => Session::getId(),
                    'lit_user_id' => lit_user()->id,
                    'last_activity' => Carbon::now(),
                ],
            );
        } else {
            LitSession::updateOrCreate(
                [
                    'session_id' => Session::getId(),
                ],
                [
                    'user_agent'    => $request->server('HTTP_USER_AGENT'),
                    'ip_address'    => $request->ip(),
                    'lit_user_id' => lit_user()->id,
                    'last_activity' => Carbon::now(),
                ],
            );
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('lit.login');
        }
    }

    /**
     * Store session location after response is sent.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return void
     */
    public function terminate($request, $response)
    {
        $session = LitSession::where('session_id', Session::getId())->first();

        if (! $session) {
            return;
        }

        if (! is_array($session->payload)) {
            $session->payload = [];
        }

        // Only if location hasn't been set before.
        if (array_key_exists('location', $session->payload)) {
            return;
        }

        $location = $this->fetchLocation();

        if (! $location) {
            return;
        }

        $session->update([
            'payload' => ['location' => [
                'city'    => $location['city'] ?? '',
                'country' => $location['country'] ?? '',
            ]],
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
