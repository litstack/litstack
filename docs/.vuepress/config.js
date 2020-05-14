const meta = {
    title: 'Fjord | Laravel Content-Administration',
    description:
        'Fjord is a multilanguage admin-panel/cms scaffolding package that helps you creating CRUD in seconds via Artisan-commands. It also lets you manage the "static" content of each of your websites pages (Headlines, Text, Images), as well repetitive mixed contents you define. Generating content as well as passing it to your views and retrieving it is super simple.',
    url: 'https://laravel-fjord.com'
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
    ['meta', { name: 'theme-color', content: '#4951f2' }],
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
        nav: [
            {
                text: 'Documentation',
                link: '/guide/getting-started/introduction.html'
            },
            { text: '🕹 Demo', link: 'https://playground.fjord-admin.com' }
        ],
        sidebar: {
            '/': [
                {
                    title: 'Get started',
                    collapsable: false,
                    children: [
                        ['guide/getting-started/introduction', 'Introduction'],
                        ['guide/getting-started/installation', 'Installation'],
                        ['guide/getting-started/configuration', 'Configuration']
                    ]
                },
                {
                    title: 'Basics',
                    collapsable: false,
                    children: [
                        ['guide/basics/navigation', 'Navigation'],
                        ['guide/basics/localization', 'Localization'],
                        ['guide/basics/vue', 'Extend With Vue'],
                        ['guide/basics/helpers', 'Helpers']
                    ]
                },
                {
                    title: 'CRUD',
                    collapsable: false,
                    children: [
                        ['guide/crud/create-crud', 'Models'],
                        ['guide/crud/create-forms', 'Forms'],
                        ['guide/crud/config-index', 'Index Config'],
                        ['guide/crud/config-form', 'Form Config'],
                        ['guide/crud/config-table', 'Table']
                    ]
                },
                {
                    title: 'Fields',
                    collapsable: true,
                    children: [
                        ['guide/fields/introduction', 'Introduction'],
                        ['guide/fields/validation', 'Validation'],
                        ['guide/fields/input', 'Input'],
                        ['guide/fields/textarea', 'Textarea'],
                        ['guide/fields/wysiwyg', 'WYSIWYG'],
                        ['guide/fields/boolean', 'Boolean'],
                        ['guide/fields/checkboxes', 'Checkboxes'],
                        ['guide/fields/range', 'Range'],
                        ['guide/fields/select', 'Select'],
                        ['guide/fields/date-time', 'Date/Time'],
                        ['guide/fields/image', 'Image'],
                        ['guide/fields/icon', 'Icon'],
                        ['guide/fields/code', 'Code Editor'],
                        ['guide/fields/password', 'Password'],
                        ['guide/fields/relation', 'Relation'],
                        ['guide/fields/one_relation', 'oneRelation'],
                        ['guide/fields/many_relation', 'manyRelation'],
                        ['guide/fields/block', 'Block'],
                        ['guide/fields/modal', 'Modal'],
                        ['guide/fields/component', 'Component']
                    ]
                },
                {
                    title: 'Frontend',
                    collapsable: false,
                    children: [
                        ['guide/frontend/javascript', 'Javascript'],
                        ['guide/frontend/vue', 'Vue'],
                        ['guide/frontend/components', 'Vue Components']
                    ]
                },
                {
                    title: 'Package Development',
                    collapsable: true,
                    children: [['guide/package/basics', 'Basics']]
                }
                /*
                {
                    title: 'Digging Deeper',
                    collapsable: false,
                    children: [
                        ['guide/deeper/package', 'Package Development'],
                        ['guide/deeper/lifecycle', 'Lifecycle']
                    ]
                }
                */
            ]
        }
    }
};
