<template>
  <div v-if="servers && servers.length > 0"
       class="columns is-multiline">
    <Server v-for="server in servers"
            :key="server.postID"
            :text="text"
            :server="server"></Server>
  </div>
  <Loader v-else-if="serverCount > 0"></Loader>
  <span v-else></span>
</template>

<script>
import Server from './Server.vue'
import Loader from './Loader.vue'

export default {
  data () {
    return {
    }
  },
  props: ['servers', 'text', 'serverCount'],
  components: {
    Server: Server,
    Loader: Loader
  }
}
</script>

<style lang="scss">

</style>
