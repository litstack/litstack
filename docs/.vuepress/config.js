const meta = {
    title: 'Fjord | Laravel base CMS/Admin-Panel',
    description:
        'Fjord is a multilanguage admin-panel/cms scaffolding package that helps you creating CRUD in seconds via Artisan-commands. It also lets you manage the "static" content of each of your websites pages (Headlines, Text, Images), as well repetitive mixed contents you define. Generating content as well as passing it to your views and retrieving it is super simple.',
    url: 'https://fjord-cms.com'
};

let head = [
    [
        'link',
        {
            href:
                '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600|Roboto Mono',
            rel: 'stylesheet',
            type: 'text/css'
        }
    ],
    ['link', { rel: 'icon', href: `/vue-logo.png` }],
    ['meta', { name: 'theme-color', content: '#1584ff' }],
    ['meta', { name: 'apple-mobile-web-app-capable', content: 'yes' }]
];

module.exports = {
    base: '/',
    title: 'Fjord',
    description: 'description',
    head,
    plugins: [
        [
            '@vuepress/google-analytics',
            {
                ga: 'UA-77559383-19'
            }
        ]
    ],
    themeConfig: {
        repo: 'aw-studio/fjord',
        editLinks: true,
        docsDir: 'docs',
        nav: [{ text: 'Home', link: '/' }],
        sidebar: {
            '/': [
                {
                    title: 'Get started',
                    collapsable: false,
                    children: [
                        ['guide/getting-started/installation', 'Installation'],
                        [
                            'guide/getting-started/configuration',
                            'Configuration'
                        ],
                        ['guide/getting-started/playground', '🕹 Playground']
                    ]
                },
                {
                    title: 'Basics',
                    collapsable: false,
                    children: [
                        ['guide/basics/navigation', 'Navigation'],
                        ['guide/basics/localization', 'Localization'],
                        ['guide/basics/helpers', 'Helpers'],
                        ['guide/basics/vue', 'Extend Vue']
                    ]
                },
                {
                    title: 'CRUD',
                    collapsable: true,
                    children: [
                        ['guide/crud/introduction', 'Introduction'],
                        ['guide/crud/create-crud', 'CRUD-Models'],
                        ['guide/crud/create-forms', 'Forms'],
                        ['guide/crud/config-index', 'Index Config'],
                        ['guide/crud/config-form', 'Form Config']
                    ]
                },
                {
                    title: 'Fields',
                    collapsable: true,
                    children: [
                        ['guide/fields/introduction', 'Introduction'],
                        ['guide/fields/input', 'Input'],
                        ['guide/fields/textarea', 'Textarea'],
                        ['guide/fields/wysiwyg', 'WYSIWYG'],
                        ['guide/fields/boolean', 'Boolean'],
                        ['guide/fields/checkboxes', 'Checkboxes'],
                        ['guide/fields/range', 'Range'],
                        ['guide/fields/select', 'Select'],
                        ['guide/fields/icon', 'Icon'],
                        ['guide/fields/date-time', 'Date/Time'],
                        ['guide/fields/image', 'Image'],
                        ['guide/fields/block', 'Block'],
                        ['guide/fields/relation', 'Relation'],
                        ['guide/fields/component', 'Component']
                    ]
                },
                {
                    title: 'Digging Deeper',
                    collapsable: false,
                    children: [
                        ['guide/deeper/package', 'Package Development'],
                        ['guide/deeper/lifecycle', 'Lifecycle']
                    ]
                }
            ]
        }
    }
};
