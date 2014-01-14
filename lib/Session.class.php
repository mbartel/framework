<?php

/**
 * HTTP Request helper class
 *
 * @author mbartel (Michael.Bartel@gmx.net)
 */
class Session {

  private static $params = array();

  /**
   * Returns the HTTP POST or GET value for the given name
   * @param type $name the name of the parameter
   * @param object $default this will be returned, if there is no parameter for the given name
   * @return string or array
   */
  public static function param($name, $default = NULL) {
    return isset(self::$params[$name]) ? self::$params[$name] : (isset($_POST[$name]) ? $_POST[$name] : (isset($_GET[$name]) ? $_GET[$name] : $default));
  }

  /**
   * Stores a value in the user session
   * @param string $key the key
   * @param object $value the value
   */
  public static function setSessionValue($key, $value) {
    $_SESSION[$key] = $value;
  }

  /**
   * Returns the value stored in the user session for the given key
   * @param type $key the key
   * @return object the stored value or null
   */
  public static function getSessionValue($key) {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
  }

  /**
   * Sends the given array as JSON to the client and stops the execution
   * @param array $data data as array
   */
  public static function renderJSON($data) {
    header('Content-type: application/json;');
    die(json_encode($data));
  }

  /**
   * Redirects the user to the given URL
   * @param string $url the new destination
   */
  public static function redirectTo($url) {
    header("Location: " . $url);
  }

  /**
   * Sets the HTTP status code and message
   * @param string $status the HTTP status code
   * @param string $message the HTTP status message
   */
  public static function writeStatus($status = 200, $message = 'OK') {
    header('HTTP/1.0 ' . $status . ' ' . $message);
    header('Status: ' . $status . ' ' . $message);
  }

  /**
   * Starts/initializes the user session
   */
  private static function startSession() {
    session_start();
  }

  /**
   * Destroyes the user session
   */
  private static function destroySession() {
    session_unset();
    session_destroy();
    setcookie(session_name(), NULL, time() - SESSIONTIMEOUT - 1);
  }

  /**
   * Checks if the user is logged in correctly and redirects to the login page if not
   */
  private static function check() {
    $last_login = self::getSessionValue('last_login');
    if ($last_login != null && $last_login < time() + SESSIONTIMEOUT) {
      self::setSessionValue('last_login', time());
    } else {
      self::destroySession();
      self::redirectTo(BASE_URL . 'login');
    }

    if (!self::isLoggedIn()) {
      self::redirectTo(BASE_URL . 'login');
    }
  }

  /**
   * Checks if the user is logged in
   * @return boolean true, if the user is logged in
   */
  public static function isLoggedIn() {
    return self::getSessionValue('user') != NULL;
  }

  /**
   * Returns the user data object/array from the user session
   * @return array with user data
   */
  public static function getUser() {
    return self::getSessionValue('user');
  }

  /**
   * Checks if the requested page/URL matches the given pattern and parses the URL paramters
   * @param string $uri the URI with parameters
   * @return boolean true, if the given URI matches the requested page
   */
  public static function match($uri) {
    $uriParts = explode('/', $uri);

    $parts = array();
    foreach ($uriParts as $part) {
      $parts[] = ($part[0] == ':') ? '(?P<' . substr($part, 1) . '>.*?)' : $part;
    }

    $reqex = '~^' . join('/', $parts) . '/?$~';

    if (preg_match($reqex, $_GET[REWRITE_PARAM], $matches)) {
      unset($matches[0], $matches[1], $matches[2], $matches[3], $matches[4], $matches[5]);
      self::$params = $matches;
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Delivers static content like CSS, JavaScript and Fonts
   */
  private static function deliverStaticContent() {
    $firstURLPart = substr($_GET[REWRITE_PARAM], 0, strpos($_GET[REWRITE_PARAM], '/'));

    if ($firstURLPart == 'css' || $firstURLPart == 'fonts' || $firstURLPart == 'js') {
      $filename = ROOT . $_GET[REWRITE_PARAM];
      if (file_exists($filename)) {
        header('Content-type: ' . ($firstURLPart == 'css' ? 'text/css' : ($firstURLPart == 'js' ? 'application/javascript' : 'application/font')) . ';');
        die(file_get_contents($filename));
      } else {
        self::writeStatus(404, 'Not Found');
      }
    }
  }

  /**
   * Initializes the session object
   */
  public static function init() {
    self::startSession();

    // deliver static content
    self::deliverStaticContent();

    // Bootstrap
    Template::addStyleSheet('bootstrap.min');
    Template::addStyleSheet('bootstrap-theme.min');

    // jQuery
    Template::addJavaScript('jquery-2.0.3.min');
    Template::addJavaScript('bootstrap.min');

    // Default JavaScript and StyleSheet
    Template::addStyleSheet('default');
    Template::addJavaScript('default');

    Template::assign('BASE', BASE_URL);
    Template::assign('pagetitle', PAGETITLE);
    Template::assign('user', self::getSessionValue('user'));

    if (self::match('login')) {
      self::loginPage();
    } else {
      if (self::match('logout')) {
        self::destroySession();
      }

      // checks the user session and handles the seesion time out
      self::check();
    }
  }

  /**
   * Shows the login page and handles the user login
   * @global type $USERS predefined user list
   */
  private static function loginPage() {
    global $USERS;

    Template::setTemplate('login');

    $email = self::param('email');
    if ($email != null) {
      $email = strtolower($email);
      $passwd = md5(self::param('password'));

      foreach (Settings::get('users') as $user) {
        if ($email == strtolower($user['email']) && ($passwd == $user['password'] || md5($passwd) == $user['password'])) {
          self::setSessionValue('user', $user);
          self::setSessionValue('last_login', time());
          self::redirectTo(BASE_URL);
        }
      }
    }

    Template::display();
    die();
  }

}
