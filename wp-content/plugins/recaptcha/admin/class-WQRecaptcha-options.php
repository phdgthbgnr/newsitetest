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
     * URL API (site key)
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $root The current root name of collection of domains / pair sitekey - secretkey.
     */
    private $urlApi = '';
    /**
     * URL API (site key)
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $root The current root name of collection of domains / pair sitekey - secretkey.
     */
    private $plugin_name;
    /**
     * init Array options with root ID
     *
     *
     * @since   1.0.0
     */
    public function __construct($n)
    {
        $this->plugin_name = $n;
        $this->options[$this->root] = array();
        $this->options['currentDomain'] = '';
        $this->options['urlAPI'] = '';
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
        // $this->currentDomain = $dom;
        $this->options['currentDomain'] = $dom;
        if (empty($this->options[$this->root])) {
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
     * @return  string
     */
    public function get_url_api()
    {
        return $this->options['urlAPI'];
    }
    /**
     * set the selected current domain
     *
     *
     * @since   1.0.0
     * @access  public
     * @var     string
     */
    public function set_url_api($url)
    {
        $this->options['urlAPI'] = $url;
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
        // $this->currentDomain = $dom;
        $this->options['currentDomain'] = $dom;
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
        return $this->options['currentDomain'];
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
        if (key_exists($this->options['currentDomain'], $this->options[$this->root])) {
            $this->options[$this->root][$this->options['currentDomain']][$key] = $val;
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
        if (key_exists($this->options['currentDomain'], $this->options[$this->root]) && key_exists($typekey, $this->options[$this->root][$this->options['currentDomain']])) {
            $this->options[$this->root][$this->options['currentDomain']][$typekey] = $val;
        }
    }
    /**
     * get key
     *
     *
     * @since   1.0.0
     * @access  public
     * @var     string $typekey 'sitekey' or 'secretkey'
     * @return  string value of the site key or secret key oof the current selected domain
     */
    public function get_key($typekey)
    {
        if (key_exists($this->options['currentDomain'], $this->options[$this->root]) && key_exists($typekey, $this->options[$this->root][$this->options['currentDomain']])) {
            return $this->options[$this->root][$this->options['currentDomain']][$typekey];
        }
    }
    /**
     *
     */

    public function remove_domain()
    {
        if (count($this->options[$this->root]) > 0 && key_exists($this->options['currentDomain'], $this->options[$this->root])) {
            unset($this->options[$this->root][$this->options['currentDomain']]);
            // $this->currentDomain = array_key_first($this->options[$this->root]);

            return 'success';
        } else {
            return 'error';
        }
    }

    /**
     * serialize_and_update
     *
     * @access  public
     * @return void
     */
    public function serialize_and_updateOption()
    {
        update_option($this->plugin_name, serialize($this->options));
    }
    /**
     * serialize_and_update
     *
     * @access  public
     * @return void
     */
    public function getOption_and_unserialize()
    {
        $raw_options = get_option($this->plugin_name);
        if (!empty($raw_options)) {
            try {
                $this->options = unserialize($raw_options);
            } catch (Exception $e) {
                die('erreur');
            }
        }
    }
}
