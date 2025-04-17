import axios from 'axios';
window.axios = axios;

// Set up default headers
// Set Axios to automatically include CSRF token from cookies
axios.defaults.withCredentials = true; // Required for cookies
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Function to get the CSRF token from cookies
function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
}

// Attach CSRF token to every request
axios.interceptors.request.use(config => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    // const csrfToken = getCookie('XSRF-TOKEN');
    if (csrfToken) {
        config.headers['X-CSRF-TOKEN'] = csrfToken;
    } else {
        config.headers['X-CSRF-TOKEN'] = getCookie('XSRF-TOKEN');
    }
    return config;
}, error => Promise.reject(error));

// Function to fetch a new CSRF token
async function fetchNewCsrfToken() {
    try {
        return await axios.get('/sanctum/csrf-cookie'); // Laravel CSRF refresh
    } catch (error) {
        console.error('Failed to refresh CSRF token:', error);
        throw error;
    }
}

// Handle 419 CSRF token expiration
axios.interceptors.response.use(response => response, async error => {
    if (error.response && error.response.status === 419) {
        // Prevent infinite loop by checking if we've already retried
        if (error.config._retryAttempt) {
            console.error('CSRF token refresh failed, preventing request loop.');
            return Promise.reject(error);
        }

        error.config._retryAttempt = true; // Mark as retried

        try {
            const response = await fetchNewCsrfToken();

            if (!response || !response.data || !response.data.csrfToken) {
                throw new Error('Invalid CSRF token response');
            }

            const csrfToken = response.data.csrfToken;
            document.querySelector('meta[name="csrf-token"]').setAttribute('content', csrfToken);
            error.config.headers['X-CSRF-TOKEN'] = csrfToken; // Update the token

            return axios(error.config); // Retry the request once
        } catch (refreshError) {
            console.error('Failed to refresh CSRF token:', refreshError);
            return Promise.reject(refreshError);
        }
    }
    return Promise.reject(error);
});

// Log all requests
axios.interceptors.request.use(request => {
    console.log('Request:', request);
    return request;
});

// Log all responses
axios.interceptors.response.use(response => {
    console.log('Response:', response);
    return response;
}, error => {
    console.error('Axios Error:', error.response);
    return Promise.reject(error);
});
