<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Localization
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Localization;

use phpOMS\Stdlib\Base\EnumArray;

/**
 * PHP Time zones.
 *
 * @package    phpOMS\Localization
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class TimeZoneEnumArray extends EnumArray
{
    protected static $constants = [
        0   => 'Africa/Abidjan',
        1   => 'Africa/Accra',
        2   => 'Africa/Addis_Ababa',
        3   => 'Africa/Algiers',
        4   => 'Africa/Asmara',
        5   => 'Africa/Bamako',
        6   => 'Africa/Bangui',
        7   => 'Africa/Banjul',
        8   => 'Africa/Bissau',
        9   => 'Africa/Blantyre',
        10  => 'Africa/Brazzaville',
        11  => 'Africa/Bujumbura',
        12  => 'Africa/Cairo',
        13  => 'Africa/Casablanca',
        14  => 'Africa/Ceuta',
        15  => 'Africa/Conakry',
        16  => 'Africa/Dakar',
        17  => 'Africa/Dar_es_Salaam',
        18  => 'Africa/Djibouti',
        19  => 'Africa/Douala',
        20  => 'Africa/El_Aaiun',
        21  => 'Africa/Freetown',
        22  => 'Africa/Gaborone',
        23  => 'Africa/Harare',
        24  => 'Africa/Johannesburg',
        25  => 'Africa/Juba',
        26  => 'Africa/Kampala',
        27  => 'Africa/Khartoum',
        28  => 'Africa/Kigali',
        29  => 'Africa/Kinshasa',
        30  => 'Africa/Lagos',
        31  => 'Africa/Libreville',
        32  => 'Africa/Lome',
        33  => 'Africa/Luanda',
        34  => 'Africa/Lubumbashi',
        35  => 'Africa/Lusaka',
        36  => 'Africa/Malabo',
        37  => 'Africa/Maputo',
        38  => 'Africa/Maseru',
        39  => 'Africa/Mbabane',
        40  => 'Africa/Mogadishu',
        41  => 'Africa/Monrovia',
        42  => 'Africa/Nairobi',
        43  => 'Africa/Ndjamena',
        44  => 'Africa/Niamey',
        45  => 'Africa/Nouakchott',
        46  => 'Africa/Ouagadougou',
        47  => 'Africa/Porto-Novo',
        48  => 'Africa/Sao_Tome',
        49  => 'Africa/Tripoli',
        50  => 'Africa/Tunis',
        51  => 'Africa/Windhoek',
        52  => 'America/Adak',
        53  => 'America/Anchorage',
        54  => 'America/Anguilla',
        55  => 'America/Antigua',
        56  => 'America/Araguaina',
        57  => 'America/Argentina/Buenos_Aires',
        58  => 'America/Argentina/Catamarca',
        59  => 'America/Argentina/Cordoba',
        60  => 'America/Argentina/Jujuy',
        61  => 'America/Argentina/La_Rioja',
        62  => 'America/Argentina/Mendoza',
        63  => 'America/Argentina/Rio_Gallegos',
        64  => 'America/Argentina/Salta',
        65  => 'America/Argentina/San_Juan',
        66  => 'America/Argentina/San_Luis',
        67  => 'America/Argentina/Tucuman',
        68  => 'America/Argentina/Ushuaia',
        69  => 'America/Aruba',
        70  => 'America/Asuncion',
        71  => 'America/Atikokan',
        72  => 'America/Bahia',
        73  => 'America/Bahia_Banderas',
        74  => 'America/Barbados',
        75  => 'America/Belem',
        76  => 'America/Belize',
        77  => 'America/Blanc-Sablon',
        78  => 'America/Boa_Vista',
        79  => 'America/Bogota',
        80  => 'America/Boise',
        81  => 'America/Cambridge_Bay',
        82  => 'America/Campo_Grande',
        83  => 'America/Cancun',
        84  => 'America/Caracas',
        85  => 'America/Cayenne',
        86  => 'America/Cayman',
        87  => 'America/Chicago',
        88  => 'America/Chihuahua',
        89  => 'America/Costa_Rica',
        90  => 'America/Creston',
        91  => 'America/Cuiaba',
        92  => 'America/Curacao',
        93  => 'America/Danmarkshavn',
        94  => 'America/Dawson',
        95  => 'America/Dawson_Creek',
        96  => 'America/Denver',
        97  => 'America/Detroit',
        98  => 'America/Dominica',
        99  => 'America/Edmonton',
        100 => 'America/Eirunepe',
        101 => 'America/El_Salvador',
        102 => 'America/Fortaleza',
        103 => 'America/Glace_Bay',
        104 => 'America/Godthab',
        105 => 'America/Goose_Bay',
        106 => 'America/Grand_Turk',
        107 => 'America/Grenada',
        108 => 'America/Guadeloupe',
        109 => 'America/Guatemala',
        110 => 'America/Guayaquil',
        111 => 'America/Guyana',
        112 => 'America/Halifax',
        113 => 'America/Havana',
        114 => 'America/Hermosillo',
        115 => 'America/Indiana/Indianapolis',
        116 => 'America/Indiana/Knox',
        117 => 'America/Indiana/Marengo',
        118 => 'America/Indiana/Petersburg',
        119 => 'America/Indiana/Tell_City',
        120 => 'America/Indiana/Vevay',
        121 => 'America/Indiana/Vincennes',
        122 => 'America/Indiana/Winamac',
        123 => 'America/Inuvik',
        124 => 'America/Iqaluit',
        125 => 'America/Jamaica',
        126 => 'America/Juneau',
        127 => 'America/Kentucky/Louisville',
        128 => 'America/Kentucky/Monticello',
        129 => 'America/Kralendijk',
        130 => 'America/La_Paz',
        131 => 'America/Lima',
        132 => 'America/Los_Angeles',
        133 => 'America/Lower_Princes',
        134 => 'America/Maceio',
        135 => 'America/Managua',
        136 => 'America/Manaus',
        137 => 'America/Marigot',
        138 => 'America/Martinique',
        139 => 'America/Matamoros',
        140 => 'America/Mazatlan',
        141 => 'America/Menominee',
        142 => 'America/Merida',
        143 => 'America/Metlakatla',
        144 => 'America/Mexico_City',
        145 => 'America/Miquelon',
        146 => 'America/Moncton',
        147 => 'America/Monterrey',
        148 => 'America/Montevideo',
        149 => 'America/Montreal',
        150 => 'America/Montserrat',
        151 => 'America/Nassau',
        152 => 'America/New_York',
        153 => 'America/Nipigon',
        154 => 'America/Nome',
        155 => 'America/Noronha',
        156 => 'America/North_Dakota/Beulah',
        157 => 'America/North_Dakota/Center',
        158 => 'America/North_Dakota/New_Salem',
        159 => 'America/Ojinaga',
        160 => 'America/Panama',
        161 => 'America/Pangnirtung',
        162 => 'America/Paramaribo',
        163 => 'America/Phoenix',
        164 => 'America/Port-au-Prince',
        165 => 'America/Port_of_Spain',
        166 => 'America/Porto_Velho',
        167 => 'America/Puerto_Rico',
        168 => 'America/Rainy_River',
        169 => 'America/Rankin_Inlet',
        170 => 'America/Recife',
        171 => 'America/Regina',
        172 => 'America/Resolute',
        173 => 'America/Rio_Branco',
        174 => 'America/Santa_Isabel',
        175 => 'America/Santarem',
        176 => 'America/Santiago',
        177 => 'America/Santo_Domingo',
        178 => 'America/Sao_Paulo',
        179 => 'America/Scoresbysund',
        180 => 'America/Shiprock',
        181 => 'America/Sitka',
        182 => 'America/St_Barthelemy',
        183 => 'America/St_Johns',
        184 => 'America/St_Kitts',
        185 => 'America/St_Lucia',
        186 => 'America/St_Thomas',
        187 => 'America/St_Vincent',
        188 => 'America/Swift_Current',
        189 => 'America/Tegucigalpa',
        190 => 'America/Thule',
        191 => 'America/Thunder_Bay',
        192 => 'America/Tijuana',
        193 => 'America/Toronto',
        194 => 'America/Tortola',
        195 => 'America/Vancouver',
        196 => 'America/Whitehorse',
        197 => 'America/Winnipeg',
        198 => 'America/Yakutat',
        199 => 'America/Yellowknife',
        200 => 'Antarctica/Casey',
        201 => 'Antarctica/Davis',
        202 => 'Antarctica/DumontDUrville',
        203 => 'Antarctica/Macquarie',
        204 => 'Antarctica/Mawson',
        205 => 'Antarctica/McMurdo',
        206 => 'Antarctica/Palmer',
        207 => 'Antarctica/Rothera',
        208 => 'Antarctica/South_Pole',
        209 => 'Antarctica/Syowa',
        210 => 'Antarctica/Vostok',
        211 => 'Arctic/Longyearbyen',
        212 => 'Asia/Aden',
        213 => 'Asia/Almaty',
        214 => 'Asia/Amman',
        215 => 'Asia/Anadyr',
        216 => 'Asia/Aqtau',
        217 => 'Asia/Aqtobe',
        218 => 'Asia/Ashgabat',
        219 => 'Asia/Baghdad',
        220 => 'Asia/Bahrain',
        221 => 'Asia/Baku',
        222 => 'Asia/Bangkok',
        223 => 'Asia/Beirut',
        224 => 'Asia/Bishkek',
        225 => 'Asia/Brunei',
        226 => 'Asia/Choibalsan',
        227 => 'Asia/Chongqing',
        228 => 'Asia/Colombo',
        229 => 'Asia/Damascus',
        230 => 'Asia/Dhaka',
        231 => 'Asia/Dili',
        232 => 'Asia/Dubai',
        233 => 'Asia/Dushanbe',
        234 => 'Asia/Gaza',
        235 => 'Asia/Harbin',
        236 => 'Asia/Hebron',
        237 => 'Asia/Ho_Chi_Minh',
        238 => 'Asia/Hong_Kong',
        239 => 'Asia/Hovd',
        240 => 'Asia/Irkutsk',
        241 => 'Asia/Jakarta',
        242 => 'Asia/Jayapura',
        243 => 'Asia/Jerusalem',
        244 => 'Asia/Kabul',
        245 => 'Asia/Kamchatka',
        246 => 'Asia/Karachi',
        247 => 'Asia/Kashgar',
        248 => 'Asia/Kathmandu',
        249 => 'Asia/Kolkata',
        250 => 'Asia/Krasnoyarsk',
        251 => 'Asia/Kuala_Lumpur',
        252 => 'Asia/Kuching',
        253 => 'Asia/Kuwait',
        254 => 'Asia/Macau',
        255 => 'Asia/Magadan',
        256 => 'Asia/Makassar',
        257 => 'Asia/Manila',
        258 => 'Asia/Muscat',
        259 => 'Asia/Nicosia',
        260 => 'Asia/Novokuznetsk',
        261 => 'Asia/Novosibirsk',
        262 => 'Asia/Omsk',
        263 => 'Asia/Oral',
        264 => 'Asia/Phnom_Penh',
        265 => 'Asia/Pontianak',
        266 => 'Asia/Pyongyang',
        267 => 'Asia/Qatar',
        268 => 'Asia/Qyzylorda',
        269 => 'Asia/Rangoon',
        270 => 'Asia/Riyadh',
        271 => 'Asia/Sakhalin',
        272 => 'Asia/Samarkand',
        273 => 'Asia/Seoul',
        274 => 'Asia/Shanghai',
        275 => 'Asia/Singapore',
        276 => 'Asia/Taipei',
        277 => 'Asia/Tashkent',
        278 => 'Asia/Tbilisi',
        279 => 'Asia/Tehran',
        280 => 'Asia/Thimphu',
        281 => 'Asia/Tokyo',
        282 => 'Asia/Ulaanbaatar',
        283 => 'Asia/Urumqi',
        284 => 'Asia/Vientiane',
        285 => 'Asia/Vladivostok',
        286 => 'Asia/Yakutsk',
        287 => 'Asia/Yekaterinburg',
        288 => 'Asia/Yerevan',
        289 => 'Atlantic/Azores',
        290 => 'Atlantic/Bermuda',
        291 => 'Atlantic/Canary',
        292 => 'Atlantic/Cape_Verde',
        293 => 'Atlantic/Faroe',
        294 => 'Atlantic/Madeira',
        295 => 'Atlantic/Reykjavik',
        296 => 'Atlantic/South_Georgia',
        297 => 'Atlantic/St_Helena',
        298 => 'Atlantic/Stanley',
        299 => 'Australia/Adelaide',
        300 => 'Australia/Brisbane',
        301 => 'Australia/Broken_Hill',
        302 => 'Australia/Currie',
        303 => 'Australia/Darwin',
        304 => 'Australia/Eucla',
        305 => 'Australia/Hobart',
        306 => 'Australia/Lindeman',
        307 => 'Australia/Lord_Howe',
        308 => 'Australia/Melbourne',
        309 => 'Australia/Perth',
        310 => 'Australia/Sydney',
        311 => 'Europe/Amsterdam',
        312 => 'Europe/Andorra',
        313 => 'Europe/Athens',
        314 => 'Europe/Belgrade',
        315 => 'Europe/Berlin',
        316 => 'Europe/Bratislava',
        317 => 'Europe/Brussels',
        318 => 'Europe/Bucharest',
        319 => 'Europe/Budapest',
        320 => 'Europe/Chisinau',
        321 => 'Europe/Copenhagen',
        322 => 'Europe/Dublin',
        323 => 'Europe/Gibraltar',
        324 => 'Europe/Guernsey',
        325 => 'Europe/Helsinki',
        326 => 'Europe/Isle_of_Man',
        327 => 'Europe/Istanbul',
        328 => 'Europe/Jersey',
        329 => 'Europe/Kaliningrad',
        330 => 'Europe/Kiev',
        331 => 'Europe/Lisbon',
        332 => 'Europe/Ljubljana',
        333 => 'Europe/London',
        334 => 'Europe/Luxembourg',
        335 => 'Europe/Madrid',
        336 => 'Europe/Malta',
        337 => 'Europe/Mariehamn',
        338 => 'Europe/Minsk',
        339 => 'Europe/Monaco',
        340 => 'Europe/Moscow',
        341 => 'Europe/Oslo',
        342 => 'Europe/Paris',
        343 => 'Europe/Podgorica',
        344 => 'Europe/Prague',
        345 => 'Europe/Riga',
        346 => 'Europe/Rome',
        347 => 'Europe/Samara',
        348 => 'Europe/San_Marino',
        349 => 'Europe/Sarajevo',
        350 => 'Europe/Simferopol',
        351 => 'Europe/Skopje',
        352 => 'Europe/Sofia',
        353 => 'Europe/Stockholm',
        354 => 'Europe/Tallinn',
        355 => 'Europe/Tirane',
        356 => 'Europe/Uzhgorod',
        357 => 'Europe/Vaduz',
        358 => 'Europe/Vatican',
        359 => 'Europe/Vienna',
        360 => 'Europe/Vilnius',
        361 => 'Europe/Volgograd',
        362 => 'Europe/Warsaw',
        363 => 'Europe/Zagreb',
        364 => 'Europe/Zaporozhye',
        365 => 'Europe/Zurich',
        366 => 'Indian/Antananarivo',
        367 => 'Indian/Chagos',
        368 => 'Indian/Christmas',
        369 => 'Indian/Cocos',
        370 => 'Indian/Comoro',
        371 => 'Indian/Kerguelen',
        372 => 'Indian/Mahe',
        373 => 'Indian/Maldives',
        374 => 'Indian/Mauritius',
        375 => 'Indian/Mayotte',
        376 => 'Indian/Reunion',
        377 => 'Pacific/Apia',
        378 => 'Pacific/Auckland',
        379 => 'Pacific/Chatham',
        380 => 'Pacific/Chuuk',
        381 => 'Pacific/Easter',
        382 => 'Pacific/Efate',
        383 => 'Pacific/Enderbury',
        384 => 'Pacific/Fakaofo',
        385 => 'Pacific/Fiji',
        386 => 'Pacific/Funafuti',
        387 => 'Pacific/Galapagos',
        388 => 'Pacific/Gambier',
        389 => 'Pacific/Guadalcanal',
        390 => 'Pacific/Guam',
        391 => 'Pacific/Honolulu',
        392 => 'Pacific/Johnston',
        393 => 'Pacific/Kiritimati',
        394 => 'Pacific/Kosrae',
        395 => 'Pacific/Kwajalein',
        396 => 'Pacific/Majuro',
        397 => 'Pacific/Marquesas',
        398 => 'Pacific/Midway',
        399 => 'Pacific/Nauru',
        400 => 'Pacific/Niue',
        401 => 'Pacific/Norfolk',
        402 => 'Pacific/Noumea',
        403 => 'Pacific/Pago_Pago',
        404 => 'Pacific/Palau',
        405 => 'Pacific/Pitcairn',
        406 => 'Pacific/Pohnpei',
        407 => 'Pacific/Port_Moresby',
        408 => 'Pacific/Rarotonga',
        409 => 'Pacific/Saipan',
        410 => 'Pacific/Tahiti',
        411 => 'Pacific/Tarawa',
        412 => 'Pacific/Tongatapu',
        413 => 'Pacific/Wake',
        414 => 'Pacific/Wallis',
        415 => 'UTC',
    ];
}
