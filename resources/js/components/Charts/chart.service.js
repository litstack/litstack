export const PRIMARY = '#4951f2';
export const SECONDARY = '#6c8199';
export const WHITE = '#fff';
export const COLORS = [
    '#FF6766',
    '#C5F466',
    '#AA6BCE',
    '#66E067',
    '#708CCD',
    '#FFAC66',
    '#E367AD',
    '#FFCC67',
    '#67C2C5',
    '#8A70CF',
    '#FFFC67',
    '#FEE566',
];

export function firstColor(variant) {
    switch (variant) {
        case WHITE:
            return PRIMARY;
            break;
        case PRIMARY:
            return WHITE;
            break;
        case SECONDARY:
            return WHITE;
            break;
        default:
            return PRIMARY;
            break;
    }
}

export function secondColor(variant) {
    return variant == WHITE
        ? '#ddd'
        : `#${lightenDarkenColor(variant.replace('#', ''), -50)}`;
}

export function lightenDarkenColor(col, amt) {
    col = parseInt(col, 16);
    return (
        ((col & 0x0000ff) + amt) |
        ((((col >> 8) & 0x00ff) + amt) << 8) |
        (((col >> 16) + amt) << 16)
    ).toString(16);
}

export function variantColor(variant) {
    switch (variant) {
        case 'white':
            return WHITE;
        case 'primary':
            return PRIMARY;
        case 'secondary':
            return SECONDARY;
        default:
            return WHITE;
    }
}
