<template>
<div>
  <search v-on:updateSearchedData="updateSearchedData($event)"/>

  <loading v-if="loading" />

  <div class="live" v-if="!loading">
    <div v-for="live in searchedData" :key="live.radio_url" class="col m6">
      <div class="card horizontal" :class="live.radio_url == trackUi ? 'active' : ''">
        <button type="button" class="card-image" @click="updateStreamLink(live)" 
        :disabled="live.radio_url == trackUi ? true : false">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
        </button>
        <div class="card-stacked">
          <div class="card-content">
            <div class="center-align">
              <h6>{{ live.name }}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
  import Loading from '@/components/global/Loading';
  import Search from '@/components/global/Search';
  import axios from 'axios';

  export default {
    name: 'Live',
    components: {Loading, Search},
    props: ['streamLink'],
    data() {
      return {
        loading: true,
        lives: [],
        searched: ''
      }
    },
    computed: {
      searchedData() {
        return this.lives.filter((live) => live.name.toLowerCase().match(this.searched));
      },
      trackUi() {
        return this.streamLink;
      }
    },
    methods: {
      getLiveRadios() {
        axios.get('https://api.mp3quran.net/radios/radio_arabic.json')
        .then((res) => {

          this.lives = res.data.radios;

          this.loading = false;

        }).catch(err => console.log(err.message));
      },
      updateSearchedData(data) {
        this.loading = true;

        this.searched = data;
        
        setTimeout(() => {this.loading = false;}, 1000);
      },
      updateStreamLink(stream) {
        this.$emit('updateStream', stream);
        this.$emit('reFirePlayer');
      }
    },
    mounted() {
      this.getLiveRadios();
    },
    destroyed() {
      this.loading = true;
    }
  }
</script>
