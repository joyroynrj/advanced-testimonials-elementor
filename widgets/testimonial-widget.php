<?php
if (!defined('ABSPATH')) {
    exit;
}

class ATE_Testimonial_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'ate-testimonials';
    }

    public function get_title() {
        return esc_html__('Advanced Testimonials', 'advanced-testimonials-elementor');
    }

    public function get_icon() {
        return 'eicon-testimonial';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['testimonial', 'review', 'carousel', 'slider'];
    }

    public function get_script_depends() {
        // Force enqueue scripts
        wp_enqueue_script('swiper');
        wp_enqueue_script('ate-testimonials');
        
        return ['swiper', 'ate-testimonials'];
    }

    public function get_style_depends() {
        // Force enqueue styles
        wp_enqueue_style('swiper');
        wp_enqueue_style('ate-testimonials');
        wp_enqueue_style('font-awesome-6');
        
        return ['swiper', 'ate-testimonials', 'font-awesome-6'];
    }

    protected function register_controls() {
        // Testimonials Content Section
        $this->start_controls_section(
            'section_testimonials',
            [
                'label' => esc_html__('Testimonials', 'advanced-testimonials-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        // Image
        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'show_image',
            [
                'label' => esc_html__('Show Image', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'advanced-testimonials-elementor'),
                'label_off' => esc_html__('Hide', 'advanced-testimonials-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Name
        $repeater->add_control(
            'name',
            [
                'label' => esc_html__('Name', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('John Doe', 'advanced-testimonials-elementor'),
                'label_block' => true,
            ]
        );

        // Title
        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('CEO, Company', 'advanced-testimonials-elementor'),
                'label_block' => true,
            ]
        );

        // Rating
        $repeater->add_control(
            'rating',
            [
                'label' => esc_html__('Rating', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5,
                'step' => 0.5,
                'default' => 5,
            ]
        );

        $repeater->add_control(
            'show_rating',
            [
                'label' => esc_html__('Show Rating', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'advanced-testimonials-elementor'),
                'label_off' => esc_html__('Hide', 'advanced-testimonials-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Content
        $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'advanced-testimonials-elementor'),
            ]
        );

        // Link
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'advanced-testimonials-elementor'),
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label' => esc_html__('Testimonial Slides', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'name' => esc_html__('John Doe', 'advanced-testimonials-elementor'),
                        'title' => esc_html__('CEO, Company', 'advanced-testimonials-elementor'),
                        'content' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'advanced-testimonials-elementor'),
                        'rating' => 5,
                    ],
                    [
                        'name' => esc_html__('Jane Smith', 'advanced-testimonials-elementor'),
                        'title' => esc_html__('Marketing Director', 'advanced-testimonials-elementor'),
                        'content' => esc_html__('Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'advanced-testimonials-elementor'),
                        'rating' => 5,
                    ],
                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->end_controls_section();

        // Global Options Section
        $this->start_controls_section(
            'section_global_options',
            [
                'label' => esc_html__('Global Options', 'advanced-testimonials-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'global_show_image',
            [
                'label' => esc_html__('Show Images', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'advanced-testimonials-elementor'),
                'label_off' => esc_html__('Hide', 'advanced-testimonials-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'global_show_rating',
            [
                'label' => esc_html__('Show Ratings', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'advanced-testimonials-elementor'),
                'label_off' => esc_html__('Hide', 'advanced-testimonials-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Additional Options Section
        $this->start_controls_section(
            'section_additional_options',
            [
                'label' => esc_html__('Additional Options', 'advanced-testimonials-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => esc_html__('Slides to Show', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
            ]
        );

        $this->add_responsive_control(
            'slides_to_scroll',
            [
                'label' => esc_html__('Slides to Scroll', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => 1,
                'tablet_default' => 1,
                'mobile_default' => 1,
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__('Loop', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'advanced-testimonials-elementor'),
                'label_off' => esc_html__('No', 'advanced-testimonials-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'advanced-testimonials-elementor'),
                'label_off' => esc_html__('No', 'advanced-testimonials-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed (ms)', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => esc_html__('Pause on Hover', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'advanced-testimonials-elementor'),
                'label_off' => esc_html__('No', 'advanced-testimonials-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => esc_html__('Navigation', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'both' => esc_html__('Arrows and Dots', 'advanced-testimonials-elementor'),
                    'arrows' => esc_html__('Arrows', 'advanced-testimonials-elementor'),
                    'dots' => esc_html__('Dots', 'advanced-testimonials-elementor'),
                    'none' => esc_html__('None', 'advanced-testimonials-elementor'),
                ],
                'default' => 'both',
            ]
        );

        $this->add_control(
            'space_between',
            [
                'label' => esc_html__('Space Between Slides', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 30,
            ]
        );

        $this->end_controls_section();

        // Style Sections
        $this->register_style_controls();
    }

    protected function register_style_controls() {
        // Box Style
        $this->start_controls_section(
            'section_box_style',
            [
                'label' => esc_html__('Box', 'advanced-testimonials-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__('Padding', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 30,
                    'right' => 30,
                    'bottom' => 30,
                    'left' => 30,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'box_background',
            [
                'label' => esc_html__('Background Color', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .ate-testimonial-item',
            ]
        );

        $this->add_responsive_control(
            'box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .ate-testimonial-item',
            ]
        );

        $this->end_controls_section();

        // Image Style
        $this->start_controls_section(
            'section_image_style',
            [
                'label' => esc_html__('Image', 'advanced-testimonials-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_position',
            [
                'label' => esc_html__('Position', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'top' => esc_html__('Top', 'advanced-testimonials-elementor'),
                    'left' => esc_html__('Left', 'advanced-testimonials-elementor'),
                    'right' => esc_html__('Right', 'advanced-testimonials-elementor'),
                ],
                'default' => 'top',
            ]
        );

        $this->add_responsive_control(
            'image_align',
            [
                'label' => esc_html__('Alignment', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'advanced-testimonials-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'advanced-testimonials-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'advanced-testimonials-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-image' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'image_position' => 'top',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('Width', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'size' => 80,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-image img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_size',
            [
                'label' => esc_html__('Height', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'size' => 80,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => esc_html__('Spacing', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .ate-testimonial-image img',
            ]
        );

        $this->end_controls_section();

        // Name Style
        $this->start_controls_section(
            'section_name_style',
            [
                'label' => esc_html__('Name', 'advanced-testimonials-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__('Color', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .ate-testimonial-name',
            ]
        );

        $this->add_responsive_control(
            'name_spacing',
            [
                'label' => esc_html__('Spacing', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Title Style
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title', 'advanced-testimonials-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .ate-testimonial-title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => esc_html__('Spacing', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Rating Style
        $this->start_controls_section(
            'section_rating_style',
            [
                'label' => esc_html__('Rating', 'advanced-testimonials-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'rating_color',
            [
                'label' => esc_html__('Star Color', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFB800',
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-rating i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_size',
            [
                'label' => esc_html__('Size', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_spacing',
            [
                'label' => esc_html__('Spacing', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Content Style
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => esc_html__('Content', 'advanced-testimonials-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .ate-testimonial-content',
            ]
        );

        $this->add_responsive_control(
            'content_spacing',
            [
                'label' => esc_html__('Spacing', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ate-testimonial-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Navigation Style
        $this->start_controls_section(
            'section_navigation_style',
            [
                'label' => esc_html__('Navigation', 'advanced-testimonials-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Arrow Style
        $this->add_control(
            'arrows_heading',
            [
                'label' => esc_html__('Arrows', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => esc_html__('Color', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ate-swiper-button-prev, {{WRAPPER}} .ate-swiper-button-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => esc_html__('Hover Color', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .ate-swiper-button-prev:hover, {{WRAPPER}} .ate-swiper-button-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_background',
            [
                'label' => esc_html__('Background Color', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ate-swiper-button-prev, {{WRAPPER}} .ate-swiper-button-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_background_hover',
            [
                'label' => esc_html__('Background Hover Color', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ate-swiper-button-prev:hover, {{WRAPPER}} .ate-swiper-button-next:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_size',
            [
                'label' => esc_html__('Size', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ate-swiper-button-prev, {{WRAPPER}} .ate-swiper-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_padding',
            [
                'label' => esc_html__('Padding', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ate-swiper-button-prev, {{WRAPPER}} .ate-swiper-button-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_border_radius',
            [
                'label' => esc_html__('Border Radius', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ate-swiper-button-prev, {{WRAPPER}} .ate-swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Dots Style
        $this->add_control(
            'dots_heading',
            [
                'label' => esc_html__('Dots', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label' => esc_html__('Color', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dot_active_color',
            [
                'label' => esc_html__('Active Color', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dot_size',
            [
                'label' => esc_html__('Size', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dots_position',
            [
                'label' => esc_html__('Position', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'inside' => esc_html__('Inside', 'advanced-testimonials-elementor'),
                    'outside' => esc_html__('Outside', 'advanced-testimonials-elementor'),
                ],
                'default' => 'outside',
            ]
        );

        $this->add_responsive_control(
            'dots_spacing',
            [
                'label' => esc_html__('Spacing from Carousel', 'advanced-testimonials-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'dots_position' => 'outside',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();

        if (empty($settings['testimonials'])) {
            return;
        }

        $swiper_settings = [
            'slidesPerView' => $settings['slides_to_show_mobile'] ?? 1,
            'slidesPerGroup' => $settings['slides_to_scroll_mobile'] ?? 1,
            'spaceBetween' => $settings['space_between'] ?? 30,
            'loop' => $settings['loop'] === 'yes',
            'autoplay' => $settings['autoplay'] === 'yes' ? [
                'delay' => $settings['autoplay_speed'],
                'pauseOnMouseEnter' => $settings['pause_on_hover'] === 'yes',
                'disableOnInteraction' => false,
            ] : false,
            'breakpoints' => [
                768 => [
                    'slidesPerView' => $settings['slides_to_show_tablet'] ?? 2,
                    'slidesPerGroup' => $settings['slides_to_scroll_tablet'] ?? 1,
                ],
                1024 => [
                    'slidesPerView' => $settings['slides_to_show'] ?? 3,
                    'slidesPerGroup' => $settings['slides_to_scroll'] ?? 1,
                ],
            ],
        ];

        $show_arrows = in_array($settings['navigation'], ['arrows', 'both']);
        $show_dots = in_array($settings['navigation'], ['dots', 'both']);

        if ($show_arrows) {
            $swiper_settings['navigation'] = [
                'nextEl' => '.ate-swiper-button-next-' . $widget_id,
                'prevEl' => '.ate-swiper-button-prev-' . $widget_id,
            ];
        }

        if ($show_dots) {
            $swiper_settings['pagination'] = [
                'el' => '.swiper-pagination-' . $widget_id,
                'clickable' => true,
            ];
        }

        $image_position = $settings['image_position'] ?? 'top';
        $dots_position = $settings['dots_position'] ?? 'outside';
        ?>
        <div class="ate-testimonials-wrapper ate-image-position-<?php echo esc_attr($image_position); ?> ate-dots-<?php echo esc_attr($dots_position); ?>">
            <div class="swiper ate-testimonials-carousel" data-swiper-settings='<?php echo esc_attr(json_encode($swiper_settings)); ?>'>
                <div class="swiper-wrapper">
                    <?php foreach ($settings['testimonials'] as $testimonial): 
                        $show_image = $settings['global_show_image'] === 'yes' && $testimonial['show_image'] === 'yes';
                        $show_rating = $settings['global_show_rating'] === 'yes' && $testimonial['show_rating'] === 'yes';
                        
                        $link_tag = 'div';
                        $link_attrs = '';
                        
                        if (!empty($testimonial['link']['url'])) {
                            $link_tag = 'a';
                            $link_attrs = 'href="' . esc_url($testimonial['link']['url']) . '"';
                            if ($testimonial['link']['is_external']) {
                                $link_attrs .= ' target="_blank"';
                            }
                            if ($testimonial['link']['nofollow']) {
                                $link_attrs .= ' rel="nofollow"';
                            }
                        }
                        ?>
                        <div class="swiper-slide">
                            <<?php echo $link_tag; ?> <?php echo $link_attrs; ?> class="ate-testimonial-item">
                                <?php if ($show_image && !empty($testimonial['image']['url'])): ?>
                                    <div class="ate-testimonial-image">
                                        <img src="<?php echo esc_url($testimonial['image']['url']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>">
                                    </div>
                                <?php endif; ?>

                                <div class="ate-testimonial-content-wrapper">
                                    <?php if ($show_rating && !empty($testimonial['rating'])): ?>
                                        <div class="ate-testimonial-rating">
                                            <?php $this->render_stars($testimonial['rating']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($testimonial['content'])): ?>
                                        <div class="ate-testimonial-content">
                                            <?php echo wp_kses_post($testimonial['content']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($testimonial['name'])): ?>
                                        <div class="ate-testimonial-name">
                                            <?php echo esc_html($testimonial['name']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($testimonial['title'])): ?>
                                        <div class="ate-testimonial-title">
                                            <?php echo esc_html($testimonial['title']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </<?php echo $link_tag; ?>>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($show_arrows): ?>
                    <div class="ate-swiper-button-prev ate-swiper-button-prev-<?php echo esc_attr($widget_id); ?>">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </div>
                    <div class="ate-swiper-button-next ate-swiper-button-next-<?php echo esc_attr($widget_id); ?>">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </div>
                <?php endif; ?>

                <?php if ($show_dots): ?>
                    <div class="swiper-pagination swiper-pagination-<?php echo esc_attr($widget_id); ?>"></div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    protected function render_stars($rating) {
        $full_stars = floor($rating);
        $half_star = ($rating - $full_stars) >= 0.5;
        $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);

        for ($i = 0; $i < $full_stars; $i++) {
            echo '<i class="eicon-star" aria-hidden="true"></i>';
        }

        if ($half_star) {
            echo '<i class="eicon-star-half" aria-hidden="true"></i>';
        }

        for ($i = 0; $i < $empty_stars; $i++) {
            echo '<i class="eicon-star-o" aria-hidden="true"></i>';
        }
    }
}