<?php

namespace Oveleon\ContaoOnofficeApiBundle;

class OnOfficeConstants
{
    const ESTATE_STATUS_ACTIVE = 1;
    const ESTATE_STATUS_INACTIVE = 2;
    const ESTATE_STATUS_ARCHIVE = 0;

    const ESTATE_TYPE_HOUSE = 'haus';
    const ESTATE_TYPE_APARTMENT = 'wohnung';
    const ESTATE_TYPE_OFFICE = 'buero_praxen';
    const ESTATE_TYPE_COMMERCIAL_LEISURE = 'freizeitimmbilien_gewerblich';
    const ESTATE_TYPE_HOSPITALITY = 'gastgewerbe';
    const ESTATE_TYPE_PLOT = 'grundstueck';
    const ESTATE_TYPE_STORAGE = 'hallen_lager_prod';
    const ESTATE_TYPE_CONSTRUCTION = 'hausbau';
    const ESTATE_TYPE_RETAIL = 'einzelhandel';
    const ESTATE_TYPE_FORESTRY = 'land_und_forstwirtschaft';
    const ESTATE_TYPE_PARKING = 'parken';
    const ESTATE_TYPE_ROOM = 'zimmer';
    const ESTATE_TYPE_INVESTMENT = 'zinshaus_renditeobjekt';
    const ESTATE_TYPE_OTHER = 'sonstige';

    const ESTATE_SUBTYPE_ESTATE = 'anwesen';
    const ESTATE_SUBTYPE_STUDIO = 'atelier';
    const ESTATE_SUBTYPE_ATTIC_APARTMENT = 'attikawohnung';
    const ESTATE_SUBTYPE_SETTLERS_FARM = 'aussiedlerhof';
    const ESTATE_SUBTYPE_EXHIBITION_SPACE = 'ausstellungsflaeche';
    const ESTATE_SUBTYPE_CASH_OPERATION = 'barbetrieb';
    const ESTATE_SUBTYPE_FARMHOUSE = 'bauernhaus';
    const ESTATE_SUBTYPE_FARM = 'bauernhof';
    const ESTATE_SUBTYPE_MOUNTAIN_HUT = 'berghuette';
    const ESTATE_SUBTYPE_SPECIAL = 'besondereImmobilie';
    const ESTATE_SUBTYPE_LOG_CABIN = 'blockhaus';
    const ESTATE_SUBTYPE_BOAT_MOORING = 'bootsliegeplatz';
    const ESTATE_SUBTYPE_BUNGALOW = 'bungalow';
    const ESTATE_SUBTYPE_HOUSE_CONSTRUCTION_BUNGALOW = 'hausbau_bungalow';
    const ESTATE_SUBTYPE_OFFICE_AND_WAREHOUSE = 'buero_und_lager';
    const ESTATE_SUBTYPE_OFFICE_FLOOR = 'bueroetage';
    const ESTATE_SUBTYPE_OFFICE_SPACE = 'bueroflaeche';
    const ESTATE_SUBTYPE_OFFICE_BUILDING = 'buerogebaeude';
    const ESTATE_SUBTYPE_OFFICE_HOUSE = 'buerohaus';
    const ESTATE_SUBTYPE_OFFICE_CENTRE = 'buerozentrum';
    const ESTATE_SUBTYPE_CAFE = 'cafe';
    const ESTATE_SUBTYPE_CARPORT = 'carport';
    const ESTATE_SUBTYPE_CHALET = 'chalet';
    const ESTATE_SUBTYPE_ATTIC = 'dachgeschoss';
    const ESTATE_SUBTYPE_DISCO = 'diskothek';
    const ESTATE_SUBTYPE_DOUBLE_GARAGE = 'doppelgarage';
    const ESTATE_SUBTYPE_DOUBLE_HOUSE = 'doppelhaus';
    const ESTATE_SUBTYPE_SEMI_DETACHED_HOUSE = 'doppelhaushaelfte';
    const ESTATE_SUBTYPE_DUPLEX = 'duplex';
    const ESTATE_SUBTYPE_CONDOMINIUM = 'eigentumswohnung';
    const ESTATE_SUBTYPE_FAMILY_HOUSE = 'einfamilienhaus';
    const ESTATE_SUBTYPE_CONSTRUCTION_FAMILY_HOUSE = 'hausbau_einfamilienhaus';
    const ESTATE_SUBTYPE_FAMILY_HOUSE_APARTMENT = 'einfamilienhausMitEinliegerwohnung';
    const ESTATE_SUBTYPE_SHOPPING_CENTERS = 'einkaufscentren';
    const ESTATE_SUBTYPE_SHOPPING_CENTER = 'einkaufszentrum';
    const ESTATE_SUBTYPE_ONE_ROOM_LOCATION = 'einraumlokal';
    const ESTATE_SUBTYPE_SINGLE_GARAGE = 'einzelgarage';
    const ESTATE_SUBTYPE_RETAIL_STORE = 'einzelhandelsladen';
    const ESTATE_SUBTYPE_GROUND_FLOOR = 'erdgeschoss';
    const ESTATE_SUBTYPE_FLOOR = 'etage';
    const ESTATE_SUBTYPE_TIMBERED_HOUSE = 'fachwerkhaus';
    const ESTATE_SUBTYPE_FACTORY_OUTLET = 'factory_outlet';
    const ESTATE_SUBTYPE_VACATION_HOUSE = 'ferienhaus';
    const ESTATE_SUBTYPE_VACATION_APARTMENT = 'ferienwohnung';
    const ESTATE_SUBTYPE_OPEN_SPACES = 'freiflaechen';
    const ESTATE_SUBTYPE_LEISURE = 'freizeit';
    const ESTATE_SUBTYPE_RECREATIONAL_FACILITY = 'freizeitanlage';
    const ESTATE_SUBTYPE_GASTRONOMY = 'gastronomie';
    const ESTATE_SUBTYPE_GASTRONOMIE_APARTMENT = 'gastronomie_und_wohnung';
    const ESTATE_SUBTYPE_HOSPITALITY = 'gaststaette';
    const ESTATE_SUBTYPE_MIXED = 'gemischt';
    const ESTATE_SUBTYPE_BUSINESS_HOUSE = 'geschaeftshaus';
    //const ESTATE_SUBTYPE_ = 'gewerbe';
    //const ESTATE_SUBTYPE_ = 'gewerbeanwesen';
    //const ESTATE_SUBTYPE_ = 'gewerbeeinheit';
    //const ESTATE_SUBTYPE_ = 'gewerbepark';
    //const ESTATE_SUBTYPE_ = 'gewerbezentrum';
    //const ESTATE_SUBTYPE_ = 'gaestehaus';
    //const ESTATE_SUBTYPE_ = 'halle';
    //const ESTATE_SUBTYPE_ = 'hochparterre';
    //const ESTATE_SUBTYPE_ = 'hochregallager';
    //const ESTATE_SUBTYPE_ = 'holzhaus';
    //const ESTATE_SUBTYPE_ = 'hotel_garni';
    //const ESTATE_SUBTYPE_ = 'hotelanwesen';
    //const ESTATE_SUBTYPE_ = 'hotels';
    //const ESTATE_SUBTYPE_ = 'industrie';
    //const ESTATE_SUBTYPE_ = 'industrieanlagen';
    //const ESTATE_SUBTYPE_ = 'industriehalle';
    //const ESTATE_SUBTYPE_ = 'industriehalle_und_freiflaeche';
    //const ESTATE_SUBTYPE_ = 'jagd_und_forstwirtschaft';
    //const ESTATE_SUBTYPE_ = 'jagdrevier';
    //const ESTATE_SUBTYPE_ = 'kaufhaus';
    //const ESTATE_SUBTYPE_ = 'kiosk';
    //const ESTATE_SUBTYPE_ = 'kuehlhaus';
    //const ESTATE_SUBTYPE_ = 'kuehlregallager';
    //const ESTATE_SUBTYPE_ = 'ladenlokal';
    //const ESTATE_SUBTYPE_ = 'lager';
    //const ESTATE_SUBTYPE_ = 'lager_mit_freiflaeche';
    //const ESTATE_SUBTYPE_ = 'lagerflaeche';
    //const ESTATE_SUBTYPE_ = 'lagerhalle';
    //const ESTATE_SUBTYPE_ = 'land_forstwirtschaft';
    //const ESTATE_SUBTYPE_ = 'landhaus';
    //const ESTATE_SUBTYPE_ = 'hausbau_landhaus';
    //const ESTATE_SUBTYPE_ = 'landwirtschaftliche_betriebe';
    //const ESTATE_SUBTYPE_ = 'loft';
    //const ESTATE_SUBTYPE_ = 'loft-studio-atelier';
    //const ESTATE_SUBTYPE_ = 'maisonette';
    //const ESTATE_SUBTYPE_ = 'hausbau_mehrfamilienhaus';
    //const ESTATE_SUBTYPE_ = 'mehrfamilienhaus';
    //const ESTATE_SUBTYPE_ = 'parkhaus';
    //const ESTATE_SUBTYPE_ = 'parken_parkhaus';
    //const ESTATE_SUBTYPE_ = 'parkplatz_strom';
    //const ESTATE_SUBTYPE_ = 'pensionen';
    //const ESTATE_SUBTYPE_ = 'penthouse';
    //const ESTATE_SUBTYPE_ = 'pflegeheim';
    //const ESTATE_SUBTYPE_ = 'praxis';
    //const ESTATE_SUBTYPE_ = 'praxisetage';
    //const ESTATE_SUBTYPE_ = 'praxisflaeche';
    //const ESTATE_SUBTYPE_ = 'praxishaus';
    //const ESTATE_SUBTYPE_ = 'produktion';
    //const ESTATE_SUBTYPE_ = 'raucherlokal';
    //const ESTATE_SUBTYPE_ = 'reiheneck';
    //const ESTATE_SUBTYPE_ = 'reihenend';
    //const ESTATE_SUBTYPE_ = 'hausbau_reihenhaus';
    //const ESTATE_SUBTYPE_ = 'reihenhaus';
    //const ESTATE_SUBTYPE_ = 'reihenmittel';
    //const ESTATE_SUBTYPE_ = 'reiterhoefe';
    //const ESTATE_SUBTYPE_ = 'restaurant';
    //const ESTATE_SUBTYPE_ = 'resthof';
    //const ESTATE_SUBTYPE_ = 'rohdachboden';
    //const ESTATE_SUBTYPE_ = 'sb_maerkte';
    //const ESTATE_SUBTYPE_ = 'sb_markt';
    //const ESTATE_SUBTYPE_ = 'scheunen';
    //const ESTATE_SUBTYPE_ = 'schloss';
    //const ESTATE_SUBTYPE_ = 'seeliegenschaft';
    //const ESTATE_SUBTYPE_ = 'service';
    //const ESTATE_SUBTYPE_ = 'sondernutzung';
    //const ESTATE_SUBTYPE_ = 'sonstige';
    //const ESTATE_SUBTYPE_ = 'sonstige_landwirtschaftsimmobilien';
    //const ESTATE_SUBTYPE_ = 'souterrain';
    //const ESTATE_SUBTYPE_ = 'speditionslager';
    //const ESTATE_SUBTYPE_ = 'sportanlage';
    //const ESTATE_SUBTYPE_ = 'stadthaus';
    //const ESTATE_SUBTYPE_ = 'stellplatz';
    //const ESTATE_SUBTYPE_ = 'strandhaus';
    //const ESTATE_SUBTYPE_ = 'tankstelle';
    //const ESTATE_SUBTYPE_ = 'teich_und_fischwirtschaft';
    //const ESTATE_SUBTYPE_ = 'terrassen';
    //const ESTATE_SUBTYPE_ = 'tiefgarage';
    //const ESTATE_SUBTYPE_ = 'tiefgaragenstellplatz';
    //const ESTATE_SUBTYPE_ = 'verbrauchermarkt';
    //const ESTATE_SUBTYPE_ = 'verbrauchermaerkte';
    //const ESTATE_SUBTYPE_ = 'vergnuegungsparks_und_center';
    //const ESTATE_SUBTYPE_ = 'verkaufssflaeche';
    //const ESTATE_SUBTYPE_ = 'verkaufshalle';
    //const ESTATE_SUBTYPE_ = 'viehwirtschaft';
    //const ESTATE_SUBTYPE_ = 'villa';
    //const ESTATE_SUBTYPE_ = 'hausbau_villa';
    //const ESTATE_SUBTYPE_ = 'weitere_beherbergungsbetriebe';
    //const ESTATE_SUBTYPE_ = 'werkstatt';
    //const ESTATE_SUBTYPE_ = 'wohn_und_geschaeftshaus';
    //const ESTATE_SUBTYPE_ = 'wohnanlage';
    //const ESTATE_SUBTYPE_ = 'wohnanlagen';
    //const ESTATE_SUBTYPE_ = 'wohnen';
    //const ESTATE_SUBTYPE_ = 'zimmer';
    //const ESTATE_SUBTYPE_ = 'zweifamilienhaus';
    //const ESTATE_SUBTYPE_ = 'hausbau_zweifamilienhaus';
}
