<?php
namespace Vacancy\UtilsBundle\Repository;

class DefaultDataRepository
{
    /**
     * @return array
     */
    public function getDefaultLanguages()
    {
        return [
            'fr' => 'French',
            'it' => 'Italian',
            'ru' => 'Russian'
        ];
    }

    public function getDefaultDepartments()
    {
        return ['IT', 'HR', 'Sales', 'Marketing', 'Support'];
    }

    public function getDefaultVacancies()
    {
        return [
            [
                'title' => 'vacancy #1 en',
                'description' => 'description #1 en. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'department' => 'IT',
                'translations' => [
                    [
                        'language' => 'it',
                        'title' => 'vacancy #1 it',
                        'description' => 'description #1 it. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    ],
                    [
                        'language' => 'fr',
                        'title' => 'vacancy #1 fr',
                        'description' => 'description #1 fr. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    ],
                ]
            ],
            [
                'title' => 'vacancy #2 en',
                'description' => 'description #2 en. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'department' => 'Sales',
                'translations' => [
                    [
                        'language' => 'ru',
                        'title' => 'vacancy #2 ru',
                        'description' => 'description #2 ru. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    ],
                    [
                        'language' => 'fr',
                        'title' => 'vacancy #2 fr',
                        'description' => 'description #2 fr. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    ],
                ]
            ],
            [
                'title' => 'vacancy #3 en',
                'description' => 'description #3 en. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'department' => 'HR',
                'translations' => [
                    [
                        'language' => 'ru',
                        'title' => 'vacancy #3 ru',
                        'description' => 'description #3 ru. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    ],
                    [
                        'language' => 'fr',
                        'title' => 'vacancy #3 fr',
                        'description' => 'description #3 fr. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    ],
                ]
            ],
            [
                'title' => 'vacancy #4 en',
                'description' => 'description #4 en. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'department' => 'Marketing',
                'translations' => []
            ],
            [
                'title' => 'vacancy #5 en',
                'description' => 'description #5 en. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'department' => 'Support',
                'translations' => [
                    [
                        'language' => 'ru',
                        'title' => 'vacancy #5 ru',
                        'description' => 'description #1 ru. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    ]
                ]
            ],
            [
                'title' => 'vacancy #6 en',
                'description' => 'description #6 en. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'department' => 'IT',
                'translations' => [
                    [
                        'language' => 'it',
                        'title' => 'vacancy #1 it',
                        'description' => 'description #1 it. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    ],
                    [
                        'language' => 'ru',
                        'title' => 'vacancy #1 ru',
                        'description' => 'description #1 ru. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    ],
                    [
                        'language' => 'fr',
                        'title' => 'vacancy #1 fr',
                        'description' => 'description #1 fr. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    ]
                ]
            ]
        ];
    }
}