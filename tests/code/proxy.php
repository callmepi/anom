<?php
/**
 * @param class: needs to be a valid (full if namespaced) class
 * @param method: method to call
 * @param args (array): an array of passed arguments into method call
 * @param ttl (int): will be used in some cache service
 */
function proxy($class, $method, $args, $ttl)
{   
    // ksort($args);  // is not recursive, won't sort category keys
    $key = $class .':'. $method .':'. json_encode(
        $args,
        JSON_UNESCAPED_LINE_TERMINATORS|JSON_UNESCAPED_UNICODE
    );
    $data = call_user_func_array([$class, $method], $args);
    reply_json([
        'key' => $key,
        'cache' => sha1($key),
        'data' => $data
    ]);
}

# proxy('app\models\market\ProductCategories_model','categoryProducts',
#     [
#         [ 'id'=>10100, 'Hierarchy' => '.10.10100' ],     // pass category (needs Hierarchy)
#         904     // pas store
#     ],
#     100     // ttl
# );

proxy(app\models\market\ProductCategories_model::class,'categoryProducts',
    [
        [ 'id'=>10100, 'Hierarchy' => '.10.10100' ],     // pass category (needs Hierarchy)
        904     // pas store
    ],
    100     // ttl
);

