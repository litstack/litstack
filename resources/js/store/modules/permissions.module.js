const state = {
    permissions: null,
    roles: [],
    litPermissionsRole: '',
    litPermissionsOperations: [],
    litPermissionsRolePermissions: [],
    litPermissionsPermissions: [],
};

const getters = {
    permissions(state) {
        return state.permissions;
    },
    roles(state) {
        return state.roles;
    },
    litPermissionsRole(state) {
        return state.litPermissionsRole;
    },
    litPermissionsOperations(state) {
        return state.litPermissionsOperations;
    },
    litPermissionsRolePermissions(state) {
        return state.litPermissionsRolePermissions;
    },
    litPermissionsPermissions(state) {
        return state.litPermissionsPermissions;
    },
};

const mutations = {
    SET_PERMISSIONS(state, permissions) {
        state.permissions = permissions;
    },
    SET_ROLES(state, roles) {
        state.roles = roles;
    },
    SET_LIT_PERMISSIONS_ROLE(state, role) {
        state.litPermissionsRole = role;
    },
    SET_LIT_PERMISSIONS_OPERATIONS(state, operiations) {
        state.litPermissionsOperations = operiations;
    },
    SET_LIT_PERMISSIONS_ROLE_PERMISSIONS(state, role_permissions) {
        state.litPermissionsRolePermissions = role_permissions;
    },
    SET_LIT_PERMISSIONS_PERMISSIONS(state, permissions) {
        state.litPermissionsPermissions = permissions;
    },
};

const actions = {};

const module = {
    state,
    getters,
    mutations,
    actions,
};

export default module;
