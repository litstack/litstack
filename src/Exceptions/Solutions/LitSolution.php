<?php

namespace Ignite\Exceptions\Solutions;

use Facade\IgnitionContracts\BaseSolution;

class LitSolution extends BaseSolution
{
    /**
     * Create new LitSolution instance.
     *
     * @param string $title
     *
     * @return void
     */
    public function __construct(string $title)
    {
        parent::__construct($title);

        $this->setDocsLink('https://www.lit-admin.com/');
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
            'Lit Docs' => $link,
        ]);

        return $this;
    }
}
