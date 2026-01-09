<?php

it('can be created', function () {
    $signers = new \Noardcode\LaravelSignhost\Collections\TransactionFileMetaDataSignersCollection([
        new \Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData\Signer(
            id: 'Signer_1',
            formSets: ['Formset_1'],
        ),
    ]);

    $formsets = new \Noardcode\LaravelSignhost\Collections\TransactionFileMetaDataFormSetsCollection([
        new Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData\FormSet(
            name: 'Formset_1',
            fieldTypes: [
                new \Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData\FormSet\FieldType(
                    name: 'Field_1',
                    formSetType: \Noardcode\LaravelSignhost\Enums\FormSetType::Signature,
                    location: new \Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData\FormSet\Location(
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
                ),
            ]
        ),
    ]);

    $fileMetaData = new Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData(
        1,
        'Name',
        true,
        $signers,
        $formsets
    );

    expect($fileMetaData->getDisplayOrder())->toBe(1)
        ->and($fileMetaData->getDisplayName())->toBe('Name')
        ->and($fileMetaData->getSetParaph())->toBe(true)
        ->and($fileMetaData->getSigners())->toBe($signers)
        ->and($fileMetaData->getFormSets())->toBe($formsets);

    $this->assertEquals([
        'DisplayOrder' => 1,
        'DisplayName' => 'Name',
        'SetParaph' => true,
        'Signers' => $signers->toArray(),
        'FormSets' => $formsets->toArray(),
    ], $fileMetaData->toArray());
});
