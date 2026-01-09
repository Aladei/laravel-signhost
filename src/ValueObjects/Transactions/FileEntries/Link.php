<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Enums\FileEntryLinkRel;

/**
 * Class Link
 */
class Link implements ToSignhostArrayContract
{
    /**
     * @param  FileEntryLinkRel  $rel
     * @param  string  $type
     * @param  string  $link
     */
    public function __construct(
        protected FileEntryLinkRel $rel,
        protected string $type,
        protected string $link,
    ) {
        //
    }

    /**
     * @return FileEntryLinkRel
     */
    public function getRel(): FileEntryLinkRel
    {
        return $this->rel;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Rel' => $this->getRel()->value,
            'Type' => $this->getType(),
            'Link' => $this->getLink(),
        ];
    }
}
