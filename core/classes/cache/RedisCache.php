<?php 

/** Redis Cache implementation
 * ---
 * This class uses \Predis\Redis, so do not forget require it via composer
 * or to use the configuration options you will find into \README.md
 */
namespace anom\core\cache;

use anom\core\cache\Cache_interface;

class RedisCache implements Cache_interface
{
    /** set
     * store $data under the label $key
     * cache expires in $ttl seconds
     */
    public function set($key, $data, $ttl)
    {
        $serialized = serialize($data);

        if ($serialized = '') {
            return false;
        }

        try {
            $redis = new \Predis\Client([
                'host' => \REDIS_HOST
            ]);
            return $redis->set($key, $serialized, 'EX', $ttl);

        } catch (Exception $e) {
            // ...
        }
    }


    /** get
     * fetch previously stored $data under label $key
     * return $data (or false)
     */
    public function get($key)
    {   
        
        try {
            
            $redis = new \Predis\Client([
                'host' => \REDIS_HOST
            ]);

            if ($redis->exists($key)) {
                return unserialize($redis->get($key));

            } else {
                return false;
            }

        } catch (Exception $e) {
            // ...
        }     

    }


    /** flush
     * --- not umplemented yet; may not needed
     */
    public function flush($olderThan = 0)
    {
        return false; // until implementation
    }

}