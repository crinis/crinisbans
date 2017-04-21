<template>
  <div class="column">
    <div class="box server">
      <div class="columns is-multiline is-gapless has-text-centered">
        <div class="column is-full">
          <h5>{{ server.title }}</h5>
        </div>
        <div class="column is-full"
             v-if="server.serverInfo">
          <b>{{ server.serverInfo.mapName }}</b>
        </div>
        <div class="column is-full"
             v-if="server.serverInfo">
          {{ server.serverInfo.numberOfPlayers }}/{{ server.serverInfo.maxPlayers }}
        </div>
        <div class="column is-full column-bottom-margin">
          {{ server.address }}:{{ server.port }}
        </div>
        <div class="column is-full">
          <Button :href="server.addressConnect"
                  :title="text.serverConnect"
                  icon="fa-gamepad"
                  class="box-icon-link"></Button>
          <Button v-if="server.hasGotv"
                  :href="server.gotvAddressConnect"
                  :title="text.gotvConnect"
                  icon="fa-television"
                  class="box-icon-link"></Button>
        </div>
        <div class="column is-full">
          <Button v-if="server.players && server.players.length > 0"
                  href="#"
                  :title="text.viewPlayers"
                  icon="fa-users"
                  @click="showModal = true"
                  class="box-icon-link"
                  preventDefault="true"></Button>
          <Button :href="server.permaLink"
                  :title="text.serverLink"
                  icon="fa-arrow-circle-right"
                  class="box-icon-link"></Button>
          <transition name="fade">
            <Modal v-if="showModal && server.players && server.players.length > 0"
                   @close="showModal  = false">
              <PlayerTable :text="text"
                           :ajaxPlayers="server.players"></PlayerTable>
            </Modal>
          </transition>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Button from './Button.vue'
import Modal from './Modal.vue'
import PlayerTable from './PlayerTable.vue'
export default {
  data () {
    return {
      'showModal': false
    }
  },
  props: ['server', 'text'],
  components: {
    Button: Button,
    Modal: Modal,
    PlayerTable: PlayerTable
  }
}
</script>

<style scoped lang="scss">

</style>
