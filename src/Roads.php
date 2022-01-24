<?php

namespace Lar\Roads;

use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;

/**
 * Class Road.
 *
 * @package Lar\Roads
 */
class Roads
{
    /**
     * Temp route attributes.
     *
     * @var array
     */
    public static $_tmp_attributes = [];

    /**
     * @var bool
     */
    protected $lang = null;

    /**
     * @var \Illuminate\Routing\Router
     */
    protected $last_rout;

    /**
     * @param string $layout
     * @param bool $asx
     * @return Roads
     */
    public function layout(string $layout, bool $asx = false)
    {
        if ($asx) {
            $this->asx($layout);
        }

        return $this->middleware(['web', 'dom', "layout:{$layout}"]);
    }

    /**
     * Set equal name and prefix.
     *
     * @param  string  $name
     * @param  bool  $last
     * @return Roads
     */
    public function asx(string $name, bool $last = false)
    {
        return $this->as($name, $last)->prefix($name);
    }

    /**
     * Enable language routes.
     *
     * @param  bool  $switcher
     * @return Roads
     */
    public function lang(bool $switcher = true)
    {
        if ($switcher) {
            $this->prefix(\Layout::nowLang())->middleware('lang');
        }

        return $this;
    }

    /**
     * Add getters to routes.
     *
     * @param $gets
     * @param array $props
     * @return Roads
     */
    public function gets($gets, ...$props)
    {
        if (! is_array($gets)) {
            $gets = func_get_args();
        }

        $gets = implode(',', $gets);

        return $this->middleware("gets:{$gets}");
    }

    /**
     * Set name of routes.
     *
     * @param  string  $name
     * @param  bool  $last
     * @return Roads
     */
    public function as(string $name, bool $last = false)
    {
        if (str_replace(['{', '?', '}'], '', $name) !== $name) {
            return $this;
        }

        return $this->__tmpAttribute(['as' => trim($name, '.').(! $last ? '.' : '')]);
    }

    /**
     * Set domain of routes.
     *
     * @param string $domain
     * @return Roads
     */
    public function domain(string $domain)
    {
        return $this->__tmpAttribute(['domain' => $domain]);
    }

    /**
     * Set route middleware.
     *
     * @param $middleware
     * @return Roads
     */
    public function middleware($middleware)
    {
        if (! is_array($middleware)) {
            $middleware = func_get_args();
        }

        return $this->__tmpAttribute(['middleware' => $middleware]);
    }

    /**
     * Set name of routes (as alias).
     *
     * @param string $name
     * @return Roads
     */
    public function name(string $name)
    {
        return $this->as($name);
    }

    /**
     * Set namespace of routes.
     *
     * @param string $namespace
     * @return Roads
     */
    public function namespace(string $namespace)
    {
        return $this->__tmpAttribute(['namespace' => $namespace]);
    }

    /**
     * Set route prefix.
     *
     * @param string $prefix
     * @return Roads
     */
    public function prefix(string $prefix)
    {
        return $this->__tmpAttribute(['prefix' => $prefix]);
    }

    /**
     * Set route prefix.
     *
     * @param $where
     * @param array $props
     * @return Roads
     */
    public function where(array $where)
    {
        return $this->__tmpAttribute(['where' => $where]);
    }

    /**
     * Set web middleware.
     *
     * @return Roads
     */
    public function web()
    {
        return $this->middleware('web');
    }

    /**
     * Set auth middleware.
     *
     * @param  array  $guards
     * @return Roads
     */
    public function auth(...$guards)
    {
        $add = implode(',', array_diff(func_get_args(), [null]));
        $add = ! empty($add) ? ":{$add}" : '';

        return $this->middleware("auth{$add}");
    }

    /**
     * Set auth middleware.
     *
     * @param  null  $guard
     * @param  null  $field
     * @return Roads
     */
    public function auth_basic($guard = null, $field = null)
    {
        $add = implode(',', array_diff(func_get_args(), [null]));
        $add = ! empty($add) ? ":{$add}" : '';

        return $this->middleware("auth.basic{$add}");
    }

    /**
     * Set bindings middleware.
     *
     * @return Roads
     */
    public function bindings()
    {
        return $this->middleware('bindings');
    }

    /**
     * Set cache.headers middleware.
     *
     * @param  array  $options
     * @return Roads
     */
    public function cache_headers($options = null)
    {
        $add = implode(',', array_diff(func_get_args(), [null]));
        $add = ! empty($add) ? ":{$add}" : '';

        return $this->middleware("cache.headers{$add}");
    }

    /**
     * Set can middleware.
     *
     * @param $ability
     * @param  array  $models
     * @return Roads
     */
    public function can($ability, ...$models)
    {
        $add = implode(',', array_diff(func_get_args(), [null]));
        $add = ! empty($add) ? ":{$add}" : '';

        return $this->middleware("can{$add}");
    }

    /**
     * Set guest middleware.
     *
     * @return Roads
     */
    public function guest()
    {
        return $this->middleware('guest');
    }

    /**
     * Set password.confirm middleware.
     *
     * @param  null  $redirectToRoute
     * @return Roads
     */
    public function password_confirm($redirectToRoute = null)
    {
        $add = implode(',', array_diff(func_get_args(), [null]));
        $add = ! empty($add) ? ":{$add}" : '';

        return $this->middleware("password.confirm{$add}");
    }

    /**
     * Set guest middleware.
     *
     * @return Roads
     */
    public function signed()
    {
        return $this->middleware('signed');
    }

    /**
     * Set throttle middleware.
     *
     * @param  null  $maxAttempts
     * @param  null  $decayMinutes
     * @param  null  $prefix
     * @return Roads
     */
    public function throttle($maxAttempts = null, $decayMinutes = null, $prefix = null)
    {
        $add = implode(',', array_diff(func_get_args(), [null]));
        $add = ! empty($add) ? ":{$add}" : '';

        return $this->middleware("throttle{$add}");
    }

    /**
     * Set verified middleware.
     *
     * @param  null  $redirectToRoute
     * @return Roads
     */
    public function verified($redirectToRoute = null)
    {
        $add = implode(',', array_diff(func_get_args(), [null]));
        $add = ! empty($add) ? ":{$add}" : '';

        return $this->middleware("verified{$add}");
    }

    /**
     * Set cors middleware.
     *
     * @return Roads
     */
    public function cors()
    {
        return $this->middleware('cors');
    }

    /**
     * Set api middleware.
     *
     * @return Roads
     */
    public function api()
    {
        return $this->middleware('api');
    }

    /**
     * Set web.encrypt_cookies middleware.
     *
     * @return Roads
     */
    public function encrypt_cookies()
    {
        return $this->middleware('encrypt_cookies');
    }

    /**
     * Set web.queued_cookies middleware.
     *
     * @return Roads
     */
    public function queued_cookies()
    {
        return $this->middleware('queued_cookies');
    }

    /**
     * Set web.start_session middleware.
     *
     * @return Roads
     */
    public function start_session()
    {
        return $this->middleware('start_session');
    }

    /**
     * Set web.share_errors middleware.
     *
     * @return Roads
     */
    public function share_errors()
    {
        return $this->middleware('share_errors');
    }

    /**
     * Set web.verify_csrf middleware.
     *
     * @return Roads
     */
    public function verify_csrf()
    {
        return $this->middleware('verify_csrf');
    }

    /**
     * Set web.substitute_bindings middleware.
     *
     * @return Roads
     */
    public function substitute_bindings()
    {
        return $this->middleware('substitute_bindings');
    }

    /**
     * Register a new GET route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function get($uri, $action = null)
    {
        return $this->__manipulator('get', $uri, $action);
    }

    /**
     * Register a new GET component route with the router.
     *
     * @param  string  $uri
     * @param $component
     * @return \Illuminate\Routing\Route
     */
    public function component($uri, $component)
    {
        return $this->__manipulator('get', $uri, '\\Lar\\Roads\\ComponentController@'.$component);
    }

    /**
     * Register a new POST route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function post($uri, $action = null)
    {
        return $this->__manipulator('post', $uri, $action);
    }

    /**
     * Register a new PUT route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function put($uri, $action = null)
    {
        return $this->__manipulator('put', $uri, $action);
    }

    /**
     * Register a new PATCH route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function patch($uri, $action = null)
    {
        return $this->__manipulator('patch', $uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function delete($uri, $action = null)
    {
        return $this->__manipulator('delete', $uri, $action);
    }

    /**
     * Register a new OPTIONS route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function options($uri, $action = null)
    {
        return $this->__manipulator('options', $uri, $action);
    }

    /**
     * Register a new route responding to all verbs.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function any($uri, $action = null)
    {
        return $this->__manipulator('any', $uri, $action);
    }

    /**
     * Register a new Fallback route with the router.
     *
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function fallback($action)
    {
        return $this->__manipulator('fallback', $action);
    }

    /**
     * Create a redirect from one URI to another.
     *
     * @param  string  $uri
     * @param  string  $destination
     * @param  int  $status
     * @return \Illuminate\Routing\Route
     */
    public function redirect($uri, $destination, $status = 302)
    {
        return $this->__manipulator('redirect', $uri, $destination, $status);
    }

    /**
     * Create a permanent redirect from one URI to another.
     *
     * @param  string  $uri
     * @param  string  $destination
     * @return \Illuminate\Routing\Route
     */
    public function permanentRedirect($uri, $destination)
    {
        return $this->__manipulator('permanentRedirect', $uri, $destination);
    }

    /**
     * Register a new route that returns a view.
     *
     * @param  string  $uri
     * @param  string  $view
     * @param  array  $data
     * @return \Illuminate\Routing\Route
     */
    public function view($uri, $view, $data = [])
    {
        return $this->__manipulator('view', $uri, $view, $data);
    }

    /**
     * Register a new route with the given verbs.
     *
     * @param  array|string  $methods
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null $action
     * @return \Illuminate\Routing\Route
     */
    public function match($methods, $uri, $action = null)
    {
        return $this->__manipulator('match', $methods, $uri, $action);
    }

    /**
     * Register an array of resource controllers.
     *
     * @param array $resources
     * @param array $options
     * @return Roads
     */
    public function resources(array $resources, array $options = [])
    {
        $this->__manipulator('resources', $resources, $options);

        return $this;
    }

    /**
     * Route a resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\PendingResourceRegistration|\Illuminate\Routing\Route
     */
    public function resource($name, $controller, array $options = [])
    {
        return $this->__manipulator('resource', $name, $controller, $options);
    }

    /**
     * Register an array of API resource controllers.
     *
     * @param array $resources
     * @param array $options
     * @return Roads
     */
    public function apiResources(array $resources, array $options = [])
    {
        $this->__manipulator('apiResources', $resources, $options);

        return $this;
    }

    /**
     * Route an API resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\PendingResourceRegistration|\Illuminate\Routing\Route
     */
    public function apiResource($name, $controller, array $options = [])
    {
        return $this->__manipulator('resource', $name, $controller, $options);
    }

    /**
     * @param \Closure|string|array $props
     * @param \Closure|string|null $call
     * @return Roads
     */
    public function group($props, $call = null)
    {
        if (is_embedded_call($props)) {
            $call = $props;
            $props = [];
        }

        if (is_string($props)) {
            $call = $props;
            $props = [];
        }

        if (isset($props['middleware']) && ! is_array($props['middleware'])) {
            $props['middleware'] = [$props['middleware']];
        }

        $props = array_merge_recursive(static::$_tmp_attributes, $props);

        static::$_tmp_attributes = [];

        if (is_embedded_call($call)) {
            \Route::group($props, function (Router $router) use ($call) {
                $this->last_rout = $router;

                call_user_func($call, $this);

//                embedded_call($call, [
//                    'router' => $router,
//                    'route' => $router,
//                    'Road' => $this,
//                    Router::class => $router,
//                    static::class => $this
//                ]);
            });
        } elseif (is_string($call)) {
            $_tmp = null;

            foreach ($props as $key => $prop) {
                if (! $_tmp) {
                    $_tmp = \Route::$key($prop);
                } else {
                    $_tmp = $_tmp->{$key}($prop);
                }
            }

            if ($_tmp instanceof RouteRegistrar) {
                $_tmp->group($call);
            }
        }

        return $this;
    }

    /**
     * Register the typical authentication routes for an application.
     *
     * @param array $options
     * @return Roads
     */
    public function auth_routes(array $options = [])
    {
        if ($this->last_rout) {
            $this->last_rout->auth($options);
        } else {
            \Route::auth($options);
        }

        return $this;
    }

    public static $_lang_routes = [];

    /**
     * Register a new route.
     *
     * @param  string  $method
     * @param  array  $props
     * @return \Illuminate\Routing\Route
     */
    public function __manipulator($method = 'get', ...$props)
    {
        $_tmp = null;

        foreach (static::$_tmp_attributes as $key => $prop) {
            if (! $_tmp) {
                if (! $this->last_rout) {
                    $_tmp = \Route::$key($prop);
                } else {
                    $_tmp = $this->last_rout->$key($prop);
                }
            } else {
                $_tmp = $_tmp->{$key}($prop);
            }
        }

        static::$_tmp_attributes = [];

        if ($_tmp instanceof RouteRegistrar) {
            return $_tmp->{$method}(...$props);
        }

        $return = \Route::$method(...$props);

        return $return;
    }

    /**
     * @param array $attributes
     * @return Roads
     */
    public function __tmpAttribute(array $attributes)
    {
        foreach ($attributes as $key => $attribute) {
            if (! isset(static::$_tmp_attributes[$key])) {
                static::$_tmp_attributes[$key] = $attribute;
            } else {
                if ($key === 'middleware') {
                    static::$_tmp_attributes[$key] = array_merge(static::$_tmp_attributes[$key], (is_array($attribute) ? $attribute : [$attribute]));
                } elseif ($key === 'as') {
                    static::$_tmp_attributes[$key] .= ".{$attribute}";
                } elseif ($key === 'namespace') {
                    static::$_tmp_attributes[$key] .= "\\{$attribute}";
                } elseif ($key === 'prefix') {
                    if (is_array(static::$_tmp_attributes[$key])) {
                        dd(static::$_tmp_attributes[$key], $attribute);
                    }

                    static::$_tmp_attributes[$key] .= "/{$attribute}";
                } else {
                    static::$_tmp_attributes[$key] = $attribute;
                }
            }
        }

        return $this;
    }
}
