<?php

class HighriseCategory extends HighriseAPI
{
  protected $type;

  public $id;
  public $name;
  public $account_id;
  public $color;
  public $created_at;
  public $elements_count;
  public $updated_at;

  public function __construct(HighriseAPI $highrise, $type)
  {
    $this->highrise = $highrise;
    $this->account = $highrise->account;
    $this->token = $highrise->token;
    $this->debug = $highrise->debug;
    $this->curl = curl_init();

    $this->type = $type;
  }

  public function __toString()
  {
    return $this->name . ": " . $this->id;
  }

  public function toXML()
  {
    return $this->getXMLObject()->asXML();
  }

  protected function getXMLObject()
  {
    $xml = new SimpleXMLElement("<{$this->type}_category></{$this->type}_category>");
    $xml->addChild("id", $this->getId());
    $xml->id->addAttribute("type", "integer");
    $xml->addChild("name", $this->getName());
    $xml->addChild("account-id", $this->getAccountId());
    $xml->addChild("color", $this->getColor());
    $xml->addChild("created-at", $this->getCreatedAt());
    $xml->addChild("elements-count", $this->getElementsCount());
    $xml->addChild("updated-at", $this->getUpdatedAt());
    return $xml;
  }

  public function loadFromXMLObject($xml_obj)
  {
    if ($this->debug) {
      print_r($xml_obj);
    }

    $this->setId($xml_obj->{'id'});
    $this->setName($xml_obj->{'name'});
    $this->setAccountId($xml_obj->{'account-id'});
    $this->setColor($xml_obj->{'color'});
    $this->setCreatedAt($xml_obj->{'created-at'});
    $this->setElementsCount($xml_obj->{'elements-count'});
    $this->setUpdatedAt($xml_obj->{'updated-at'});
    
    return true;
  }

  protected function setId($id)
  {
    $this->id = (string)$id;
  }

  public function getId()
  {
    return $this->id;
  }

  public function setName($name)
  {
    $this->name = (string)$name;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setColor($color)
  {
    $this->color = (string)$color;
  }

  public function getColor()
  {
    return $this->color;
  }

  protected function setType($type)
  {
    $this->type = (string)$type;
  }

  public function getType($type)
  {
    return $this->type;
  }

  protected function setAccountId($account_id)
  {
    $this->account_id = (string)$account_id;
  }

  public function getAccountId()
  {
    return $this->account_id;
  }

  protected function setCreatedAt($created_at)
  {
    $this->created_at = (string)$created_at;
  }

  public function getCreatedAt()
  {
    return $this->created_at;
  }

  protected function setElementsCount($elements_count)
  {
    $this->elements_count = (string)$elements_count;
  }

  public function getElementsCount()
  {
    return $this->elements_count;
  }

  protected function setUpdatedAt($updated_at)
  {
    $this->updated_at = (string)$updated_at;
  }

  public function getUpdatedAt()
  {
    return $this->updated_at;
  }
}
