<?php

namespace Oveleon\ContaoOnofficeApiBundle;

/**
 * Reads and writes onOffice auth keys
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $key
 * @property boolean $restrictIp
 * @property boolean $restrictHost
 * @property string  $allowedIps
 * @property string  $allowedHosts
 *
 * @method static OnofficeAuthModel|null findById($id, array $opt=array())
 * @method static OnofficeAuthModel|null findOneBy($col, $val, array $opt=array())
 * @method static OnofficeAuthModel|null findOneByTstamp($val, array $opt=array())
 * @method static OnofficeAuthModel|null findOneByKey($val, array $opt=array())
 *
 * @method static \Model\Collection|OnofficeAuthModel[]|OnofficeAuthModel|null findByTstamp($val, array $opt=array())
 * @method static \Model\Collection|OnofficeAuthModel[]|OnofficeAuthModel|null findByRestrictIp($val, array $opt=array())
 * @method static \Model\Collection|OnofficeAuthModel[]|OnofficeAuthModel|null findByRestrictHost($val, array $opt=array())
 * @method static \Model\Collection|OnofficeAuthModel[]|OnofficeAuthModel|null findMultipleByIds($var, array $opt=array())
 * @method static \Model\Collection|OnofficeAuthModel[]|OnofficeAuthModel|null findBy($col, $val, array $opt=array())
 * @method static \Model\Collection|OnofficeAuthModel[]|OnofficeAuthModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByTstamp($val, array $opt=array())
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class OnofficeAuthModel extends \Model
{

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_onoffice_auth';
}
