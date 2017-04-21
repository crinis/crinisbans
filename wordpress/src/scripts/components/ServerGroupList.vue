<template>
  <div class="columns">
    <div class="column is-full">
      <ServerGroup v-for="serverGroup in serverGroups"
                   :key="serverGroup.postID"
                   v-if="serverGroup"
                   :serverGroup="serverGroup"
                   :text="text"></ServerGroup>
    </div>
  </div>
</template>

<script>
import ServerGroup from './ServerGroup.vue'

export default {
  props: ['serverGroups', 'text'],
  data () {
    return {}
  },
  components: {
    ServerGroup: ServerGroup
  }
}
</script>

<style lang="scss">

</style>
