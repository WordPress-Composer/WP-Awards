const ifWindow = () => {
    return typeof window !== 'undefined'
        && window.location
        && window.location.origin;
}

/**
 * Gets the api url
 * @return {string} url of api
 */
export const url = ifWindow() ? window.location.origin + '/wp-json/voting/v1' : '/wp-json/voting/v1';

export const axiosWpAuthAccess = {
    withCredentials: true,
    headers: {
        'X-WP-Nonce': window && window.wpApiSettings && window.wpApiSettings.nonce ? window.wpApiSettings.nonce : null
    }
}