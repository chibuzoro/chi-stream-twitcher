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
                        height="40"
                ></v-img>
                <v-text-field
                        v-model="channel"
                        v-loading="progress"
                        label="Type Your Favourite Streamer Here. Hit Enter to Subscribe"
                        solo
                        @keydown.enter="loadStream"
                >
                </v-text-field>
            </v-flex>

            <v-flex mb-4 xs12>
                <embed-video :streamer="stream"></embed-video>
            </v-flex>

            <v-flex
                    mb-5
                    xs12
            >
                <v-subheader headline font-weight-bold mb-3>Recent Events</v-subheader>

                <v-layout justify-center>
                    <v-flex xs12>
                        <v-list>
                            <v-list-tile v-for="(event,idx) in events" :key="idx">
                                <v-list-tile-avatar><img :src="event.thumbnail"></v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title v-text="event.title"></v-list-tile-title>
                                    <v-list-tile-sub-title v-text="event.message"></v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-flex>

                </v-layout>
            </v-flex>

        </v-layout>
    </v-container>
</template>
<script>
    import EmbedVideo from "../components/EmbedVideo";
    import Pusher from 'pusher-js'

    export default {
        components: {EmbedVideo},
        data: () => ({
            channel: '',
            stream: '',
            progress: '',
            events: [],
            pusher: null,
        }),

        created() {

            this.channel = localStorage.getItem('channel');

            // set the accesstoken

            let twitcherAccess = localStorage.getItem('twitcherAccess');

            if (twitcherAccess && twitcherAccess.access_token){
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + twitcherAccess.access_token;
            }

            this.pusher = new Pusher(pusher.key, {
                cluster: pusher.cluster
            });

            if (this.channel !== ''){
                this.loadStream();
            }


        },
        methods: {
            loadStream: function () {
                this.progress = 'primary';
                let url = apiUrl + '/stream/' + this.channel;
                let formerChannel = localStorage.getItem('channel');
                this.unsubscribePusherUpdates(formerChannel); // remove old subscriptions

                axios.get(url).then(({data}) => {
                    this.progress = false;
                    this.stream = this.channel ? this.channel : formerChannel;
                    this.events = data;
                    localStorage.setItem('channel', this.channel);
                    this.subscribePusherUpdates(this.channel);

                }).catch(({response}) => {
                    if (response && response.status === 401) {
                        localStorage.clear();
                        this.$router.replace('/')
                    }
                });

            },
            subscribePusherUpdates(channel) {
                let url = apiUrl + '/stream/subscribe/' + channel;

                axios.post(url, []).then(()=>{
                    this.pusher.subscribe(channel.toLowerCase());
                    this.pusher.bind('stream_changed', (event) => {
                        this.events.unshift(event);
                    });
                })

            },
            unsubscribePusherUpdates(channel) {
                this.pusher.unsubscribe(channel.toLowerCase());
                this.pusher.unbind_all(() => {
                   // removed all unwanted subs
                })
            }
        }

    }
</script>
