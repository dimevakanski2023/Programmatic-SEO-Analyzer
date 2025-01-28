<?php

namespace WPProgrammaticSEO;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SEOAnalyzer
{
    /**
     * Registers the custom REST API endpoint.
     */
    public function register_rest_endpoint()
    {
        add_action('rest_api_init', function () {
            register_rest_route('seo/v1', '/analyze', [
                'methods'  => 'GET',
                'callback' => [$this, 'analyze_posts'],
                'permission_callback' => '__return_true', // Make sure to adjust permissions as needed
            ]);
        });
    }

    /**
     * Analyzes published posts for word count and keyword density.
     *
     * @param \WP_REST_Request $request
     * @return array
     */
    public function analyze_posts($request)
    {
        $keyword = sanitize_text_field($request->get_param('keyword'));
        if (empty($keyword)) {
            return new \WP_Error('no_keyword', 'Keyword parameter is required.', ['status' => 400]);
        }

        $posts = get_posts([
            'post_type'   => 'post',
            'post_status' => 'publish',
            'numberposts' => -1,
        ]);

        $results = [];

        foreach ($posts as $post) {
            $word_count = str_word_count(strip_tags($post->post_content));
            $keyword_count = substr_count(strtolower($post->post_content), strtolower($keyword));
            $keyword_density = ($word_count > 0) ? ($keyword_count / $word_count) * 100 : 0;

            $results[] = [
                'post_title'      => $post->post_title,
                'word_count'      => $word_count,
                'keyword_density' => $keyword_density,
            ];
        }

        return $results;
    }
}
