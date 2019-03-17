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
        $this->options[$this->root] = array();
    }

    public function add_domain($dom)
    {
        $this->currentDomain = $dom;
        if (empty($this->options)) {
            $this->options[$this->root] = array($dom => array('sitekey' => '', 'secretkey' => ''));
        } else {
            if (!key_exists($dom, $this->options[$this->root])) {
                array_push($this->options[$this->root][$dom] = array('sitekey' => '', 'secretkey' => ''));
            }

        }
    }

    public function set_current_dom($dom)
    {
        $this->currentDomain = $dom;
    }

    public function get_current_dom()
    {
        return $this->currentDomain;
    }

    public function get_all_dom()
    {
        return $this->options[$this->root];
    }

    public function add_key($key, $val)
    {
        if (key_exists($this->currentDomain, $this->options[$this->root])) {
            $this->options[$this->root][$this->currentDomain][$key] = $val;
        }

    }

    public function set_key($typekey, $val)
    {
        if (key_exists($this->currentDomain, $this->options[$this->root]) && key_exists($typekey, $this->options[$this->root][$this->currentDomain])) {
            $this->options[$this->root][$this->currentDomain][$typekey] = $val;
        }
    }

    public function get_key($typekey)
    {
        if (key_exists($this->currentDomain, $this->options[$this->root]) && key_exists($typekey, $this->options[$this->root][$this->currentDomain])) {
            return $this->options[$this->root][$this->currentDomain][$typekey];
        }
    }

    public function serialize_obj()
    {
        return serialize($this);
    }
}
