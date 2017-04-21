/* eslint-disable no-undef */
import Vue from 'vue'
import VueResource from 'vue-resource'
import ServerGroupList from './components/ServerGroupList.vue'
import ServerList from './components/ServerList.vue'

Vue.use(VueResource)

Vue.http.options.emulateJSON = true
Vue.http.options.emulateHTTP = true

import '../styles/custom.scss'

function fetchServers (serverPostIDs, nonce, onReceiveServers) {
  let ajax = {}
  ajax['action'] = 'cb_server_ajax'
  ajax['nonce'] = nonce
  ajax['serverPostIDs'] = serverPostIDs
  Vue.http.post(cbEnv.ajaxUrl, ajax).then(onReceiveServers, function () { })
}

if (document.querySelector('#cb-server-group-post-list')) {
  /* eslint-disable no-new */
  new Vue({
    el: '#cb-server-group-post-list',
    render: function (createElement) {
      return createElement(ServerGroupList, {
        props: {
          serverGroups: this.serverGroups,
          text: this.text
        }
      })
    },
    data: {
      serverGroups: cbEnv.serverGroups,
      text: cbEnv.text
    },
    components: {
      ServerGroupList: ServerGroupList
    },
    methods: {
      updateServerGroupList: function () {
        let context = this
        this.serverGroups.forEach(function (serverGroup, index) {
          fetchServers(serverGroup.serverPostIDs, cbEnv.nonce, function (response) {
            serverGroup.servers = response.body
            context.serverGroups[index] = serverGroup
          })
        })
      }
    },
    created: function () {
      this.updateServerGroupList()
      let context = this
      setInterval(function () {
        context.updateServerGroupList()
      }, 30000)
    }
  })
}
if (document.querySelector('#cb-server-group-post')) {
  /* eslint-disable no-new */
  new Vue({
    el: '#cb-server-group-post',
    render: function (createElement) {
      return createElement(ServerList, {
        props: {
          servers: this.servers,
          serverCount: this.serverCount,
          text: this.text
        }
      })
    },
    data: {
      servers: cbEnv.serverGroup.servers,
      text: cbEnv.text,
      serverCount: cbEnv.serverGroup.serverPostIDs.length
    },
    components: {
      ServerList: ServerList
    },
    methods: {
      updateServerGroup: function () {
        let context = this
        fetchServers(cbEnv.serverGroup.serverPostIDs, cbEnv.nonce, function (response) {
          context.servers = response.body
        })
      }
    },
    created: function () {
      this.updateServerGroup()
      let context = this
      setInterval(function () {
        context.updateServerGroup()
      }, 30000)
    }
  })
}
