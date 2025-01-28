<?php
/**
 * Plugin Name: Programmatic SEO Analyzer
 * Description: A WordPress plugin for analyzing posts' SEO metrics such as word count and keyword density.
 * Version: 1.0.0
 * Author: Dime Vakanski
 * Namespace: WPProgrammaticSEO
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue DataTables scripts and styles
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('datatables-js', 'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js', ['jquery'], '1.13.4', true);
    wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css', [], '1.13.4');
});

// Autoload Classes
spl_autoload_register(function ($class) {
    $namespace = 'WPProgrammaticSEO\\';
    if (strpos($class, $namespace) === 0) {
        $class_name = str_replace($namespace, '', $class);
        $file_path = plugin_dir_path(__FILE__) . 'includes/' . str_replace('\\', '/', $class_name) . '.php';
        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }
});

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/SEOAnalyzer.php';

use WPProgrammaticSEO\SEOAnalyzer;

// Initialize and Register the SEO Analyzer Plugin
add_action('plugins_loaded', function () {
    $seoAnalyzer = new SEOAnalyzer();
    $seoAnalyzer->register_rest_endpoint();

    // Hook frontend table display function to a shortcode
    add_shortcode('seo_analyzer_table', function () {
        ob_start();
        ?>
        <div id="seo-analyzer-table" style="margin: 20px 0;">
            <input type="text" id="seo-keyword" placeholder="Enter keyword" style="padding: 5px; margin-right: 10px;">
            <button id="seo-analyze-btn" style="padding: 5px 10px;">Analyze</button>
            <table id="seo-results" class="display" style="width: 100%; margin-top: 20px;">
                <thead>
                    <tr>
                        <th>Post Title</th>
                        <th>Word Count</th>
                        <th>Keyword Density</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>No data available</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                const table = $('#seo-results').DataTable(); // Initialize DataTables
    
                $('#seo-analyze-btn').on('click', function () {
                    const keyword = $('#seo-keyword').val().trim();
                    if (!keyword) {
                        alert('Please enter a keyword.');
                        return;
                    }
    
                    fetch(`${window.location.origin}/wp-json/seo/v1/analyze?keyword=${encodeURIComponent(keyword)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Failed to fetch data');
                            }
                            return response.json();
                        })
                        .then(data => {
                            table.clear(); // Clear existing table data
    
                            if (data.length === 0) {
                                alert('No results found for the keyword.');
                                return;
                            }
    
                            data.forEach(post => {
                                table.row.add([
                                    post.post_title,
                                    post.word_count,
                                    `${post.keyword_density.toFixed(2)}%`
                                ]);
                            });
    
                            table.draw(); // Redraw the table with new data
                        })
                        .catch(error => {
                            console.error(error);
                            alert('An error occurred while fetching the data.');
                        });
                });
            });
        </script>
        <?php
        return ob_get_clean();
    });
    
    
});

// Activation Hook
register_activation_hook(__FILE__, function () {
    // Ensure any setup, like custom database tables or options
});

// Deactivation Hook
register_deactivation_hook(__FILE__, function () {
    // Cleanup tasks, if necessary
});
