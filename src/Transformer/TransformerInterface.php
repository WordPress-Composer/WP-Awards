<?php 

namespace Voting\Transformer;

abstract class TransformerInterface
{
    abstract public function getItem();
    abstract public function getItems();
}