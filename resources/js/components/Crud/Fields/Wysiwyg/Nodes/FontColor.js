import { Mark } from 'tiptap';
import { toggleMark } from 'tiptap-commands';

export default class FontColor extends Mark {
    get name() {
        return 'font_color';
    }

    get schema() {
        return {
            parseDOM: [{ tag: 'span' }],
            toDOM: () => ['span', { style: 'color:green' }, 0]
        };
    }

    commands({ type }) {
        return () => toggleMark(type);
    }
}
