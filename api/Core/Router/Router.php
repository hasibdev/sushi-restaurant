<?php

namespace Jara\Core\Router;

class Router
{
    /**
     * Path
     *
     * @var String path of http request URL.
     */
    protected $path;

    /**
     * Method
     *
     * @var String http request method
     */
    protected $method;

    /**
     * Params
     *
     * @var Array array of params in url
     */
    public $params;

    /**
     * Routes
     *
     * @var Array array of routes
     */
    private $routes;

    /**
     * IsMatch
     *
     * @var Boolean is the url matched
     */
    private $isMatch;

    /**
     * Set essential values on construct
     */
    public function __construct()
    {
        // Get current path and remove unwanted charecters.
        $this->path = $this->clean(explode('?', $_SERVER['REQUEST_URI'])[0]);

        // Get the request method
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);

        // Array of routes
        $this->routes = array();

        // Return this object
        return $this;
    }

    /**
     * Remove starting, ending slash and extra slashes
     *
     * @param String $path path to clean
     *
     * @return String return cleaned path string
     */
    private function clean(String $path)
    {
        return trim(preg_replace('/(\/+)/', '/', $path), "/");
    }

    /**
     * Register new route 
     *
     * @param String $method method of the request (all valid http request methods)
     * @param String $pattern the path to be matched
     * @param Callable $callback callback function
     *
     * @return void registers the route
     */
    public function add(String $method, String $pattern, $callback)
    {
        $this->routes[$method][$pattern] = $callback;

        return $this;
    }

    /**
     * Callback handler
     *
     * @param Callable $callback callback function
     *
     * @return void
     */
    private function getView($callback)
    {
        call_user_func($callback, $this->params);
    }

    /**
     * Start routing.
     *
     * @return void
     */
    public function run()
    {
        // All routes for current request type
        $routesArr = $this->routes[$this->method];

        // Loop all routes for current request type
        foreach ($routesArr as $pattern => $callback) {

            // Clean pattern
            $pattern = $this->clean($pattern);

            // Last match status
            $this->isMatch = false;

            // Parts of pattern
            $patternArray = explode('/', $pattern);

            // Parts of path
            $pathArray = explode('/', $this->path);

            // Value key pair for pattern and path
            $valueKey = array();

            // Build the value key pair
            foreach ($pathArray as $key => $value) {
                if (isset($patternArray[$key])) {
                    $valueKey[$value] = $patternArray[$key];
                } else {
                    $valueKey[$value] = "";
                }
            }

            // Params value from request url
            $this->params = array();

            // if exact match found
            if ($patternArray === $pathArray) {
                $this->isMatch = true;
                // check each param
            } else {
                // Match parts of the path to pattern
                foreach ($valueKey as $key => $value) {
                    // Ignore everyting after ? and mark as matched
                    if ($value === '?') {
                        $this->isMatch = true;
                        break;
                        // Exact match, mark as matched
                    } elseif ($key === $value) {
                        // keep checking for match
                        continue;
                        // It is a param check if param value is valid
                    } elseif (strpos($value, ':') === 0) {
                        $regex = array(
                            ':abc' => '(^[a-zA-Z]+)$', // only alphabets
                            ':num' => '(^[0-9]+)$', // only numbers
                            ':slug'  => '(^[a-zA-Z0-9-_]+)$', // only slug with alphabets, numbers and (-_)
                            ':any' => '(.*)' // any value
                        );

                        // Match the regex patten and param value
                        if (preg_match("/$regex[$value]/", $key)) {
                            // Matched, put value in params and mark as matched
                            array_push($this->params, $key);
                            $this->isMatch = true;
                        } else {
                            // Did not match
                            $this->isMatch = false;
                            break;
                        }
                    } else {
                        // Did not match
                        $this->isMatch = false;
                        break;
                    }
                }
            }
            // If matched handle the callback else keep trying.
            if ($this->isMatch) {
                $this->getView($callback);
                break;
            } else {
                continue;
            }
        }
    }
}
