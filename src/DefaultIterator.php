<?php

namespace Agoradesign\DOMXPathIterator;

class DefaultIterator implements \Iterator {

  /**
   * The DOMDocument we are iterating.
   *
   * @var \DOMDocument
   */
  protected $document;

  protected $filterExpression;

  /**
   * @var \DOMNodeList[]
   */
  protected $nodeList;

  protected $index;

  public function __construct(\DOMDocument $document, $filter_expression) {
    $this->index = 0;
    $this->document = $document;
    $this->filterExpression = $filter_expression;
    $this->init();
  }

  protected function init() {
    $xpath = new \DOMXPath($this->document);
    $this->nodeList = $xpath->query($this->filterExpression);
  }

  /**
   * @return \DOMElement
   */
  public function current() {
    return $this->nodeList->item($this->index);
  }

  /**
   * {@inheritdoc}
   */
  public function key() {
    return $this->index;
  }

  /**
   * {@inheritdoc}
   */
  public function next() {
    $this->index++;
  }

  /**
   * {@inheritdoc}
   */
  public function rewind() {
    $this->index = 0;
  }

  /**
   * {@inheritdoc}
   */
  public function valid() {
    return isset($this->nodeList[$this->index]);
  }

  /**
   * @return \DOMDocument
   */
  public function getDocument() {
    return $this->document;
  }
  
  public function count() {
    return $this->nodeList->length;
  }
}
