<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CRUD Translations
    |--------------------------------------------------------------------------
    |
    | The crud translations include all translations related to the litstack
    | crud system. This includes fields and other parts of the crud pages.
    |
    */

    'preview'          => 'Vorschau',
    'of'               => 'von',
    'n_items_selected' => ':count Element ausgewählt | :count Elemente ausgewählt',

    'messages' => [
        'not_created' => ':model muss erstellt werden, um <i>:relation</i> hinzufügen zu können.',
    ],

    'fields' => [
        'block' => [
            'expand'       => 'ausklappen',
            'expand_all'   => 'Alle ausklappen',
            'collapse_all' => 'Alle einklappen',
            'messages'     => [
                'new_block' => 'Neuen Block hinzugefügt (:type)',
            ],

        ],
        'relation' => [
            'goto'     => 'zur Verknüpfung',
            'unlink'   => 'Verknüpfung aufheben',
            'edit'     => 'Verknüpfung bearbeiten',
            'messages' => [
                'relation_linked'   => ':relation erfolreich verknüpft',
                'relation_unlinked' => 'Verknüpfung aufgehoben.',
                'confirm_unlink'    => 'Bitte bestätigen Sie, dass Sie die Verknüpfung aufheben möchten.',
                'max_items_reached' => 'Es können maximal :count items ausgewählt werden..',
            ],
        ],
        'wysiwyg' => [
            'new_window' => 'Link in neuem Fenster öffnen',
        ],
        'list' => [
            'messages' => [
                'max_depth'           => 'Die Liste kann maximal :count Ebenen verschachtelt werden.',
                'confirm_delete'      => 'Soll :item Element wirklich gelöscht werden?',
                'confirm_delete_info' => 'Wenn Sie diesen Element entfernen, entfernen Sie dadurch auch alle darunter befindlichen Elemente.',
            ],
        ],
        'media' => [
            'messages' => [
                'image_uploaded' => 'Bild erfolgreich hochgeladen',
            ],
        ],
    ],

    'meta' => [
        'title_hint'       => 'Leicht verständlicher sinnvoller Satz. Gibt eine Vorstellung, worum es im Seiteninhalt geht. Maximal :width breit.',
        'keywords_hint'    => 'Die wichtigsten Schlüsselwörter des Seiteninhaltes. Einzelne (wenige) Wörter durch Kommata getrennt.',
        'description_hint' => 'Kurze aber aussagekräftige Inhaltsangabe der Seite. Beinhaltet die wichtigsten Schlüsselwörter des Seiteninhaltes.',
    ],
];
