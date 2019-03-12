<?php

class WQRecaptcha_Options
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The root name of array domains.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $root The current root name of collection of domains / pair sitekey - secretkey.
     */

    private $root = 'domains';

    private $options = array();

    public function __construct($plugin_name, $version, $root)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->root = $root;
    }

    public function add_domain($dom)
    {
        if (empty($this->domains)) {
            array_push($this->options, array($this->root => array('currentdomain' => $dom, $dom => array('sitekey' => '', 'secretkey' => ''))));
        } else {
            if (!key_exists($dom, $this->options[$this->root])) {
                array_push($this->options[$this->root], array('currentdomain' => $dom, $dom => array('sitekey' => '', 'secretkey' => '')));
            }

        }
    }

    public function add_sitekey($dom, $key, $val)
    {
        if (key_exists($dom, $this->options[$this->root])) {
            $this->options[$this->root][$dom][$key] = $val;
        }

    }

    public function set_current_dom($dom){
        $this->options[$this->root]['currentdomain'] = $dom;
    }

    public function serialize_obj()
    {
        return serialize($this);
    }
}
