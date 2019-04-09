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
                        label="Your Favourite Streamer?"
                        solo
                        @keydown.enter="loadStream"
                >
                    <v-progress-circular :value="progress"></v-progress-circular>
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
                            <v-list-tile v-for="event,idx in events" :key="idx">
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
            this.pusher = new Pusher(pusher.key, {
                cluster: pusher.cluster
            });

            // let formerChannel = localStorage.getItem('channel');


        },
        methods: {
            loadStream: function () {
                let url = apiUrl + '/stream/' + this.channel;
                let formerChannel = localStorage.getItem('channel');
                //
                //
                // // avoid duplicate request to server
                // if (formerChannel && this.channel !== formerChannel) {
                //
                //
                //
                // }


                if (formerChannel){
                    this.unsubscribePusherUpdates(formerChannel);
                }

                this.progress = 0;

                axios.get(url).then(({data}) => {
                    this.stream = this.channel ? this.channel : formerChannel;
                    this.progress = 100;
                    this.events = data;
                    localStorage.setItem('channel', this.channel);
                    this.subscribePusherUpdates(this.channel);

                }).catch(({response}) => {
                    this.progress = 100;
                    if (response && response.status === 401) {
                        this.$router.replace('/')
                    }
                });

            },
            subscribePusherUpdates(channel) {
                let url = apiUrl + '/stream/subscribe/' + channel;

                axios.post(url, []).then(()=>{
                    this.pusher.logToConsole = true;
                    this.pusher.subscribe(channel.toLowerCase());
                    this.pusher.bind('stream_changed', (event) => {
                        console.log(event);
                        this.events.unshift(event);
                    });
                })

            },
            unsubscribePusherUpdates(channel) {
                this.pusher.unsubscribe(channel);
                this.pusher.unbind_all(() => {
                    console.log('removed from channel')
                })
            }
        }

    }
</script>
