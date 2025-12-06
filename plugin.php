<?php
/**
 * Plugin Name: SEO Indexing Enabler
 * Description: Enables search engine indexing for specific domain
 * Version: 1.0
 * Author: Daniel Bradley
 */

function enable_search_engine_indexing_by_domain($domain) {
    $input_domain = parse_url($domain, PHP_URL_HOST);
    if (!$input_domain) {
        $input_domain = $domain;
    }
    
    $input_domain = strtolower(trim($input_domain));
    $input_domain = preg_replace('/^www\./', '', $input_domain);
    
    $current_site_url = get_site_url();
    $current_domain = parse_url($current_site_url, PHP_URL_HOST);
    $current_domain = strtolower(trim($current_domain));
    $current_domain = preg_replace('/^www\./', '', $current_domain);
    
    if ($input_domain !== $current_domain) {
        return array(
            'success' => false,
            'message' => 'Domain mismatch: This site is on ' . $current_domain . ', but you requested ' . $input_domain
        );
    }
    
    $current_status = get_option('blog_public');
    
    if ($current_status == '1') {
        return array(
            'success' => true,
            'message' => 'Search engine indexing is already enabled',
            'already_enabled' => true
        );
    }
    
    $result = update_option('blog_public', '1');
    
    return array(
        'success' => $result,
        'message' => $result ? 'Search engine indexing enabled successfully' : 'Failed to enable indexing'
    );
}

register_activation_hook(__FILE__, function() {
    $result = enable_search_engine_indexing_by_domain('yourdomain.com'); // Change this!
    update_option('seo_indexing_last_result', $result['message']);
});

add_action('init', function() {
    if (!get_option('seo_indexing_enabled')) {
        $result = enable_search_engine_indexing_by_domain('yourdomain.com'); // Change this!
        if ($result['success']) {
            update_option('seo_indexing_enabled', true);
        }
    }
});