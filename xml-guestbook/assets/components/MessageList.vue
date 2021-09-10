<template>
    <div>
        <ul class="no-dots-list">
            <li v-for="message in messages" :key="message.id">
                <message
                    :message="message"
                    :path="path"
                    @delete-message="deleteMessage"
                    @send-message="sendMessage"
                />
                <message-list
                    v-if="Object.keys(message.replies).length > 0"
                    :messages="message.replies"
                    :path="path=='' ? message.id : path + '-' + message.id"
                    @delete-message="deleteMessage"
                    @send-message="sendMessage"
                />
            </li>
        </ul>

    </div>
</template>

<script>
import Message from '../components/Message.vue'

export default {
    name: "MessageList",
    components: {
        Message,
    },
    props: {
        messages: {
            type: Object,
            required: true,
        },
        path: {
            type: String,
            default: '',
        }
    },
    methods: {
        deleteMessage(id, path) {
            this.$emit('delete-message', id, path)
        },
        sendMessage(id, path) {
            this.$emit('send-message', id, path)
        }
    },
    mounted() {
        this.$bus.$on('updateUI', event => {
            this.$forceUpdate()
        })
    }
}
</script>

<style>
.no-dots-list {
    list-style-type: none;
}
</style>