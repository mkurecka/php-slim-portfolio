# Blog Webhook API Documentation

This document describes how to use the blog webhook API to publish blog posts from external sources.

## Overview

The blog webhook API allows you to programmatically create blog posts by sending a POST request to the webhook endpoint. This is useful for integrating with external content management systems, automation tools, or other services that generate content.

## Endpoint

```
POST /api/webhook/blog
```

## Authentication

Authentication is required for all webhook requests. You can include your API key in one of the following ways:

1. **Bearer Token** in the Authorization header:
   ```
   Authorization: Bearer YOUR_API_KEY
   ```

2. **Custom Header**:
   ```
   X-API-Key: YOUR_API_KEY
   ```

3. **Query Parameter**:
   ```
   /api/webhook/blog?api_key=YOUR_API_KEY
   ```

## Request Format

The request body should be a JSON object with the following structure:

```json
{
  "title": "Blog Post Title",
  "content": "Full markdown content...",
  "excerpt": "Optional excerpt text",
  "slug": "optional-custom-slug",
  "date": "2025-03-22",
  "tags": ["tag1", "tag2"],
  "youtube_url": "https://youtube.com/watch?v=..."
}
```

### Required Fields

- `title`: The title of the blog post
- `content`: The full content of the blog post in Markdown format

### Optional Fields

- `excerpt`: A short summary of the blog post. If not provided, it will be automatically generated from the content.
- `slug`: A URL-friendly version of the title. If not provided, it will be automatically generated from the title.
- `date`: The publication date in YYYY-MM-DD format. If not provided, the current date will be used.
- `tags`: An array of tags for the blog post. If not provided, an empty array will be used.
- `youtube_url`: A URL to a YouTube video associated with the post. If not provided, it will be set to null.

## Response Format

The API will respond with a JSON object containing information about the result of the operation.

### Success Response (201 Created)

```json
{
  "success": true,
  "message": "Blog post created successfully",
  "status": 201,
  "post": {
    "id": 3,
    "slug": "blog-post-title",
    "title": "Blog Post Title"
  }
}
```

### Error Responses

#### Invalid API Key (401 Unauthorized)

```json
{
  "success": false,
  "message": "Invalid API key",
  "status": 401
}
```

#### Webhook Disabled (403 Forbidden)

```json
{
  "success": false,
  "message": "Blog webhook is disabled",
  "status": 403
}
```

#### Missing Required Fields (400 Bad Request)

```json
{
  "success": false,
  "message": "Missing required fields: title and content are required",
  "status": 400
}
```

#### Server Error (500 Internal Server Error)

```json
{
  "success": false,
  "message": "Error creating blog post: [error message]",
  "status": 500
}
```

## Example

### cURL Example

```bash
curl -X POST \
  http://example.com/api/webhook/blog \
  -H 'Content-Type: application/json' \
  -H 'X-API-Key: your-api-key-here' \
  -d '{
    "title": "My New Blog Post",
    "content": "## Hello World\n\nThis is my first blog post created via the API.",
    "tags": ["api", "test"]
  }'
```

### PHP Example

```php
<?php
$apiKey = 'your-api-key-here';
$url = 'http://example.com/api/webhook/blog';

$data = [
    'title' => 'My New Blog Post',
    'content' => "## Hello World\n\nThis is my first blog post created via the API.",
    'tags' => ['api', 'test']
];

$jsonData = json_encode($data);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-API-Key: ' . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

echo "Response Code: $httpCode\n";
echo "Response: $response\n";
```

## Setting Up the Webhook

1. Log in to the admin dashboard
2. Navigate to Webhook Settings
3. Enable the Blog Webhook
4. Generate an API key (or use the existing one)
5. Save the settings

The API key will be displayed on the settings page. Make sure to keep this key secure, as it provides access to create content on your site.
