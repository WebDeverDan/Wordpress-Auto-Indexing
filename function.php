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
            'message' => 'Domain mismatch: This site is on ' . $current_domain . ', but you requested ' . $input_domain,
            'current_domain' => $current_domain,
            'requested_domain' => $input_domain
        );
    }
    
    $current_status = get_option('blog_public');
    
    if ($current_status == '1') {
        return array(
            'success' => true,
            'message' => 'Search engine indexing is already enabled for ' . $input_domain,
            'domain' => $input_domain,
            'already_enabled' => true
        );
    }
    
    $result = update_option('blog_public', '1');
    
    if ($result) {
        return array(
            'success' => true,
            'message' => 'Search engine indexing has been enabled successfully for ' . $input_domain,
            'domain' => $input_domain,
            'previous_status' => 'disabled',
            'current_status' => 'enabled'
        );
    } else {
        return array(
            'success' => false,
            'message' => 'Failed to enable search engine indexing for ' . $input_domain,
            'domain' => $input_domain
        );
    }
}

// Execute it immediately when the site loads
add_action('init', function() {
    $result = enable_search_engine_indexing_by_domain('yourdomain.com');
    // Optional: Log the result
    error_log('SEO Indexing: ' . $result['message']);
});