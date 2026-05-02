<<<<<<< HEAD
/**
 * Echo Service — Quản lý subscribe/unsubscribe WebSocket channels
 *
 * Dùng duy nhất WebSocket / Reverb cho thông báo realtime.
 * Channel names:
 *   - private-admin.{id}
 *   - private-user.{id}
 */

let _userChannel = null;
let _adminChannel = null;
let _subscribedUserId = null;
let _subscribedAdminId = null;

const safeLeave = (channelName) => {
    try {
        window.Echo?.leave(channelName);
    } catch (error) {
        console.warn('[Echo] Failed to leave channel:', channelName, error);
    }
};

export const updateEchoToken = (token) => {
    if (!window.Echo?.connector?.options?.auth?.headers) return;

    window.Echo.connector.options.auth.headers.Authorization = token
        ? `Bearer ${token}`
        : '';
=======
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

let _subscribedUserId = null;
let _userChannel = null;
let _subscribedAdminId = null;
let _adminChannel = null;

const safeLeave = (channelName) => {
    if (window.Echo) {
        window.Echo.leave(channelName);
        console.log(`[Echo] Left channel: ${channelName}`);
    }
};

export const leaveAllChannels = () => {
    if (_subscribedUserId) safeLeave(`user.${_subscribedUserId}`);
    if (_subscribedAdminId) safeLeave(`admin.${_subscribedAdminId}`);
    _subscribedUserId = null;
    _userChannel = null;
    _subscribedAdminId = null;
    _adminChannel = null;
};

export const updateEchoToken = (token) => {
    const bearerToken = token ? `Bearer ${token}` : '';
    
    if (window.Echo?.options?.auth?.headers) {
        window.Echo.options.auth.headers.Authorization = bearerToken;
    }

    if (window.Echo?.connector?.options?.auth?.headers) {
        window.Echo.connector.options.auth.headers.Authorization = bearerToken;
    }
    
    console.log('[Echo] Token updated:', !!token);
>>>>>>> qlkh-login
};

export const subscribeUser = (userId, onNotify) => {
    if (!window.Echo || !userId) return null;

<<<<<<< HEAD
    if (_subscribedUserId === userId && _userChannel) return _userChannel;

    if (_subscribedUserId) {
        safeLeave(`user.${_subscribedUserId}`);
    }
=======
    // Tránh subscribe lặp lại
    if (_subscribedUserId === userId && _userChannel) return _userChannel;

    if (_subscribedUserId) safeLeave(`user.${_subscribedUserId}`);
>>>>>>> qlkh-login

    _subscribedUserId = userId;
    _userChannel = window.Echo.private(`user.${userId}`);

    if (onNotify) {
        _userChannel.notification((notification) => {
            console.log('[Echo] User notification:', notification);
            try {
                onNotify(notification);
            } catch (error) {
                console.error('[Echo] User notification handler failed:', error);
            }
        });
    }

    console.log(`[Echo] Subscribed to private-user.${userId}`);
    return _userChannel;
};

export const subscribeAdmin = (adminId, onNotify) => {
    if (!window.Echo || !adminId) return null;

<<<<<<< HEAD
    if (_subscribedAdminId === adminId && _adminChannel) return _adminChannel;

    if (_subscribedAdminId) {
        safeLeave(`admin.${_subscribedAdminId}`);
    }
=======
    // Tránh subscribe lặp lại
    if (_subscribedAdminId === adminId && _adminChannel) return _adminChannel;

    if (_subscribedAdminId) safeLeave(`admin.${_subscribedAdminId}`);
>>>>>>> qlkh-login

    _subscribedAdminId = adminId;
    _adminChannel = window.Echo.private(`admin.${adminId}`);

    if (onNotify) {
        _adminChannel.notification((notification) => {
            console.log('[Echo] Admin notification:', notification);
            try {
                onNotify(notification);
            } catch (error) {
                console.error('[Echo] Admin notification handler failed:', error);
            }
        });
    }

    console.log(`[Echo] Subscribed to private-admin.${adminId}`);
    return _adminChannel;
<<<<<<< HEAD
};

export const leaveAllChannels = () => {
    if (window.Echo) {
        if (_subscribedUserId) {
            safeLeave(`user.${_subscribedUserId}`);
        }
        if (_subscribedAdminId) {
            safeLeave(`admin.${_subscribedAdminId}`);
        }
    }

    _userChannel = null;
    _adminChannel = null;
    _subscribedUserId = null;
    _subscribedAdminId = null;
=======
>>>>>>> qlkh-login
};