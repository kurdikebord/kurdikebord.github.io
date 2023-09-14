<template>
  <div>
    <search v-on:updateSearchedData="updateSearchedData($event)"/>

    <div class="reciter_details" v-if="!loading">
      <div>
        {{ reciter.name }}
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
      </div>
      <div>
        {{ reciter.rewaya }}
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
      </div>
      <div>
        {{ reciter.count }}
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
      </div>
    </div>
    <div class="row">

      <loading v-if="loading" />

      <div v-if="!loading" class="data_container">
        <div v-for="sura in searchedData" :key="sura.id" class="col m6 card_container">
          <div class="card horizontal" :class="sura.link == trackUi ? 'active' : ''">
            <button type="button" class="card-image" :disabled="sura.link == trackUi ? true : false" 
            @click="updateCurrentTrack(sura)">
              <div class="playing_ui">
                <span></span> <span></span>
                <span></span> <span></span>
                <span></span> <span></span> 
                <span></span> <span></span>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
            </button>
            <div class="card-stacked">
              <div class="card-content">
                <div class="center-align">
                  <h6>{{ sura.name }}</h6>
                </div>
              </div>
              <div class="card-action">
                <a :href="sura.link" target="_blank" :download="sura.name" class="btn_card">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                </a>
                <span class="btn_card">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                </span>
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
    name: 'Reciter',
    props: ['streamLink'],
    data() {
      return {
        loading: true,
        surasData: [],
        reciter: [],
        searched: ''
      }
    },
    components: {Loading, Search},
    computed: {
      currId () {
        return this.$router.currentRoute.query.id;
      },
      searchedData() {
        return this.reciter.suras_info.filter((sura) => sura.name.toLowerCase().match(this.searched));
      },
      trackUi() {
        return this.streamLink;
      }
    },
    methods: {
      async getCurrReciter() {
        await axios.get('https://mp3quran.net/api/_arabic_sura.json')
        .then((res) => this.surasData = res.data.Suras_Name)
        .catch((err) => console.log(err.message));

        axios.get('https://mp3quran.net/api/_arabic.php')
        .then((res) => {

            if(this.currId) { //Check about query id is exist

              let currReciter = res.data.reciters.filter(rec => rec.id === this.currId);
              const surasNumbers = currReciter[0].suras.split(',');

              currReciter[0].suras_info = [];

              this.surasData.forEach(ele => {
                for(let i = 0; i < this.surasData.length; i++) {
                  if(ele.id == surasNumbers[i]) {

                    let sNumber = ele.id;

                    if(ele.id < 10) sNumber = `00${ele.id}`
                    else if (ele.id >= 10 && ele.id < 100) sNumber = `0${ele.id}`

                    let sura = {
                      id: ele.id,
                      name: ele.name,
                      link: `${currReciter[0].Server}/${sNumber}.mp3`
                    }
                    currReciter[0].suras_info.push(sura);
                  }}
              });

              this.reciter = currReciter[0];
              
              this.loading = false;
              
            } 
            else  this.$router.replace('/');  // Redirect to home page if current id  is undefined

        }).catch((err) => console.log(err.message));
        
      },
      updateSearchedData(data) {
        this.loading = true;

        this.searched = data;
        
        setTimeout(() => {this.loading = false;}, 1000);
      },
      updateCurrentTrack(track) {
        this.$emit('updateStream', track);

        this.$emit('reFirePlayer');
      }
    },
    mounted() {
      this.getCurrReciter();
    },
    destroyed() {
      this.loading = true;
    }
  }
</script>
