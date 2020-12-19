<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Message\Mail
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

use phpOMS\Stdlib\Base\Enum;

/**
 * Transaction types enum.
 *
 * @package phpOMS\Message\Mail
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class SmtpTransactionPattern extends Enum
{
    public const EXIM = '/[\d]{3} OK id=(.*)/';

    public const SENDMAIL = '/[\d]{3} 2.0.0 (.*) Message/';

    public const POSTFIX = '/[\d]{3} 2.0.0 Ok: queued as (.*)/';

    public const MICROSOFT_ESMTP = '/[0-9]{3} 2.[\d].0 (.*)@(?:.*) Queued mail for delivery/';

    public const AMAZON_SES = '/[\d]{3} Ok (.*)/';

    public const SENDGRID = '/[\d]{3} Ok: queued as (.*)/';

    public const CAMPAIGNMONITOR = '/[\d]{3} 2.0.0 OK:([a-zA-Z\d]{48})/';
}