<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Session;

/**
 * Console session class.
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class ConsoleSession implements SessionInterface
{

    /**
     * Session ID.
     *
     * @var string|int
     * @since 1.0.0
     */
    private $sid = null;

    /**
     * Constructor.
     *
     * @param string|int|bool $sid Session id
     *
     * @since  1.0.0
     */
    public function __construct($sid = false)
    {
        $this->sid = $sid;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, bool $overwrite = true) : bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key) : bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getSID()
    {
        return $this->sid;
    }

    /**
     * {@inheritdoc}
     */
    public function setSID($sid) : void
    {
        $this->sid = $sid;
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function lock()
    {
    }
}
