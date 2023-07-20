<?php

/** Repository
 * is an abstract class that strores entities
 * and serves them via ::pull() method when needed
 * 
 * it also has an ::echo() method to list the 
 */
namespace anom\core;

abstract class Repository
{

    /** all repositories (need to) have
     * one public static array named '$repository'
     */
    public static $repository = [];

    /** pull
     * one entity from repository
     * @param $entity (string): the label of the entity
     */
    public static function pull($entity)
    {
        if (array_key_exists($entity, static::$repository)) {

            return static::$repository[$entity];

        } else die('repository does not exist');

    }


    /** echo
     * lists the entities of the repository;
     * by default it only lists the labes of the entities;
     * @param $content (bool): if true serve contents along with the labels
     * @return (array)
     */
    public static function echo($content =false)
    {
        if ($content)   return static::$repository;
        else            return array_keys(static::$repository);
    }
}