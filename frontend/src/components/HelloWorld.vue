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

      <v-flex
              mb-5
              xs12
      >
        <h2 class="headline font-weight-bold mb-3">What's next?</h2>

        <v-layout justify-center>
          <v-btn
                  class="subheading mx-3"
                  @click="loginToTwitch"
          >
            Login To Proceed
          </v-btn>
        </v-layout>
      </v-flex>

    </v-layout>
  </v-container>
</template>

<script>
  export default {
    props: ['msg'],
    data: () => ({

    }),
    methods:{
      loginToTwitch: function () {
        let url = apiUrl + '/auth';
        axios.get(url).then(({data})=>{
          // redirect to twitcher api
          window.location.href = data.authUrl;
        }).catch(({response}) =>{
          console.log(response);
        });
      }
    }
  }
</script>

<style>

</style>
