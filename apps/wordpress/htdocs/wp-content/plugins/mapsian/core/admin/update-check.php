<?php

class mapsian_auto_update {

	public $current_version;
	public $update_path;
	public $plugin_path;
	public $plugin_name;
	public $plugin_slug;
	public $slug;

    function __construct($plugin_path, $plugin_slug){
		$this->plugin_path = $plugin_path;
		$this->update_path = 'http://update.wpkorea.org/Mapsian/';
		$this->plugin_slug = $plugin_slug;
		$this->current_version = $this->get_mapsian_update_info('Version');
		$this->plugin_name = $this->get_mapsian_update_info('Name');

		list($t1, $t2) = explode('/', $plugin_slug);
		$this->slug = str_replace('.php', '', $t2);

		// define the alternative API for updating checking
		add_filter('pre_set_site_transient_update_plugins', array(&$this, 'check_mapsian_update'));

		// Define the alternative response for information checking
		add_filter('plugins_api', array(&$this, 'check_mapsian_info'), 10, 3);
	}

	public function get_mapsian_update_info($rType){  // 플러그인 정보 추출
		$default_headers = array(
			'Name' => 'Plugin Name',
			'Version' => 'Version'
		);

		$plugin_data = get_file_data($this->plugin_path, $default_headers, 'plugin');
		return $plugin_data[$rType];
	}

    /**
     * Add our self-hosted autoupdate plugin to the filter transient
     *
     * @param $transient
     * @return object $ transient
     */
    public function check_mapsian_update($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }
 
        // Get the remote version
        $remote_version = $this->getRemote_mapsian_version();
 
        // If a newer version is available, add the update
        if (version_compare($this->current_version, $remote_version, '<')) {
            $obj = new stdClass();
            $obj->slug = $this->slug;
            $obj->new_version = $remote_version;
            $obj->url = $this->update_path;
            $obj->package = $this->update_path;
            $transient->response[$this->plugin_slug] = $obj;
        }
        //var_dump($transient);
        return $transient;
    }
 
    /**
     * Add our self-hosted description to the filter
     *
     * @param boolean $false
     * @param array $action
     * @param object $arg
     * @return bool|object
     */
    public function check_mapsian_info($false, $action, $arg)
    {
        if ($arg->slug === $this->slug) {
            $information = $this->getRemote_mapsian_information();
            return $information;
        }
        return false;
    }
 

    /**
     * Return the remote version
     * @return string $remote_version
     */
    public function getRemote_mapsian_version()
    {
        $request = wp_remote_post($this->update_path, array('body' => array('action' => 'version')));
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
            return $request['body'];
        }
        return false;
    }
 
    /**
     * Get information about the remote version
     * @return bool|object
     */
    public function getRemote_mapsian_information()
    {
        $request = wp_remote_post($this->update_path, array('body' => array('action' => 'info')));
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
            return unserialize($request['body']);
        }
        return false;
    }
 
    /**
     * Return the status of the plugin licensing
     * @return boolean $remote_license
     */
    public function getRemote_mapsian_license()
    {
        $request = wp_remote_post($this->update_path, array('body' => array('action' => 'license')));
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
            return $request['body'];
        }
        return false;
    }
}



?>