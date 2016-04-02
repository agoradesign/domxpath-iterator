<?php

namespace Agoradesign\DOMXPathIterator;

class ValueExtractingIterator implements \OuterIterator {

  private $innerIterator;

  protected $childrenXpathExpression;
  
  protected $resultKeyXpathExpression;
  
  protected $resultValueXpathExpression;

  protected $virtualProperties = [];

  public function __construct(DefaultIterator $xpath_iterator, $children_xpath_expresion, $result_key_xpath_expression, $result_value_xpath_expression) {
    $this->innerIterator = $xpath_iterator;
    $this->childrenXpathExpression = $children_xpath_expresion;
    $this->resultKeyXpathExpression = $result_key_xpath_expression;
    $this->resultValueXpathExpression = $result_value_xpath_expression;
  }

  /**
   * @var $virtualProperties array keyed by source property and an array as value,
   *    consisting of 'name' (target name of the virtual property) and 'callback',
   *    a callback function, that will generate the property based on the element's value.  
   */
  public function setVirtualProperties(array $virtualProperties) {
    $this->virtualProperties = $virtualProperties;
  }

  public function getInnerIterator () {
    return $this->innerIterator;
  }

  /**
   * @return string[]
   */
  public function current() {
    $result = [];
    $parent = $this->innerIterator->current();
    $document = $this->innerIterator->getDocument();
    $xpath = new \DOMXPath($document);
    $children = $xpath->query($this->childrenXpathExpression, $parent);
    foreach ($children as $child) {
      $key = $xpath->evaluate($this->resultKeyXpathExpression, $child);
      if (!empty($key)) {
        $value = $xpath->evaluate($this->resultValueXpathExpression, $child);
        $result[$key] = $value;
      }
      if (isset($this->virtualProperties[$key])) {
        $name = $this->virtualProperties[$key]['name'];
        $callback = $this->virtualProperties[$key]['callback'];
        $result[$name] = call_user_func($callback, $value);
      }
    }
    return $result;
  }

  public function key() {
    return $this->innerIterator->key();
  }

  public function next() {
    return $this->innerIterator->next();
  }

  public function rewind() {
    return $this->innerIterator->rewind();
  }

  public function valid() {
    return $this->innerIterator->valid();
  }
  
  public function count() {
    return $this->innerIterator->count();
  }
}
