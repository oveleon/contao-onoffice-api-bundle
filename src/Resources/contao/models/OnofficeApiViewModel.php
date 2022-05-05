<?php

namespace Oveleon\ContaoOnofficeApiBundle;

/**
 * Reads and writes onOffice api views
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $type
 * @property string  $title
 * @property string  $alias
 * @property string  $data
 * @property string  $categories
 * @property string  $recordids
 * @property string  $estateids
 * @property string  $filter
 * @property string  $sortby
 * @property integer $filterid
 * @property integer $estateid
 * @property integer $listlimit
 * @property integer $listoffset
 * @property boolean $formatoutput
 * @property string  $estatelanguage
 * @property string  $outputlanguage
 * @property string  $language
 * @property boolean $tracking
 * @property string  $size
 * @property boolean $protected
 * @property string  $groups
 * @property boolean $published
 *
 * @method static OnofficeApiViewModel|null findById($id, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneBy($col, $val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByTstamp($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByType($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByTitle($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByAlias($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByData($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByCategories($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByRecordids($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByEstateids($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByFilter($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneBySortby($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByFilterid($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByEstateid($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByListlimit($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByListoffset($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByFormatoutput($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByEstatelanguage($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByOutputlanguage($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByLanguage($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByTracking($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneBySize($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByProtected($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByGroups($val, array $opt=array())
 * @method static OnofficeApiViewModel|null findOneByPublished($val, array $opt=array())
 *
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByTstamp($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByType($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByTitle($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByAlias($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByIdOrAlias($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByData($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByCategories($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByRecordids($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByEstateids($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByFilter($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findBySortby($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByFilterid($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByEstateid($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByListlimit($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByListoffset($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByFormatoutput($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByEstatelanguage($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByOutputlanguage($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByLanguage($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByTracking($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findBySize($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByProtected($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByGroups($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findByPublished($val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findMultipleByIds($var, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findBy($col, $val, array $opt=array())
 * @method static \Model\Collection|OnofficeApiViewModel[]|OnofficeApiViewModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByTstamp($val, array $opt=array())
 * @method static integer countByType($val, array $opt=array())
 * @method static integer countByTitle($val, array $opt=array())
 * @method static integer countByAlias($val, array $opt=array())
 * @method static integer countByData($val, array $opt=array())
 * @method static integer countByCategories($val, array $opt=array())
 * @method static integer countByRecordids($val, array $opt=array())
 * @method static integer countByEstateids($val, array $opt=array())
 * @method static integer countByFilter($val, array $opt=array())
 * @method static integer countBySortby($val, array $opt=array())
 * @method static integer countByFilterid($val, array $opt=array())
 * @method static integer countByEstateid($val, array $opt=array())
 * @method static integer countByListlimit($val, array $opt=array())
 * @method static integer countByListoffset($val, array $opt=array())
 * @method static integer countByFormatoutput($val, array $opt=array())
 * @method static integer countByEstatelanguage($val, array $opt=array())
 * @method static integer countByOutputlanguage($val, array $opt=array())
 * @method static integer countByLanguage($val, array $opt=array())
 * @method static integer countByTracking($val, array $opt=array())
 * @method static integer countBySize($val, array $opt=array())
 * @method static integer countByProtected($val, array $opt=array())
 * @method static integer countByGroups($val, array $opt=array())
 * @method static integer countByPublished($val, array $opt=array())
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class OnofficeApiViewModel extends \Model
{

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_onoffice_api_view';
}
