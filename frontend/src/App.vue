<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <multiselect
          :options=""
        ></multiselect>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'
export default {
  name: 'App',
  components: {
    Multiselect
  },
  data () {
    return {
      users: [],
      books: [],
      log: []
    }
  },
  methods: {
    fetchUsers () {},
    fetchBooks () {},
  }
}
</script>

<style lang="scss">
  @import '../node_modules/bootstrap/scss/bootstrap.scss';
</style>
