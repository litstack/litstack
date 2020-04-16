/**
 * Check if array equals another one.
 */
Array.equals = (a, b) => {
    if (a === b) return true;
    if (a == null || b == null) return false;

    for (let i in b) {
        if (!a.includes(b[i])) return false;
    }
    return true;
};
