<?php namespace Jenssegers\AB\Session;

use Illuminate\Support\Facades\Cookie;

class CookieSession implements SessionInterface {

    /**
     * The name of the cookie.
     *
     * @var string
     */
    protected $cookiePrefix = 'ab';

    /**
     * A copy of the session data.
     *
     * @var array
     */
    protected $data = null;

    /**
     * Cookie lifetime.
     *
     * @var integer
     */
    protected $minutes = 60;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->data = [];
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        $cookieName = $this->fullCookieName($name);

        return Cookie::get($cookieName);
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;

        $cookieName = $this->fullCookieName($name);
        $cookieValue = $value;

        return Cookie::queue($cookieName, $cookieValue, $this->minutes);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        foreach($this->data as $name => $value) {
            $cookieName = $this->fullCookieName($name);

            Cookie::forget($cookieName);
        }

        return $this->data = [];
    }

    private function fullCookieName($name) {
        return $this->cookiePrefix . '.' . $name;
    }

}
