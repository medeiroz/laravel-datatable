<?php

use Illuminate\Database\Eloquent\Builder;
use Medeiroz\LaravelDatatable\Entities\EagerLoad;
use Mockery\MockInterface;

it('apply', function () {
    $mockBuilder = Mockery::mock(Builder::class, function (MockInterface $mock) {
        $mock->shouldReceive('with')
            ->once()
            ->with('address:id,street,city')
            ->andReturnSelf();
    });

    $eagerLoad = new EagerLoad('address', ['id', 'street', 'city']);

    $eagerLoad->apply($mockBuilder);
});
