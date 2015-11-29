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

use phpOMS\Datatypes\Exception\InvalidEnumValue;
use phpOMS\Message\RequestMethod;
use phpOMS\Uri\InvalidUriException;

/**
 * Request class.
 *
 * @category   Framework
 * @package    phpOMS\Request
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Rest
{
    private $url = '';

    private $method = '';

    public function setUrl(\string $url) {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidUriException('$url');
        }

        $this->url = $url;
    }

    public function setMethod(\string $method) {
        if(!RequestMethod::isValidValue($method)) {
            throw new InvalidEnumValue($method);
        }

        $this->method = $method;
    }

    public function callApi($data = false)
    {
        $curl = curl_init();

        switch ($this->method) {
            case RequestMethod::POST:
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case RequestMethod::PUT:
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data) {
                    $this->url = sprintf("%s?%s", $this->url, http_build_query($data));
                }
        }

        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }
}
