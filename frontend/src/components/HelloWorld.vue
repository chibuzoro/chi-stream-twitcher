<template>
    <v-container>
        <v-layout
                text-xs-center
                wrap
        >
            <v-flex xs12>
                <v-img
                        :src="require('../assets/twitch.svg')"
                        class="my-3"
                        contain
                        height="200"
                ></v-img>
            </v-flex>

            <v-flex mb-4>
                <h1 class="display-1 font-weight-bold mb-3">
                    {{msg}}
                </h1>
                <p class="subheading font-weight-regular">
                    A place to see your favorite streamer's Twitch events in real-time,
                </p>
            </v-flex>
<v-fade-transition leave-absolute>
            <v-flex v-if="guest"
                    mb-5
                    xs12
            >
                <h2 class="headline font-weight-bold mb-3">What's next?</h2>

                <v-layout justify-center v-fade-transition-transition>
                    <v-btn
                            class="subheading mx-3"
                            @click="loginToTwitch"
                    >
                        Login To Proceed
                    </v-btn>
                </v-layout>
            </v-flex>
            <v-flex v-else
                    mb-5
                    xs12
            >
                <h2 class="headline font-weight-bold mb-3">Who's your Favorite Streamer?</h2>

                <v-layout justify-center row>
                    <v-flex sm4>
                        <v-text-field
                                autofocus=true
                                v-model="channel"
                                label="Input Streamer Username and Hit enter to view. "
                                solo
                                @keydown.enter="loadStreamPage"
                        >
                        </v-text-field>
                    </v-flex>

                </v-layout>
            </v-flex>
</v-fade-transition>

        </v-layout>
    </v-container>
</template>

<script>
    export default {
        props: ['msg'],
        data: () => ({
            guest: true,
            channel: ''
        }),
        mounted: function () {

            let twitcherAccess = localStorage.getItem('twitcherAccess');

            if (twitcherAccess && twitcherAccess.access_token){
                this.$router.push('stream')
            }

            // expects returned code from authcode request.
            if (this.$route.query && this.$route.query.code) {
                this.getAccessToken(this.$route.query);
            }

        },
        methods: {
            loginToTwitch: function () {
                let url = apiUrl + '/auth';
                axios.get(url).then(({data}) => {
                    // redirect to twitcher api to authenticate and receive authcode
                    window.location.href = data.authUrl;
                }).catch(({response}) => {
                   // error from twitcher APi
                });
            },
            loadStreamPage: function(){
                localStorage.setItem('channel', this.channel);
                this.$router.push('stream');
            },
            getAccessToken: function (query) {
                let url = apiUrl + '/auth/token?' + this.convertObjToQueryParam(query);
                axios.get(url).then(({data}) => {
                    this.guest = false;
                    // redirect to twitcher api
                    localStorage.setItem('twitcherAccess', JSON.stringify(data));
                    // store token in axios session so its sent with every request
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.access_token;

                }).catch(({response}) => {
                    console.log(response);
                });
            },
            convertObjToQueryParam: function (obj) {
                return Object.keys(obj).map(key => key + '=' + encodeURIComponent(obj[key])).join('&')
            }
        }
    }
</script>

<style>

</style>
