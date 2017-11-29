<?php

namespace AppBundle\Entity;

class ProductImport
{
    private $file;
    private $laboratory;

    /**
     * Set file
     *
     * @param string $file
     *
     * @return ProductImport
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set laboratory
     *
     * @param string $laboratory
     *
     * @return ProductImport
     */
    public function setLaboratory($laboratory)
    {
        $this->laboratory = $laboratory;

        return $this;
    }

    /**
     * Get laboratory
     *
     * @return string
     */
    public function getLaboratory()
    {
        return $this->laboratory;
    }
}
