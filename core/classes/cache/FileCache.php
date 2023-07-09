<?php

/* FileCache
 * -----------------------------------------------------------------------------
 * 
 * Saves serialized data into filesystem.
 * 
 * Use it for repetitive expensive database-queries
 * or other common time/source-consuming tasks
 * 
 * Performance on a local NVMe-SSD drive can be great;
 * Performance on a typical HDD is just fair.
 *
 * NOTE:
 * Memcached and Redis are much faster cache-technologies
 * (when implemented locally) but not always available;
 * FileCache is (almost) always available;
 * 
 * RECOMMENDATION:
 * Before you decide on your actual caching technology
 * design and run a few comparative benchmarks
 * 
 * -----------------------------------------------------------------------------
 */
class FileCache implements Cache_interface
{

    /** store
     * ---
     * serializes and saves data in a file
     * along with TTL (time-to-live)
     */
    public function set(string $key, $data, int $ttl)
    {
        // filename is a hashed 48-hex-digit string
        // probability of collision = exp( (-k*(k-1)) / 2N )
        // example case: for 10tril.samples P(collision) = 1.5E-11
        $filename = FILECACHE_PATH . sha1($key);

        // Opening file for write
        $h = fopen($filename, 'w');
        if (!$h) throw new Exception('Can not write to cache');

        // Serialize along with the TTL
        $data = serialize( array(
            time() + $ttl,      // array[0] holds expiration time
            $data)              // array[1] holds the actual data
        );

        if (fwrite($h,$data) === false) {
          throw new Exception('Can not write to cache');
        }
        fclose($h);
    }


    /** fetch
     * ---
     * feth data for certain key
     * @param $key (string)
     * @return fetched $data -or- false on failure
     */
    public function get(string $key)
    {
        $filename = FILECACHE_PATH . sha1($key);

        // can not read the cache-file? return false
        if (!file_exists($filename) || !is_readable($filename)) return false;

        $data = file_get_contents($filename);   // get cache-file contents
        $data = @unserialize($data);            // unserialize

        if (!$data) {

           // Unlinking the file when unserializing failed
           unlink($filename);
           return false;

        }

        // checking if the data was expired
        if (time() > $data[0]) {

           // Unlinking
           unlink($filename);
           return false;

        }

        return $data[1];
    }


    public function flush(int $olderThan = 0)
    {
        return false;   // reply false until implementation
    }

}
