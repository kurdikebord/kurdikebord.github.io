<template>
  <div>
    <search v-on:updateSearchedData="updateSearchedData($event)"/>
    
    <loading v-if="loading" />

    <div v-if="!loading" class="reciter_container">
      <div v-for="reciter in searchedData" :key="reciter.id" class="col m6">
        <div class="card horizontal">
          <div class="card-image" @click="showReciter(reciter.id)">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
          </div>
          <div class="card-stacked">
            <div class="card-content">
              <div class="center-align">
                <h6>{{ reciter.name }}</h6>
              </div>
              <div class="details">
                <span>{{ reciter.count}}</span>
                <span>سورة</span>
              </div>
              <div class="details">
                <span>رواية:</span>
                <span>{{ reciter.rewaya }}</span>
              </div>
            </div>
            <div class="card-action">
              <span class="btn_card" @click="addToFavorite(reciter.id)">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </span>
              <!-- <span class="btn_card">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
              </span> -->
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
  import {mapState} from 'vuex';

  export default {
    name: "home",
    components: {Loading, Search},
    props: ['menu'],
    data() {
      return {
        loading: true,
        reciters: [],
        searched: ''
      }
    },
    computed: {
      ...mapState(['logged', 'user']),
      searchedData() {
        return this.reciters.filter((rec) => rec.name.toLowerCase().match(this.searched));
      }
    },
    methods: {
      getReciters() {
        axios.get('https://mp3quran.net/api/_arabic.php')
        .then((res) => {

          this.reciters = res.data.reciters;

          this.loading = false;

        }).catch((err) => console.log(err.message));
      },
      showReciter(reciter) {
        this.$router.push('/reciter?id=' + reciter);
      },
      updateSearchedData(data) {
        this.loading = true;

        this.searched = data;
        
        setTimeout(() => {this.loading = false;}, 1000);
      },
      addToFavorite(reciter) {
          if(this.logged) {




            // able to add
          } else {
              M.toast({html: 'يجب تسجيل الدخول للإضافة للمفضلة'});
          }
      }
      
    },
    mounted () {
      this.getReciters();
    },
    distroyed() {
      this.loading = true;
    }
  };
</script>
