<?php

return [

    'app' => [

        'title' => 'General',
        'desc' => 'All the general settings for application.',
        'icon' => 'fa fa-cogs',

        'elements' => [
            [
                'type' => 'text',
                'data' => 'string',
                'name' => 'app_name',
                'label' => 'App Name',
                'rules' => 'required|min:2|max:50'
            ]
        ]
    ],
	
	'account' => [

        'title' => 'Company Account',
        'desc' => 'All the Account settings for Company.',
        'icon' => 'fa fa-bank',

        'elements' => [
            [
                'type' => 'text',
                'data' => 'string',
                'name' => 'company_acc',
                'label' => 'Company Account',
                'rules' => 'required|min:2|max:50'
            ]
        ]
    ],
	
	'bank' => [

        'title' => 'Company Bank',
        'desc' => 'All the Name settings for Bank.',
        'icon' => 'fa fa-bank',

        'elements' => [
            [
                'type' => 'text',
                'data' => 'string',
                'name' => 'company_bank',
                'label' => 'Company Bank',
                'rules' => 'required|min:2|max:50'
            ]
        ]
    ],

    'social' => [

        'title' => 'Social Media',
        'desc' => 'All the social media settings for application.',
        'icon' => 'fa fa-share',

        'elements' => [
            [
                'type' => 'text',
                'data' => 'string',
                'name' => 'facebook_name',
                'label' => 'Facebook Name'
            ],
            [
                'type' => 'text',
                'data' => 'string',
                'name' => 'twitter_name',
                'label' => 'Twitter Name'
            ],
            [
                'type' => 'text',
                'data' => 'string',
                'name' => 'instagram_name',
                'label' => 'Instagram Name'
            ]
        ]
    ],

    'Locale' => [

        'title' => 'Localization',
        'desc' => 'Set your localization settings like format of Date and number etc.',
        'icon' => 'fa fa-globe',

        'elements' => [
            [
                'type' => 'select',
                'data' => 'string',
                'name' => 'date_format',
                'label' => 'Date format',
                'rules' => 'required',
                'class' => 'w-auto px-2',
                'options' => [
                    'd/m/Y' => date('d/m/Y'),
					'd-m-Y' => date("d-m-Y"),
					'm/d/Y' => date('m/d/Y'),
                    'm.d.y' => date("m.d.y"),
                    'j, n, Y' => date("j, n, Y"),
                    'M j, Y' => date("M j, Y"),
                    'D, M j, Y' => date('D, M j, Y')
                ],
                'value' => 'm/d/Y'
            ],
            [
                'type' => 'select',
                'data' => 'string',
                'name' => 'time_format',
                'label' => 'Time format',
                'rules' => 'string',
                'class' => 'w-auto px-2',
                'options' => [
                    'g:i a' => date('g:i a') . ' (12-hour format)',
                    'g:i:s A' => date('g:i A') . ' (12-hour format)',
                    'G:i' => date("G:i"). ' (24-hour format)',
                    'h:i:s a' => date("h:i:s a") . ' (12-hour with leading zero)',
                    'h:i:s A' => date("h:i:s A")
                ],
                'value' => 'g:i a'
            ],
            [
                'type' => 'select',
                'data' => 'string',
                'name' => 'timezone',
                'label' => 'Timezone',
                'class' => 'w-auto px-2',
                'rules' => 'string',
                'options' => array_combine(
                    DateTimeZone::listIdentifiers(DateTimeZone::ALL),
                    DateTimeZone::listIdentifiers(DateTimeZone::ALL)
                ),
                'value' => config('app.timezone', 'Asia/Jakarta')
            ],
            [
                'type' => 'select',
                'data' => 'string',
                'name' => 'locale',
                'label' => 'Language',
                'class' => 'w-auto px-2',
                'rules' => 'string',
                'options' => [
                    'en' => 'English',
                    'id' => 'Indonesia'
                ],
                'value' => config('app.locale','en')
            ]
        ]
    ],
	
	'tabungan' => [

        'title' => 'Bunga Tabungan',
        'desc' => 'Atur bunga tabungan',
        'icon' => 'fa fa-money',

        'elements' => [
            [
                'type' => 'text',
				'data' => 'number',
                'name' => 'bunga_tabungan',
                'label' => 'Bunga',
                'rules' => 'required'
            ]
        ]
    ],
	
	'pinjaman' => [

        'title' => 'Bunga Pinjaman',
        'desc' => 'Atur bunga pinjaman per tahun',
        'icon' => 'fa fa-money',

        'elements' => [
            [
                'type' => 'text',
				'data' => 'number',
                'name' => 'bunga_pinjaman',
                'label' => 'Bunga',
                'rules' => 'required'
            ]
        ]
    ],
];