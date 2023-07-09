<?php 
namespace app\extends;

use app\models\Cms_model;
use app\models\Access_model;

/** Cache service
 * -----------------------------------------------------------------------------
 * 
 * class that gathers common source-expensive queries
 * and helps for performance optimization
 * 
 * when forcing cache creation the following option is used:
 * $options = PROXY_IGNORE_CACHE
 * 
 */
class Cache_service
{

    public static function courses_struct( $options = 0 )
    {
        return proxy(                  // cache-get courses
            [\app\models\Cms_model::class, 'courses_struct'],
            [], CACHE_ROOT_TTL,
            $options
        );
    }

    public static function files_attributes( $options = 0 )
    {
        return  proxy(
            [\app\models\Cms_model::class, 'files'],
            [], CACHE_ROOT_TTL,
            $options
        );
    }


    /** entity
     * 
     * create and retrieve a cached array of some entity
     * 
     * @param $entiry (string|method): method of the main CMS model
     * NOTE: CRITICAL: method must exist in the main CMS model
     */
    public static function entity( $entity, $options = 0 )
    {
        $allowed_methods = [    // to make sure method existence
            'page',
            'course',
            'lesson',
        ];

        // TODO: needs testing before release
        // return proxy(
        //     [\app\models\Cms_model::class, $entity],
        //     [], CACHE_ROOT_TTL,
        //     $options
        // );
    }


}