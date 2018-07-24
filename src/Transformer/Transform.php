<?php 

namespace Voting\Transformer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Exception;

final class Transform
{
    protected $input;

    protected function __construct($input)
    {
        $this->input = $input;
    }

    public static function collection(Collection $collection)
    {
        return new self($collection);
    }

    public static function model(Model $award)
    {
        return new self($award);
    }

    public function using(Transformer $transformer)
    {
        if ($this->input instanceof Model) {
            return $transformer->getItem($this->input);
        } else if ($this->input instanceof Collection) {
            return $transformer->getItems($this->input);
        } else {
            throw new Exception('Cannot map a value that is not an Eloquent Model or Collection');
        }
    }

}