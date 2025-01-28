# Programmatic SEO Analyzer Plugin

## Overview
The **Programmatic SEO Analyzer** is a WordPress plugin that helps analyze SEO metrics for your posts, such as word count and keyword density. It provides insights via a frontend table (shortcode) and a REST API endpoint.

---

## Features
- Analyze published posts for word count and keyword density.
- User-friendly table display using a shortcode.
- REST API endpoint for developers to retrieve analysis programmatically.
- **Sortable columns and pagination for large datasets** in the frontend table.

---

## Installation
1. Download the plugin ZIP file or clone the repository.
2. Upload the plugin to your WordPress installation:
   - Go to **Plugins > Add New** in the WordPress admin panel.
   - Click **Upload Plugin**, choose the file, and click **Install Now**.
3. Activate the plugin from the **Plugins** menu.

---

## Usage

### Shortcode
You can display the SEO analyzer table on any post or page using the `[seo_analyzer_table]` shortcode.

#### Steps:
1. Create or edit a post/page.
2. Add the `[seo_analyzer_table]` shortcode where you want the SEO analysis table to appear.
3. Save and preview the page. The table will include:
   - An input box to enter a keyword.
   - A button to analyze published posts for the entered keyword.
   - A table displaying the post title, word count, and keyword density.
   - **Sortable columns to order data by post title, word count, or keyword density.**
   - **Pagination to handle large datasets efficiently.**

#### Example:
```html
[seo_analyzer_table]
```

### REST API Endpoint
For developers, the plugin exposes a custom REST API endpoint for retrieving SEO analysis data.

#### Endpoint Details:
- **URL:** `https://yourwebsite.com/wp-json/seo/v1/analyze`
- **Method:** `GET`
- **Parameters:**
  - `keyword` (required): The keyword for analysis.

#### Example Request:
```bash
GET https://yourwebsite.com/wp-json/seo/v1/analyze?keyword=example
```

#### Example Response:
```json
[
  {
    "post_title": "Example Post",
    "word_count": 500,
    "keyword_density": 2.5
  },
  {
    "post_title": "Another Post",
    "word_count": 750,
    "keyword_density": 1.33
  }
]
```

#### Steps to Use:
1. Send a GET request to the endpoint with the `keyword` parameter.
2. The API will return an array of objects, each containing:
   - `post_title`: Title of the post.
   - `word_count`: Total word count of the post.
   - `keyword_density`: Keyword density as a percentage.

---

## Troubleshooting
### Common Issues
- **Plugin Activation Error:** Ensure PHP version is 7.0 or higher.
- **Empty Results:** Verify that you have published posts and entered a valid keyword.
- **API Endpoint Not Found:** Ensure permalinks are enabled and set to any structure other than "Plain."

---

## Support
For issues, feature requests, or contributions, please contact the plugin author or submit a pull request to the repository.

---

## License
This plugin is open-source and available under the MIT License.

