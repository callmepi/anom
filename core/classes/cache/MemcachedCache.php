<?php 

/** Memcached Implementation
 * (implements Cache interface; extends Cache)
 * ---
 */
namespace anom\core\cache;

use anom\core\cache\Cache_interface;

class MemcachedCache implements Cache_interface
{

    public function set($key, $data, $ttl)
    {
        $hashkey = md5($key);

        $mc = new Memcached();
        $mc->addServer(\MEMCACHE_HOST, 11211);

        $mc->set($hashkey, $data, $ttl);
    }

    
    public function get($key)
    {
        $hashkey = md5($key);
        
        $mc = new Memcached();
        $mc->addServer(\MEMCACHE_HOST, 11211);

        return $mc->get($hashkey);

    }

    public function flush($olderThan = 0)
    {
        return false;
    }

}