<?php

/**
 * Settings helper class
 *
 * @author mbartel
 */
class Settings {

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

        self::$settings = json_decode(file_get_contents($settingsFile));
    }

    /**
     * Returns the settings value for the given key
     * @param string $key the key for the settings value
     * @return array/string settings value
     */
    public static function get($key) {
        if (self::$settings == null) {
            self::loadSettings();
        }
        return isset(self::$settings[$key]) ? self::$settings[$key] : null;
    }

}
