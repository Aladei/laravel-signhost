<?php

use Noardcode\LaravelSignhost\Enums\FormSetType;

it('can be created', function () {
    $fieldType = new Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData\FormSet\FieldType(
        'Field_1',
        FormSetType::Signature,
        new Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData\FormSet\Location(
            'search',
            1,
            10,
            20,
            30,
            40,
            100,
            50,
            2
        )
    );

    $formSet = new Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData\FormSet(
        'Formset_1',
        [$fieldType]
    );

    expect($formSet->getFieldTypes())->toBe([$fieldType])
        ->and($formSet->getName())->toBe('Formset_1');

    $this->assertEquals([
        'Field_1' => $fieldType->toArray(),
    ], $formSet->toArray());
});

it('cannot be created without a name', function () {
    $this->expectException(InvalidArgumentException::class);

    new Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData\FormSet(
        '',
        []
    );
});

it('cannot be created with strange characters in a name', function () {
    $this->expectException(InvalidArgumentException::class);

    new Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData\FormSet(
        'J@(*(*@H%*@H%@(*%%@*H%(',
        []
    );
});
