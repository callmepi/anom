<?php

/** Cache interface
 * -----------------------------------------------------------------------------
 * 
 * Interface to save results of expensive data-extraction proccess,
 * in a fast-accessed medium (ussually RAM or NVME SSD units)
 * 
 * -----------------------------------------------------------------------------
 */
interface Cache_interface
{
    /** Cache::set($key, $data, $ttl) : void
     * 
     * @param $key (string): reference label
     * @param $data (mixed): actual data (avoid storing true|false)
     * @param $ttl (int): time to live (seconds)
     */
    public function set(string $key, $data, int $ttl);


    /** Cache::get($key) : mixed
     *
     * @param $key (string)
     * @return mixed: fetched $data -or- false on failure
     */
    public function get(string $key);


    /** Cache::flush( $olderThan ) : void
     *
     * clear cache older than $olderThan
     * @param $olderThan (int) in seconds
     */
    public function flush(int $olderThan = 0);

}