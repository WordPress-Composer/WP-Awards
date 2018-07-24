<?php

use PHPUnit\Framework\TestCase;
use Voting\Infrastructure\RouteHandler;

/**
 * Testing route handler callbacks
 * @author Gemma Black <gblackuk@gmail.com>
 */
class RouteHandlerTest extends TestCase
{

    /**
     * Testing that route handler calls callback function by printing a sting
     * @return void
     */
    public function testHandlerCallsFunctionThatPrintsString()
    {
        $this->expectOutputString('Printable string');
        new RouteHandler(function() {
            echo 'Printable string';
        });
    }


    /**
     * Testing route handler picks up first parameter
     * @return void
     */
    public function testHandlerCallsFunctionsThatPrintsFirstOfParameters()
    {
        $data = [
            'data' => [
                'attributes' => [
                    'title' => 'Some title'
                ]
            ]
        ];
        $this->expectOutputString(json_encode($data));
        new RouteHandler(function($param) {
            echo json_encode($param);
        }, [$data]);
    }


    /**
     * Testing route handler picks up second parameter
     * @return void
     */
    public function testHandlerCallsFunctionsThatPrintsSecondOfParameters()
    {
        $this->expectOutputString(json_encode([2]));
        new RouteHandler(function($param, $param2) {
            echo json_encode($param2);
        }, [[1], [2]]);
    }

}
