<?php namespace Jungbaksacom\TaskProcess\Facades;


use Illuminate\Support\Facades\Facade;

class TaskProcess extends Facade {

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'TaskProcess';
    }

}