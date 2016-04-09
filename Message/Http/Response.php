<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Message\Http;

use phpOMS\System\MimeType;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Utils\ArrayUtils;
use phpOMS\DataStorage\Cookie\CookieJar;
use phpOMS\DataStorage\Session\HttpSession;

/**
 * Response class.
 *
 * @category   Framework
 * @package    phpOMS\Response
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Response extends ResponseAbstract implements RenderableInterface
{
    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
        $this->header = new Header();
    }

    /**
     * Set response.
     *
     * @param array $response Response to set
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setResponse(array $response)
    {
        $this->response = $response;
    }

    /**
     * Push a specific response ID.
     *
     * @param int $id Response ID
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function pushResponseId(int $id)
    {
        ob_start();
        echo $this->response[$id];
        ob_end_flush();
    }

    /**
     * Remove response by ID.
     *
     * @param int $id Response ID
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function remove(int $id) : bool
    {
        if (self::$isLocked) {
            throw new \Exception('Already locked');
        }

        if (isset($this->response[$id])) {
            unset($this->response[$id]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion() : string
    {
        return '1.0';
    }

    /**
     * {@inheritdoc}
     */
    public function getBody() : string
    {
        return $this->render();
    }

    /**
     * Generate response.
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function render() : string
    {
        switch($this->header->get('Content-Type')) {
            case MimeType::M_JSON:
                return $this->getJson();
            default:
                return $this->getRaw();
        }
    }

    private function getJson() : string
    {
        return json_encode($this->getArray());
    }

    private function getRaw() : string
    {
        $render = '';

        foreach ($this->response as $key => $response) {
            if ($response instanceOf \Serializable) {
                $render .= $response->serialize();
            } elseif (is_string($response) || is_numeric($response)) {
                $render .= $response;
            } elseif (is_array($response)) {
                $render .= json_encode($response);
                // TODO: remove this. This should never happen since then someone forgot to set the correct header. it should be json header!
            } else {
                var_dump($response);
                throw new \Exception('Wrong response type');
            }
        }

        return $render;
    }

    private function getArray() : array
    {
        $result = [];

        foreach($this->response as $key => $response) {
            if($reponse instanceof Views) {
                $result += $response->getArray();
            } elseif(is_array($response)) {
                $result += $response;
            } elseif(is_scalar($response)) {
                $result[] = $response;
            } elseif($response instanceof \Serializable) {
                $result[] = $response->serialize();
            } else {
                throw new \Exception('Wrong response type');
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getReasonPhrase() : string
    {
        return $this->header->getHeader('Status');
    }
}
