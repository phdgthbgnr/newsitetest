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
    /**
     * array of settings
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $root The current root name of collection of domains / pair sitekey - secretkey.
     */
    private $options = array();
    /**
     * current domain selected or new domain
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $root The current root name of collection of domains / pair sitekey - secretkey.
     */
    private $currentDomain = '';
    /**
     * init Array options with root ID
     *
     *
     * @since   1.0.0
     */
    public function __construct()
    {
        $this->options[$this->root] = array();
    }
    /**
     * init Array options with root ID
     *
     *
     * @since   1.0.0
     * @access  public
     * @var     string
     */
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
    /**
     * set the selected current domain
     *
     *
     * @since   1.0.0
     * @access  public
     * @var     string
     */
    public function set_current_dom($dom)
    {
        $this->currentDomain = $dom;
    }
    /**
     * return the selected current domain
     *
     *
     * @since   1.0.0
     * @access  public
     * @return  string
     */
    public function get_current_dom()
    {
        return $this->currentDomain;
    }
    /**
     * return all domains
     *
     *
     * @since   1.0.0
     * @access  public
     * @return  array
     */
    public function get_all_dom()
    {
        return $this->options[$this->root];
    }
    /**
     * set key
     *
     *
     * @since   1.0.0
     * @access  public
     * @param   string $key 'sitekey' or 'secretkey'
     * @param   string $val value of the site key or secret key
     */
    public function add_key($key, $val)
    {
        if (key_exists($this->currentDomain, $this->options[$this->root])) {
            $this->options[$this->root][$this->currentDomain][$key] = $val;
        }

    }
    /**
     * set key
     *
     *
     * @since   1.0.0
     * @access  public
     * @param   string typekey 'sitekey' or 'secretkey'
     * @param   string $val value of the site key or secret key
     */
    public function set_key($typekey, $val)
    {
        if (key_exists($this->currentDomain, $this->options[$this->root]) && key_exists($typekey, $this->options[$this->root][$this->currentDomain])) {
            $this->options[$this->root][$this->currentDomain][$typekey] = $val;
        }
    }
     /**
     * set key
     *
     *
     * @since   1.0.0
     * @access  public
     * @var     string $typekey 'sitekey' or 'secretkey'
     * @return  string value of the site key or secret key oof the current selected domain
     */
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
