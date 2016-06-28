<?php

/**
 * A manager for video thumbnails providers.
 */
class Video_Thumbnails_Providers {

	/**
	 * The ID of this plugin.
     *
	 * @var string $plugin_name
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var string $version
	 */
	private $version;

	/**
	 * The settings class.
	 *
	 * @var Refactored_Settings $settings
	 */
	private $settings;

	/**
	 * The video providers.
	 *
	 * @var array $providers
	 */
	private $providers;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      Refactored_Settings    $settings    The settings of this plugin.
	 */
	public function __construct( $plugin_name, $version, $settings ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->settings = $settings;
        $this->providers = array();

	}

    public function load_providers()
    {
        $providers = apply_filters('video_thumbnails/providers', array(
            'collegehumor' => 'CollegeHumor_Thumbnails',
            'dailymotion' => 'Dailymotion_Thumbnails',
            'facebook' => 'Facebook_Thumbnails',
            'funnyordie' => 'FunnyOrDie_Thumbnails',
            'googledrive' => 'GoogleDrive_Thumbnails',
            'livestream' => 'Livestream_Thumbnails',
            'metacafe' => 'Metacafe_Thumbnails',
            'mpora' => 'Mpora_Thumbnails',
            'rutube' => 'Rutube_Thumbnails',
            'sapo' => 'Sapo_Thumbnails',
            'ted' => 'Ted_Thumbnails',
            'tudou' => 'Tudou_Thumbnails',
            'twitch' => 'Twitch_Thumbnails',
            'vimeo' => 'Vimeo_Thumbnails',
            'vine' => 'Vine_Thumbnails',
            'vk' => 'Vk_Thumbnails',
            'wistia' => 'Wistia_Thumbnails',
            'youku' => 'Youku_Thumbnails',
            'youtube' => 'YouTube_Thumbnails',
        ));

        foreach ( $providers as $key => $class_name ) {
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/providers/class-' . $key . '-thumbnails.php';
            $this->providers[$key] = new $class_name( $this->settings );
        }
    }

    public function get_providers()
    {
        return $this->providers;
    }

    protected function init_providers()
    {
        foreach ( $this->providers as $provider ) {
            $provider->init();
        }
    }

    public function init()
    {
        $this->load_providers();
        $this->init_providers();
    }

    public function parse( $content ) {
        $videos = array();

        foreach ( $this->providers as $provider ) {
            $videos = array_merge(
                $videos,
                $provider->parse( $content )
            );
        }

        return $videos;
    }
}