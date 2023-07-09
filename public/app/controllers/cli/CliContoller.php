<?php

namespace app\controllers\cli;

/** CliContoller
 * 
 * A typical cli constoller; this one is used to
 * re-generate cashes of commonly used entities
 * with expensive queries like (ex:) categories;
 * 
 * Proxy calls make use of PROXY_IGNORE_CACHE
 * 
 * CliController calls Model methods who create
 * cashed data with the exact same arguments as
 * when called from the web interface; this way
 * the Model::method(arguments) triplete creates
 * the same keys as via the web interface.
 * 
 * NOTE: Only Cli interface is allowed¨
 * `if (php_sapi_name() != 'cli') return false;`
 * 
 * The Cli-interfaced script can run manualy or
 * (most usual scenario) scheduled via a cron job
 * in order to refresh cashes befor expiration.
 * 
 * ---------------------------------------------
 */
class CliContoller
{


    /** (re-) cacheCategoriesTree
     * regenerate cache for product_categories tree
     * @param (void) 
     */
    public static function cacheCategoriesTree()
    {   
        // Only Cli interface is allowed
        if (php_sapi_name() != 'cli') return false;

        # PROXY call:
        # CMS \ Cms_model::courses_struct(): 
        # + Refresh Cache
        # --------------------------------------
        echo textColor("Creating category_products tree Cache ... ", \NORMAL);
        $reply = proxy(
            [\app\models\Cms_model::class, 'courses_struct'],
            [], \CACHE_ROOT_TTL,
            \PROXY_IGNORE_CACHE
        );
        echo ($reply == false) ? textColor("Failed\n", \FAIL) : textColor("Done!\n", \SUCCESS);
    }


}