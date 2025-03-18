<?php
/*
 Plugin Name: WPICP License
 Plugin URI: https://wpicp.com/download
 Description: Must-have for WordPress sites in China, showing your ICP license.
 Author: WPICP.com
 Author URI: https://wpicp.com/
 Text Domain: wpicp-license
 Domain Path: /languages
 Version: 1.8.0
 Network: True
 License: GPLv2 or later
 License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'wpicp_load_textdomain');
function wpicp_load_textdomain() {
    load_plugin_textdomain('wpicp-license', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

if (is_multisite()) {
    add_action('network_admin_menu', 'wpicp_license_network_menu');
    function wpicp_license_network_menu() {
        add_submenu_page(
            'settings.php',
            __('WP ICP License Settings', 'wpicp-license'),
            __('ICP License', 'wpicp-license'),
            'manage_network_options',
            'wpicp_license_settings',
            'wpicp_license_settings_page'
        );
    }
} else {
    add_action('admin_menu', 'wpicp_license_menu');
    function wpicp_license_menu() {
        add_options_page(
            __('WP ICP License Settings', 'wpicp-license'),
            __('ICP License', 'wpicp-license'),
            'manage_options',
            'wpicp_license_settings',
            'wpicp_license_settings_page'
        );
    }
}

add_action('admin_init', 'wpicp_license_settings');
function wpicp_license_settings() {
    add_settings_section('wpicp_license_section', __('ICP License Number', 'wpicp-license'), 'wpicp_license_section_callback', 'wpicp_license_tab');
    add_settings_section('wpicp_certificates_section', __('Certificates Center', 'wpicp-license'), 'wpicp_certificates_section_callback', 'wpicp_certificates_tab');
    add_settings_section('wpicp_badges_section', __('Badges', 'wpicp-license'), 'wpicp_badges_section_callback', 'wpicp_badges_tab');
    add_settings_section('wpicp_settings_section', __('Settings', 'wpicp-license'), 'wpicp_settings_section_callback', 'wpicp_settings_tab');

    add_settings_field('wpicp_license_field', __('ICP License', 'wpicp-license'), 'wpicp_license_field_callback', 'wpicp_license_tab', 'wpicp_license_section');
    add_settings_field('wpicp_wangan_field', __('Wangan License', 'wpicp-license'), 'wpicp_wangan_field_callback', 'wpicp_license_tab', 'wpicp_license_section');
    add_settings_field('wpicp_company', __('Company Name', 'wpicp-license'), 'wpicp_company_field_callback', 'wpicp_license_tab', 'wpicp_license_section');
    add_settings_field('wpicp_email', __('Report Email', 'wpicp-license'), 'wpicp_email_field_callback', 'wpicp_license_tab', 'wpicp_license_section');
    add_settings_field('wpicp_phone', __('Complaint Hotline', 'wpicp-license'), 'wpicp_phone_field_callback', 'wpicp_license_tab', 'wpicp_license_section');
    add_settings_field('wpicp_edi', __('EDI License Number', 'wpicp-license'), 'wpicp_edi_field_callback', 'wpicp_license_tab', 'wpicp_license_section');
    add_settings_field('wpicp_app', __('APP License Number', 'wpicp-license'), 'wpicp_app_field_callback', 'wpicp_license_tab', 'wpicp_license_section');
    add_settings_field('wpicp_miniapp', __('MiniAPP License Number', 'wpicp-license'), 'wpicp_miniapp_field_callback', 'wpicp_license_tab', 'wpicp_license_section');

    add_settings_field('wpicp_certificates', __('Certificates', 'wpicp-license'), 'wpicp_certificates_field_callback', 'wpicp_certificates_tab', 'wpicp_certificates_section');
    add_settings_field('wpicp_certificates_page', __('Certificates Page', 'wpicp-license'), 'wpicp_certificates_page_callback', 'wpicp_certificates_tab', 'wpicp_certificates_section');

    add_settings_field('wpicp_badges', __('Custom Badges', 'wpicp-license'), 'wpicp_badges_field_callback', 'wpicp_badges_tab', 'wpicp_badges_section');
    add_settings_field('wpicp_preset_badges', __('Preset Badges', 'wpicp-license'), 'wpicp_preset_badges_field_callback', 'wpicp_badges_tab', 'wpicp_badges_section');

    if (is_multisite()) {
        add_settings_field('wpicp_allow_subsite_override', __('Allow Subsite Overrides', 'wpicp-license'), 'wpicp_allow_subsite_override_callback', 'wpicp_settings_tab', 'wpicp_settings_section');
    }
    add_settings_field('wpicp_default_image_size', __('Default Image Size', 'wpicp-license'), 'wpicp_default_image_size_callback', 'wpicp_settings_tab', 'wpicp_settings_section');
    add_settings_field('wpicp_custom_css_class', __('Custom CSS Class', 'wpicp-license'), 'wpicp_custom_css_class_callback', 'wpicp_settings_tab', 'wpicp_settings_section');
    add_settings_field('wpicp_tab_visibility', __('Tab Visibility', 'wpicp-license'), 'wpicp_tab_visibility_callback', 'wpicp_settings_tab', 'wpicp_settings_section');

    $option_group = is_multisite() ? 'wpicp_license_network_settings' : 'wpicp_license_settings';
    register_setting($option_group, 'wpicp_license', 'sanitize_text_field');
    register_setting($option_group, 'wpicp_wangan', 'sanitize_text_field');
    register_setting($option_group, 'wpicp_province', 'sanitize_text_field');
    register_setting($option_group, 'wpicp_company', 'sanitize_text_field');
    register_setting($option_group, 'wpicp_email', 'sanitize_email');
    register_setting($option_group, 'wpicp_phone', 'sanitize_text_field');
    register_setting($option_group, 'wpicp_edi', 'sanitize_text_field');
    register_setting($option_group, 'wpicp_app', 'sanitize_text_field');
    register_setting($option_group, 'wpicp_miniapp', 'sanitize_text_field');
    register_setting($option_group, 'wpicp_certificates', 'wpicp_sanitize_certificates');
    register_setting($option_group, 'wpicp_certificates_page', 'intval');
    register_setting($option_group, 'wpicp_badges', 'wpicp_sanitize_badges');
    register_setting($option_group, 'wpicp_selected_presets', 'wpicp_sanitize_array');
    register_setting($option_group, 'wpicp_allow_subsite_override', 'wpicp_sanitize_checkbox');
    register_setting($option_group, 'wpicp_default_image_size', 'sanitize_text_field');
    register_setting($option_group, 'wpicp_custom_css_class', 'sanitize_text_field');
    register_setting($option_group, 'wpicp_tab_visibility', 'wpicp_sanitize_tab_visibility');
}

function wpicp_sanitize_certificates($input) {
    if (!is_array($input)) return [];
    return array_map(function($cert) {
        return [
            'image_id' => intval($cert['image_id'] ?? 0),
            'title' => sanitize_text_field($cert['title'] ?? '')
        ];
    }, $input);
}

function wpicp_sanitize_badges($input) {
    if (!is_array($input)) return [];
    return array_map(function($badge) {
        return [
            'image_id' => intval($badge['image_id'] ?? 0),
            'url' => esc_url_raw($badge['url'] ?? '')
        ];
    }, $input);
}

function wpicp_sanitize_array($input) {
    if (!is_array($input)) return [];
    return array_map('sanitize_text_field', $input);
}

function wpicp_sanitize_checkbox($input) {
    return $input === '1' ? '1' : '0';
}

function wpicp_sanitize_tab_visibility($input) {
    $default_tabs = [
        'wpicp_license_tab' => '1',
        'wpicp_certificates_tab' => '1',
        'wpicp_badges_tab' => '1',
        'wpicp_settings_tab' => '1'
    ];
    if (!is_array($input)) return $default_tabs;
    foreach ($default_tabs as $tab_id => $default_value) {
        if ($tab_id === 'wpicp_settings_tab') {
            $default_tabs[$tab_id] = '1'; // Settings tab 始终可见
        } else {
            $default_tabs[$tab_id] = isset($input[$tab_id]) && $input[$tab_id] === '1' ? '1' : '0';
        }
    }
    $visible_count = array_sum(array_map('intval', $default_tabs));
    if ($visible_count < 2) { // 确保至少保留一个非 Settings 的标签
        $default_tabs['wpicp_license_tab'] = '1';
    }
    return $default_tabs;
}

add_action('admin_init', 'wpicp_subsite_settings');
function wpicp_subsite_settings() {
    if (!is_network_admin()) {
        add_settings_field('wpicp_license_subsite_field', __('ICP License', 'wpicp-license'), 'wpicp_license_subsite_field_callback', 'general', 'default');
        add_settings_field('wpicp_wangan_subsite_field', __('Wangan License', 'wpicp-license'), 'wpicp_wangan_subsite_field_callback', 'general', 'default');
        register_setting('general', 'wpicp_license', 'sanitize_text_field');
        register_setting('general', 'wpicp_wangan', 'sanitize_text_field');
    }
}

add_action('admin_enqueue_scripts', 'wpicp_enqueue_scripts');
function wpicp_enqueue_scripts($hook) {
    if (strpos($hook, 'settings_page_wpicp_license_settings') !== false) {
        wp_enqueue_media();
        wp_enqueue_style('wpicp-admin-css', plugins_url('assets/css/admin.css', __FILE__), [], '1.8.0');
        wp_enqueue_script('wpicp-admin-js', plugins_url('assets/js/admin.js', __FILE__), ['jquery'], '1.8.0', true);
    }
}

function wpicp_license_section_callback() {
    echo '<p>' . __('Manage your ICP and related license information here.', 'wpicp-license') . '</p>';
}

function wpicp_certificates_section_callback() {
    echo '<p>' . __('Add certificates with titles and images. Use <code>[wpicp_certificates]</code> to display them (e.g., [wpicp_certificates id="0"] or [wpicp_certificates ids="0,1"]).', 'wpicp-license') . '</p>';
}

function wpicp_badges_section_callback() {
    echo '<p>' . __('Add custom badges or select presets. Use <code>[wpicp_badges]</code> (e.g., [wpicp_badges id="0"] or [wpicp_badges ids="0,1"]).', 'wpicp-license') . '</p>';
}

function wpicp_settings_section_callback() {
    echo '<p>' . __('General settings for the WPICP License plugin.', 'wpicp-license') . '</p>';
}

function wpicp_license_field_callback() {
    $value = is_multisite() ? get_site_option('wpicp_license') : get_option('wpicp_license');
    echo '<input type="text" id="wpicp_license" name="wpicp_license" value="' . esc_attr($value) . '"/>';
    echo '<p class="description" style="font-size:13px;">' . __('Enter your ICP license number information. <a href="https://wpicp.com/document/correct-format" target="_blank" rel="noopener">(Correct format?)</a>', 'wpicp-license') . '</p>';
}

function wpicp_wangan_field_callback() {
    $wangan = is_multisite() ? get_site_option('wpicp_wangan') : get_option('wpicp_wangan');
    $province = is_multisite() ? get_site_option('wpicp_province') : get_option('wpicp_province');
    ?>
    <input type="number" id="wpicp_wangan" name="wpicp_wangan" min="0" value="<?php echo esc_attr($wangan); ?>"/>
    <select id="wpicp_province" name="wpicp_province">
        <?php
        $provinces = [
            '京' => __('Beijing', 'wpicp-license'), '津' => __('Tianjin', 'wpicp-license'), '冀' => __('Hebei', 'wpicp-license'),
            '晋' => __('Shanxi', 'wpicp-license'), '蒙' => __('Inner Mongolia', 'wpicp-license'), '辽' => __('Liaoning', 'wpicp-license'),
            '吉' => __('Jilin', 'wpicp-license'), '黑' => __('Heilongjiang', 'wpicp-license'), '沪' => __('Shanghai', 'wpicp-license'),
            '苏' => __('Jiangsu', 'wpicp-license'), '浙' => __('Zhejiang', 'wpicp-license'), '皖' => __('Anhui', 'wpicp-license'),
            '闽' => __('Fujian', 'wpicp-license'), '赣' => __('Jiangxi', 'wpicp-license'), '鲁' => __('Shandong', 'wpicp-license'),
            '豫' => __('Henan', 'wpicp-license'), '鄂' => __('Hubei', 'wpicp-license'), '湘' => __('Hunan', 'wpicp-license'),
            '粤' => __('Guangdong', 'wpicp-license'), '桂' => __('Guangxi', 'wpicp-license'), '琼' => __('Hainan', 'wpicp-license'),
            '渝' => __('Chongqing', 'wpicp-license'), '川' => __('Sichuan', 'wpicp-license'), '黔' => __('Guizhou', 'wpicp-license'),
            '滇' => __('Yunnan', 'wpicp-license'), '藏' => __('Tibet', 'wpicp-license'), '陕' => __('Shaanxi', 'wpicp-license'),
            '甘' => __('Gansu', 'wpicp-license'), '青' => __('Qinghai', 'wpicp-license'), '宁' => __('Ningxia', 'wpicp-license'),
            '新' => __('Xinjiang', 'wpicp-license')
        ];
        foreach ($provinces as $abbr => $name) {
            echo '<option value="' . esc_attr($abbr) . '"' . selected($province, $abbr, false) . '>' . esc_html($name) . '</option>';
        }
        ?>
    </select>
    <p class="description" style="font-size:13px;"><?php _e('Enter your Wangan license number and select the abbreviation of your province.', 'wpicp-license'); ?></p>
    <?php
}

function wpicp_company_field_callback() {
    $value = is_multisite() ? get_site_option('wpicp_company') : get_option('wpicp_company');
    echo '<input type="text" id="wpicp_company" name="wpicp_company" value="' . esc_attr($value) . '"/>';
    echo '<p class="description" style="font-size:13px;">' . __('Use the shortcode <code>[wpicp_company]</code>', 'wpicp-license') . '</p>';
}

function wpicp_email_field_callback() {
    $value = is_multisite() ? get_site_option('wpicp_email') : get_option('wpicp_email');
    echo '<input type="text" id="wpicp_email" name="wpicp_email" value="' . esc_attr($value) . '"/>';
    echo '<p class="description" style="font-size:13px;">' . __('Use the shortcode <code>[wpicp_email]</code>', 'wpicp-license') . '</p>';
}

function wpicp_phone_field_callback() {
    $value = is_multisite() ? get_site_option('wpicp_phone') : get_option('wpicp_phone');
    echo '<input type="text" id="wpicp_phone" name="wpicp_phone" value="' . esc_attr($value) . '"/>';
    echo '<p class="description" style="font-size:13px;">' . __('Use the shortcode <code>[wpicp_phone]</code>', 'wpicp-license') . '</p>';
}

function wpicp_edi_field_callback() {
    $value = is_multisite() ? get_site_option('wpicp_edi') : get_option('wpicp_edi');
    echo '<input type="text" id="wpicp_edi" name="wpicp_edi" value="' . esc_attr($value) . '"/>';
    echo '<p class="description" style="font-size:13px;">' . __('Use the shortcode <code>[wpicp_edi]</code>', 'wpicp-license') . '</p>';
}

function wpicp_app_field_callback() {
    $value = is_multisite() ? get_site_option('wpicp_app') : get_option('wpicp_app');
    echo '<input type="text" id="wpicp_app" name="wpicp_app" value="' . esc_attr($value) . '"/>';
    echo '<p class="description" style="font-size:13px;">' . __('Use the shortcode <code>[wpicp_app]</code>', 'wpicp-license') . '</p>';
}

function wpicp_miniapp_field_callback() {
    $value = is_multisite() ? get_site_option('wpicp_miniapp') : get_option('wpicp_miniapp');
    echo '<input type="text" id="wpicp_miniapp" name="wpicp_miniapp" value="' . esc_attr($value) . '"/>';
    echo '<p class="description" style="font-size:13px;">' . __('Use the shortcode <code>[wpicp_miniapp]</code>', 'wpicp-license') . '</p>';
}

function wpicp_certificates_field_callback() {
    $certificates = is_multisite() ? get_site_option('wpicp_certificates', []) : get_option('wpicp_certificates', []);
    if (!is_array($certificates)) $certificates = [];
    ?>
    <div id="wpicp_certificates_container">
        <?php foreach ($certificates as $index => $cert) { ?>
            <div class="wpicp_certificate_row" style="margin-bottom: 10px;">
                <?php echo wp_get_attachment_image($cert['image_id'], 'thumbnail', false, ['style' => 'vertical-align:middle;']); ?>
                <input type="hidden" name="wpicp_certificates[<?php echo $index; ?>][image_id]" value="<?php echo esc_attr($cert['image_id']); ?>"/>
                <input type="text" name="wpicp_certificates[<?php echo $index; ?>][title]" value="<?php echo esc_attr($cert['title']); ?>" placeholder="Title" style="width: 200px;"/>
                <button type="button" class="button wpicp_remove_certificate"><?php _e('Remove', 'wpicp-license'); ?></button>
            </div>
        <?php } ?>
    </div>
    <button type="button" class="button wpicp-add-certificate"><?php _e('Add Certificate', 'wpicp-license'); ?></button>
    <script>
    jQuery(document).ready(function($) {
        if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
            console.error('WordPress media library is not available.');
            return;
        }
        var certCount = <?php echo count($certificates); ?>;
        $('.wpicp-add-certificate').on('click', function(e) {
            e.preventDefault();
            var frame = wp.media({
                title: '<?php _e("Select Certificate Image", "wpicp-license"); ?>',
                multiple: false,
                library: { type: 'image' },
                button: { text: '<?php _e("Select", "wpicp-license"); ?>' }
            });
            frame.on('select', function() {
                var selection = frame.state().get('selection').first().toJSON();
                var html = '<div class="wpicp_certificate_row" style="margin-bottom: 10px;">' +
                    '<img src="' + selection.url + '" style="max-width: 100px; vertical-align:middle;">' +
                    '<input type="hidden" name="wpicp_certificates[' + certCount + '][image_id]" value="' + selection.id + '"/>' +
                    '<input type="text" name="wpicp_certificates[' + certCount + '][title]" placeholder="Title" style="width: 200px;"/>' +
                    '<button type="button" class="button wpicp_remove_certificate"><?php _e("Remove", "wpicp-license"); ?></button>' +
                    '</div>';
                $('#wpicp_certificates_container').append(html);
                certCount++;
            });
            frame.open();
        });
        $(document).on('click', '.wpicp_remove_certificate', function(e) {
            e.preventDefault();
            $(this).closest('.wpicp_certificate_row').remove();
        });
    });
    </script>
    <?php
}

function wpicp_certificates_page_callback() {
    $page_id = is_multisite() ? get_site_option('wpicp_certificates_page', 0) : get_option('wpicp_certificates_page', 0);
    $pages = get_pages(['post_status' => 'publish']);
    ?>
    <select id="wpicp_certificates_page" name="wpicp_certificates_page">
        <option value="0"><?php _e('None', 'wpicp-license'); ?></option>
        <?php foreach ($pages as $page) {
            echo '<option value="' . esc_attr($page->ID) . '"' . selected($page_id, $page->ID, false) . '>' . esc_html($page->post_title) . '</option>';
        } ?>
    </select>
    <p class="description"><?php _e('Select a page containing [wpicp_certificates] to link certificate images.', 'wpicp-license'); ?></p>
    <?php
}

function wpicp_badges_field_callback() {
    $badges = is_multisite() ? get_site_option('wpicp_badges', []) : get_option('wpicp_badges', []);
    if (!is_array($badges)) $badges = [];
    ?>
    <div id="wpicp_badges_container">
        <?php foreach ($badges as $index => $badge) { ?>
            <div class="wpicp_badge_row" style="margin-bottom: 10px;">
                <?php echo wp_get_attachment_image($badge['image_id'], 'thumbnail', false, ['style' => 'vertical-align:middle;']); ?>
                <input type="hidden" name="wpicp_badges[<?php echo $index; ?>][image_id]" value="<?php echo esc_attr($badge['image_id']); ?>"/>
                <input type="text" name="wpicp_badges[<?php echo $index; ?>][url]" value="<?php echo esc_attr($badge['url']); ?>" placeholder="URL" style="width: 300px;"/>
                <button type="button" class="button wpicp_remove_badge"><?php _e('Remove', 'wpicp-license'); ?></button>
            </div>
        <?php } ?>
    </div>
    <button type="button" class="button wpicp-add-badge"><?php _e('Add Badge', 'wpicp-license'); ?></button>
    <script>
    jQuery(document).ready(function($) {
        if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
            console.error('WordPress media library is not available.');
            return;
        }
        var badgeCount = <?php echo count($badges); ?>;
        $('.wpicp-add-badge').on('click', function(e) {
            e.preventDefault();
            var frame = wp.media({
                title: '<?php _e("Select Badge Image", "wpicp-license"); ?>',
                multiple: false,
                library: { type: 'image' },
                button: { text: '<?php _e("Select", "wpicp-license"); ?>' }
            });
            frame.on('select', function() {
                var selection = frame.state().get('selection').first().toJSON();
                var html = '<div class="wpicp_badge_row" style="margin-bottom: 10px;">' +
                    '<img src="' + selection.url + '" style="max-width: 100px; vertical-align:middle;">' +
                    '<input type="hidden" name="wpicp_badges[' + badgeCount + '][image_id]" value="' + selection.id + '"/>' +
                    '<input type="text" name="wpicp_badges[' + badgeCount + '][url]" placeholder="URL" style="width: 300px;"/>' +
                    '<button type="button" class="button wpicp_remove_badge"><?php _e("Remove", "wpicp-license"); ?></button>' +
                    '</div>';
                $('#wpicp_badges_container').append(html);
                badgeCount++;
            });
            frame.open();
        });
        $(document).on('click', '.wpicp_remove_badge', function(e) {
            e.preventDefault();
            $(this).closest('.wpicp_badge_row').remove();
        });
    });
    </script>
    <?php
}

function wpicp_preset_badges_field_callback() {
    $selected_presets = is_multisite() ? get_site_option('wpicp_selected_presets', []) : get_option('wpicp_selected_presets', []);
    if (!is_array($selected_presets)) $selected_presets = [];
    $presets = [
        'government' => [
            'label' => __('Government Certifications', 'wpicp-license'),
            'items' => [
                'miit' => ['title' => __('MIIT ICP Filing', 'wpicp-license'), 'url' => 'https://beian.miit.gov.cn/', 'image' => plugins_url('/assets/images/miit.jpg', __FILE__)],
                'psb' => ['title' => __('Public Security Bureau', 'wpicp-license'), 'url' => 'https://www.beian.gov.cn/', 'image' => plugins_url('/assets/images/psb.jpg', __FILE__)],
                '12377' => ['title' => __('12377 Reporting', 'wpicp-license'), 'url' => 'https://www.12377.cn/', 'image' => plugins_url('/assets/images/12377.jpg', __FILE__)],
                'wenming' => ['title' => __('China Civilization Network', 'wpicp-license'), 'url' => 'http://www.wenming.cn/', 'image' => plugins_url('/assets/images/wenming.jpg', __FILE__)],
            ],
        ],
        'cybersecurity' => [
            'label' => __('Cybersecurity Certifications', 'wpicp-license'),
            'items' => [
                'piyao' => ['title' => __('Piyao Rumors', 'wpicp-license'), 'url' => 'https://www.piyao.org.cn/', 'image' => plugins_url('/assets/images/piyao.jpg', __FILE__)],
                'cac' => ['title' => __('Cyberspace Administration', 'wpicp-license'), 'url' => 'http://www.cac.gov.cn/', 'image' => plugins_url('/assets/images/cac.jpg', __FILE__)],
                'cnca' => ['title' => __('CNCA Certification', 'wpicp-license'), 'url' => 'http://www.cnca.gov.cn/', 'image' => plugins_url('/assets/images/cnca.jpg', __FILE__)],
            ],
        ],
        'trustworthiness' => [
            'label' => __('Trustworthiness Certifications', 'wpicp-license'),
            'items' => [
                'knet' => ['title' => __('Knet Trust', 'wpicp-license'), 'url' => 'https://ss.knet.cn/', 'image' => plugins_url('/assets/images/knet.jpg', __FILE__)],
                'szcert' => ['title' => __('SZCert', 'wpicp-license'), 'url' => 'http://szcert.ebs.org.cn/', 'image' => plugins_url('/assets/images/szcert.jpg', __FILE__)],
                'sz315' => ['title' => __('SZ315 Integrity', 'wpicp-license'), 'url' => 'https://www.sz315.org/', 'image' => plugins_url('/assets/images/sz315.jpg', __FILE__)],
                'trustasia' => ['title' => __('TrustAsia', 'wpicp-license'), 'url' => 'https://www.trustasia.com/', 'image' => plugins_url('/assets/images/trustasia.jpg', __FILE__)],
            ],
        ],
    ];
    ?>
    <div id="wpicp_preset_badges_container">
        <?php foreach ($presets as $group => $group_data) { ?>
            <h4><?php echo esc_html($group_data['label']); ?></h4>
            <?php foreach ($group_data['items'] as $key => $preset) { ?>
                <label style="display: block; margin-bottom: 10px;">
                    <input type="checkbox" name="wpicp_selected_presets[]" value="<?php echo esc_attr($key); ?>" <?php checked(in_array($key, $selected_presets)); ?> />
                    <img src="<?php echo esc_url($preset['image']); ?>" style="max-width: 100px; vertical-align: middle; margin-right: 10px;" />
                    <?php echo esc_html($preset['title']); ?> (<?php echo esc_url($preset['url']); ?>)
                </label>
            <?php } ?>
        <?php } ?>
    </div>
    <p class="description"><?php _e('Select preset badges to display with [wpicp_badges].', 'wpicp-license'); ?></p>
    <?php
}

function wpicp_allow_subsite_override_callback() {
    $value = get_site_option('wpicp_allow_subsite_override'); // 不设置默认值，依赖数据库
    if ($value === false) { // 如果未初始化，设为 '1'
        $value = '1';
        update_site_option('wpicp_allow_subsite_override', '1');
    }
    echo '<input type="checkbox" id="wpicp_allow_subsite_override" name="wpicp_allow_subsite_override" value="1"' . checked($value, '1', false) . '/>';
    echo '<label for="wpicp_allow_subsite_override">' . __('Enable to allow subsites to override network settings.', 'wpicp-license') . '</label>';
    if (WP_DEBUG) {
        error_log('WPICP License: Allow Subsite Override displayed as - ' . $value);
    }
}

function wpicp_default_image_size_callback() {
    $value = is_multisite() ? get_site_option('wpicp_default_image_size', 'thumbnail') : get_option('wpicp_default_image_size', 'thumbnail');
    ?>
    <select id="wpicp_default_image_size" name="wpicp_default_image_size">
        <option value="thumbnail" <?php selected($value, 'thumbnail'); ?>><?php _e('Thumbnail', 'wpicp-license'); ?></option>
        <option value="full" <?php selected($value, 'full'); ?>><?php _e('Full Size', 'wpicp-license'); ?></option>
    </select>
    <p class="description"><?php _e('Default size for certificates and badges when no "full" attribute is specified.', 'wpicp-license'); ?></p>
    <?php
}

function wpicp_custom_css_class_callback() {
    $value = is_multisite() ? get_site_option('wpicp_custom_css_class', '') : get_option('wpicp_custom_css_class', '');
    echo '<input type="text" id="wpicp_custom_css_class" name="wpicp_custom_css_class" value="' . esc_attr($value) . '"/>';
    echo '<p class="description">' . __('Add a custom CSS class to the shortcode output for styling.', 'wpicp-license') . '</p>';
}

function wpicp_tab_visibility_callback() {
    $visibility = is_multisite() ? get_site_option('wpicp_tab_visibility', ['wpicp_license_tab' => '1', 'wpicp_certificates_tab' => '1', 'wpicp_badges_tab' => '1', 'wpicp_settings_tab' => '1']) : get_option('wpicp_tab_visibility', ['wpicp_license_tab' => '1', 'wpicp_certificates_tab' => '1', 'wpicp_badges_tab' => '1', 'wpicp_settings_tab' => '1']);
    $tabs = [
        'wpicp_license_tab' => __('ICP License Number', 'wpicp-license'),
        'wpicp_certificates_tab' => __('Certificates Center', 'wpicp-license'),
        'wpicp_badges_tab' => __('Badges', 'wpicp-license'),
    ];
    foreach ($tabs as $tab_id => $label) {
        $checked = isset($visibility[$tab_id]) && $visibility[$tab_id] === '1' ? 'checked' : '';
        ?>
        <label style="display: block; margin-bottom: 10px;">
            <input type="checkbox" name="wpicp_tab_visibility[<?php echo esc_attr($tab_id); ?>]" value="1" <?php echo $checked; ?> />
            <?php echo esc_html($label); ?>
        </label>
        <?php
    }
    echo '<input type="hidden" name="wpicp_tab_visibility[wpicp_settings_tab]" value="1">';
    echo '<p class="description">' . __('Uncheck to hide the corresponding tab from the settings page. The Settings tab is always visible.', 'wpicp-license') . '</p>';
}

function wpicp_license_subsite_field_callback() {
    $site_value = get_option('wpicp_license');
    $network_value = get_site_option('wpicp_license');
    $allow_override = get_site_option('wpicp_allow_subsite_override', '1') === '1'; // 默认 '1'，但确保数据库优先
    $value = $allow_override ? ($site_value ?: $network_value) : $network_value;
    $disabled = is_multisite() && !$allow_override ? ' disabled' : '';
    echo '<input type="text" id="wpicp_license" name="wpicp_license" value="' . esc_attr($value) . '"' . $disabled . '/>';
    echo '<p class="description">' . __('Enter your ICP license number information.', 'wpicp-license');
    if (is_multisite() && !$allow_override) echo ' ' . __('Network settings are enforced.', 'wpicp-license');
    echo '</p>';
}

function wpicp_wangan_subsite_field_callback() {
    $site_value = get_option('wpicp_wangan');
    $network_value = get_site_option('wpicp_wangan');
    $allow_override = get_site_option('wpicp_allow_subsite_override', '1') === '1';
    $value = $allow_override ? ($site_value ?: $network_value) : $network_value;
    $disabled = is_multisite() && !$allow_override ? ' disabled' : '';
    echo '<input type="text" id="wpicp_wangan" name="wpicp_wangan" value="' . esc_attr($value) . '"' . $disabled . '/>';
    echo '<p class="description">' . __('Enter your Wangan license number.', 'wpicp-license');
    if (is_multisite() && !$allow_override) echo ' ' . __('Network settings are enforced.', 'wpicp-license');
    echo '</p>';
}

function wpicp_license_settings_page() {
    if (is_multisite() && !current_user_can('manage_network_options')) {
        wp_die(__('You do not have permission to access this page.', 'wpicp-license'));
    }
    $visibility = is_multisite() ? get_site_option('wpicp_tab_visibility', ['wpicp_license_tab' => '1', 'wpicp_certificates_tab' => '1', 'wpicp_badges_tab' => '1', 'wpicp_settings_tab' => '1']) : get_option('wpicp_tab_visibility', ['wpicp_license_tab' => '1', 'wpicp_certificates_tab' => '1', 'wpicp_badges_tab' => '1', 'wpicp_settings_tab' => '1']);
    $tabs = [
        'wpicp_license_tab' => ['label' => __('ICP License Number', 'wpicp-license'), 'section' => 'wpicp_license_tab'],
        'wpicp_certificates_tab' => ['label' => __('Certificates Center', 'wpicp-license'), 'section' => 'wpicp_certificates_tab'],
        'wpicp_badges_tab' => ['label' => __('Badges', 'wpicp-license'), 'section' => 'wpicp_badges_tab'],
        'wpicp_settings_tab' => ['label' => __('Settings', 'wpicp-license'), 'section' => 'wpicp_settings_tab'],
    ];
    ?>
    <div class="wrap">
        <h1><?php _e('ICP License Settings', 'wpicp-license'); ?></h1>
        <form method="post" action="<?php echo is_multisite() ? 'edit.php?action=wpicp_update_network_options' : 'options.php'; ?>">
            <?php settings_fields(is_multisite() ? 'wpicp_license_network_settings' : 'wpicp_license_settings'); ?>
            <div class="wpicp-tabs">
                <ul class="nav-tab-wrapper">
                    <?php
                    $first_visible = true;
                    foreach ($tabs as $tab_id => $tab_data) {
                        if ($tab_id !== 'wpicp_settings_tab' && isset($visibility[$tab_id]) && $visibility[$tab_id] === '0') continue;
                        $active = $first_visible ? ' nav-tab-active' : '';
                        echo '<li><a href="#' . esc_attr($tab_id) . '" class="nav-tab' . $active . '">' . esc_html($tab_data['label']) . '</a></li>';
                        $first_visible = false;
                    }
                    ?>
                </ul>
                <?php
                $first_visible = true;
                foreach ($tabs as $tab_id => $tab_data) {
                    $is_hidden = $tab_id !== 'wpicp_settings_tab' && isset($visibility[$tab_id]) && $visibility[$tab_id] === '0';
                    $style = $first_visible && !$is_hidden ? '' : ' style="display: none;"';
                    echo '<div id="' . esc_attr($tab_id) . '" class="tab-content"' . $style . '>';
                    do_settings_sections($tab_data['section']);
                    echo '</div>';
                    if ($is_hidden) {
                        // 为隐藏的选项卡添加隐藏字段，保留数据
                        if ($tab_id === 'wpicp_license_tab') {
                            $license = is_multisite() ? get_site_option('wpicp_license', '') : get_option('wpicp_license', '');
                            $wangan = is_multisite() ? get_site_option('wpicp_wangan', '') : get_option('wpicp_wangan', '');
                            $province = is_multisite() ? get_site_option('wpicp_province', '') : get_option('wpicp_province', '');
                            $company = is_multisite() ? get_site_option('wpicp_company', '') : get_option('wpicp_company', '');
                            $email = is_multisite() ? get_site_option('wpicp_email', '') : get_option('wpicp_email', '');
                            $phone = is_multisite() ? get_site_option('wpicp_phone', '') : get_option('wpicp_phone', '');
                            $edi = is_multisite() ? get_site_option('wpicp_edi', '') : get_option('wpicp_edi', '');
                            $app = is_multisite() ? get_site_option('wpicp_app', '') : get_option('wpicp_app', '');
                            $miniapp = is_multisite() ? get_site_option('wpicp_miniapp', '') : get_option('wpicp_miniapp', '');
                            echo '<input type="hidden" name="wpicp_license" value="' . esc_attr($license) . '">';
                            echo '<input type="hidden" name="wpicp_wangan" value="' . esc_attr($wangan) . '">';
                            echo '<input type="hidden" name="wpicp_province" value="' . esc_attr($province) . '">';
                            echo '<input type="hidden" name="wpicp_company" value="' . esc_attr($company) . '">';
                            echo '<input type="hidden" name="wpicp_email" value="' . esc_attr($email) . '">';
                            echo '<input type="hidden" name="wpicp_phone" value="' . esc_attr($phone) . '">';
                            echo '<input type="hidden" name="wpicp_edi" value="' . esc_attr($edi) . '">';
                            echo '<input type="hidden" name="wpicp_app" value="' . esc_attr($app) . '">';
                            echo '<input type="hidden" name="wpicp_miniapp" value="' . esc_attr($miniapp) . '">';
                        } elseif ($tab_id === 'wpicp_certificates_tab') {
                            $certificates = is_multisite() ? get_site_option('wpicp_certificates', []) : get_option('wpicp_certificates', []);
                            $certificates_page = is_multisite() ? get_site_option('wpicp_certificates_page', 0) : get_option('wpicp_certificates_page', 0);
                            foreach ($certificates as $index => $cert) {
                                echo '<input type="hidden" name="wpicp_certificates[' . $index . '][image_id]" value="' . esc_attr($cert['image_id']) . '">';
                                echo '<input type="hidden" name="wpicp_certificates[' . $index . '][title]" value="' . esc_attr($cert['title']) . '">';
                            }
                            echo '<input type="hidden" name="wpicp_certificates_page" value="' . esc_attr($certificates_page) . '">';
                        } elseif ($tab_id === 'wpicp_badges_tab') {
                            $badges = is_multisite() ? get_site_option('wpicp_badges', []) : get_option('wpicp_badges', []);
                            $selected_presets = is_multisite() ? get_site_option('wpicp_selected_presets', []) : get_option('wpicp_selected_presets', []);
                            foreach ($badges as $index => $badge) {
                                echo '<input type="hidden" name="wpicp_badges[' . $index . '][image_id]" value="' . esc_attr($badge['image_id']) . '">';
                                echo '<input type="hidden" name="wpicp_badges[' . $index . '][url]" value="' . esc_attr($badge['url']) . '">';
                            }
                            foreach ($selected_presets as $preset) {
                                echo '<input type="hidden" name="wpicp_selected_presets[]" value="' . esc_attr($preset) . '">';
                            }
                        }
                    }
                    $first_visible = $first_visible && !$is_hidden ? false : $first_visible;
                }
                ?>
            </div>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

if (is_multisite()) {
    add_action('network_admin_edit_wpicp_update_network_options', 'wpicp_update_network_options');
    function wpicp_update_network_options() {
        check_admin_referer('wpicp_license_network_settings-options');
        
        if (isset($_POST['wpicp_license'])) {
            update_site_option('wpicp_license', sanitize_text_field($_POST['wpicp_license']));
        }
        if (isset($_POST['wpicp_wangan'])) {
            update_site_option('wpicp_wangan', sanitize_text_field($_POST['wpicp_wangan']));
        }
        if (isset($_POST['wpicp_province'])) {
            update_site_option('wpicp_province', sanitize_text_field($_POST['wpicp_province']));
        }
        if (isset($_POST['wpicp_company'])) {
            update_site_option('wpicp_company', sanitize_text_field($_POST['wpicp_company']));
        }
        if (isset($_POST['wpicp_email'])) {
            update_site_option('wpicp_email', sanitize_email($_POST['wpicp_email']));
        }
        if (isset($_POST['wpicp_phone'])) {
            update_site_option('wpicp_phone', sanitize_text_field($_POST['wpicp_phone']));
        }
        if (isset($_POST['wpicp_edi'])) {
            update_site_option('wpicp_edi', sanitize_text_field($_POST['wpicp_edi']));
        }
        if (isset($_POST['wpicp_app'])) {
            update_site_option('wpicp_app', sanitize_text_field($_POST['wpicp_app']));
        }
        if (isset($_POST['wpicp_miniapp'])) {
            update_site_option('wpicp_miniapp', sanitize_text_field($_POST['wpicp_miniapp']));
        }
        // 调整复选框保存逻辑
        $allow_override = isset($_POST['wpicp_allow_subsite_override']) && $_POST['wpicp_allow_subsite_override'] === '1' ? '1' : '0';
        update_site_option('wpicp_allow_subsite_override', $allow_override);
        if (isset($_POST['wpicp_certificates'])) {
            update_site_option('wpicp_certificates', wpicp_sanitize_certificates($_POST['wpicp_certificates']));
        }
        if (isset($_POST['wpicp_certificates_page'])) {
            update_site_option('wpicp_certificates_page', intval($_POST['wpicp_certificates_page']));
        }
        if (isset($_POST['wpicp_badges'])) {
            update_site_option('wpicp_badges', wpicp_sanitize_badges($_POST['wpicp_badges']));
        }
        if (isset($_POST['wpicp_selected_presets'])) {
            update_site_option('wpicp_selected_presets', wpicp_sanitize_array($_POST['wpicp_selected_presets']));
        }
        if (isset($_POST['wpicp_default_image_size'])) {
            update_site_option('wpicp_default_image_size', sanitize_text_field($_POST['wpicp_default_image_size']));
        }
        if (isset($_POST['wpicp_custom_css_class'])) {
            update_site_option('wpicp_custom_css_class', sanitize_text_field($_POST['wpicp_custom_css_class']));
        }
        if (isset($_POST['wpicp_tab_visibility'])) {
            update_site_option('wpicp_tab_visibility', wpicp_sanitize_tab_visibility($_POST['wpicp_tab_visibility']));
        }

        if (WP_DEBUG) {
            error_log('WPICP License: Network options updated - ' . print_r($_POST, true));
            error_log('WPICP License: Allow Subsite Override saved as - ' . $allow_override);
        }
        wp_redirect(network_admin_url('settings.php?page=wpicp_license_settings&updated=true'));
        exit;
    }
} else {
    add_action('update_option_wpicp_tab_visibility', 'wpicp_update_single_site_options', 10, 3);
    function wpicp_update_single_site_options($old_value, $new_value, $option) {
        update_option('wpicp_tab_visibility', wpicp_sanitize_tab_visibility($new_value));
    }
}

add_action('plugins_loaded', 'wpicp_sync_cn_settings');
function wpicp_sync_cn_settings() {
    if (get_option('wpicp_license') == '' && get_option('cn_icp') != '') {
        update_option('wpicp_license', get_option('cn_icp'));
    }
    if (get_option('wpicp_wangan') == '' && get_option('cn_ga') != '') {
        update_option('wpicp_wangan', get_option('cn_ga'));
    }
}

add_shortcode('wpicp_license', 'wpicp_license_shortcode');
function wpicp_license_shortcode() {
    $site_value = get_option('wpicp_license');
    $network_value = get_site_option('wpicp_license');
    $allow_override = get_site_option('wpicp_allow_subsite_override', '1') === '1';
    $license = (is_multisite() && $allow_override) ? ($site_value ?: $network_value) : $network_value;
    if ($license) {
        return '<a href="https://beian.miit.gov.cn" target="_blank" rel="nofollow">' . esc_html($license) . '</a>';
    }
    return '';
}

add_shortcode('wpicp_wangan', 'wpicp_wangan_shortcode');
function wpicp_wangan_shortcode() {
    $site_wangan = get_option('wpicp_wangan');
    $network_wangan = get_site_option('wpicp_wangan');
    $site_province = get_option('wpicp_province');
    $network_province = get_site_option('wpicp_province');
    $allow_override = get_site_option('wpicp_allow_subsite_override', '1') === '1';
    $wangan = (is_multisite() && $allow_override) ? ($site_wangan ?: $network_wangan) : $network_wangan;
    $province = (is_multisite() && $allow_override) ? ($site_province ?: $network_province) : $network_province;
    if (!$wangan || !$province) return '';
    $text = '<img src="' . esc_url(plugins_url('assets/images/gongan.jpg', __FILE__)) . '" alt="Wangan License" style="vertical-align:middle;" /> ' . esc_html($province) . '公网安备' . esc_html($wangan) . '号';
    return '<a href="https://www.beian.gov.cn/portal/registerSystemInfo?recordcode=' . urlencode($wangan) . '" target="_blank" rel="nofollow">' . $text . '</a>';
}

add_shortcode('wpicp_province', 'wpicp_province_shortcode');
function wpicp_province_shortcode() {
    $site_value = get_option('wpicp_province');
    $network_value = get_site_option('wpicp_province');
    $allow_override = get_site_option('wpicp_allow_subsite_override', '1');
    $province = (is_multisite() && $allow_override) ? ($site_value ?: $network_value) : $network_value;
    $provinces = [
        '京' => __('Beijing', 'wpicp-license'), '津' => __('Tianjin', 'wpicp-license'), '冀' => __('Hebei', 'wpicp-license'),
        '晋' => __('Shanxi', 'wpicp-license'), '蒙' => __('Inner Mongolia', 'wpicp-license'), '辽' => __('Liaoning', 'wpicp-license'),
        '吉' => __('Jilin', 'wpicp-license'), '黑' => __('Heilongjiang', 'wpicp-license'), '沪' => __('Shanghai', 'wpicp-license'),
        '苏' => __('Jiangsu', 'wpicp-license'), '浙' => __('Zhejiang', 'wpicp-license'), '皖' => __('Anhui', 'wpicp-license'),
        '闽' => __('Fujian', 'wpicp-license'), '赣' => __('Jiangxi', 'wpicp-license'), '鲁' => __('Shandong', 'wpicp-license'),
        '豫' => __('Henan', 'wpicp-license'), '鄂' => __('Hubei', 'wpicp-license'), '湘' => __('Hunan', 'wpicp-license'),
        '粤' => __('Guangdong', 'wpicp-license'), '桂' => __('Guangxi', 'wpicp-license'), '琼' => __('Hainan', 'wpicp-license'),
        '渝' => __('Chongqing', 'wpicp-license'), '川' => __('Sichuan', 'wpicp-license'), '黔' => __('Guizhou', 'wpicp-license'),
        '滇' => __('Yunnan', 'wpicp-license'), '藏' => __('Tibet', 'wpicp-license'), '陕' => __('Shaanxi', 'wpicp-license'),
        '甘' => __('Gansu', 'wpicp-license'), '青' => __('Qinghai', 'wpicp-license'), '宁' => __('Ningxia', 'wpicp-license'),
        '新' => __('Xinjiang', 'wpicp-license')
    ];
    return isset($provinces[$province]) ? esc_html($provinces[$province]) : '';
}

add_shortcode('wpicp_p', 'wpicp_province_abbr_shortcode');
function wpicp_province_abbr_shortcode() {
    $site_value = get_option('wpicp_province');
    $network_value = get_site_option('wpicp_province');
    $allow_override = get_site_option('wpicp_allow_subsite_override', '1');
    $province = (is_multisite() && $allow_override) ? ($site_value ?: $network_value) : $network_value;
    return $province ? esc_html($province) : '';
}

add_shortcode('wpicp_company', 'wpicp_company_shortcode');
function wpicp_company_shortcode() {
    $site_value = get_option('wpicp_company');
    $network_value = get_site_option('wpicp_company');
    $allow_override = get_site_option('wpicp_allow_subsite_override', '1');
    $company = (is_multisite() && $allow_override) ? ($site_value ?: $network_value) : $network_value;
    return $company ? esc_html($company) : '';
}

add_shortcode('wpicp_email', 'wpicp_email_shortcode');
function wpicp_email_shortcode() {
    $site_value = get_option('wpicp_email');
    $network_value = get_site_option('wpicp_email');
    $allow_override = get_site_option('wpicp_allow_subsite_override', '1');
    $email = (is_multisite() && $allow_override) ? ($site_value ?: $network_value) : $network_value;
    return $email ? esc_html($email) : '';
}

add_shortcode('wpicp_phone', 'wpicp_phone_shortcode');
function wpicp_phone_shortcode() {
    $site_value = get_option('wpicp_phone');
    $network_value = get_site_option('wpicp_phone');
    $allow_override = get_site_option('wpicp_allow_subsite_override', '1');
    $phone = (is_multisite() && $allow_override) ? ($site_value ?: $network_value) : $network_value;
    return $phone ? esc_html($phone) : '';
}

add_shortcode('wpicp_edi', 'wpicp_edi_shortcode');
function wpicp_edi_shortcode() {
    $site_value = get_option('wpicp_edi');
    $network_value = get_site_option('wpicp_edi');
    $allow_override = get_site_option('wpicp_allow_subsite_override', '1');
    $edi = (is_multisite() && $allow_override) ? ($site_value ?: $network_value) : $network_value;
    return $edi ? esc_html($edi) : '';
}

add_shortcode('wpicp_app', 'wpicp_app_shortcode');
function wpicp_app_shortcode() {
    $site_value = get_option('wpicp_app');
    $network_value = get_site_option('wpicp_app');
    $allow_override = get_site_option('wpicp_allow_subsite_override', '1');
    $app = (is_multisite() && $allow_override) ? ($site_value ?: $network_value) : $network_value;
    return $app ? esc_html($app) : '';
}

add_shortcode('wpicp_miniapp', 'wpicp_miniapp_shortcode');
function wpicp_miniapp_shortcode() {
    $site_value = get_option('wpicp_miniapp');
    $network_value = get_site_option('wpicp_miniapp');
    $allow_override = get_site_option('wpicp_allow_subsite_override', '1');
    $miniapp = (is_multisite() && $allow_override) ? ($site_value ?: $network_value) : $network_value;
    return $miniapp ? esc_html($miniapp) : '';
}

add_shortcode('wpicp_certificates', 'wpicp_certificates_shortcode');
function wpicp_certificates_shortcode($atts) {
    $atts = shortcode_atts(['full' => 'false', 'id' => '', 'ids' => ''], $atts);
    $certificates = is_multisite() ? get_site_option('wpicp_certificates', []) : get_option('wpicp_certificates', []);
    $page_id = is_multisite() ? get_site_option('wpicp_certificates_page', 0) : get_option('wpicp_certificates_page', 0);
    $default_size = is_multisite() ? get_site_option('wpicp_default_image_size', 'thumbnail') : get_option('wpicp_default_image_size', 'thumbnail');
    $custom_class = is_multisite() ? get_site_option('wpicp_custom_css_class', '') : get_option('wpicp_custom_css_class', '');
    if (!is_array($certificates) || empty($certificates)) return '';
    if ($atts['id'] !== '') {
        $index = intval($atts['id']);
        $certificates = isset($certificates[$index]) ? [$certificates[$index]] : [];
    } elseif ($atts['ids'] !== '') {
        $ids = array_map('intval', explode(',', $atts['ids']));
        $certificates = array_filter($certificates, function($key) use ($ids) {
            return in_array($key, $ids);
        }, ARRAY_FILTER_USE_KEY);
    }
    if (empty($certificates)) return '';
    $size = $atts['full'] === 'true' ? 'full' : $default_size;
    $output = '<div class="wpicp-certificates ' . esc_attr($custom_class) . '" style="display: flex; flex-wrap: wrap; gap: 10px;">';
    $page_url = $page_id ? get_permalink($page_id) : '#';
    foreach ($certificates as $cert) {
        $image = wp_get_attachment_image($cert['image_id'], $size);
        $title = '<div style="text-align: center;">' . esc_html($cert['title']) . '</div>';
        $content = $atts['full'] === 'true' ? $image . $title : '<a href="' . esc_url($page_url) . '" target="_blank" rel="noopener">' . $image . $title . '</a>';
        $output .= '<div style="margin: 5px;">' . $content . '</div>';
    }
    $output .= '</div>';
    return $output;
}

add_shortcode('wpicp_badges', 'wpicp_badges_shortcode');
function wpicp_badges_shortcode($atts) {
    $atts = shortcode_atts(['id' => '', 'ids' => '', 'preset' => 'false'], $atts);
    $badges = is_multisite() ? get_site_option('wpicp_badges', []) : get_option('wpicp_badges', []);
    $selected_presets = is_multisite() ? get_site_option('wpicp_selected_presets', []) : get_option('wpicp_selected_presets', []);
    $default_size = is_multisite() ? get_site_option('wpicp_default_image_size', 'thumbnail') : get_option('wpicp_default_image_size', 'thumbnail');
    $custom_class = is_multisite() ? get_site_option('wpicp_custom_css_class', '') : get_option('wpicp_custom_css_class', '');
    if (!is_array($badges)) $badges = [];
    if (!is_array($selected_presets)) $selected_presets = [];
    $presets = [
        'miit' => ['title' => __('MIIT ICP Filing', 'wpicp-license'), 'url' => 'https://beian.miit.gov.cn/', 'image' => plugins_url('/assets/images/miit.jpg', __FILE__)],
        'psb' => ['title' => __('Public Security Bureau', 'wpicp-license'), 'url' => 'https://www.beian.gov.cn/', 'image' => plugins_url('/assets/images/psb.jpg', __FILE__)],
        '12377' => ['title' => __('12377 Reporting', 'wpicp-license'), 'url' => 'https://www.12377.cn/', 'image' => plugins_url('/assets/images/12377.jpg', __FILE__)],
        'wenming' => ['title' => __('China Civilization Network', 'wpicp-license'), 'url' => 'http://www.wenming.cn/', 'image' => plugins_url('/assets/images/wenming.jpg', __FILE__)],
        'piyao' => ['title' => __('Piyao Rumors', 'wpicp-license'), 'url' => 'https://www.piyao.org.cn/', 'image' => plugins_url('/assets/images/piyao.jpg', __FILE__)],
        'cac' => ['title' => __('Cyberspace Administration', 'wpicp-license'), 'url' => 'http://www.cac.gov.cn/', 'image' => plugins_url('/assets/images/cac.jpg', __FILE__)],
        'cnca' => ['title' => __('CNCA Certification', 'wpicp-license'), 'url' => 'http://www.cnca.gov.cn/', 'image' => plugins_url('/assets/images/cnca.jpg', __FILE__)],
        'knet' => ['title' => __('Knet Trust', 'wpicp-license'), 'url' => 'https://ss.knet.cn/', 'image' => plugins_url('/assets/images/knet.jpg', __FILE__)],
        'szcert' => ['title' => __('SZCert', 'wpicp-license'), 'url' => 'http://szcert.ebs.org.cn/', 'image' => plugins_url('/assets/images/szcert.jpg', __FILE__)],
        'sz315' => ['title' => __('SZ315 Integrity', 'wpicp-license'), 'url' => 'https://www.sz315.org/', 'image' => plugins_url('/assets/images/sz315.jpg', __FILE__)],
        'trustasia' => ['title' => __('TrustAsia', 'wpicp-license'), 'url' => 'https://www.trustasia.com/', 'image' => plugins_url('/assets/images/trustasia.jpg', __FILE__)],
    ];
        $all_badges = $badges;
    if ($atts['preset'] !== 'true') {
        foreach ($selected_presets as $key) {
            if (isset($presets[$key])) {
                $all_badges[] = ['image_id' => null, 'url' => $presets[$key]['url'], 'preset_image' => $presets[$key]['image']];
            }
        }
    }
    if (empty($all_badges)) return '';
    if ($atts['id'] !== '') {
        $index = intval($atts['id']);
        $all_badges = isset($all_badges[$index]) ? [$all_badges[$index]] : [];
    } elseif ($atts['ids'] !== '') {
        $ids = array_map('intval', explode(',', $atts['ids']));
        $all_badges = array_filter($all_badges, function($key) use ($ids) {
            return in_array($key, $ids);
        }, ARRAY_FILTER_USE_KEY);
    }
    if (empty($all_badges)) return '';
    $output = '<div class="wpicp-badges ' . esc_attr($custom_class) . '" style="display: flex; flex-wrap: wrap; gap: 10px;">';
    foreach ($all_badges as $badge) {
        $image = isset($badge['preset_image']) ? '<img src="' . esc_url($badge['preset_image']) . '" style="max-width: 100px;" />' : wp_get_attachment_image($badge['image_id'], $default_size);
        $output .= '<a href="' . esc_url($badge['url']) . '" target="_blank" rel="noopener">' . $image . '</a>';
    }
    $output .= '</div>';
    return $output;
}