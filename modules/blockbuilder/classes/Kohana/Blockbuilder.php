<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Blockbuilder.
 *
 * @package    Blockbuilder
 * @category   Base
 * @author     Myself Team
 * @copyright  (c) 2014 The Pita Group
 * @license    http://kohanaphp.com/license.html
 */
abstract class Kohana_Blockbuilder {

    /**
    * @var array configuration settings
    */
    protected $_config = array();

    /**
     * Class Main Constructor Method
     * This method is executed every time your module class is instantiated.
     */
    public function __construct() {

        // Loading module configuration file data
      //$this->_config = Kohana::config('Blockbuilder')->as_array();

        // Say hi! usign date entered in the config file
        echo 'HelloBLockbuilder      ! ';//$this->_config['some_config_value'];
       // exit;

    }

    /**
     * This method echos the given Text
     *
     * @param   string   Text to show
     */
    static function show_text($text) {

        // Echo Some Text
        echo ' & here is the static method! '.$text;

        // Show Documentation Link
        echo ' - '.MOD_CONSTANT.' see your <a href="/guide/modulename">Module Documentation</a>';

    }
    
    
     abstract public function load();
     
     abstract public function create();
     
     abstract public function index();
     
     abstract public function form();

}
