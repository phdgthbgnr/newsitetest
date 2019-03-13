<?php

class WQRecaptcha_Options
{

    /**
     * The root name of array domains.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $root The current root name of collection of domains / pair sitekey - secretkey.
     */

    private $root = 'domains';

    private $options = array();

    private $currentDomain = '';

    public function __construct()
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->root = $root;
    }

    public function add_domain($dom)
    {
        $this->currentDomain = $dom;
        if (empty($this->options)) {
            array_push($this->options, array($this->root => array('currentdomain' => $dom, $dom => array('sitekey' => '', 'secretkey' => ''))));
        } else {
            if (!key_exists($dom, $this->options[$this->root])) {
                array_push($this->options[$this->root], array('currentdomain' => $dom, $dom => array('sitekey' => '', 'secretkey' => '')));
            }

        }
    }

    public function add_sitekey($key, $val)
    {
        if (key_exists($dom, $this->options[$this->root])) {
            $this->options[$this->root][$this->currentDomain][$key] = $val;
        }

    }

    public function set_sitekey($typekey, $val)
    {
        if (key_exists($this->currentDomain, $this->options[$this->root]) && key_exists($typekey, $this->options[$this->root][$this->currentDomain])) {
            $this->options[$this->root][$this->currentDomain][$typekey] = $val;
        }
    }

    public function get_sitekey($typekey)
    {
        if (key_exists($this->currentDomain, $this->options[$this->root]) && key_exists($typekey, $this->options[$this->root][$this->currentDomain])) {
            return $this->options[$this->root][$this->currentDomain][$typekey];
        }
    }

    public function set_current_dom($dom)
    {
        $this->currentDomain = $dom;
        $this->options[$this->root]['currentdomain'] = $dom;
    }

    public function serialize_obj()
    {
        return serialize($this);
    }
}
