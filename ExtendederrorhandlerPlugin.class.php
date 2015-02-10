<?php

require 'bootstrap.php';

/**
 * ExtendederrorhandlerPlugin.class.php
 *
 * ...
 *
 * @author  Florian Bieringer <florian.bieringer@uni-passau.de>
 * @version 1.0
 */
class ExtendederrorhandlerPlugin extends StudIPPlugin implements SystemPlugin {

    public static function getErrors() {
        return array(E_ERROR, E_WARNING, E_PARSE);
    }

    public function __construct() {
        parent::__construct();
        set_error_handler(array($this, "extendedErrorHandler"));
        register_shutdown_function(array($this, "shutdownFunction"));
        
        // In case of production help user to show no errors and be able to redirect
        if (Studip\ENV == 'production') {
            ini_set('display_errors', 0);
        }
        
        $navigation = new AutoNavigation(_('ErrorReport'));
        $navigation->setURL(PluginEngine::GetURL($this, array(), 'show'));
        Navigation::addItem('/admin/extendederrorhandlerplugin', $navigation);
    }

    public function initialize() {


        PageLayout::addStylesheet($this->getPluginURL() . '/assets/style.css');
        PageLayout::addScript($this->getPluginURL() . '/assets/application.js');
    }

    public function perform($unconsumed_path) {
        $this->setupAutoload();
        $dispatcher = new Trails_Dispatcher(
                $this->getPluginPath(), rtrim(PluginEngine::getLink($this, array(), null), '/'), 'show'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }

    private function setupAutoload() {
        if (class_exists('StudipAutoloader')) {
            StudipAutoloader::addAutoloadPath(__DIR__ . '/models');
        } else {
            spl_autoload_register(function ($class) {
                include_once __DIR__ . $class . '.php';
            });
        }
    }

    public function shutdownFunction() {
        $error = error_get_last();
        if ($error['type'] === E_ERROR) {
            $this->extendedErrorHandler($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    public function extendedErrorHandler($errno, $errstr, $errfile, $errline) {

        if (in_array($errno, self::getErrors())) {

            // Collect data
            $values = array(
                $errno,
                $errstr,
                $errfile,
                $errline,
                $GLOBALS['user']->id,
                $_SERVER['REMOTE_ADDR'],
                time(),
                Request::isXhr() ? 'true' : 'false',
                $errno == E_ERROR ? $_SERVER['REQUEST_URI'] : '',
                $errno == E_ERROR ? var_export($_REQUEST, true) : ''
            );
            try {

                // Prepare sql not cached since errors shouldnt occur this often
                $stmt = DBManager::get()->prepare("INSERT INTO extended_errors (code, text, file, line, user_id, ip, mkdate, xhr, requested_url, request_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute($values);

                // On fatal in productive mode redirect
                if ($errno == E_ERROR) {
                    if (Studip\ENV == 'production') {
                        URLHelper::setBaseUrl($GLOBALS['ABSOLUTE_URI_STUDIP']);
                        $location = PluginEngine::getLink($this, array(), null) . "show/error";
                        header("Location: " . $location);
                    }
                    die;
                }
            } catch (Exception $ex) {
                die;
            }
            return false;
        }
    }

}
