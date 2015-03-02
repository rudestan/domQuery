<?php
/**
 *
 * An array implementation to have an easy access to domQuery/Element objects
 *
 */

namespace domQuery;

class ArrayElement extends \ArrayObject implements \ArrayAccess, \Countable, \IteratorAggregate {

    protected $elements = [];

    /**
     * @param int $index
     * @return bool
     */
    public function offsetExists($index = 0) {
        return isset($this->elements[$index]);
    }

    /**
     * @param int $index
     * @return bool|mixed
     */
    public function offsetGet($index = 0) {
        if($this->offsetExists($index)) {
            return $this->elements[$index];
        }
        return false;
    }

    /**
     * @param mixed $index
     * @param mixed $value
     * @return bool|void
     */
    public function offsetSet($index, $value) {
        if($index) {
            $this->elements[$index] = $value;
        } else {
            $this->elements[] = $value;
        }
        return true;

    }

    /**
     * @param mixed $index
     * @return bool|void
     */
    public function offsetUnset($index) {
        unset($this->elements[$index]);
        return true;
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->elements);
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator() {
        return new \ArrayIterator($this->elements);
    }

    /**
     * Wrapper for count() method.
     * @return int
     */
    public function length() {
        return $this->count();
    }

    /**
     * Magic method that allows to access directly to domQuery/Element in case there is only one element in the array.
     * In all other cases local method of ArrayElement class will be called.
     * @param $name
     * @param $args
     * @throws \Exception
     */
    public function __call($name, $args) {

        if(method_exists($this, $name)) {
            return call_user_func_array(array($this, $name), $args);
        }

        if(count($this->elements) == 1) {
            return call_user_func_array(array($this->elements[0], $name), $args);
        } elseif(count($this->elements) > 1) {
            throw new \Exception("There are more than 1 element (".count($this->elements).") in the array, please access to that element by it's index!");
        } else {
            return null;
        }
    }

} 