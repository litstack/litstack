<?php

namespace Ignite\Auth;

use Closure;
use Ignite\Contracts\Auth\Authentication as AuthenticationContract;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;

class Authentication implements AuthenticationContract
{
    /**
     * Auth factory instance.
     *
     * @var Factory
     */
    protected $auth;

    /**
     * Guard instance.
     *
     * @var Guard|StatefulGuard
     */
    protected $guard;

    /**
     * Attempting closures.
     *
     * @var array
     */
    protected $attempting = [];

    /**
     * Credentials resolver.
     *
     * @var array
     */
    protected $credentialResolvers = [];

    /**
     * Create new Authentication instance.
     *
     * @param  Factory $auth
     * @return void
     */
    public function __construct(Factory $auth)
    {
        $this->auth = $auth;
        $this->guard = $auth->guard(config('lit.guard'));

        $this->attemptCredentials(
            fn ($credentials) => $credentials
        );

        if (config('lit.login.username')) {
            $this->attemptCredentials(function ($credentials) {
                $credentials['username'] = $credentials['email'];
                unset($credentials['email']);

                return $credentials;
            });
        }
    }

    /**
     * Apply credentials resolver.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function attemptCredentials(Closure $closure)
    {
        $this->credentialResolvers[] = $closure;

        return $this;
    }

    /**
     * Reset Credential attempts.
     *
     * @return $this
     */
    public function resetCredentialAttemps()
    {
        $this->credentialResolvers = [];

        return $this;
    }

    /**
     * Execute authentication.
     *
     * @param  array $credentials
     * @param  bool  $remember
     * @param  array $parameters
     * @return bool
     */
    public function attempt($credentials, bool $remember = false, array $parameters = [])
    {
        if (! $this->attempts($credentials, $remember)) {
            return false;
        }

        return $this->extendedAttempt($parameters);
    }

    /**
     * Do attempts with all credentials.
     *
     * @param  array $credentials
     * @param  bool  $remember
     * @return bool
     */
    protected function attempts($credentials, bool $remember)
    {
        foreach ($this->credentialResolvers as $resolver) {
            $credentials = $resolver($credentials);

            if ($this->guard->attempt($credentials, $remember)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add attempting closure.
     *
     * @param  Closure $closure
     * @return void
     */
    public function attempting(Closure $closure)
    {
        $this->attempting[] = $closure;
    }

    /**
     * Execute extended attempt.
     *
     * @param  array $parameters
     * @return bool
     */
    protected function extendedAttempt(array $parameters)
    {
        if (empty($this->attempting)) {
            return true;
        }

        $user = $this->guard->user();

        $this->guard->logout();

        foreach ($this->attempting as $closure) {
            if ($closure($user, $parameters)) {
                continue;
            }

            return false;
        }

        $this->guard->login($user);

        return true;
    }
}
