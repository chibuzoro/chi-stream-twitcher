import Pusher from 'pusher-js'
export default {
    data:()=>({
        pusher: ''
    }),
    created() {
        this.pusher = new Pusher(pusher.key, {
            cluster: pusher.cluster
        });

    },
    methods:{
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
            this.pusher.unsubscribe(channel);
            this.pusher.unbind_all(() => {
                console.log('removed from channel')
            })
        }
    }
}