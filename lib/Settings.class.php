<?php

/**
 * Settings helper class
 *
 * @author mbartel
 */
class Settings {

    // the complete settings
    private static $settings;

    /**
     * Loads the settings from the JSON file in the config folder
     */
    private static function loadSettings() {
        $settingsFile = ROOT . 'config/settings.json';

        if (!file_exists($settingsFile)) {
            self::$settings = array();
            return;
        }

        self::$settings = json_decode(file_get_contents($settingsFile), TRUE);
        foreach (self::$settings as $key => $value) {
          Template::assign($key, $value);
        }
    }

    /**
     * Returns the settings value for the given key
     * @param string $key the key for the settings value
     * @return array/string settings value
     */
    private static function get($key) {
        if (self::$settings == NULL) {
            self::loadSettings();
        }
        return isset(self::$settings[$key]) ? self::$settings[$key] : NULL;
    }

    /**
     * Returns the user object for the given email address
     * @param string $email the email address of the user
     * @return the user object
     */
    public static function userByEmail($email) {
        foreach (self::get('users') as $user) {
          if (strtolower($user['email']) == strtolower($email)) {
            return $user;
          }
        }
        return NULL;
    }

    /**
     * Returns an associative array with host, port, username and password for the database connection
     * @return object database credentials
     */
    public static function getDBCredentials() {
      return self::get('database');
    }
    
    /**
     * The list of plugins
     * @return array of plugin objects
     */
    public static function getPlugins() {
      return self::get('plugins');
    }
    
    /**
     * Returns an array with all cron jobs
     */
    public static function getCronJobs() {
      return self::$settings['cronjobs'];
    }
    
    /**
     * Returns the plugin for the given name
     * @param string $name the name of the plugin
     * @return object the plugin data
     */
    public static function getPlugin($name) {
      foreach (self::get('plugins') as $plugin) {
        if (strtolower($name) == strtolower($plugin['name'])) {
          return $plugin;
        }
      }
      return NULL;
    }
}
