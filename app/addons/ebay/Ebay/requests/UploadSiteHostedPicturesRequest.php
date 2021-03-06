<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

namespace Ebay\requests;

/**
 * Class UploadSiteHostedPicturesRequest
 * @package Ebay\requests
 * @see http://developer.ebay.com/Devzone/XML/docs/Reference/ebay/UploadSiteHostedPictures.html
 */
class UploadSiteHostedPicturesRequest extends Request
{
    /** @var string Absolute url */
    public $url;
    /** @var string Picture name  */
    public $name;

    /**
     * @param string $url
     * @param string $name
     */
    public function __construct($url, $name)
    {
        $this->url = $url;
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function xml()
    {
        return <<<XML
<ExternalPictureURL>{$this->url}</ExternalPictureURL>
<PictureName>{$this->name}</PictureName>
XML;
    }
}
