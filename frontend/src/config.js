// Global Config file
require('dotenv').config();

global.apiUrl = process.env.API_URL;

global.pusher = {
    key: process.env.PUSHER_KEY,
    cluster: process.env.PUSHER_CLUSTER
};