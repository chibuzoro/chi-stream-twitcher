<template>
    <v-container>
        <v-layout
                text-xs-center
                wrap
        >
            <v-flex xs12>
                <!-- Add a placeholder for the Twitch embed -->
                <div id="twitch-embed"></div>
            </v-flex>

        </v-layout>
    </v-container>
</template>

<script>
    export default {
        props: ['streamer'],
        data: () => ({

        }),
        mounted: function(){
            let twitchEmbed = document.createElement('script');
            twitchEmbed.setAttribute('src', 'https://embed.twitch.tv/embed/v1.js');
            document.head.appendChild(twitchEmbed);
        },
        watch:{
            streamer: function (val) {
                this.loadEmbed(val);
            }
        },
        methods:{
            loadEmbed: function (channel) {
                // lets clear previously embedded videos
                document.getElementById('twitch-embed').innerHTML = '';
                let twitchEmbed = new Twitch.Embed("twitch-embed", {
                    width: 854,
                    height: 480,
                    channel: channel,
                    // theme   : 'dark',
                });

                twitchEmbed.addEventListener(Twitch.Embed.VIDEO_READY, () => {
                    let player = twitchEmbed.getPlayer();
                    player.play();
                });
            }
        }
    }
</script>

<style>

</style>
