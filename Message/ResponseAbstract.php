<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Message
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message;

/**
 * Response abstract class.
 *
 * @package    phpOMS\Message
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
abstract class ResponseAbstract implements MessageInterface, \JsonSerializable
{
    /**
     * Responses.
     *
     * @var array
     * @since 1.0.0
     */
    protected $response = [];

    /**
     * Header.
     *
     * @var HeaderAbstract
     * @since 1.0.0
     */
    protected $header = null;

    /**
     * Get response by ID.
     *
     * @param mixed $id Response ID
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public function get($id)
    {
        return $this->response[$id] ?? null;
    }

    /**
     * Add response.
     *
     * @param mixed $key       Response id
     * @param mixed $response  Response to add
     * @param bool  $overwrite Overwrite
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function set($key, $response, bool $overwrite = true) : void
    {
        // This is not working since the key contains :: from http://
        //$this->response = ArrayUtils::setArray((string) $key, $this->response, $response, ':', $overwrite);
        $this->response[$key] = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Generate response array from views.
     *
     * @return array
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    abstract public function toArray() : array;

    /**
     * Get header.
     *
     * @return HeaderAbstract
     *
     * @since  1.0.0
     */
    public function getHeader() : HeaderAbstract
    {
        return $this->header;
    }

    /**
     * Get response body.
     *
     * @param bool $optimize Optimize response / minify
     *
     * @return string
     *
     * @since  1.0.0
     */
    abstract public function getBody(bool $optimize = false) : string;
}
