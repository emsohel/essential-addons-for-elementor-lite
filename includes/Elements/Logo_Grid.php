<?php 
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Repeater as Repeater;
use \Elementor\Utils as Utils;
use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Widget_Base as Widget_Base;
use \Essential_Addons_Elementor\Classes\Bootstrap;

class Logo_Grid extends Widget_Base
{
    use \Essential_Addons_Elementor\Traits\Helper;

    public function get_name()
    {
        return 'eael-logo-grid';
    }

    public function get_title()
    {
        return esc_html__('EA Logo Grid', 'essential-addons-elementor');
    }

    public function get_icon()
    {
        return 'eicon-accordion';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    protected function _register_controls()
    {
        /**
         * Logo Grid Settings
         */
        $this->start_controls_section(
            'eael_section_logo_grid_settings',
            [
                'label' => esc_html__('Logo Grid Settings', 'essential-addons-elementor'),
            ]
        );

        // $this->add_control(
		// 	'carousel',
		// 	[
		// 		'label' => __( 'Add Images', 'elementor' ),
		// 		'type' => Controls_Manager::GALLERY,
		// 		'default' => [],
		// 	]
		// );

        $repeater = new Repeater();

        $repeater->add_control(
            'eael_section_logo_grid_image',
			[
				'label' => esc_html__( 'Choose Image', 'essential-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
        );

        $repeater->add_control(
			'eael_section_logo_grid_show_title',
			[
				'label' => __( 'Show Title', 'plugin-domain' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
        
        $repeater->add_control(
			'title',
			[
				'label' => __( 'Logo Title', 'plugin-domain' ),
				'type' => Controls_Manager::TEXT,
                'default' => __( 'Title', 'plugin-domain' ),
                'condition' => [
                    'eael_section_logo_grid_show_title' => 'yes',
                ],
			]
        );

        $this->add_control(
            'logo_grid',
            [
                'title' => __( 'add logo' ),
                'type'  => CONTROLS_MANAGER::REPEATER,
                'fields'    => $repeater->get_controls(),
            ]
        );
        
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
    }

    protected function _content_template() {
        
    }
}
?>