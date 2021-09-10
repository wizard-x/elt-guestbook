<template>
    <v-card elevation="0" outlined class="mb-2" :data-id="message.id">
        <v-card-title>
            {{ message.author }}
        </v-card-title>
        <v-card-subtitle>
            {{ message.published }}
        </v-card-subtitle>
        <v-card-text>
            {{ message.id}} : {{ message.text }}
        </v-card-text>
        <v-card-actions>
            <v-btn small :data-id="message.id" @click="show = !show"><v-icon>mdi-comment-edit-outline</v-icon>Ответить</v-btn>
            <v-btn small :data-id="message.id"><v-icon>mdi-pencil-outline</v-icon>Редактировать</v-btn>
            <v-btn small
                :data-id="message.id"
                :data-path="path"
                @click="deleteMessage"
            >
                <v-icon>mdi-delete-outline</v-icon>
                Удалить
            </v-btn>
        </v-card-actions>
        <v-expand-transition>
            <div v-show="show">
                <v-divider></v-divider>
                <v-card-text>
                    <v-text-field label="Введите свое имя" outlined :id="'author-' + message.id">
                    </v-text-field>
                    <v-textarea label="Текст сообщения" outlined :id="'text-' + message.id">
                    </v-textarea>
                </v-card-text>
                <v-card-actions>
                    <v-btn small
                        :data-id="message.id"
                        :data-path="path"
                        @click="sendMessage"
                    >
                        <v-icon>mdi-comment-send-outline</v-icon>Отправить</v-btn>
                </v-card-actions>
            </div>
        </v-expand-transition>
    </v-card>
</template>

<script>

export default {
    props: {
        message: {
            type: Object,
            required: true,
        },
        path: {
            type: String,
            default: '',
        }
    },
    data() {
        return {
            show: false,
        }
    },
    methods: {
        deleteMessage(event) {
            this.$emit('delete-message', event.currentTarget.getAttribute('data-id'), event.currentTarget.getAttribute('data-path'))
        },
        sendMessage(event) {
            this.$emit('send-message', event.currentTarget.getAttribute('data-id'), event.currentTarget.getAttribute('data-path'))
            this.show = !this.show
        }
    },
    mounted() {
        this.$bus.$on('updateUI', event => {
            this.$forceUpdate()
        })
    },
}
</script>

<style>
</style>