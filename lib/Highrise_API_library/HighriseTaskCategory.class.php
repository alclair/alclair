<?php

class HighriseTaskCategory extends HighriseCategory
{
  public function __construct(HighriseAPI $highrise)
  {
    parent::__construct($highrise, 'task');
  }
}
