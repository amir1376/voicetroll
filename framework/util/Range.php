<?php
namespace framework\util;
class Range implements \Iterator {
  private $a;
  private $b;

  private $header=0;
  private $maxHead;

  public function __construct(int $x,int $y) {

    $this->a=min($x,$y);
    $this->b=max($x,$y);
    $this->maxHead=$this->b-$this->a;
  }
  public function isInRange(int $c) {
    if ($c<=$this->a&& $c<=$this->b){return true;}
    return false;
  }

  /**
   * Return the current element
   * @link https://php.net/manual/en/iterator.current.php
   * @return mixed Can return any type.
   * @since 5.0.0
   */
  public function current() {
    return $this->a+$this->header;
  }

  /**
   * Move forward to next element
   * @link https://php.net/manual/en/iterator.next.php
   * @return void Any returned value is ignored.
   * @since 5.0.0
   */
  public function next() {
    $this->header++;
  }

  /**
   * Return the key of the current element
   * @link https://php.net/manual/en/iterator.key.php
   * @return mixed scalar on success, or null on failure.
   * @since 5.0.0
   */
  public function key() {
    return $this->header;
  }

  /**
   * Checks if current position is valid
   * @link https://php.net/manual/en/iterator.valid.php
   * @return boolean The return value will be casted to boolean and then evaluated.
   * Returns true on success or false on failure.
   * @since 5.0.0
   */
  public function valid() {
    return $this->header<=$this->maxHead;
  }

  /**
   * Rewind the Iterator to the first element
   * @link https://php.net/manual/en/iterator.rewind.php
   * @return void Any returned value is ignored.
   * @since 5.0.0
   */


  public function rewind() {
    $this->header=0;
  }
}