<?php

class Template {

  static private $tpl;
  static private $template;
  static private $javascripts;
  static private $stylesheets;

  /**
   * Returns the singelton instance of Smarty Template Engine
   * @return Smarty
   */
  static public function getTemplate() {
    if (!isset(self::$tpl)) {
      self::$tpl = new Smarty();
      self::$tpl->template_dir = TEMPLATE_DIR;
      self::$tpl->compile_dir = TEMPLATE_COMPILE_DIR;
      self::$tpl->cache_dir = TEMPLATE_CACHE;
      if (defined('TEMPLATE_CONFIG_DIR')) {
        self::$tpl->config_dir = TEMPLATE_CONFIG_DIR;
      }
    }
    return self::$tpl;
  }

  /**
   * Adds CSS to template
   * @param string $stylesheet style sheet file name or list of style sheet names
   */
  static public function addStyleSheet($stylesheet) {
    if (self::$stylesheets == null || !is_array(self::$stylesheets)) {
      self::$stylesheets = array();
    }
    if (is_array($stylesheet)) {
      foreach ($stylesheet as $css) {
        self::addStyleSheet($css);
      }
    }
    $stylesheet = str_contains($stylesheet, '.css') ? $stylesheet : $stylesheet . '.css';
    if (!in_array($stylesheet, self::$stylesheets) && !is_array($stylesheet)) {
      self::$stylesheets[$stylesheet] = $stylesheet;
    }
  }
  
  /**
   * Adds javascript to template
   * @param string $javascript
   */
  static public function addJavaScript($javascript) {
    if (self::$javascripts == null || !is_array(self::$javascripts)) {
      self::$javascripts = array();
    }
    if (is_array($javascript)) {
      foreach ($javascript as $jscript) {
        self::addJavaScript($jscript);
      }
    }
    $javascript = str_contains($javascript, '.js') ? $javascript : $javascript . '.js';
    if (!in_array($javascript, self::$javascripts) && !is_array($javascript)) {
      self::$javascripts[$javascript] = $javascript;
    }
  }

  /**
   * Removes javascript from template
   * @param string $javascript
   */
  static public function removeJavaScript($javascript) {
    if ((self::$javascripts != null) && is_array(self::$javascripts) && !empty(self::$javascripts)) {
      if (in_array($javascript, self::$javascripts)) {
        unset(self::$javascripts[$javascript]);
      }
    }
  }

  /**
   * Assign variable to template
   *
   * @param $key
   * @param $value
   */
  static public function assign($key, $value) {
    $tpl = self::getTemplate();
    $tpl->assign($key, $value);
  }

  /**
   * Assign variable by reference to template
   *
   * @param $key
   * @param $value
   */
  static public function assignByRef($key, &$value) {
    $tpl = self::getTemplate();
    $tpl->assign_by_ref($key, $value);
  }

  /**
   * Returns style sheet array
   * @return array list of style sheets
   */
  static private function getStyleSheets() {
    if (!isset(self::$stylesheets) || (self::$stylesheets == null)) {
      self::$stylesheets = array();
    }
    return self::$stylesheets;
  }  
  
  /**
   * Returns javascripts array
   * @return array
   */
  static private function getJavaScripts() {
    if (!isset(self::$javascripts) || (self::$javascripts == null)) {
      self::$javascripts = array();
    }
    return self::$javascripts;
  }

  /**
   * Displays template
   *
   * @param string $template (optional)
   */
  static public function display($template = '') {
    if ($template != '') {
      self::setTemplate($template);
    }
    $tpl = self::getTemplate();
    $tpl->assign('javascripts', self::getJavaScripts());
    $tpl->assign('stylesheets', self::getStyleSheets());
    print $tpl->fetch(self::$template);
  }

  /**
   * Returns template output as string
   *
   * @param string $template (optional)
   * @return string
   */
  static public function fetch($template = '') {
    if ($template != '') {
      self::setTemplate($template);
    }
    $tpl = self::getTemplate();
    $tpl->assign('javascripts', self::getJavaScripts());
    return $tpl->fetch(self::$template, null, null, false);
  }

  /**
   * Sets template to display
   *
   * @param string $template the template file name
   */
  static public function setTemplate($template) {
    self::$template = str_contains($template, '.tpl') ? $template : $template . '.tpl';
    if (!file_exists(TEMPLATE_DIR . self::$template)) {
      die('template file ' . TEMPLATE_DIR . self::$template . ' does not exist');
    }
  }

}
