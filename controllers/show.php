<?php

class ShowController extends StudipController {

    const SINCE = 1209600;

    public function __construct($dispatcher) {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
    }

    public function before_filter(&$action, &$args) {
        parent::before_filter($action, $args);

        $this->set_layout($GLOBALS['template_factory']->open('layouts/base_without_infobox.php'));
//      PageLayout::setTitle('');
    }

    public function index_action() {
        $GLOBALS['perm']->check('root');
        $stmt = DBManager::get()->prepare("SELECT text,file,line,COUNT(*) as occurance FROM extended_errors WHERE code = ? AND mkdate > ? GROUP BY code,file, line ORDER BY occurance DESC");
        $stmt->execute(array(E_ERROR, time() - self::SINCE));
        $this->errors['Errors'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->execute(array(E_WARNING, time() - self::SINCE));
        $this->errors['Warnings'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function details_action() {
        $GLOBALS['perm']->check('root');
        $stmt = DBManager::get()->prepare("SELECT * FROM extended_errors WHERE file = ? AND line = ? ORDER BY mkdate DESC");
        $stmt->execute(array(Request::get('file'), Request::get('line')));
        $this->errors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function error_action() {
        
    }

    // customized #url_for for plugins
    function url_for($to) {
        $args = func_get_args();

        # find params
        $params = array();
        if (is_array(end($args))) {
            $params = array_pop($args);
        }

        # urlencode all but the first argument
        $args = array_map('urlencode', $args);
        $args[0] = $to;

        return PluginEngine::getURL($this->dispatcher->plugin, $params, join('/', $args));
    }

}
