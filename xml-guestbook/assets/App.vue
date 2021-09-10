<template>
    <v-app>
        <v-navigation-drawer v-model="drawer" app>
        </v-navigation-drawer>
        <v-app-bar app>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>XML Guestbook</v-toolbar-title>
        </v-app-bar>
        <v-main>
            <v-container>
                <message-list
                    :messages='messages'
                    @delete-message="deleteMessage"
                    @send-message="sendMessage"
                />
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import MessageList from './components/MessageList.vue'
import GuestbookAPIService from './services/GuestbookAPIService'

export default {
    name: 'App',
    components: {
        MessageList,
    },
    data() {
        return {
            messages: {},
            drawer: false,
        }
    },

    async mounted() {
        console.log('mounted');
        this.messages = await GuestbookAPIService.getAllMessages()
    },

    methods: {
        async deleteMessage(id, path) {
            var indexes = path.split('-')
            var tmp = this.messages
            while (indexes.length > 0) {
                var index = indexes.shift()
                tmp = (index.length > 0) ? tmp[index].replies : tmp
            }
            delete(tmp[id])
            this.$bus.$emit('updateUI')
            GuestbookAPIService.deleteMessage(id)
        },

        async sendMessage(id, path) {
            const text = document.getElementById('text-'+id).value
            const author = document.getElementById('author-'+id).value
            console.log(id, path, author, text)

            const tmp = this.findMessageByPath(path)
            const data = await GuestbookAPIService.replyMessage(id, author, text)
            if (Object.keys(tmp[id].replies) == 0) {
                tmp[id].replies = {}
            }
            tmp[id].replies[data.id.toString()] = {
                id: data.id,
                author: author,
                repliedTo: id > 0 ? id : '',
                replies: {},
                published: new Date().toLocaleDateString(['ru-RU'],{month: '2-digit', day: '2-digit', year: '2-digit'}),
                text: text,
            }
            this.$bus.$emit('updateUI')
            const _update = setInterval(()=>{
                const el = document.querySelector('[data-id="'+data.id+'"]')
                if (el) {
                    el.classList.add('pulse')
                    window.scroll({
                        behavior: 'smooth',
                        left: 0,
                        top: el.offsetTop
                    });
                    clearInterval(_update);
                    setTimeout(function() {
                        el.classList.remove('pulse')
                    }, 1000)
                }  
            }, 200)
        },

        findMessageByPath(path) {
            var indexes = path.split('-')
            var tmp = this.messages
            while (indexes.length > 0) {
                var index = indexes.shift()
                tmp = (index.length > 0) ? tmp[index].replies : tmp
            }
            return tmp
        }
    }

}
</script>

<style>
.pulse {
  transform: scale(1);
  animation: pulse 2s infinite;    
}

@keyframes pulse {
  0% {
    transform: scale(0.95);
    box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.7);
  }
  
  70% {
    transform: scale(1);
    box-shadow: 0 0 0 10px rgba(0, 0, 0, 0);
  }
  
  100% {
    transform: scale(0.95);
    box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
  }
}
</style>