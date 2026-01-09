<?php

use Noardcode\LaravelSignhost\Exceptions\SignhostException;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Onfido;

it('returns the correct type', function () {
    $onfido = new Onfido('dba0c424-023c-4454-b0ce-72ce625b08c0');
    expect($onfido->getType())->toBe('Onfido');
});

it('can be created with a valid workflow id', function () {
    $onfido = new Onfido('dba0c424-023c-4454-b0ce-72ce625b08c0');
    expect($onfido)->toBeObject()
        ->and($onfido->getWorkflowId())->toBe('dba0c424-023c-4454-b0ce-72ce625b08c0');
});

it('can be created without workflow id', function () {
    $onfido = new Onfido;
    expect($onfido)->toBeObject()
        ->and($onfido->getWorkflowId())->toBeNull();
});

it('fails on incorrect workflow id format', function () {
    new Onfido('wrong-uuid-format');
})->throws(SignhostException::class, 'Workflow Id is not a valid UUID.');

it('casts to array correctly', function () {
    $onfido = new Onfido('dba0c424-023c-4454-b0ce-72ce625b08c0');
    expect($onfido->toArray())->toEqual([
        'Type' => 'Onfido',
        'WorkflowId' => 'dba0c424-023c-4454-b0ce-72ce625b08c0',
    ]);
});
