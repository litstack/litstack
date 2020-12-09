import { Mark } from 'tiptap';
import { updateMark, removeMark } from 'tiptap-commands';

export default class FontColor extends Mark {
    get name() {
        return 'font_color';
    }

    get defaultOptions() {
        return {
            style: null,
        };
    }

    get schema() {
        return {
            attrs: {
                style: {
                    default: 'red',
                },
            },
            parseDOM: [
                {
                    tag: 'span',
                    getAttrs: (dom) => ({
                        style: dom.getAttribute('style'),
                    }),
                },
            ],
            toDOM: (node) => [
                'span',
                {
                    ...node.attrs,
                },
                0,
            ],
        };
    }

    commands({ type }) {
        return (attrs) => {
            if (attrs.style) {
                return updateMark(type, attrs);
            }

            return removeMark(type);
        };
    }
}
