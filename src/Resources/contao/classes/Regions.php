<?php

namespace Oveleon\ContaoOnofficeApiBundle;

use Symfony\Component\HttpClient\HttpClient;

class Regions extends \Backend
{
    /**
     * Setup onoffice regions import
     *
     * @return string
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function setupImport()
    {
        if (\Input::post('FORM_SUBMIT') == 'tl_regions_import')
        {
            if ($lang = trim(\Input::post('language')))
            {
                $this->startImport($lang);

                if(!\Message::hasMessages())
                {
                    $container = \System::getContainer();
                    \Message::addConfirmation($GLOBALS['TL_LANG']['tl_regions']['importComplete']);
                    $this->redirect($container->get('router')->generate('contao_backend', array('do'=>'onoffice_regions')));
                }
            }
            else
            {
                \Message::addError($GLOBALS['TL_LANG']['tl_regions']['errNoLanguage']);
            }
        }

        \Message::addInfo($GLOBALS['TL_LANG']['tl_regions']['importConfirm']);

        // Return the form
        return \Message::generate() . '
<div id="tl_buttons">
<a href="' . ampersand(str_replace('&key=importRegions', '', \Environment::get('request'))) . '" class="header_back" title="' . \StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']) . '" accesskey="b">' . $GLOBALS['TL_LANG']['MSC']['backBT'] . '</a>
</div>
<form action="' . ampersand(\Environment::get('request')) . '" id="tl_theme_import" class="tl_form tl_edit_form" method="post" enctype="multipart/form-data">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_regions_import">
<input type="hidden" name="REQUEST_TOKEN" value="' . REQUEST_TOKEN . '">
<div class="tl_tbox">
<div class="widget">
<h3>' . $GLOBALS['TL_LANG']['tl_regions']['language'][0] . '</h3>
  <input type="text" name="language" id="language" class="tl_text" required onfocus="Backend.getScrollOffset()">
  <p class="tl_help tl_tip">' . $GLOBALS['TL_LANG']['tl_regions']['language'][1] . '</p>
</div></div>
</div>
<div class="tl_formbody_submit">
<div class="tl_submit_container">
  <button type="submit" name="save" id="save" class="tl_submit" accesskey="s">' . $GLOBALS['TL_LANG']['tl_regions']['importRegions'][0] . '</button>
</div>
</div>
</form>';
    }

    /**
     * Import onoffice regions
     *
     * @param $lang
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function startImport($lang)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', \Environment::get('url') . '/api/onoffice/v1/regions', [
            'query' => [
                'language'  => $lang,
                'offset' => 14800
            ]
        ]);

        $statusCode = $response->getStatusCode();

        if($statusCode == '200')
        {
            // Truncate table
            $this->truncateRegions();

            // Get response as array
            $arrData = $response->toArray();

            if($arrData['status']['errorcode'] != 0)
            {
                \Message::addError($arrData['status']['message']);
                return;
            }

            if(!count($arrData['data']['records']))
            {
                \Message::addError($GLOBALS['TL_LANG']['tl_regions']['emptyRecords']);
                return;
            }

            // Add root from language
            $root = new OnofficeRegionsModel();

            $root->title = $lang;
            $root->type = 'root';
            $root->language = $lang;
            $root->tstamp = time();
            $root->published = 1;

            $root->save();

            // Import regions
            $this->importRegions($arrData['data']['records'], $root->id);
        }
        else
        {
            \Message::addError($response->getInfo()['error']);
        }
    }

    /**
     * Import onoffice regions
     *
     * @param $arrRecords
     *
     * @param null $pid
     */
    public function importRegions($arrRecords, $pid=0)
    {
        if (empty($arrRecords))
        {
            return;
        }

        foreach ($arrRecords as $record)
        {
            // Skip elements node
            if(isset($record['elements']))
            {
                $record = $record['elements'];
            }

            $root = new OnofficeRegionsModel();

            $root->title        = $record['name'];
            $root->type         = 'regular';
            $root->pid          = $pid;
            $root->oid          = $record['id'];
            $root->description  = $record['description'];
            $root->postalcodes  = empty($record['postalcodes']) ? null : serialize($record['postalcodes']);
            $root->state        = $record['state'];
            $root->country      = $record['country'];
            $root->tstamp       = time();
            $root->published    = 1;

            $root->save();

            if(isset($record['children']))
            {
                $this->importRegions($record['children'], $root->id);
            }
        }
    }

    /**
     * Truncate regions
     */
    private function truncateRegions()
    {
        $objDatabase = \Database::getInstance();

        // Truncate the table
        $objDatabase->execute("TRUNCATE TABLE tl_regions");
    }
}
