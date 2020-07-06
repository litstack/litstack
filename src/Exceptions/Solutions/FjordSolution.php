<?php

namespace Fjord\Exceptions\Solutions;

use Facade\IgnitionContracts\BaseSolution;

class FjordSolution extends BaseSolution
{
    /**
     * Create new FjordSolution instance.
     *
     * @param string $title
     *
     * @return void
     */
    public function __construct(string $title)
    {
        parent::__construct($title);

        $this->setDocsLink('https://www.fjord-admin.com/');
    }

    /**
     * Disable docs link.
     *
     * @return $this
     */
    public function withoutDocs()
    {
        $this->setDocumentationLinks([]);

        return $this;
    }

    /**
     * Set docs link.
     *
     * @param string $link
     *
     * @return $this
     */
    public function setDocsLink(string $link)
    {
        $this->setDocumentationLinks([
            'Fjord Docs' => $link,
        ]);

        return $this;
    }
}
