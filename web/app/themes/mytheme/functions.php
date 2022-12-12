<?php

add_action('init', 'prowp_register_my_post_types');

function prowp_register_my_post_types()
{
    register_post_type(
        'products',
        [
            'labels' => ['name' => 'Products'],
            'public' => true,
            'show_in_graphql' => true,
            'supports' => ['title', 'editor', 'custom-fields'],
            'graphql_single_name' => 'Product',
            'graphql_plural_name' => 'Products',
            'graphql_interfaces' => ['Node', 'NodeWithTitle'],
            'taxonomies' => ['category']
        ]
    );

    register_graphql_connection(
        [
            'fromType' => 'RootQuery',
            'toType' => 'Product',
            'fromFieldName' => 'productsByTerm',
            'connectionArgs' => [
                'categoryId' => [
                    'type' => 'Int',
                    'description' => __('Category Id', 'your-textdomain'),
                ]
            ],
            'resolve' => function ($root, $args, $context, $info) {
                $resolver = new \WPGraphQL\Data\Connection\PostObjectConnectionResolver($root, $args, $context, $info);
                $resolver->set_query_arg('post_type', ['products']);
                return $resolver->get_connection();
            }
        ]
    );
}
