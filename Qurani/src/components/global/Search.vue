<template>
  <form class="search_form">
    <div class="input-field">
      <input id="search" v-model="search" @keyup="updateSearch()" type="search" placeholder="بحث">
      <label class="label-icon" for="search"><i class="material-icons">search</i></label>
      <i class="material-icons">close</i>
    </div>
  </form>
</template>

<script>
  export default {
    name: 'Search',
    data() {
      return {
        search: ''
      }
    },
    methods: {
      updateSearch() {
        this.$emit('updateSearchedData', this.search.toLowerCase());
      }
    }
  }
</script>
