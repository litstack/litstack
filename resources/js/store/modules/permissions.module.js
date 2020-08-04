import Vue from 'vue';

const state = {
    permissions: null,
    roles: [],
    fjPermissionsRole: '',
    fjPermissionsOperations: [],
    fjPermissionsRolePermissions: [],
    fjPermissionsPermissions: []
};

const getters = {
    permissions(state) {
        return state.permissions;
    },
    roles(state) {
        return state.roles;
    },
    fjPermissionsRole(state) {
        return state.fjPermissionsRole;
    },
    fjPermissionsOperations(state) {
        return state.fjPermissionsOperations;
    },
    fjPermissionsRolePermissions(state) {
        return state.fjPermissionsRolePermissions;
    },
    fjPermissionsPermissions(state) {
        return state.fjPermissionsPermissions;
    }
};

const mutations = {
    SET_PERMISSIONS(state, permissions) {
        state.permissions = permissions;
    },
    SET_ROLES(state, roles) {
        state.roles = roles;
    },
    SET_FJ_PERMISSIONS_ROLE(state, role) {
        state.fjPermissionsRole = role;
    },
    SET_FJ_PERMISSIONS_OPERATIONS(state, operiations) {
        state.fjPermissionsOperations = operiations;
    },
    SET_FJ_PERMISSIONS_ROLE_PERMISSIONS(state, role_permissions) {
        state.fjPermissionsRolePermissions = role_permissions;
    },
    SET_FJ_PERMISSIONS_PERMISSIONS(state, permissions) {
        state.fjPermissionsPermissions = permissions;
    }
};

const actions = {};

const module = {
    state,
    getters,
    mutations,
    actions
};

export default module;
