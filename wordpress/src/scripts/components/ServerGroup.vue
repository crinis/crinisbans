<template>
  <div class="columns is-gapless is-multiline">
    <div class="column is-full">
      <h3><Button :href="serverGroup.permaLink" icon="fa-arrow-circle-right">{{ serverGroup.title }}</Button></h3>
    </div>
    <div class="column is-full"
         v-if="serverGroup.excerpt">
      {{ serverGroup.excerpt }}
    </div>
    <div class="column is-full">
      <ServerList :text="text"
                  :serverCount="serverGroup.servers.length"
                  :servers="serverGroup.servers"></ServerList>
    </div>
  </div>
</template>

<script>
import ServerList from './ServerList.vue'
import Button from './Button.vue'

export default {
  props: ['serverGroup', 'text'],
  data () {
    return {
    }
  },
  components: {
    ServerList: ServerList,
    Button: Button
  }
}
</script>

<style lang="scss">

</style>
