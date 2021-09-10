<template>
    <v-app>
        <v-navigation-drawer v-model="drawer" app>
        </v-navigation-drawer>
        <v-app-bar app>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>XML Guestbook</v-toolbar-title>
            <v-icon class="mx-6">mdi-heart</v-icon>
            <v-btn small @click="show = !show"><v-icon>mdi-comment-send-outline</v-icon>Написать новое сообщение</v-btn>

        </v-app-bar>
        <v-main>
            <v-container>
                <v-expand-transition>
                    <v-container>
                        <div v-show="show" class="grey lighten-5">
                            <v-card-text>
                                <v-text-field label="Введите свое имя" outlined :id="'author-'">
                                </v-text-field>
                                <v-textarea label="Текст сообщения" outlined :id="'text-'">
                                </v-textarea>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn small
                                    @click="sendMessage('', '')"
                                >
                                    <v-icon>mdi-comment-send-outline</v-icon>Отправить</v-btn>
                            </v-card-actions>
                            <v-divider></v-divider>
                        </div>
                    </v-container>
                </v-expand-transition>
                <message-list
                    :messages='messages'
                    @delete-message="deleteMessage"
                    @send-message="sendMessage"
                    @update-message="updateMessage"
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
            show: true,
        }
    },

    async mounted() {
        console.log('mounted');
        this.messages = await GuestbookAPIService.getAllMessages()
    },

    methods: {
        async deleteMessage(id, path) {
            var tmp = this.findMessageByPath(path)
            delete(tmp[id])
            this.$bus.$emit('updateUI')
            GuestbookAPIService.deleteMessage(id)
        },

        async sendMessage(id, path) {
            const text = document.getElementById('text-'+id).value
            const author = document.getElementById('author-'+id).value

            const data = await GuestbookAPIService.replyMessage(id, author, text)
            const date = new Date().toLocaleDateString(['ru-RU'],{month: '2-digit', day: '2-digit', year: '2-digit'})
            if (path == '') {
                this.messages[data.id.toString()] = {
                    id: data.id,
                    author: author,
                    repliedTo: '',
                    replies: {},
                    published: date,
                    text: text,
                }
            } else {
                const tmp = this.findMessageByPath(path)
                if (Object.keys(tmp[id].replies) == 0) {
                    tmp[id].replies = {}
                }
                tmp[id].replies[data.id.toString()] = {
                    id: data.id,
                    author: author,
                    repliedTo: id > 0 ? id : '',
                    replies: {},
                    published: date,
                    text: text,
                }
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

        async updateMessage(id, path) {
            const text = document.getElementById('message-'+id).value
            const data = await GuestbookAPIService.updateMessage(id, text)
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