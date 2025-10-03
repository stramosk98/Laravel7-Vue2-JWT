/**
 * Check if the user has a permission
 * @param {Object} user - The user object
 * @param {string} permission_name - The name of the permission to check
 * @returns {boolean} - True if the user has the permission, false otherwise
 */
export function hasPermission(user, permission_name) {
    if (!user || Object.keys(user).length === 0) return false;
    if (user.role_id === 1) return true;
    const permissions = user.permissions || [];
    return permissions.some(permission => permission.name === permission_name);
}