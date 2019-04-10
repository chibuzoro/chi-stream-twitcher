// Global Config file
require('dotenv').config();

global.apiUrl = process.env.VUE_APP_API_URL;
global.pusher = {
    key: process.env.VUE_APP_PUSHER_KEY,
    cluster: process.env.VUE_APP_PUSHER_CLUSTER
};