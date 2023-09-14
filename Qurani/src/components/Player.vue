<template>
  <transition name="slide-fade">
    <div :class="{player_fullscreen : fullScreen}">
      <div class="player">
        <div class="player_cover">
          <span class="fullScreen" v-if="!fullScreen" @click="toggleFullScreen(true)">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-maximize"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
          </span>
          <span class="fullScreen" v-if="fullScreen" @click="toggleFullScreen(false)">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minimize"><path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"></path></svg>
          </span>
          <!-- <img src="../assets/quran.jpg" alt="qurani"> -->
          <div class="track_title">
            {{ getStreamTitle }}
          </div>
        </div>
        <vue-plyr ref="plyr">
          <audio>
            <source :src="playerStream" type="audio/mp3"/>
          </audio>
        </vue-plyr>
      </div>
    </div>
  </transition>
</template>

<script>
  export default {
    name: 'Player',
    props: ['playerStream', 'getStreamTitle'],
    data() {
      return {
        fullScreen: false
      }
    },
    computed: {
      player () {
        return this.$refs.plyr.player
      }
    },
    mounted () {
      document.addEventListener('keydown', (e) => {if(e.keyCode === 27) this.fullScreen =  false});
    },
    methods: {
      toggleFullScreen(status) {
        this.fullScreen = status;
      }
    }
  }
</script>

