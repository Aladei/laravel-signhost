<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData\FormSet;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;

/**
 * Class Location
 *
 */
class Location implements ToSignhostArrayContract
{
    /**
     * @param  string  $search  The text to search in the pdf document to use as the position for the field.
     * @param  int  $occurence  When using text search, only match this matches occurence.
     * @param  int|null  $top  Offset from the top of the search text or the page.
     * @param  int|null  $right  Offset from the right of the search text or the page.
     * @param  int|null  $bottom  Offset from the bottom of the search text or the page.
     * @param  int|null  $left  Offset from the left of the search text or the page.
     * @param  int|null  $width  The width of the field. Can't be uses when both left and right are specified.
     * @param  int|null  $height  The height of the field. Can't be uses when both top and bottom are specified.
     * @param  int|null  $pageNumber  The page number of the document to place the field on.
     */
    public function __construct(
        protected string $search,
        protected int $occurence,
        protected ?int $top = null,
        protected ?int $right = null,
        protected ?int $bottom = null,
        protected ?int $left = null,
        protected ?int $width = null,
        protected ?int $height = null,
        protected ?int $pageNumber = null,
    ) {
        //
    }

    /**
     * @return string
     */
    public function getSearch(): string
    {
        return $this->search;
    }

    /**
     * @return int
     */
    public function getOccurence(): int
    {
        return $this->occurence;
    }

    /**
     * @return int|null
     */
    public function getTop(): ?int
    {
        return $this->top;
    }

    /**
     * @return int|null
     */
    public function getRight(): ?int
    {
        return $this->right;
    }

    /**
     * @return int|null
     */
    public function getBottom(): ?int
    {
        return $this->bottom;
    }

    /**
     * @return int|null
     */
    public function getLeft(): ?int
    {
        return $this->left;
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @return int|null
     */
    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'Search' => $this->getSearch(),
            'Occurence' => $this->getOccurence(),
            'Top' => $this->getTop(),
            'Right' => $this->getRight(),
            'Bottom' => $this->getBottom(),
            'Left' => $this->getLeft(),
            'Width' => $this->getWidth(),
            'Height' => $this->getHeight(),
            'PageNumber' => $this->getPageNumber(),
        ], static fn ($value) => $value !== null);
    }
}
