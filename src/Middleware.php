<?php
/**
 * Created by PhpStorm.
 * User: yishuixm
 * Date: 2016/7/24
 * Time: 13:27
 */

namespace yishuixm\slim\Session;


class Middleware
{

    public function __construct($settings = [])
    {
        $defaults = [
            'lifetime'    => '20 minutes',
            'path'        => '/',
            'domain'      => null,
            'secure'      => false,
            'httponly'    => false,
            'name'        => 'slim_session',
            'autorefresh' => false,
        ];
        $settings = array_merge($defaults, $settings);
        if (is_string($lifetime = $settings['lifetime'])) {
            $settings['lifetime'] = strtotime($lifetime) - time();
        }
        $this->settings = $settings;
        ini_set('session.gc_probability', 1);
        ini_set('session.gc_divisor', 1);
        ini_set('session.gc_maxlifetime', 30 * 24 * 60 * 60);
    }
    
    public function __invoke($request, $response, $next)
    {

        $this->startSession();

        return $response = $next($request, $response);
    }

    protected function startSession()
    {
        $settings = $this->settings;
        $name = $settings['name'];
        session_set_cookie_params(
            $settings['lifetime'],
            $settings['path'],
            $settings['domain'],
            $settings['secure'],
            $settings['httponly']
        );
        if (session_id()) {
            if ($settings['autorefresh'] && isset($_COOKIE[$name])) {
                setcookie(
                    $name,
                    $_COOKIE[$name],
                    time() + $settings['lifetime'],
                    $settings['path'],
                    $settings['domain'],
                    $settings['secure'],
                    $settings['httponly']
                );
            }
        }
        session_name($name);
        session_cache_limiter(false);
        session_start();
    }
}