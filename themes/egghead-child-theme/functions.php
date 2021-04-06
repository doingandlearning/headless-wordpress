<?php

add_action('wp_enqueue_scripts', 'enqueue_parent_styles');

function enqueue_parent_styles()
{
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

add_action('rest_api_init', function () {
  register_rest_field('post', 'comments', [
    'get_callback' => function ($post_arr) {
      $args = [
        'post_id' => $post_arr['id']
      ];
      $comments = get_comments($args);
      return (array) $comments;
    },
    'schema' => [
      'description' => __('Comments'),
      'type' => 'array'
    ]
  ]);
});

add_action('rest_api_init', function () {
  register_rest_field('post', 'author_details', [
    'get_callback' => function ($post_arr) {
      $author_obj = get_user_by('id', $post_arr['author']);
      return (array) $author_obj;
    },
    'schema' => [
      'description' => __('Author Details'),
      'type' => 'array'
    ]
  ]);
});

add_filter("allowed_block_types", "headless_allowed_block_types");

function headless_allowed_block_types($allowed_blocks)
{
  return [
    'core/image',
    'core/paragraph',
    'core/heading',
    'core/list'
  ];
}

add_filter('register_post_type_args', function ($args, $post_type) {
  if ($post_type === "chairs") {
    $args['show_in_graphql'] = true;
    $args['graphql_single_name'] = "chair";
    $args['graphql_plural_name'] = "chairs";
  }

  return $args;
}, 10, 2);

add_filter('http_request_host_is_external', '__return_true');
