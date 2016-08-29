<?php
namespace Fab\WebService\Resolver;

/*
 * This file is part of the Fab/WebService project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

/**
 * Class Settings
 */
class Settings
{
    const MANY = 'many';
    const ONE = 'one';

    /**
     * @var string
     */
    protected $contentType = '';

    /**
     * @var int
     */
    protected $contentIdentifier = 0;

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $excludedFields = [];

    /**
     * @var string
     */
    protected $manyOrOne = '';

    /**
     * @var array
     */
    protected $orderings = [];

    /**
     * @var array
     */
    protected $routeSegments = [];

    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * @var string
     */
    protected $format = 'json';

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     * @return $this
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @return int
     */
    public function getContentIdentifier()
    {
        return (int)$this->routeSegments[1];
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return string
     */
    public function getManyOrOne()
    {
        return $this->manyOrOne;
    }

    /**
     * @param string $manyOrOne
     * @return $this
     */
    public function setManyOrOne($manyOrOne)
    {
        $this->manyOrOne = $manyOrOne;
        return $this;
    }

    /**
     * @return array
     */
    public function getExcludedFields()
    {
        return $this->excludedFields;
    }

    /**
     * @param array $excludedFields
     * @return $this
     */
    public function setExcludedFields(array $excludedFields)
    {
        $this->excludedFields = $excludedFields;
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderings()
    {
        return $this->orderings;
    }

    /**
     * @param array $orderings
     * @return $this
     */
    public function setOrderings($orderings)
    {
        $this->orderings = $orderings;
        return $this;
    }

    /**
     * @return array
     */
    public function getRouteSegments()
    {
        return $this->routeSegments;
    }

    /**
     * @param array $routeSegments
     * @return $this
     */
    public function setRouteSegments($routeSegments)
    {
        $this->routeSegments = $routeSegments;

        $routeSize = count($this->routeSegments);

        // Detect the format
        if (preg_match('/([\w]+)\.(atom|csv|html|json|xml)/', $this->routeSegments[$routeSize - 1], $matches)) {
            $this->format = $matches[2];
            $this->routeSegments[$routeSize - 1] = $matches[1];
        }
        return $this;
    }

    /**
     * @return int
     */
    public function countRouteSegments()
    {
        return count($this->routeSegments);
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastRouteSegment()
    {
        return array_pop($this->routeSegments);
    }

    /**
     * @return string
     */
    public function getFistRouteSegment()
    {
        return array_shift($this->routeSegments);
    }

}
